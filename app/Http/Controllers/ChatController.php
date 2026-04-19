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
            $maxTokensReq = (int) $request->input('max_tokens', 4096);
            $enableThinkingReq = filter_var($request->input('enable_thinking', false), FILTER_VALIDATE_BOOLEAN);

            // 1. DETEKSI MODE & INPUT
            $isSimple = $this->isSimpleQuery($userMessage);
            $hasImage = $request->has('image_data') && !empty($request->image_data);
            $hasGithub = $request->has('github_repo') && !empty($request->github_repo);
            $manualMode = $request->input('manual_mode', 'auto');

            $maxTokensReq = (int) $request->input('max_tokens', 4096);
            $enableThinkingReq = filter_var($request->input('enable_thinking', false), FILTER_VALIDATE_BOOLEAN);

            // =========================================================
            // 🌟 MULTI-ENGINE ARCHITECTURE (GROQ + NVIDIA ONLY) 🌟
            // =========================================================
            $activeMode = 'fast';
            if ($manualMode !== 'auto') {
                $activeMode = $manualMode;
            } else {
                if ($hasImage) $activeMode = 'vision';
                elseif ($hasGithub) $activeMode = 'coding';
                elseif (!$isSimple) $activeMode = 'smart';
                else $activeMode = 'fast';
            }

            if ($activeMode !== 'smart') {
                $maxTokensReq = 2048; // Hemat token untuk Groq/Vision
                $enableThinkingReq = false; // Matikan Thinking paksa
            }

            $aiConfig = $this->getAiConfiguration($activeMode);
            $selectedModel = $aiConfig['model'];
            $timeout = $aiConfig['timeout'];

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

            // 3. KONSTRUKSI PESAN & PANGGIL API
            $aiReply = "";
            $githubContent = "";

            if ($hasGithub) {
                $githubContent = $this->fetchGithubRepoContent($request->github_repo, $userMessage);
                if (\Illuminate\Support\Str::startsWith($githubContent, 'SISTEM ERROR')) {
                    $aiReply = "⚠️ **GitHub Scanner Terblokir**\n\n" . $githubContent;
                }
            }

            if (empty($aiReply)) {
                try {
                    $configSahaja = config('sahaja');
                    $systemPrompt = is_array($configSahaja) ? ($configSahaja['personality'] ?? "Kamu adalah SAHAJA AI.") : ($configSahaja ?? "Kamu adalah SAHAJA AI.");
                    $aturanKode = "\n\nATURAN KODE: Anda WAJIB membungkus kodingan menggunakan Markdown standar (3 backticks). DILARANG KERAS menambahkan simbol apapun sebelum tanda backticks.";

                    // SUNTIKAN JURUS THINKING MODE DENGAN CoT (Chain of Thought)
                    if ($enableThinkingReq) {
                        $aturanKode .= "\n\n[CRITICAL INSTRUCTION - CHAIN OF THOUGHT]: You MUST use the Chain of Thought (CoT) reasoning process. Sebelum memberikan jawaban akhir, kamu WAJIB memecah
                        masalah dan berpikir selangkah demi selangkah (step-by-step).
                        \n1. Chain-of-Thought (CoT) Advanced
                        Untuk SEMUA pertanyaan kompleks (matematika, logika, coding, analisis), WAJIB melakukan reasoning eksplisit:
                        \nParse & deconstruct problem
                        \nIdentify relevant knowledge domains
                        \nApply appropriate methodology/framework
                        \nExecute step-by-step solution
                        \nValidate & cross-check results
                        \nSynthesize final answer dengan konteks user

                        \n2. Self-Correction Mechanism
                        Selalu tanyakan diri sendiri: 'Apakah ini sudah benar? Ada sudut pandang lain?' sebelum finalisasi jawaban.

                        \n3. Multi-Perspective Analysis
                        Untuk topik kompleks, berikan analisis dari 2-3 sudut pandang berbeda (technical, business, ethical, dll) lalu synthesize.
                        \nLakukan juga langkah ini jika memungkinkan:
                        \n1. Analisis masalahnya secara mendalam.
                        \n2. Evaluasi berbagai kemungkinan pendekatan.
                        \n3. Jabarkan logika penyelesaiannya.
                        \n\nBungkus seluruh proses berpikirmu secara eksklusif di dalam tag <thinking> dan ditutup dengan </thinking>.
                        Setelah tag ditutup, barulah berikan jawaban finalmu kepada user secara rapi.";
                    }

                    $messages = [];

                    // JALUR KHUSUS VISION (Format NVIDIA / OpenAI)
                    if ($hasImage) {
                        $messages[] = ["role" => "system", "content" => $systemPrompt];
                        $messages[] = [
                            "role" => "user",
                            "content" => [
                                ["type" => "text", "text" => $userMessage ?: "Tolong jelaskan gambar ini secara detail."],
                                ["type" => "image_url", "image_url" => ["url" => $request->image_data]]
                            ]
                        ];
                    }
                    // JALUR GITHUB
                    elseif ($hasGithub) {
                        $messages[] = ["role" => "system", "content" => "Kamu adalah SAHAJA AI, Senior Software Engineer. Jawablah berdasarkan [DATA REPOSITORY] di bawah. Jika tertulis 'SISTEM ERROR', jelaskan error tersebut.\n" . $aturanKode];
                        $messages[] = ["role" => "user", "content" => "[URL]: " . $request->github_repo . "\n\n[DATA REPOSITORY]:\n" . $githubContent . "\n\n[PERTANYAAN USER]: " . $userMessage];
                    }
                    // JALUR CHAT STANDAR (Fast / Smart)
                    else {
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

                    // Panggil API (Groq atau Nvidia)
                    $aiReply = $this->callOpenAiCompatible($aiConfig['endpoint'], $aiConfig['key'], $selectedModel, $messages, $timeout, $maxTokensReq);

                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                    try { Log::error("AI Error: " . $errorMsg); } catch (\Exception $logErr) {}

                    // JURUS ANTI BANGKAI ERROR: Langsung lemparkan error 500 ke Frontend
                    // Script akan terhenti di sini dan TIDAK AKAN melanjutkan ke fungsi Chat::create()
                    return response()->json([
                        'error' => true,
                        'message' => 'NVIDIA/Groq sedang sibuk atau timeout. Silakan coba lagi.'
                    ], 500);
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

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
                'model_used' => $selectedModel . ' (' . strtoupper($aiConfig['provider']) . ')'
            ]);

        } catch (\Throwable $globalEx) {
            return response()->json([
                'error' => true,
                'message' => 'Fatal Error: ' . $globalEx->getMessage() . ' (Baris: ' . $globalEx->getLine() . ')'
            ], 500);
        }
    }

    // ==========================================
    // FUNGSI ROUTER & API MULTI-ENGINE
    // ==========================================
    private function getAiConfiguration($mode)
    {
        return match ($mode) {
            'smart' => [
                'provider' => 'nvidia',
                'model'    => env('MODEL_SMART', 'mistralai/mistral-small-4-119b-2603'),
                'endpoint' => env('NVIDIA_ENDPOINT', 'https://integrate.api.nvidia.com/v1/chat/completions'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            'vision' => [
                'provider' => 'nvidia',
                'model'    => env('MODEL_VISION', 'google/gemma-4-vision-it'),
                'endpoint' => env('NVIDIA_ENDPOINT', 'https://integrate.api.nvidia.com/v1/chat/completions'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 180
            ],
            'coding' => [
                'provider' => 'nvidia',
                'model'    => env('MODEL_CODING', 'qwen/qwen2.5-coder-32b-instruct'),
                'endpoint' => env('NVIDIA_ENDPOINT', 'https://integrate.api.nvidia.com/v1/chat/completions'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            default => [
                'provider' => 'groq',
                'model'    => env('MODEL_FAST', 'groq/compound'),
                'endpoint' => env('GROQ_ENDPOINT', 'https://api.groq.com/openai/v1/chat/completions'),
                'key'      => env('GROQ_API_KEY'),
                'timeout'  => 60
            ],
        };
    }

    private function callOpenAiCompatible($endpoint, $key, $model, $messages, $timeout, $maxTokens = 4096)
    {
        $response = Http::withOptions([
            'verify' => false,
            'http_errors' => true,
            'timeout' => $timeout,
            'connect_timeout' => 10
        ])
        ->withToken($key)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($endpoint, [
            "model" => $model,
            "messages" => $messages,
            "temperature" => 0.6,
            "max_tokens" => $maxTokens,
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
        $complexIndicators = [
            'coding', 'program', 'script', 'aplikasi', 'website', 'sistem',
            'database', 'query', 'error', 'debug', 'laravel', 'react', 'vue',
            'algoritma', 'api', 'server', 'deploy', 'hosting',
            'generate', 'source code'
        ];
        foreach ($complexIndicators as $ind) {
            if (str_contains($text, $ind)) {
                return false;
            }
        }

        $wordCount = str_word_count($text);
        if ($wordCount <= 15) {
            return true;
        }
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
    // SATPAM GITHUB V6: HYBRID SMART FETCH
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

            $repoInfo = Http::withOptions(['verify' => false, 'timeout' => 10])
                ->withHeaders(['User-Agent' => 'SAHAJA-AI'])
                ->get("https://api.github.com/repos/{$owner}/{$repo}");
            if (!$repoInfo->successful()) {
                return "SISTEM ERROR: Server GitHub membatasi akses (Limit 60 request/jam). Mohon istirahat sejenak dan coba lagi nanti.";
            }

            $defaultBranch = $repoInfo->json()['default_branch'] ?? 'main';

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
                        $isBlocked = true;
                        break;
                    }
                }
                if ($isBlocked) continue;

                $extension = pathinfo($path, PATHINFO_EXTENSION);
                if (\Illuminate\Support\Str::endsWith($path, '.blade.php')) $extension = 'blade.php';

                // JURUS UPGRADE: Tambahkan ekstensi jsx, ts, tsx, css untuk ekosistem React/Node!
                if (in_array(strtolower($extension), ['php', 'blade.php', 'js', 'jsx', 'ts', 'tsx', 'css', 'json', 'md'])) {
                    $pathLower = strtolower($path);
                    $treeMap .= "- {$path}\n";
                    // Tambahkan package.json agar AI tau ini project React
                    if (in_array($pathLower, ['readme.md', 'routes/web.php', 'composer.json', 'package.json'])) {
                        $coreFiles[] = $path;
                    }

                    foreach ($userWords as $word) {
                        if (strpos($pathLower, $word) !== false) {
                            $priorityFiles[] = $path;
                            break;
                        }
                    }
                }
            }

            if (strlen($treeMap) > 1500) {
                $treeMap = substr($treeMap, 0, 1500) . "\n... [STRUKTUR LAINNYA DISINGKAT]";
            }

            $filesToFetch = array_merge($priorityFiles, $coreFiles);
            $filesToFetch = array_unique($filesToFetch);
            $filesToFetch = array_slice($filesToFetch, 0, 15);

            $megaContent = $treeMap . "\n\n📄 KODE DARI FILE YANG RELEVAN:\n\n";
            foreach ($filesToFetch as $filePath) {
                $rawUrl = "https://raw.githubusercontent.com/{$owner}/{$repo}/{$defaultBranch}/{$filePath}";
                $fileContent = Http::withOptions(['verify' => false, 'timeout' => 5])->get($rawUrl);

                if ($fileContent->successful()) {
                    $content = $fileContent->body();
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
    // FUNGSI PENERIMA UMPAN BALIK
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        \App\Models\Feedback::create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Umpan balik berhasil dikirim!'
        ]);
    }
}
