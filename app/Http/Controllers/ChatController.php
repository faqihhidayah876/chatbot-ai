<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($sessionId = null)
    {
        $userId = Auth::id();
        $sessions = Session::where('user_id', $userId)
                           ->orderBy('updated_at', 'desc')
                           ->get();

        $currentSession = null;
        $chats = [];

        if ($sessionId) {
            $currentSession = Session::where('id', $sessionId)
                                     ->where('user_id', $userId)
                                     ->first();

            if ($currentSession) {
                $chats = $currentSession->chats;
            } else {
                return redirect()->route('chat.index');
            }
        }

        return view('chat', compact('sessions', 'chats', 'currentSession'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required'
        ]);

        $userMessage = $request->message;
        $sessionId = $request->session_id;
        $userId = Auth::id();

        // 1. BUAT/UPDATE SESI
        if (!$sessionId) {
            $title = Str::words($userMessage, 5, '...');
            $session = Session::create(['user_id' => $userId, 'title' => $title]);
            $sessionId = $session->id;
        } else {
            $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
            if($session) $session->touch();
        }

        // 2. KONSTRUKSI CHAT (MEMORY + SYSTEM PROMPT)
        $apiKey = env('NVIDIA_API_KEY');
        $modelName = env('NVIDIA_MODEL', 'moonshotai/kimi-k2.5');
        $url = "https://integrate.api.nvidia.com/v1/chat/completions";

        // A. SYSTEM PROMPT (PERBAIKAN: GABUNGKAN SEMUA CONFIG)
        // Kita ambil config array secara utuh
        $configSahaja = config('sahaja');

        // Cek apakah config berupa array (format baru) atau string biasa (format lama)
        if (is_array($configSahaja)) {
            $systemPrompt = $configSahaja['personality'];

            // Tambahkan bagian shortcuts, context, dll jika ada
            if (isset($configSahaja['shortcuts'])) {
                $systemPrompt .= "\n\n### ⚡ SHORTCUT COMMANDS:\n" . json_encode($configSahaja['shortcuts'], JSON_PRETTY_PRINT);
            }
            if (isset($configSahaja['context_rules'])) {
                $systemPrompt .= "\n\n### 🌍 CONTEXT AWARENESS:\n" . json_encode($configSahaja['context_rules'], JSON_PRETTY_PRINT);
            }
            if (isset($configSahaja['error_patterns'])) {
                $systemPrompt .= "\n\n### ⚠️ ERROR HANDLING PATTERNS:\n" . json_encode($configSahaja['error_patterns'], JSON_PRETTY_PRINT);
            }
        } else {
            // Fallback jika config masih string biasa
            $systemPrompt = $configSahaja;
        }

        $messages = [
            [
                "role" => "system",
                "content" => $systemPrompt
            ]
        ];

        // ========== B. SMART CONTEXT AWARENESS (Optimasi Token) ==========
        if ($sessionId) {
            // Ambil SEMUA chat untuk analisis
            $allChats = Chat::where('session_id', $sessionId)
                            ->orderBy('created_at', 'asc')
                            ->get();

            if ($allChats->count() > 0) {
                // Strategi 1: Selalu ambil 2 chat terakhir (most recent context)
                $recentChats = $allChats->slice(-2);

                // Strategi 2: Ambil 2 chat pertama (establish context awal)
                $initialChats = $allChats->slice(0, 2);

                // Strategi 3: Keyword Matching untuk relevansi
                $relevantChats = $allChats->filter(function($chat) use ($userMessage) {
                    $keywords = $this->extractKeywords($userMessage);
                    foreach ($keywords as $keyword) {
                        if (stripos($chat->user_message, $keyword) !== false ||
                            stripos($chat->ai_response, $keyword) !== false) {
                            return true;
                        }
                    }
                    return false;
                })->slice(-2); // Maks 2 relevant chat

                // Gabungkan, Unique, dan Sort ulang berdasarkan waktu
                $contextChats = $initialChats
                    ->merge($relevantChats)
                    ->merge($recentChats)
                    ->unique('id')
                    ->sortBy('created_at');

                // Limit hard 6 chat terakhir agar tidak jebol token
                if ($contextChats->count() > 6) {
                    $contextChats = $contextChats->slice(-6);
                }

                foreach ($contextChats as $chat) {
                    $messages[] = ["role" => "user", "content" => $chat->user_message];
                    $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
                }
            }
        }

        // C. Pesan User Sekarang
        $messages[] = ["role" => "user", "content" => $userMessage];

        try {
            $response = Http::withOptions([
                'verify' => false,
                'http_errors' => false,
                'timeout' => 300,  // 5 menit timeout
                'connect_timeout' => 10
            ])
            ->withToken($apiKey)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                "model" => $modelName,
                "messages" => $messages,
                "temperature" => 0.6, // Agak kreatif dikit (0.5 -> 0.6)
                "top_p" => 1,
                "max_tokens" => 4096,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiReply = $data['choices'][0]['message']['content'] ?? "Maaf, AI tidak memberikan jawaban.";
            } elseif ($response->status() == 429) {
                $aiReply = "⏳ **Server Sibuk**\n\nTerlalu banyak permintaan ke NVIDIA. Mohon tunggu beberapa saat ya 😊";
            } else {
                $errorMsg = $response->json()['error']['message'] ?? 'Unknown Error';
                $aiReply = "⚠️ **Yah, ada kendala teknis nih**\n\nKode: " . $response->status() . " - " . $errorMsg;
            }

        } catch (\Exception $e) {
            $aiReply = "🔌 **Koneksi Terputus**\n\nAI butuh waktu terlalu lama untuk berpikir. Detail: " . $e->getMessage();
        }

        // 3. SIMPAN CHAT
        Chat::create([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);

        return response()->json([
            'session_id' => $sessionId,
            'user_message' => $userMessage,
            'ai_response' => $aiReply
        ]);
    }

    public function renameSession(Request $request, $id)
    {
        $session = Session::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $session->title = $request->input('title');
        $session->save();
        return response()->json(['success' => true]);
    }

    public function deleteSession($id)
    {
        $session = Session::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $session->delete();
        return response()->json(['success' => true]);
    }

    public function newChat()
    {
        return redirect()->route('chat.index');
    }

    /**
     * Ekstrak kata kunci penting dari teks untuk pencarian relevansi.
     */
    private function extractKeywords($text)
    {
        $commonWords = ['saya', 'anda', 'kamu', 'aku', 'yang', 'dan', 'atau', 'ini', 'itu', 'dari', 'ke', 'di', 'dengan', 'untuk', 'pada', 'adalah', 'bisa', 'bagaimana', 'apa', 'mengapa', 'kapan', 'dimana', 'kenapa', 'tolong', 'minta', 'buat', 'buatkan'];

        // Hapus simbol dan ubah ke lowercase
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($text));

        $words = explode(' ', $text);
        $keywords = array_diff($words, $commonWords);

        // Ambil hanya kata dengan panjang > 3 karakter
        return array_filter($keywords, function($word) {
            return strlen($word) > 3;
        });
    }
}
