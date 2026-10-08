<?php

namespace App\Services\Ai;

use App\Models\UserApiKey;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ByokService
{
    /**
     * Validasi API key dengan test request ke provider.
     */
    public function validateKey(
        string $provider, 
        string $plainKey, 
        ?string $customBaseUrl = null
    ): array {
        // Custom provider
        if ($provider === 'custom') {
            if (!$customBaseUrl) {
                return ['valid' => false, 'message' => 'Base URL wajib diisi untuk custom provider.'];
            }
            // Validate base URL format
            if (!filter_var($customBaseUrl, FILTER_VALIDATE_URL)) {
                return ['valid' => false, 'message' => 'Base URL tidak valid.'];
            }
            
            $testEndpoint = rtrim($customBaseUrl, '/') . '/models';
            
            try {
                $response = Http::withOptions([
                    'verify' => config('services.ssl.ca_bundle'),
                    'timeout' => 15,
                ])
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $plainKey,
                        'Accept' => 'application/json',
                    ])
                    ->get($testEndpoint);

                if ($response->successful()) {
                    return ['valid' => true, 'message' => 'Key valid.'];
                }

                if (in_array($response->status(), [401, 403])) {
                    return ['valid' => false, 'message' => 'API key ditolak.'];
                }

                return [
                    'valid' => false,
                    'message' => 'Endpoint mengembalikan error ' . $response->status(),
                ];

            } catch (\Exception $e) {
                return [
                    'valid' => false,
                    'message' => 'Tidak dapat connect ke endpoint. Cek base URL Anda.',
                ];
            }
        }
        
        // Preset/Extended provider — pakai config
        $config = UserApiKey::SUPPORTED_PROVIDERS[$provider] ?? null;

        if (!$config || !isset($config['test_endpoint'])) {
            return ['valid' => false, 'message' => 'Provider tidak didukung.'];
        }

        try {
            $headers = $this->getTestHeaders($provider, $plainKey);
            
            $response = Http::withOptions([
                'verify' => config('services.ssl.ca_bundle'),
                'timeout' => 15,
            ])
                ->withHeaders($headers)
                ->get($config['test_endpoint']);

            if ($response->successful()) {
                return ['valid' => true, 'message' => 'Key valid.'];
            }

            if (in_array($response->status(), [401, 403])) {
                return ['valid' => false, 'message' => 'API key ditolak oleh provider.'];
            }

            return [
                'valid' => false,
                'message' => 'Provider mengembalikan error ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::warning('BYOK validate error', [
                'provider' => $provider,
                'message' => $e->getMessage(),
            ]);

            return [
                'valid' => false,
                'message' => 'Tidak dapat memverifikasi key. Coba lagi.',
            ];
        }
    }

    /**
     * Get headers untuk test request per provider.
     */
    private function getTestHeaders(string $provider, string $plainKey): array
    {
        $config = UserApiKey::SUPPORTED_PROVIDERS[$provider] ?? null;
        
        if (!$config) return [];
        
        $headers = [];
        
        if ($config['auth_type'] === 'bearer') {
            $headers[$config['auth_header']] = $config['auth_prefix'] . $plainKey;
        } else {
            $headers[$config['auth_header']] = $config['auth_prefix'] . $plainKey;
        }
        
        $headers['Accept'] = 'application/json';
        $headers['Content-Type'] = 'application/json';
        
        // Extra headers (contoh: OpenRouter butuh HTTP-Referer & X-Title)
        if (isset($config['extra_headers'])) {
            $headers = array_merge($headers, $config['extra_headers']);
        }
        
        return $headers;
    }

    /**
     * Ambil user's API key untuk provider tertentu.
     */
    public function getUserKey(int $userId, string $provider): ?UserApiKey
    {
        return UserApiKey::where('user_id', $userId)
            ->where('provider', $provider)
            ->where('is_active', true)
            ->where('is_valid', true)
            ->first();
    }

    /**
     * Cek apakah user punya BYOK untuk provider tertentu.
     */
    public function userHasKey(int $userId, string $provider): bool
    {
        return $this->getUserKey($userId, $provider) !== null;
    }
}
