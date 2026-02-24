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

            // 1. DETEKSI MODE & INPUT
            $isSimple = $this->isSimpleQuery($userMessage);
            $hasImage = $request->has('image_data') && !empty($request->image_data);
            $hasGithub = $request->has('github_repo') && !empty($request->github_repo);

            if ($request->has('force_mode')) {
                if ($request->force_mode === 'fast') $isSimple = true;
                elseif ($request->force_mode === 'smart') $isSimple = false;
            }

            // =========================================================
            // 🌟 ARSITEKTUR DOUBLE ENGINE (NVIDIA + GROQ) 🌟
            // =========================================================
            $provider = 'nvidia'; // Default provider

            if ($hasImage) {
                // VISION MODE: Llama 3.2 Vision (via NVIDIA)
                $selectedModel = 'meta/llama-3.2-11b-vision-instruct';
                $provider = 'nvidia';
                $timeout = 180; // 3 menit sudah cukup
            } else if ($hasGithub || !$isSimple) {
                // SMART MODE: DeepSeek V3 (via NVIDIA) - Cerdas & Reasoning Tinggi
                // Catatan: Pastikan string 'deepseek-ai/deepseek-v3' ini sesuai dengan yang ada di katalog Nvidia
                $selectedModel = 'deepseek-ai/deepseek-v3.2';
                $provider = 'nvidia';
                $timeout = 300; // 5 menit biar aman
            } else {
                // FAST MODE: Llama 3.3 70B (via GROQ) - Super Kilat!
                $selectedModel = 'moonshotai/kimi-k2-instruct-0905';
                $provider = 'groq';
                $timeout = 60; // Groq saking cepatnya, 60 detik aja udah lebih dari cukup
            }

            // 2. HANDLE SESSION (Dengan pencegah Double Room)
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

            // 3. KONSTRUKSI PESAN (Tetap sama seperti versi stabil)
            $configSahaja = config('sahaja');
            $systemPrompt = is_array($configSahaja) ? ($configSahaja['personality'] ?? "Kamu adalah SAHAJA AI.") : ($configSahaja ?? "Kamu adalah SAHAJA AI.");

            $messages = [];
            $githubContent = "";

            if ($hasGithub) {
                $githubContent = $this->fetchGithubRepoContent($request->github_repo);
            }

            $aturanKode = "\n\nATURAN KODE: Anda WAJIB membungkus kodingan menggunakan Markdown standar (3 backticks). DILARANG KERAS menambahkan simbol apapun (seperti @ atau spasi) sebelum tanda backticks.";

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
                $messages[] = ["role" => "system", "content" => "Kamu adalah SAHAJA AI, Senior Software Engineer. Jawablah berdasarkan [DATA REPOSITORY] di bawah. Jika tertulis 'SISTEM ERROR', jelaskan error tersebut." . $aturanKode];
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

            // 4. CALL API (Dengan pelemparan Provider)
            session_write_close();
            $aiReply = "";

            if ($hasGithub && \Illuminate\Support\Str::startsWith($githubContent, 'SISTEM ERROR')) {
                $aiReply = "⚠️ **GitHub Scanner Terblokir**\n\n" . $githubContent;
            } else {
                try {
                    // Oper variabel $provider ke fungsi callAI
                    $aiReply = $this->callAI($selectedModel, $messages, $timeout, $provider);
                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                    try { Log::error("AI Error: " . $errorMsg); } catch (\Exception $logErr) {}

                    if ($provider === 'nvidia') {
                        // FALLBACK: Jika Nvidia mati/lambat, BANTING SETIR KE GROQ!
                        try {
                            $fallbackReply = $this->callAI('moonshotai/kimi-k2-instruct-0905', $messages, 60, 'groq');
                            $aiReply = $fallbackReply . "\n\n*(Nvidia Engine sedang sibuk. Dialihkan ke Groq Engine)*";
                        } catch (\Exception $e2) {
                            $aiReply = "🔌 **Semua Engine Mati (Nvidia & Groq)**\n\nDetail: `" . substr($e2->getMessage(), 0, 150) . "`";
                        }
                    } else {
                        $aiReply = "🔌 **API Groq Error**\n\nDetail: `" . substr($errorMsg, 0, 200) . "`";
                    }
                }
            }

            if ($aiReply) {
                // Hapus simbol @ yang nempel di backtick (contoh: @```php jadi ```php)
                $aiReply = preg_replace('/@```/', '```', $aiReply);
                // Pastikan AI tidak ngawur ngasih 4 backtick
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

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
                'model_used' => $selectedModel . ' (' . strtoupper($provider) . ')'
            ]);

        } catch (\Throwable $globalEx) {
            return response()->json([
                'error' => true,
                'message' => 'Fatal Error: ' . $globalEx->getMessage() . ' (Baris: ' . $globalEx->getLine() . ')'
            ], 500);
        }
    }

    // FUNGSI SAKLAR API OTOMATIS
    private function callAI($model, $messages, $timeout, $provider = 'nvidia')
    {
        // Tentukan Kunci dan Pintu Gerbang berdasarkan Provider
        if ($provider === 'groq') {
            $apiKey = env('GROQ_API_KEY');
            $url = "https://api.groq.com/openai/v1/chat/completions";
        } else {
            $apiKey = env('NVIDIA_API_KEY');
            $url = "https://integrate.api.nvidia.com/v1/chat/completions";
        }

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
            throw new \Exception("HTTP {$response->status()} | Provider: {$provider} | Response: " . $response->body());
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
            $filesToFetch = array_slice($filesToFetch, 0, 4);

            $megaContent = $treeMap . "\n\n📄 KODE DARI FILE YANG RELEVAN:\n\n";

            // 5. DOWNLOAD KODINGANNYA
            foreach ($filesToFetch as $filePath) {
                $rawUrl = "https://raw.githubusercontent.com/{$owner}/{$repo}/{$defaultBranch}/{$filePath}";
                $fileContent = Http::withOptions(['verify' => false, 'timeout' => 5])->get($rawUrl);

                if ($fileContent->successful()) {
                    $content = $fileContent->body();

                    // BATASI 2000 HURUF PER FILE (Sesuai Permintaan Bosku!)
                    if (strlen($content) > 2000) {
                        $content = substr($content, 0, 2000) . "\n... [KODE DIPOTONG UNTUK MENGHEMAT MEMORI]";
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
