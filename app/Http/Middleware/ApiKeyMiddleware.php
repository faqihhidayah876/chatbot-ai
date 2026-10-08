<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ApiKey;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil API key dari header Authorization: Bearer <key>
        $plainKey = $request->bearerToken();

        if (!$plainKey) {
            return response()->json([
                'success' => false,
                'error' => 'missing_api_key',
                'message' => 'API key tidak ditemukan. Sertakan header: Authorization: Bearer <your_api_key>',
            ], 401);
        }

        // 2. Cari ApiKey di DB
        $apiKey = ApiKey::findByPlainKey($plainKey);

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error' => 'invalid_api_key',
                'message' => 'API key tidak valid.',
            ], 401);
        }

        if (!$apiKey->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'inactive_api_key',
                'message' => 'API key sudah dinonaktifkan.',
            ], 403);
        }

        // 3. Cek quota
        if (!$apiKey->hasQuotaRemaining()) {
            return response()->json([
                'success' => false,
                'error' => 'quota_exceeded',
                'message' => 'Quota harian habis. Reset otomatis pada 00:00 WIB.',
                'quota' => [
                    'daily_limit' => $apiKey->daily_limit,
                    'usage_today' => $apiKey->usage_today,
                ],
            ], 429);
        }

        // 4. Attach ke request untuk dipakai di controller
        $request->attributes->set('api_key', $apiKey);
        $request->attributes->set('api_user', $apiKey->user);

        return $next($request);
    }
}
