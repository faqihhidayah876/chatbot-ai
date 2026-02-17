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
        // Cegah PHP timeout (Max execution time diperpanjang jadi 5 menit)
        set_time_limit(300);

        $request->validate(['message' => 'required']);

        $userMessage = $request->message;
        $sessionId = $request->session_id;
        $userId = Auth::id();

        // 1. LOGIKA DUAL MODEL
        $isSimple = $this->isSimpleQuery($userMessage);

        if ($isSimple) {
            $selectedModel = 'moonshotai/kimi-k2-instruct'; // Fast
            $timeout = 25;
        } else {
            $selectedModel = 'moonshotai/kimi-k2.5'; // Smart
            $timeout = 180; // 3 Menit
        }

        $apiKey = env('NVIDIA_API_KEY');
        $url = "https://integrate.api.nvidia.com/v1/chat/completions";

        // 2. SETUP SESSION
        if (!$sessionId) {
            $title = Str::words($userMessage, 5, '...');
            $session = Session::create(['user_id' => $userId, 'title' => $title]);
            $sessionId = $session->id;
        } else {
            $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
            if ($session) {
                $session->touch();
            }
        }

        // 3. SYSTEM PROMPT
        $configSahaja = config('sahaja');
        if (is_array($configSahaja)) {
            $systemPrompt = $configSahaja['personality'];
            if (isset($configSahaja['shortcuts'])) {
                $systemPrompt .= "\n\n### SHORTCUTS:\n" . json_encode($configSahaja['shortcuts']);
            }
            if (isset($configSahaja['context_rules'])) {
                $systemPrompt .= "\n\n### CONTEXT:\n" . json_encode($configSahaja['context_rules']);
            }
        } else {
            $systemPrompt = $configSahaja;
        }

        $messages = [["role" => "system", "content" => $systemPrompt]];

        // 4. CONTEXT MEMORY
        if ($sessionId) {
            $allChats = Chat::where('session_id', $sessionId)->orderBy('created_at', 'asc')->get();
            if ($allChats->count() > 0) {
                $contextChats = $allChats->slice(-4);
                foreach ($contextChats as $chat) {
                    $messages[] = ["role" => "user", "content" => $chat->user_message];
                    $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
                }
            }
        }
        $messages[] = ["role" => "user", "content" => $userMessage];

        // 5. EKSEKUSI REQUEST DENGAN TRY-CATCH-RETRY
        $aiReply = "";
        $isFallback = false;

        try {
            // COBA REQUEST UTAMA
            $response = Http::withOptions([
                'verify' => false,
                'http_errors' => true, // Throw exception jika 4xx/5xx
                'timeout' => $timeout,
                'connect_timeout' => 10
            ])
            ->withToken($apiKey)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, [
                "model" => $selectedModel,
                "messages" => $messages,
                "temperature" => 0.6,
                "max_tokens" => 2048,
            ]);

            $data = $response->json();
            $aiReply = $data['choices'][0]['message']['content'] ?? "Maaf, tidak ada respon.";
        } catch (\Exception $e) {
            // 🔥 DISINI LETAK MAGIC-NYA 🔥
            // Jika K2.5 Timeout atau Error, Langsung Switch ke K2
            if ($selectedModel === 'moonshotai/kimi-k2.5') {
                try {
                    $isFallback = true;
                    // Retry pakai Model Ringan
                    $responseRetry = Http::withOptions([
                        'verify' => false,
                        'timeout' => 30 // Kasih waktu 30 detik buat fallback
                    ])
                    ->withToken($apiKey)
                    ->post($url, [
                        "model" => 'moonshotai/kimi-k2-instruct', // MODEL CADANGAN
                        "messages" => $messages,
                        "temperature" => 0.6,
                        "max_tokens" => 2048,
                    ]);

                    if ($responseRetry->successful()) {
                        $dataRetry = $responseRetry->json();
                        $aiReply = $dataRetry['choices'][0]['message']['content'] ?? "Error Fallback.";
                        $aiReply .= "\n\n*(Jaringan sibuk, beralih ke Mode Cepat)*";
                    } else {
                        throw new \Exception("Fallback juga gagal");
                    }
                } catch (\Exception $ex) {
                    $aiReply = "🔌 **Koneksi Padat**\n\nServer NVIDIA sedang antri parah (>1400 request). Coba lagi nanti ya.";
                }
            } else {
                // Jika yang error memang model K2 (Simple), ya sudahlah
                $aiReply = "🔌 **Koneksi Timeout**\n\nCek koneksi internetmu.";
            }
        }

        // 6. SIMPAN CHAT
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

    // --- FUNGSI DETEKSI QUERY (DIPERBAIKI) ---
    private function isSimpleQuery($text)
    {
        $text = strtolower(trim($text));

    // --- 0. EXCEPTION: Buat + Dokumen Sederhana = SIMPLE ---
    // Pattern 1: "buat ppt sederhana" (dengan kata sederhana)
        if (preg_match('/(bantu|buat|bikin|tolong).{0,20}(ppt|powerpoint|slide|presentasi).{0,10}(sederhana|simple|simpel|dasar)/i', $text)) {
            return true;
        }

    // Pattern 2: "buat ppt" saja (tanpa lengkap/kompleks)
        if (preg_match('/(buat|bikin|bantu|tolong).{0,20}(ppt|powerpoint|slide|presentasi)/i', $text)) {
            if (
                !str_contains($text, 'lengkap') &&
                !str_contains($text, 'kompleks') &&
                !str_contains($text, 'detail') &&
                !str_contains($text, 'aesthetic')
            ) {
                return true;
            }
        }

    // --- 1. DETEKSI INSTANT (Greeting/Ultra-simple) ---
        $instantPatterns = [
        '/^(halo|hai|hello|hi|p|ping|tes|test)\b/i',
        '/^(pagi|siang|sore|malam|makasih|thanks|thx)\b/i',
        '/^(wkwk|haha|hehe|lol)\b/i',
        '/^(siapa kamu|who are you)\b/i',
        ];

        foreach ($instantPatterns as $pattern) {
            if (preg_match($pattern, $text)) {
                return true;
            }
        }

    // --- 2. SCORING SYSTEM ---
        $score = 0;

    // Faktor: Panjang (bobot besar)
        $wordCount = str_word_count($text);
        if ($wordCount < 5) {
            $score += 3;
        } elseif ($wordCount < 15) {
            $score += 1;
        } elseif ($wordCount > 50) {
            $score -= 3;
        } elseif ($wordCount > 30) {
            $score -= 1;
        }

    // Faktor: Keyword Simple (+1 each, max +3)
        $simpleIndicators = [
        'ngobrol', 'curhat', 'cerita', 'ketawa', 'bantu', 'tolong',
        'gimana', 'kenapa', 'apa', 'siapa', 'kapan', 'dimana',
        'sederhana', 'k2', 'simple', 'simpel', 'buatkan',
        'ppt', 'presentasi', 'powerpoint', 'slide' // ✅ TAMBAH
        ];
        $simpleHits = 0;
        foreach ($simpleIndicators as $ind) {
            if (str_contains($text, $ind)) {
                $score += 1;
                if (++$simpleHits >= 3) {
                    break;
                }
            }
        }

    // Faktor: Keyword Complex (instant switch!)
        $complexIndicators = [
        'coding', 'program', 'script', 'aplikasi', 'website', 'sistem',
        'database', 'query', 'error', 'debug', 'laravel', 'react', 'vue',
        'analisis', 'analisa', 'skripsi', 'generate', 'deploy', 'hosting',
        'server', 'api', 'kompleks', 'lengkap', 'aesthetic' // ✅ FIX TYPO
        ];
        foreach ($complexIndicators as $ind) {
            if (str_contains($text, $ind)) {
                return false;
            }
        }

    // --- 3. DECISION ---
        return $score >= 2;
    }

    // Function lain tetap sama...
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
}
