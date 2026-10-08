<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\Ai\AiRouterService;
use App\Services\Ai\AiProviderService;
use App\Services\Ai\QueryAnalyzerService;
use App\Services\Integrations\GithubService;
use App\Services\Integrations\TavilyService;
use App\Services\Integrations\ImagenService;
use App\Services\Chat\ChatPersistenceService;

class ChatController extends Controller
{
    protected AiRouterService $aiRouter;
    protected AiProviderService $aiProvider;
    protected QueryAnalyzerService $queryAnalyzer;
    protected GithubService $github;
    protected TavilyService $tavily;
    protected ImagenService $imagen;
    protected ChatPersistenceService $chatPersistence;

    public function __construct(
        AiRouterService $aiRouter,
        AiProviderService $aiProvider,
        QueryAnalyzerService $queryAnalyzer,
        GithubService $github,
        TavilyService $tavily,
        ImagenService $imagen,
        ChatPersistenceService $chatPersistence
    ) {
        $this->aiRouter = $aiRouter;
        $this->aiProvider = $aiProvider;
        $this->queryAnalyzer = $queryAnalyzer;
        $this->github = $github;
        $this->tavily = $tavily;
        $this->imagen = $imagen;
        $this->chatPersistence = $chatPersistence;
    }

    public function index($sessionId = null)
    {
        $userId = Auth::id();
        Session::where('user_id', $userId)->doesntHave('chats')->delete();
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
            $manualMode = $request->input('manual_mode', 'auto');

            // 1. ANALYZE INPUT & DETERMINE MODE
            $inputType = $this->queryAnalyzer->detectInputType([
                'message' => $userMessage,
                'image_data_array' => $request->image_data_array,
                'github_repo' => $request->github_repo,
            ]);

            $activeMode = $this->queryAnalyzer->determineActiveMode(
                $userMessage,
                $manualMode,
                $inputType
            );

            // 2. GET AI CONFIGURATION
            $aiConfig = $this->aiRouter->getAiConfiguration($activeMode);
            $selectedModel = $aiConfig['model'];
            $timeout = $aiConfig['timeout'];

            // 3. HANDLE SESSION
            $sessionId = $this->chatPersistence->findOrCreateSession(
                $userId,
                $sessionId ? (int)$sessionId : null,
                $userMessage
            );

            // 4. INTERCEPT IMAGEN
            if (Str::startsWith(strtolower(trim($userMessage)), '/imagen')) {
                $dbMsgImagen = $userMessage;
                if ($inputType['has_image']) {
                    $imgCount = count($request->image_data_array);
                    $dbMsgImagen = "🖼️ [{$imgCount} Gambar Terlampir untuk Diedit]\n" . $userMessage;
                }

                return $this->imagen->generate(
                    $userMessage,
                    $sessionId,
                    $dbMsgImagen,
                    $request->image_data_array
                );
            }

            // 5. FETCH GITHUB CONTENT IF PRESENT
            $aiReply = "";
            $githubContent = "";

            if ($inputType['has_github']) {
                $githubContent = $this->github->fetchRepoContent($request->github_repo, $userMessage);
                if (Str::startsWith($githubContent, 'SISTEM ERROR')) {
                    $aiReply = "**GitHub Scanner Terblokir**\n\n" . $githubContent;
                }
            }

            // 6. BUILD MESSAGES & CALL AI PROVIDER
            if (empty($aiReply)) {
                try {
                    $messages = $this->buildMessages(
                        $userMessage,
                        $sessionId,
                        $inputType,
                        $request,
                        $enableWebSearchReq,
                        $enableThinkingReq,
                        $githubContent
                    );

                    $aiReply = $this->aiProvider->callOpenAiCompatible(
                        $aiConfig['endpoint'],
                        $aiConfig['key'],
                        $selectedModel,
                        $messages,
                        $timeout,
                        $maxTokensReq
                    );
                } catch (\Exception $e) {
                    Log::error('AI Provider Error', [
                        'message' => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'user_id' => Auth::id(),
                        'mode'    => $activeMode ?? 'unknown',
                        'model'   => $selectedModel ?? 'unknown',
                    ]);

                    return response()->json([
                        'error' => true,
                        'message' => 'Layanan AI sedang sibuk. Silakan coba lagi dalam beberapa saat.'
                    ], 500);
                }
            }

            // 7. CLEANUP
            if ($aiReply) {
                $aiReply = preg_replace('/@```/', '```', $aiReply);
                $aiReply = preg_replace('/````/', '```', $aiReply);
            }

            // 8. SAVE & RETURN
            $dbUserMessage = $this->prepareDbMessage($userMessage, $inputType, $request);

            $this->chatPersistence->saveChat(
                $sessionId,
                $dbUserMessage,
                $aiReply,
                $activeMode,
                strtolower($aiConfig['provider'] ?? 'unknown'),
                $selectedModel
            );

            return response()->json([
                'session_id' => $sessionId,
                'user_message' => $dbUserMessage,
                'ai_response' => $aiReply,
                'model_used' => $selectedModel . ' (' . strtoupper($aiConfig['provider']) . ')'
            ]);

        } catch (\Throwable $globalEx) {
            Log::error('Chat Fatal Error', [
                'message' => $globalEx->getMessage(),
                'file'    => $globalEx->getFile(),
                'line'    => $globalEx->getLine(),
                'trace'   => $globalEx->getTraceAsString(),
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'error' => true,
                'message' => 'Terjadi kesalahan pada server. Silakan muat ulang halaman dan coba lagi.'
            ], 500);
        }
    }

    private function buildMessages(
        string $userMessage,
        int $sessionId,
        array $inputType,
        Request $request,
        bool $enableWebSearch,
        bool $enableThinking,
        string $githubContent = ""
    ): array {
        $configSahaja = config('sahaja');
        $systemPrompt = is_array($configSahaja) ? ($configSahaja['personality'] ?? "Kamu adalah SAHAJA AI.") : ($configSahaja ?? "Kamu adalah SAHAJA AI.");
        $aturanKode = "\n\nATURAN KODE & FORMATTING:\n1. Anda WAJIB membungkus kodingan menggunakan Markdown standar (3 backticks).\n2. [CRITICAL] JIKA MEMBUAT DIAGRAM MERMAID: Anda WAJIB secara eksplisit menggunakan tag pembuka
        http://googleusercontent.com/immersive_entry_chip/0.";

        if ($enableWebSearch && !$inputType['has_github'] && !$inputType['has_image']) {
            $webContext = $this->tavily->fetchContext($userMessage);
            if (!empty($webContext)) {
                $systemPrompt .= "\n\n[INFORMASI INTERNET TERBARU]\nKamu memiliki akses ke hasil pencarian web berikut untuk membantu menjawab:\n" . $webContext . "\n\nInstruksi: Gunakan informasi di atas jika relevan dengan pertanyaan user. Jawablah secara natural seperti asisten percakapan biasa (JANGAN membuat format laporan formal/riset).";
            }
        }

        if ($enableThinking) {
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
        if ($inputType['has_image']) {
            $messages[] = ["role" => "system", "content" => $systemPrompt];

            $contentArray = [
                ["type" => "text", "text" => $userMessage ?: "Tolong analisis gambar-gambar ini secara detail."]
            ];

            foreach ($request->image_data_array as $imgBase64) {
                $contentArray[] = ["type" => "image_url", "image_url" => ["url" => $imgBase64]];
            }

            $messages[] = ["role" => "user", "content" => $contentArray];
        }
        // JALUR GITHUB
        elseif ($inputType['has_github']) {
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

                        if (count($intersection) >= 2) {
                            $cleanUserMsg = preg_replace('/🖼️ \[Gambar Terlampir\]\n/', '', $chat->user_message);
                            $cleanUserMsg = preg_replace('/📦 \[GitHub: .*\]\n/', '', $cleanUserMsg);
                            $cleanUserMsg = preg_replace('/\[Dokumen \d+: .*?\]\n"""\n.*?\n"""\n\n/s', '[Dokumen Terlampir]', $cleanUserMsg);
                            $cleanUserMsg = preg_replace('/\[REFERENSI DOKUMEN\]\n"""\n.*?\n"""\n\n/s', "📎 [Dokumen Workspace SAHAJA LLM]\n", $cleanUserMsg);

                            $messages[] = ["role" => "user", "content" => "[Konteks Relevan Masa Lalu]: " . $cleanUserMsg];
                            $messages[] = ["role" => "assistant", "content" => $chat->ai_response];
                        }
                    }

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

        return $messages;
    }

    private function prepareDbMessage(string $userMessage, array $inputType, Request $request): string
    {
        $dbUserMessage = $userMessage;
        if ($inputType['has_image']) {
            $imgCount = count($request->image_data_array ?? []);
            $dbUserMessage = "🖼️ [{$imgCount} Gambar Terlampir]\n" . $userMessage;
        } elseif ($inputType['has_github']) {
            $repoName = str_replace(['https://github.com/', '.git'], '', rtrim($request->github_repo, '/'));
            $dbUserMessage = "📦 [GitHub: {$repoName}]\n" . $userMessage;
        }
        return $dbUserMessage;
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

    public function exportSession($id)
    {
        $userId = Auth::id();
        $format = request()->input('format', 'json'); // 'json' atau 'markdown'

        $session = Session::where('id', $id)
            ->where('user_id', $userId)
            ->with(['chats' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        $timestamp = now()->format('Y-m-d_H-i-s');
        $safeTitle = Str::slug($session->title ?? 'chat', '_', 'id');
        $filename = "sahaja_chat_{$safeTitle}_{$timestamp}";

        if ($format === 'markdown') {
            return $this->exportSessionAsMarkdown($session, $filename);
        }

        return $this->exportSessionAsJson($session, $filename);
    }

    private function exportSessionAsJson($session, $filename)
    {
        $data = [
            'exported_at' => now()->toIso8601String(),
            'app' => 'SAHAJA AI',
            'version' => '5.0',
            'session' => [
                'title' => $session->title,
                'created_at' => $session->created_at->toIso8601String(),
                'updated_at' => $session->updated_at->toIso8601String(),
                'message_count' => $session->chats->count(),
            ],
            'messages' => $session->chats->map(function ($chat) {
                return [
                    'timestamp' => $chat->created_at->toIso8601String(),
                    'user_message' => $chat->user_message,
                    'ai_response' => $chat->ai_response,
                    'mode' => $chat->mode,
                    'provider' => $chat->provider,
                    'model' => $chat->model,
                ];
            }),
        ];

        return response()
            ->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.json\"");
    }

    private function exportSessionAsMarkdown($session, $filename)
    {
        $md = "# SAHAJA AI — Percakapan\n\n";
        $md .= "**Judul:** " . ($session->title ?? 'Tanpa Judul') . "  \n";
        $md .= "**Dibuat:** " . $session->created_at->format('d F Y, H:i') . "  \n";
        $md .= "**Total Pesan:** " . $session->chats->count() . "  \n";
        $md .= "**Diekspor:** " . now()->format('d F Y, H:i') . "\n\n";
        $md .= "---\n\n";

        foreach ($session->chats as $index => $chat) {
            $num = $index + 1;

            // User message
            $md .= "## 💬 Pesan #{$num} — Anda\n\n";
            $md .= "_" . $chat->created_at->format('d M Y, H:i') . "_\n\n";
            $md .= $chat->user_message . "\n\n";

            // AI response
            $md .= "### 🤖 SAHAJA AI\n\n";
            if ($chat->mode) {
                $modeLabel = ucfirst($chat->mode);
                $modelLabel = $chat->model ?? 'unknown';
                $md .= "_Mode: **{$modeLabel}** | Model: `{$modelLabel}`_\n\n";
            }
            $md .= $chat->ai_response . "\n\n";
            $md .= "---\n\n";
        }

        $md .= "\n_Diekspor dari SAHAJA AI — https://sahaja-ai.my.id_\n";

        return response($md, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.md\"",
        ]);
    }

    public function exportAllSessions()
    {
        $userId = Auth::id();

        $sessions = Session::where('user_id', $userId)
            ->with(['chats' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get();

        $data = [
            'exported_at' => now()->toIso8601String(),
            'app' => 'SAHAJA AI',
            'version' => '5.0',
            'total_sessions' => $sessions->count(),
            'total_messages' => $sessions->sum(function ($s) {
                return $s->chats->count();
            }),
            'sessions' => $sessions->map(function ($session) {
                return [
                    'title' => $session->title,
                    'created_at' => $session->created_at->toIso8601String(),
                    'updated_at' => $session->updated_at->toIso8601String(),
                    'messages' => $session->chats->map(function ($chat) {
                        return [
                            'timestamp' => $chat->created_at->toIso8601String(),
                            'user_message' => $chat->user_message,
                            'ai_response' => $chat->ai_response,
                            'mode' => $chat->mode,
                            'provider' => $chat->provider,
                            'model' => $chat->model,
                        ];
                    }),
                ];
            }),
        ];

        $filename = 'sahaja_all_chats_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()
            ->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
