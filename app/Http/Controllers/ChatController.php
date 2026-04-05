<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;
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
        try {
            $request->validate(['message' => 'required']);

            $userMessage = $request->message;
            $sessionId = $request->session_id;
            $userId = Auth::id();

            // 1. DETEKSI MODE & INPUT (Bebas Canvas!)
            $isSimple = $this->isSimpleQuery($userMessage);
            $hasImage = $request->has('image_data') && !empty($request->image_data);
            $hasGithub = $request->has('github_repo') && !empty($request->github_repo);

            // Tangkap pilihan mode manual dari Frontend
            $manualMode = $request->input('manual_mode', 'auto');

            // =========================================================
            // 🌟 ALL-NVIDIA ARCHITECTURE 🌟
            // =========================================================
            $provider = 'nvidia';

            // A. Logika Mode Manual (Pilihan User)
            if ($manualMode !== 'auto') {
                if ($manualMode === 'fast') {
                    $selectedModel = 'moonshotai/kimi-k2-instruct';
                    $timeout = 60;
                } elseif ($manualMode === 'smart') {
                    $selectedModel = 'deepseek-ai/deepseek-v3.2';
                    $timeout = 300;
                } elseif ($manualMode === 'vision') {
                    $selectedModel = 'google/gemma-4-31b-it';
                    $timeout = 180;
                } elseif ($manualMode === 'coding') {
                    $selectedModel = 'qwen/qwen3-coder-480b-a35b-instruct';
                    $timeout = 300;
                }
            }
            // B. Logika Mode Otomatis (Default)
            else {
                if ($hasImage) {
                    $selectedModel = 'google/gemma-4-31b-it'; // Vision
                    $timeout = 180;
                } elseif ($hasGithub) {
                    $selectedModel = 'qwen/qwen3-coder-480b-a35b-instruct'; // Coding
                    $timeout = 300;
                } elseif (!$isSimple) {
                    $selectedModel = 'deepseek-ai/deepseek-v3.2'; // Cerdas
                    $timeout = 300;
                } else {
                    $selectedModel = 'moonshotai/kimi-k2-instruct'; // Cepat
                    $timeout = 60;
                }
            }

            // 2. HANDLE SESSION
            if (!$sessionId) {
                $title = Str::words($userMessage, 5, '...');
                $recentSession = Session::where('user_id', $userId)
                    ->where('title', $title)
                    ->where('created_at', '>=', now()->subSeconds(15))
                    ->first();

                if ($recentSession) {
                    $sessionId = $recentSession->id;
                } else {
                    $session = Session::create(['user_id' => $userId, 'title' => $title]);
                    $sessionId = $session->id;
                }
            } else {
                $session = Session::where('id', $sessionId)->where('user_id', $userId)->first();
                if ($session) $session->touch();
            }

            // 3. KONSTRUKSI PESAN (Bersih dari prompt Canvas)
            $configSahaja = config('sahaja');
            $systemPrompt = is_array($configSahaja) ? ($configSahaja['personality'] ?? "Kamu adalah SAHAJA AI.") : ($configSahaja ?? "Kamu adalah SAHAJA AI.");

            $messages = [];
            $githubContent = "";

            if ($hasGithub) {
                $githubContent = $this->fetchGithubRepoContent($request->github_repo, $userMessage);
            }

            $aturanKode = "\n\nATURAN KODE: Anda WAJIB membungkus kodingan menggunakan Markdown standar (3 backticks). DILARANG KERAS menambahkan simbol apapun sebelum tanda backticks.";

            if ($hasImage) {
                $promptVision = "Peranmu adalah SAHAJA AI, Data Analyst. Ekstrak data dari gambar secara detail.\n\nPertanyaan User:" . $userMessage;
                $messages[] = [
                    "role" => "user",
                    "content" => [
                        [ "type" => "text", "text" => $promptVision ],
                        [ "type" => "image_url", "image_url" => [ "url" => $request->image_data ] ]
                    ]
                ];
            } else if ($hasGithub) {
                $messages[] = ["role" => "system", "content" => "Kamu adalah SAHAJA AI, Senior Software Engineer. Jawablah berdasarkan [DATA REPOSITORY] di bawah. Jika tertulis 'SISTEM ERROR', jelaskan error tersebut.\n" . $aturanKode];
                $messages[] = [
                    "role" => "user",
                    "content" => "[URL]: " . $request->github_repo . "\n\n[DATA REPOSITORY]:\n" . $githubContent . "\n\n[PERTANYAAN USER]: " . $userMessage
                ];
            } else {
                $messages[] = ["role" => "system", "content" => $systemPrompt . $aturanKode];

                if ($sessionId) {
                    $allChats = Chat::where('session_id', $sessionId)->orderBy('created_at', 'asc')->get();
                    if ($allChats->count() > 0) {
                        foreach ($allChats->slice(-4) as $chat) {
                            $cleanUserMsg = preg_replace('/🖼️ \[Gambar Terlampir\]\n/', '', $chat->user_message);
                            $cleanUserMsg = preg_replace('/📦 \[GitHub: .*\]\n/', '', $cleanUserMsg);
                            $messages[] = ["role" => "user", "content" => $cleanUserMsg];
                            $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
                        }
                    }
                }
                $messages[] = ["role" => "user", "content" => $userMessage];
            }

            // 4. CALL API
            session_write_close();
            $aiReply = "";

            if ($hasGithub && \Illuminate\Support\Str::startsWith($githubContent, 'SISTEM ERROR')) {
                $aiReply = "⚠️ **GitHub Scanner Terblokir**\n\n" . $githubContent;
            } else {
                try {
                    $aiReply = $this->callAI($selectedModel, $messages, $timeout);
                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                    try { Log::error("AI Error: " . $errorMsg); } catch (\Exception $logErr) {}
                    $aiReply = "🔌 **API NVIDIA Error**\n\nDetail: `" . substr($errorMsg, 0, 200) . "`";
                }
            }

            if ($aiReply) {
                $aiReply = preg_replace('/@```/', '```', $aiReply);
                $aiReply = preg_replace('/````/', '```', $aiReply);
            }

            // 5. SIMPAN CHAT
            $dbUserMessage = $userMessage;
            if ($hasImage) $dbUserMessage = "🖼️ [Gambar Terlampir]\n" . $userMessage;
            else if ($hasGithub) {
                $repoName = str_replace(['https://github.com/', '.git'], '', rtrim($request->github_repo, '/'));
                $dbUserMessage = "📦 [GitHub: {$repoName}]\n" . $userMessage;
            }

            Chat::create([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
            ]);

            // Kembalikan JSON (Bersih dari Canvas)
            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
                'model_used' => $selectedModel . ' (NVIDIA)'
            ]);

        } catch (\Throwable $globalEx) {
            return response()->json([
                'error' => true,
                'message' => 'Fatal Error: ' . $globalEx->getMessage() . ' (Baris: ' . $globalEx->getLine() . ')'
            ], 500);
        }
    }

    // FUNGSI API (Murni NVIDIA)
    private function callAI($model, $messages, $timeout)
    {
        $apiKey = env('NVIDIA_API_KEY');
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
            throw new \Exception("HTTP {$response->status()} | Response: " . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? null;
    }

    private function isSimpleQuery($text)
    {
        $text = strtolower(trim($text));

        // 1. KATA KUNCI TEKNIS BERAT (Otomatis Mode Cerdas)
        // Saya sudah membuang kata-kata umum seperti "tabel", "analisis", "laporan"
        // agar pertanyaan biasa tidak memicu loading lama.
        $complexIndicators = [
            'coding', 'program', 'script', 'aplikasi', 'website', 'sistem',
            'database', 'query', 'error', 'debug', 'laravel', 'react', 'vue',
            'algoritma', 'api', 'server', 'deploy', 'hosting',
            'generate', 'source code'
        ];

        // Jika prompt mengandung kata-kata teknis di atas, lempar ke K2.5
        foreach ($complexIndicators as $ind) {
            if (str_contains($text, $ind)) {
                return false; // False = Masuk Mode Cerdas (K2.5)
            }
        }

        // 2. LOGIKA HITUNG KATA (Sesuai Permintaan Bosku!)
        $wordCount = str_word_count($text);

        // Jika tidak ada kata teknis dan jumlah kata <= 15, langsung gas Mode Cepat!
        if ($wordCount <= 15) {
            return true; // True = Masuk Mode Cepat (K2)
        }

        // Jika kata lebih dari 15 (kalimat sangat panjang), lemparkan ke Mode Cerdas
        // karena biasanya pertanyaan panjang butuh pemahaman konteks yang lebih dalam.
        return false;
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

    public function shareSession($id)
    {
        $session = Session::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (!$session->share_token) {
            $session->share_token = Str::random(16);
            $session->save();
        }

        $shareUrl = route('chat.public', ['token' => $session->share_token]);

        return response()->json(['success' => true, 'url' => $shareUrl]);
    }

    public function showPublicSession($token)
    {
        $session = Session::where('share_token', $token)->firstOrFail();
        $chats = $session->chats()->orderBy('created_at', 'asc')->get();

        return view('public-chat', compact('session', 'chats'));
    }

    // ==========================================
    // SATPAM GITHUB V6: HYBRID SMART FETCH (CEPAT & BISA CARI FILE)
    // ==========================================
    private function fetchGithubRepoContent($repoUrl, $userPrompt = "")
    {
        try {
            $repoUrl = str_replace('.git', '', trim($repoUrl));
            $parts = explode('github.com/', $repoUrl);
            if (count($parts) < 2) return "SISTEM ERROR: Link GitHub tidak valid.";

            $repoPath = explode('/', $parts[1]);
            if (count($repoPath) < 2) return "SISTEM ERROR: Format repository salah.";

            $owner = $repoPath[0];
            $repo = $repoPath[1];

            // 1. CEK API GITHUB UTAMA
            $repoInfo = Http::withOptions(['verify' => false, 'timeout' => 10])
                ->withHeaders(['User-Agent' => 'SAHAJA-AI'])
                ->get("https://api.github.com/repos/{$owner}/{$repo}");

            if (!$repoInfo->successful()) {
                return "SISTEM ERROR: Server GitHub membatasi akses (Limit 60 request/jam). Mohon istirahat sejenak dan coba lagi nanti.";
            }

            $defaultBranch = $repoInfo->json()['default_branch'] ?? 'main';

            // 2. AMBIL STRUKTUR FOLDER
            $treeUrl = "https://api.github.com/repos/{$owner}/{$repo}/git/trees/{$defaultBranch}?recursive=1";
            $treeResponse = Http::withOptions(['verify' => false, 'timeout' => 15])
                ->withHeaders(['User-Agent' => 'SAHAJA-AI'])
                ->get($treeUrl);

            if (!$treeResponse->successful()) return "SISTEM ERROR: Gagal membaca struktur folder GitHub.";

            $files = $treeResponse->json()['tree'] ?? [];
            $blockedFolders = ['vendor/', 'node_modules/', 'storage/', 'public/build/', '.git/', 'tests/'];

            $treeMap = "📂 STRUKTUR FOLDER PROJECT:\n";
            $coreFiles = [];
            $priorityFiles = [];

            // 3. RADAR KATA KUNCI (Ambil kata dari prompt user, minimal 3 huruf supaya 'web' / 'api' kena)
            $cleanPrompt = preg_replace('/[^a-zA-Z0-9]/', ' ', strtolower($userPrompt));
            $userWords = array_filter(explode(' ', $cleanPrompt), function($word) {
                return strlen($word) >= 3;
            });

            foreach ($files as $file) {
                if ($file['type'] !== 'blob') continue;

                $path = $file['path'];
                $isBlocked = false;
                foreach ($blockedFolders as $blocked) {
                    if (\Illuminate\Support\Str::startsWith($path, $blocked)) {
                        $isBlocked = true; break;
                    }
                }
                if ($isBlocked) continue;

                $extension = pathinfo($path, PATHINFO_EXTENSION);
                if (\Illuminate\Support\Str::endsWith($path, '.blade.php')) $extension = 'blade.php';

                // Hanya proses file kodingan
                if (in_array(strtolower($extension), ['php', 'blade.php', 'js', 'json', 'md'])) {
                    $pathLower = strtolower($path);

                    // Bangun Peta Pohon agar AI paham struktur keseluruhan
                    $treeMap .= "- {$path}\n";

                    // Deteksi file penting (jaga-jaga kalau user gak minta file spesifik)
                    if (in_array($pathLower, ['readme.md', 'routes/web.php', 'composer.json'])) {
                        $coreFiles[] = $path;
                    }

                    // Deteksi File yang Diminta User!
                    foreach ($userWords as $word) {
                        if (strpos($pathLower, $word) !== false) {
                            $priorityFiles[] = $path;
                            break;
                        }
                    }
                }
            }

            // Batasi panjang Peta Pohon agar AI tidak pusing baca daftar isi
            if (strlen($treeMap) > 1500) {
                $treeMap = substr($treeMap, 0, 1500) . "\n... [STRUKTUR LAINNYA DISINGKAT]";
            }

            // 4. GABUNGKAN ANTREAN: File pesanan user ditaruh PALING ATAS
            $filesToFetch = array_merge($priorityFiles, $coreFiles);
            $filesToFetch = array_unique($filesToFetch);

            // DIET SERVER: Ambil MAKSIMAL 4 FILE saja agar respon di bawah 20 detik!
            $filesToFetch = array_slice($filesToFetch, 0, 7);

            $megaContent = $treeMap . "\n\n📄 KODE DARI FILE YANG RELEVAN:\n\n";

            // 5. DOWNLOAD KODINGANNYA
            foreach ($filesToFetch as $filePath) {
                $rawUrl = "https://raw.githubusercontent.com/{$owner}/{$repo}/{$defaultBranch}/{$filePath}";
                $fileContent = Http::withOptions(['verify' => false, 'timeout' => 5])->get($rawUrl);

                if ($fileContent->successful()) {
                    $content = $fileContent->body();

                    // BATASI 2000 HURUF PER FILE (Sesuai Permintaan Bosku!)
                    if (strlen($content) > 15000) {
                        $content = substr($content, 0, 15000) . "\n... [KODE DIPOTONG UNTUK MENGHEMAT MEMORI]";
                    }
                    $megaContent .= "--- FILE: {$filePath} ---\n```\n{$content}\n```\n\n";
                }
            }

            return $megaContent;

        } catch (\Exception $e) {
            return "SISTEM ERROR: " . $e->getMessage();
        }
    }
}
