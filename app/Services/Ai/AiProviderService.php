<?php

namespace App\Services\Ai;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiProviderService
{
    /**
     * Panggil API provider AI (OpenAI-compatible).
     * Moved from ChatController@callOpenAiCompatible
     */
    public function callOpenAiCompatible(
        string $endpoint,
        string $key,
        string $model,
        array $messages,
        int $timeout,
        int $maxTokens = 4096
    ): ?string {
        $response = Http::withOptions([
            'verify' => config('services.ssl.ca_bundle'),
            'http_errors' => true,
            'timeout' => $timeout,
            'connect_timeout' => 10,
        ])
            ->withToken($key)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($endpoint, [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.6,
                'max_tokens' => $maxTokens,
            ]);

        if (!$response->successful()) {
            throw new \Exception("HTTP {$response->status()} | Response: " . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? null;
    }
}
