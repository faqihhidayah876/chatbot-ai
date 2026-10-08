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
    public function validateKey(string $provider, string $plainKey): array
    {
        $config = UserApiKey::SUPPORTED_PROVIDERS[$provider] ?? null;

        if (!$config) {
            return ['valid' => false, 'message' => 'Provider tidak didukung.'];
        }

        try {
            $headers = $this->getTestHeaders($provider, $plainKey);
            
            $response = Http::withOptions([
                'verify' => config('services.ssl.ca_bundle'),
                'timeout' => 15,
            ])
                ->withHeaders($headers)
                ->{strtolower($config['test_method'])}($config['test_endpoint']);

            if ($response->successful()) {
                return ['valid' => true, 'message' => 'Key valid.'];
            }

            // 401/403 biasanya key invalid
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
        return match ($provider) {
            'openai', 'groq' => [
                'Authorization' => 'Bearer ' . $plainKey,
                'Content-Type' => 'application/json',
            ],
            'anthropic' => [
                'x-api-key' => $plainKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type' => 'application/json',
            ],
            'google' => [
                'x-goog-api-key' => $plainKey,
                'Content-Type' => 'application/json',
            ],
            default => [],
        };
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
