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
            $request->validate([
                'message' => 'required|string|max:20000',
            ], [
                'message.max' => 'Pesan terlalu panjang! Maksimal 15.000 karakter untuk mencegah overload server.'
            ]);

            $userMessage = $request->message;
            $sessionId = $request->session_id;
            $userId = Auth::id();
            $maxTokensReq = (int) $request->input('max_tokens', 4096);
            $enableThinkingReq = filter_var($request->input('enable_thinking', false), FILTER_VALIDATE_BOOLEAN);
            $enableWebSearchReq = filter_var($request->input('web_search', false), FILTER_VALIDATE_BOOLEAN);

            // 1. DETEKSI MODE & INPUT
            $isSimple = $this->isSimpleQuery($userMessage);
            $hasImage = $request->has('image_data_array') && !empty($request->image_data_array);
            $hasGithub = $request->has('github_repo') && !empty($request->github_repo);
            $manualMode = $request->input('manual_mode', 'auto');
            $isWorkspace = str_contains($userMessage, '[REFERENSI DOKUMEN]');

            $maxTokensReq = (int) $request->input('max_tokens', 4096);
            $enableThinkingReq = filter_var($request->input('enable_thinking', false), FILTER_VALIDATE_BOOLEAN);

            // =========================================================
            // 🌟 MULTI-ENGINE ARCHITECTURE (GROQ + NVIDIA ONLY) 🌟
            // =========================================================
            $activeMode = 'fast';
            if ($manualMode !== 'auto') {
                $activeMode = $manualMode;
            } else {
                if ($isWorkspace) $activeMode = 'workspace';
                if ($hasImage) $activeMode = 'vision';
                elseif ($hasGithub) $activeMode = 'coding';
                elseif (!$isSimple) $activeMode = 'smart';
                else $activeMode = 'fast';
            }

            if ($activeMode === 'vision') {
                $maxTokensReq = 2048;
                $enableThinkingReq = false;
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
            // =========================================================
            // INTERCEPTOR: DETEKSI JIKA USER MINTA BIKIN ATAU EDIT GAMBAR
            // =========================================================
            if (\Illuminate\Support\Str::startsWith(strtolower(trim($userMessage)), '/imagen')) {

                // Siapkan teks riwayat untuk disimpan di database
                $dbMsgImagen = $userMessage;
                if ($hasImage) {
                    $imgCount = count($request->image_data_array);
                    $dbMsgImagen = "🖼️ [{$imgCount} Gambar Terlampir untuk Diedit]\n" . $userMessage;
                }

                // Lempar ke mesin Sahaja Imagen dan bawa foto kiriman user (parameter ke-4)
                return $this->generateSahajaImagen(
                    $userMessage,
                    $sessionId,
                    $dbMsgImagen,
                    $request->image_data_array
                );
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

                    if ($enableWebSearchReq && !$hasGithub && !$hasImage) {
                        $webContext = $this->fetchTavilyContext($userMessage);
                        if (!empty($webContext)) {
                            $systemPrompt .= "\n\n[INFORMASI INTERNET TERBARU]\nKamu memiliki akses ke hasil pencarian web berikut untuk membantu menjawab:\n" . $webContext . "\n\nInstruksi: Gunakan informasi di atas jika relevan dengan pertanyaan user. Jawablah secara natural seperti asisten percakapan biasa (JANGAN membuat format laporan formal/riset).";
                        }
                    }

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

                        $contentArray = [
                            ["type" => "text", "text" => $userMessage ?: "Tolong analisis gambar-gambar ini secara detail."]
                        ];

                        // Looping menjejali AI dengan kelima gambar sekaligus!
                        foreach ($request->image_data_array as $imgBase64) {
                            $contentArray[] = ["type" => "image_url", "image_url" => ["url" => $imgBase64]];
                        }

                        $messages[] = ["role" => "user", "content" => $contentArray];
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
                                $recentChats = $allChats->slice(-4);
                                $olderChats = $allChats->slice(-14, 14);
                                $stopWords = ['dan', 'atau', 'yang', 'di', 'ke', 'dari', 'ini', 'itu', 'untuk', 'dengan', 'apakah', 'bagaimana', 'buatkan', 'tolong', 'saya', 'kamu', 'anda'];
                                $userWords = array_diff(str_word_count(strtolower($userMessage), 1), $stopWords);

                                foreach ($olderChats as $chat) {
                                    $chatWords = str_word_count(strtolower($chat->user_message . ' ' . $chat->ai_response), 1);
                                    $intersection = array_intersect($userWords, $chatWords);

                                    // Jika ada minimal 2 kata kunci yang sama, masukkan ke memori!
                                    if (count($intersection) >= 2) {
                                        $cleanUserMsg = preg_replace('/🖼️ \[Gambar Terlampir\]\n/', '', $chat->user_message);
                                        $cleanUserMsg = preg_replace('/📦 \[GitHub: .*\]\n/', '', $cleanUserMsg);
                                        $cleanUserMsg = preg_replace('/\[Dokumen \d+: .*?\]\n"""\n.*?\n"""\n\n/s', '[Dokumen Terlampir]', $cleanUserMsg);
                                        $cleanUserMsg = preg_replace('/\[REFERENSI DOKUMEN\]\n"""\n.*?\n"""\n\n/s', "📎 [Dokumen Workspace SAHAJA LLM]\n", $cleanUserMsg);

                                        $messages[] = ["role" => "user", "content" => "[Konteks Relevan Masa Lalu]: " . $cleanUserMsg];
                                        $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
                                    }
                                }

                                // 5. Masukkan 2 obrolan terbaru secara utuh
                                foreach ($recentChats as $chat) {
                                    $cleanUserMsg = preg_replace('/🖼️ \[Gambar Terlampir\]\n/', '', $chat->user_message);
                                    $cleanUserMsg = preg_replace('/📦 \[GitHub: .*\]\n/', '', $cleanUserMsg);
                                    $cleanUserMsg = preg_replace('/\[Dokumen \d+: .*?\]\n"""\n.*?\n"""\n\n/s', '[Dokumen Terlampir]', $cleanUserMsg);
                                    $cleanUserMsg = preg_replace('/\[REFERENSI DOKUMEN\]\n"""\n.*?\n"""\n\n/s', "📎 [Dokumen Workspace SAHAJA LLM]\n", $cleanUserMsg);

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
            if ($hasImage) {
                $imgCount = count($request->image_data_array);
                $dbUserMessage = "🖼️ [{$imgCount} Gambar Terlampir]\n" . $userMessage;
            }
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
    // ==========================================
    // FUNGSI ROUTER & API MULTI-ENGINE (SECURE)
    // ==========================================
    private function getAiConfiguration($mode)
    {
        return match ($mode) {
            'smart' => [
                'provider' => env('PROVIDER_SMART'),
                'model'    => env('MODEL_SMART'),
                'endpoint' => env('NVIDIA_ENDPOINT'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            'alpha' => [
                'provider' => env('PROVIDER_ALPHA'),
                'model'    => env('MODEL_ALPHA'),
                'endpoint' => env('NVIDIA_ENDPOINT'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            'vision' => [
                'provider' => env('PROVIDER_VISION'),
                'model'    => env('MODEL_VISION'),
                'endpoint' => env('NVIDIA_ENDPOINT'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            'coding' => [
                'provider' => env('PROVIDER_CODING'),
                'model'    => env('MODEL_CODING'),
                'endpoint' => env('NVIDIA_ENDPOINT'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            'workspace' => [
                'provider' => env('SAHAJA_LLM_PROVIDER'),
                'model'    => env('SAHAJA_LLM_MODEL'),
                'endpoint' => env('NVIDIA_ENDPOINT'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
            ],
            default => [
                'provider' => env('PROVIDER_FAST'),
                'model'    => env('MODEL_FAST'),
                'endpoint' => env('NVIDIA_ENDPOINT'),
                'key'      => env('NVIDIA_API_KEY'),
                'timeout'  => 300
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


                if (in_array(strtolower($extension), ['php', 'blade.php', 'js', 'jsx', 'ts', 'tsx', 'css', 'json', 'md', 'kt', 'java', 'xml', 'gradle', 'swift', 'dart', 'yaml'])) {
                    $pathLower = strtolower($path);
                    $treeMap .= "- {$path}\n";

                    if (in_array(basename($pathLower), ['readme.md', 'routes/web.php', 'composer.json', 'package.json', 'build.gradle', 'build.gradle.kts', 'androidmanifest.xml', 'pubspec.yaml'])) {
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

            if (strlen($treeMap) > 3000) {
                $treeMap = substr($treeMap, 0, 3000) . "\n... [STRUKTUR LAINNYA DISINGKAT]";
            }

            $filesToFetch = array_merge($priorityFiles, $coreFiles);
            $filesToFetch = array_unique($filesToFetch);
            $filesToFetch = array_slice($filesToFetch, 0, 20);

            $megaContent = $treeMap . "\n\n📄 KODE DARI FILE YANG RELEVAN:\n\n";
            foreach ($filesToFetch as $filePath) {
                $rawUrl = "https://raw.githubusercontent.com/{$owner}/{$repo}/{$defaultBranch}/{$filePath}";
                $fileContent = Http::withOptions(['verify' => false, 'timeout' => 5])->get($rawUrl);

                if ($fileContent->successful()) {
                    $content = $fileContent->body();
                    if (strlen($content) > 45000) {
                        $content = substr($content, 0, 45000) . "\n... [KODE DIPOTONG UNTUK MENGHEMAT MEMORI]";
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
    // ==========================================
    // FUNGSI WEB SEARCH (TAVILY GROUNDING)
    // ==========================================
    private function fetchTavilyContext($query)
    {
        try {
            $response = Http::withOptions(['verify' => false, 'timeout' => 10])->post('https://api.tavily.com/search', [
                'api_key' => env('TAVILY_API_KEY'),
                'query' => $query,
                'search_depth' => 'basic',
                'include_answer' => false,
                'max_results' => 5,
            ]);

            if ($response->successful()) {
                $results = $response->json()['results'] ?? [];
                if (count($results) > 0) {
                    $context = "REFERENSI WEB REAL-TIME:\n";
                    foreach ($results as $res) {
                        $context .= "- " . ($res['title'] ?? 'Artikel') . ": " . ($res['content'] ?? '') . "\n";
                    }
                    return $context;
                }
            }
        } catch (\Exception $e) {
            // Jika Tavily error/timeout, abaikan saja agar AI tetap bisa menjawab normal
            return "";
        }
        return "";
    }
    // ==========================================
    // 🎨 FITUR SAHAJA IMAGEN (GENERATE & EDIT)
    // ==========================================
    // Tambahkan parameter $imageArray di sini dengan nilai default null
    private function generateSahajaImagen($prompt, $sessionId, $userMessage, $imageArray = null)
    {
        try {
            // 1. Bersihkan prompt
            $cleanPrompt = trim(str_ireplace('/imagen', '', $prompt));
            if (empty($cleanPrompt)) {
                $cleanPrompt = "A beautiful futuristic city landscape";
            }

            $apiKey = env('FREETHEAI_API_KEY');
            if (empty($apiKey)) {
                throw new \Exception("API Key FreeTheAI belum dipasang di .env!");
            }

            // 2. Setup Default (Mode Bikin Gambar Biasa)
            $baseUrl = rtrim(env('FREETHEAI_BASE_URL', 'https://api.freetheai.xyz/v1'), '/');
            $invokeUrl = $baseUrl . '/images/generations';
            $modelName = env('FREETHEAI_MODEL', 'vhr/flux_dev');

            $payload = [
                'model' => $modelName,
                'prompt' => $cleanPrompt
            ];

            //MODE EDIT GAMBAR (Jika User Upload Foto)
            if (!empty($imageArray) && count($imageArray) > 0) {
                $invokeUrl = $baseUrl . '/images/edits'; // Rute khusus edit

                $modelName = env('FREETHEAI_EDIT_MODEL', 'img/gpt-image-2');

                $payload['model'] = $modelName;
                // Ambil gambar pertama yang diupload user (sudah dalam format base64 dari frontend)
                $payload['image'] = $imageArray[0];
            }

            // 4. TEMBAK API FREETHEAI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->withoutVerifying()
              ->timeout(120)
              ->post($invokeUrl, $payload);

            // 5. Tangkap Error
            if (!$response->successful()) {
                throw new \Exception("FreeTheAI Server Error (" . $response->status() . "): " . $response->body());
            }

            $data = $response->json();

            // 6. Ekstrak Gambar
            $base64 = $data['data'][0]['b64_json'] ?? null;
            $imageUrl = $data['data'][0]['url'] ?? null;

            if ($base64) {
                $imageName = 'imagen_' . time() . '_' . rand(1000, 9999) . '.jpg';
                $destinationPath = public_path('uploads/imagen');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                file_put_contents($destinationPath . '/' . $imageName, base64_decode($base64));
                $publicUrl = url('uploads/imagen/' . $imageName);
                $markdownImage = "![Hasil Sahaja Imagen](" . $publicUrl . ")";

            } elseif ($imageUrl) {
                $markdownImage = "![Hasil Sahaja Imagen](" . $imageUrl . ")";
            } else {
                throw new \Exception("Gagal membaca struktur respons gambar dari FreeTheAI.");
            }

            // 7. Susun Pesan Balasan
            $modeLabel = !empty($imageArray) ? 'Mengedit' : 'Membuat';
            $aiReply = "**Sahaja Imagen** telah selesai **{$modeLabel} Gambar** Anda:\n\n" . $markdownImage;

            Chat::create([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'ai_response' => $aiReply,
                'model_used' => 'Sahaja Imagen (' . $modelName . ')'
            ]);

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'ai_response' => $aiReply,
                'model_used' => 'Sahaja Imagen (' . $modelName . ')'
            ]);

        } catch (\Exception $e) {
            $errorMsg = "⚠️ **Sahaja Imagen Mengalami Kendala Teknis:**\n\n```text\n" . $e->getMessage() . "\n```";

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'ai_response' => $errorMsg,
                'model_used' => 'Sahaja Imagen (Error)'
            ]);
        }
    }
}
