<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ApiKey;
use App\Models\ApiUsageLog;
use App\Services\Ai\AiRouterService;
use App\Services\Ai\AiProviderService;
use App\Services\Ai\QueryAnalyzerService;

class ApiController extends Controller
{
    protected AiRouterService $aiRouter;
    protected AiProviderService $aiProvider;
    protected QueryAnalyzerService $queryAnalyzer;

    public function __construct(
        AiRouterService $aiRouter,
        AiProviderService $aiProvider,
        QueryAnalyzerService $queryAnalyzer
    ) {
        $this->aiRouter = $aiRouter;
        $this->aiProvider = $aiProvider;
        $this->queryAnalyzer = $queryAnalyzer;
    }

    /**
     * POST /api/v1/chat
     * 
     * Body:
     * {
     *   "message": "Halo SAHAJA AI",
     *   "mode": "fast",         // optional, default "auto"
     *   "max_tokens": 2048      // optional, default 2048
     * }
     */
    public function chat(Request $request)
    {
        $startTime = microtime(true);
        $apiKey = $request->attributes->get('api_key');
        $user = $request->attributes->get('api_user');

        try {
            // 1. Validasi
            $request->validate([
                'message' => 'required|string|max:10000',
                'mode' => 'nullable|string|in:auto,fast,smart,coding',
                'max_tokens' => 'nullable|integer|min:100|max:8192',
            ]);

            $message = $request->input('message');
            $mode = $request->input('mode', 'auto');
            $maxTokens = (int) $request->input('max_tokens', 2048);

            // 2. Tentukan mode
            $activeMode = $mode === 'auto'
                ? $this->queryAnalyzer->determineActiveMode(
                    $message,
                    'auto',
                    ['has_image' => false, 'has_github' => false, 'is_workspace' => false]
                )
                : $mode;

            // 3. Get AI config & call
            $aiConfig = $this->aiRouter->getAiConfiguration($activeMode);

            $messages = [
                ['role' => 'system', 'content' => 'Kamu adalah SAHAJA AI, asisten cerdas untuk brainstorming, coding, dan analisis dokumen. Jawab dengan ramah dan informatif dalam Bahasa Indonesia.'],
                ['role' => 'user', 'content' => $message],
            ];

            $aiReply = $this->aiProvider->callOpenAiCompatible(
                $aiConfig['endpoint'],
                $aiConfig['key'],
                $aiConfig['model'],
                $messages,
                $aiConfig['timeout'],
                $maxTokens
            );

            if (!$aiReply) {
                throw new \Exception('AI tidak memberikan response.');
            }

            // 4. Increment usage
            $apiKey->incrementUsage();

            // 5. Log usage
            $responseTime = (int) round((microtime(true) - $startTime) * 1000);
            ApiUsageLog::create([
                'api_key_id' => $apiKey->id,
                'endpoint' => '/api/v1/chat',
                'method' => 'POST',
                'mode' => $activeMode,
                'input_length' => strlen($message),
                'output_length' => strlen($aiReply),
                'status_code' => 200,
                'response_time_ms' => $responseTime,
                'ip_address' => $request->ip(),
            ]);

            // 6. Return response
            return response()->json([
                'success' => true,
                'data' => [
                    'reply' => $aiReply,
                    'mode' => $activeMode,
                    'model' => $aiConfig['model'],
                    'provider' => $aiConfig['provider'],
                ],
                'usage' => [
                    'daily_limit' => $apiKey->daily_limit,
                    'usage_today' => $apiKey->usage_today,
                    'remaining' => max(0, $apiKey->daily_limit - $apiKey->usage_today),
                ],
                'meta' => [
                    'response_time_ms' => $responseTime,
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log failure
            if ($apiKey) {
                ApiUsageLog::create([
                    'api_key_id' => $apiKey->id,
                    'endpoint' => '/api/v1/chat',
                    'method' => 'POST',
                    'status_code' => 422,
                    'response_time_ms' => (int) round((microtime(true) - $startTime) * 1000),
                    'ip_address' => $request->ip(),
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'validation_error',
                'message' => 'Data input tidak valid.',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('API chat error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'api_key_id' => $apiKey?->id,
                'user_id' => $user?->id,
            ]);

            // Log failure
            if ($apiKey) {
                ApiUsageLog::create([
                    'api_key_id' => $apiKey->id,
                    'endpoint' => '/api/v1/chat',
                    'method' => 'POST',
                    'status_code' => 500,
                    'response_time_ms' => (int) round((microtime(true) - $startTime) * 1000),
                    'ip_address' => $request->ip(),
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'server_error',
                'message' => 'Layanan AI sedang sibuk. Silakan coba lagi dalam beberapa saat.',
            ], 500);
        }
    }

    /**
     * GET /api/v1/me
     * Info tentang API key yang sedang dipakai.
     */
    public function me(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');
        $user = $request->attributes->get('api_user');

        return response()->json([
            'success' => true,
            'data' => [
                'key_name' => $apiKey->name,
                'masked_key' => $apiKey->masked_key,
                'is_active' => $apiKey->is_active,
                'daily_limit' => $apiKey->daily_limit,
                'usage_today' => $apiKey->usage_today,
                'usage_total' => $apiKey->usage_total,
                'last_used_at' => $apiKey->last_used_at?->toIso8601String(),
                'owner' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ],
        ]);
    }
}
