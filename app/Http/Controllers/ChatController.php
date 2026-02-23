<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index($sessionId = null)
    {
        $userId = Auth::id();
        \App\Models\Session::where('user_id', \Illuminate\Support\Facades\Auth::id())->doesntHave('chats')->delete();
        $sessions = Session::where('user_id', $userId)->orderBy('updated_at', 'desc')->get();
        $currentSession = null;
        $chats = [];

        if ($sessionId) {
            $currentSession = Session::where('id', $sessionId)->where('user_id', $userId)->first();
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
        // HAPUS set_time_limit KARENA RAWAN ERROR 500 DI HOSTING GRATIS
        // set_time_limit(300);

        try {
            $request->validate(['message' => 'required']);

            $userMessage = $request->message;
            $sessionId = $request->session_id;
            $userId = Auth::id();

            // 1. DETEKSI MODEL & GAMBAR
            $isSimple = $this->isSimpleQuery($userMessage);
            $hasImage = $request->has('image_data') && !empty($request->image_data);

            // Cek paksaan mode dari UI
            if ($request->has('force_mode')) {
                if ($request->force_mode === 'fast') $isSimple = true;
                elseif ($request->force_mode === 'smart') $isSimple = false;
            }

            // LOGIKA ROUTING MODEL (Text vs Vision)
            if ($hasImage) {
                // Jika ada gambar, OTOMATIS panggil Llama 3.2 Vision
                $selectedModel = 'meta/llama-3.2-11b-vision-instruct';
                $timeout = 120; // Kasih waktu ekstra buat baca gambar
            } else if ($isSimple) {
                $selectedModel = 'moonshotai/kimi-k2-instruct';
                $timeout = 120;
            } else {
                $selectedModel = 'moonshotai/kimi-k2.5';
                $timeout = 300;
            }

            // 2. HANDLE SESSION
            if (!$sessionId) {
                $title = Str::words($userMessage, 5, '...');
                $session = Session::create(['user_id' => $userId, 'title' => $title]);
                $sessionId = $session->id;
            } else {
                $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
                if ($session) $session->touch();
            }

            // 3. KONSTRUKSI PESAN
            $configSahaja = config('sahaja');
            // Tambahkan pengecekan null untuk config
            if (!$configSahaja) {
                $systemPrompt = "Kamu adalah SAHAJA AI, asisten cerdas.";
            } else if (is_array($configSahaja)) {
                $systemPrompt = $configSahaja['personality'] ?? "Kamu adalah asisten AI.";
                if (isset($configSahaja['shortcuts'])) $systemPrompt .= "\n\nSHORTCUTS:" . json_encode($configSahaja['shortcuts']);
                if (isset($configSahaja['context_rules'])) $systemPrompt .= "\n\nCONTEXT:" . json_encode($configSahaja['context_rules']);
            } else {
                $systemPrompt = $configSahaja;
            }

            $messages = [];

            // LOGIKA PEMISAHAN: VISION vs TEXT
            if ($hasImage) {
                // JIKA ADA GAMBAR: Paksa dia ekstrak data
                $promptVision = "Peranmu adalah SAHAJA AI, seorang Data Analyst dan OCR Expert kelas dunia.
                Analisis gambar ini dengan sangat teliti menggunakan bahasa Indonesia. Ekstrak semua teks, angka, metrik, dan label yang
                ada ke dalam format tabel atau bullet points. Jangan berhalusinasi.\n\nATURAN KERAS: Langsung
                berikan hasil analisismu. JANGAN PERNAH menyalin, mengulangi, atau menyebutkan instruksi ini ke
                dalam jawabanmu.\n\nPertanyaan User:" . $userMessage;

                $messages[] = [
                    "role" => "user",
                    "content" => [
                        [
                            "type" => "text",
                            "text" => $promptVision
                        ],
                        [
                            "type" => "image_url",
                            "image_url" => [
                                "url" => $request->image_data
                            ]
                        ]
                    ]
                ];
            } else {
                // JIKA CHAT BIASA: Bawa system prompt dan history chat sebelumnya
                $messages[] = ["role" => "system", "content" => $systemPrompt];

                if ($sessionId) {
                    $allChats = Chat::where('session_id', $sessionId)->orderBy('created_at', 'asc')->get();
                    if ($allChats->count() > 0) {
                        $contextChats = $allChats->slice(-4);
                        foreach ($contextChats as $chat) {
                            // Bersihkan tag [Gambar Terlampir] dari history agar Kimi tidak bingung
                            $cleanUserMsg = preg_replace('/🖼️ \[Gambar Terlampir\]\n/', '', $chat->user_message);

                            $messages[] = ["role" => "user", "content" => $cleanUserMsg];
                            $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
                        }
                    }
                }

                $messages[] = ["role" => "user", "content" => $userMessage];
            }

            // 4. CALL API (Safe Try-Catch)
            $aiReply = "";

            try {
                $aiReply = $this->callAI($selectedModel, $messages, $timeout);
            } catch (\Exception $e) {
                // Bungkus Log dengan try-catch agar jika log error, app tidak crash 500
                try {
                    Log::error("AI Error: " . $e->getMessage());
                } catch (\Exception $logErr) {}

                // FALLBACK LOGIC
                if ($selectedModel === 'moonshotai/kimi-k2.5') {
                    try {
                        $fallbackReply = $this->callAI('moonshotai/kimi-k2-instruct', $messages, 30);
                        $aiReply = $fallbackReply . "\n\n*(Mode Cerdas sibuk, beralih ke Mode Cepat)*";
                    } catch (\Exception $e2) {
                        $aiReply = "🔌 **Server Padat**\n\nServer AI sedang sangat sibuk. Silakan coba lagi nanti.";
                    }
                } else {
                    $aiReply = "🔌 **Koneksi Bermasalah**\n\nCek internet atau coba pertanyaan yang lebih pendek.";
                }
            }

            // 5. SIMPAN CHAT (Jangan simpan base64 ke database teks)
            $dbUserMessage = $userMessage;
            if ($hasImage) {
                $dbUserMessage = "🖼️ [Gambar Terlampir]\n" . $userMessage;
            }

            Chat::create([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
            ]);

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
                'model_used' => $selectedModel // Kasih tau frontend model apa yang dipakai
            ]);

        } catch (\Exception $globalEx) {
            // CATCH ALL ERROR (Agar frontend terima JSON, bukan HTML 500)
            return response()->json([
                'error' => true,
                'message' => 'Internal Error: ' . $globalEx->getMessage()
            ], 500);
        }
    }

    private function callAI($model, $messages, $timeout)
    {
        $apiKey = env('NVIDIA_API_KEY');
        // Pastikan URL benar (NVIDIA)
        $url = "https://integrate.api.nvidia.com/v1/chat/completions";

        $response = Http::withOptions([
            'verify' => false,
            'http_errors' => true,
            'timeout' => $timeout,
            'connect_timeout' => 10
        ])
        ->withToken($apiKey)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($url, [
            "model" => $model,
            "messages" => $messages,
            "temperature" => 0.6,
            "max_tokens" => 2048,
        ]);

        if (!$response->successful()) {
            throw new \Exception("HTTP Error: " . $response->status() . " Body: " . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? null;
    }

    private function isSimpleQuery($text)
    {
        $text = strtolower(trim($text));

        $instantPatterns = [
            '/^(halo|hai|hey|hei|hello|hi|p|ping|tes|test)\b/i', // Tambah hey/hei
            '/^(pagi|siang|sore|malam|makasih|thanks|thx)\b/i',
            '/^(wkwk|haha|hehe|lol|wkwkwk)\b/i',
            '/^(siapa kamu|who are you)\b/i',
        ];
        foreach ($instantPatterns as $pattern) {
            if (preg_match($pattern, $text)) return true;
        }

        $complexIndicators = [
            'coding', 'program', 'script', 'aplikasi', 'website', 'sistem',
            'database', 'query', 'error', 'debug', 'laravel', 'react', 'vue',
            'analisis', 'laporan', 'skripsi', 'makalah', 'ppt', 'presentasi',
            'buatkan', 'generate', 'deploy', 'hosting', 'server', 'api',
            'kompleks', 'lengkap', 'aesthetic', 'tabel', 'flowchart'
        ];
        foreach ($complexIndicators as $ind) {
            if (str_contains($text, $ind)) return false;
        }

        $score = 0;
        $wordCount = str_word_count($text);

        // PERBAIKAN SKOR: Kalimat <= 8 kata langsung dapet skor 2 (Auto Cepat)
        if ($wordCount <= 8) $score += 2;
        elseif ($wordCount < 15) $score += 1;
        elseif ($wordCount > 50) $score -= 3;
        elseif ($wordCount > 30) $score -= 1;

        $simpleIndicators = [
            'ngobrol', 'curhat', 'cerita', 'ketawa', 'bantu', 'tolong',
            'gimana', 'kenapa', 'apa', 'siapa', 'kapan', 'dimana', 'sederhana',
            'simple', 'simpel', 'kabar', 'ngoding', 'k2', 'puasa', 'ramadhan', 'makan'
        ];

        $simpleHits = 0;
        foreach ($simpleIndicators as $ind) {
            if (str_contains($text, $ind)) {
                $score += 1;
                if (++$simpleHits >= 3) break;
            }
        }

        return $score >= 2;
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

    // Fungsi untuk membuat / mengambil link share
    public function shareSession($id)
    {
        $session = Session::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Cek apakah chat ini sudah punya token
        if (!$session->share_token) {
            $session->share_token = Str::random(16); // Bikin kode unik acak
            $session->save();
        }

        // Buat URL lengkapnya
        $shareUrl = route('chat.public', ['token' => $session->share_token]);

        return response()->json(['success' => true, 'url' => $shareUrl]);
    }

    // Fungsi untuk menampilkan halaman Chat Publik
    public function showPublicSession($token)
    {
        // Cari sesi berdasarkan token, abaikan user_id (karena ini untuk publik)
        $session = Session::where('share_token', $token)->firstOrFail();
        $chats = $session->chats()->orderBy('created_at', 'asc')->get();

        // Tampilkan view khusus publik
        return view('public-chat', compact('session', 'chats'));
    }
}
