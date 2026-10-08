<?php

namespace App\Services\Ai;

class AiRouterService
{
    /**
     * Ambil konfigurasi provider & model untuk mode tertentu.
     * Moved from ChatController@getAiConfiguration
     */
    public function getAiConfiguration(string $mode): array
    {
        return match ($mode) {
            'smart' => [
                'provider' => config('sahaja_ai.providers.smart'),
                'model'    => config('sahaja_ai.models.smart'),
                'endpoint' => config('services.nvidia.endpoint'),
                'key'      => config('services.nvidia.key'),
                'timeout'  => 300
            ],
            'alpha' => [
                'provider' => config('sahaja_ai.providers.alpha'),
                'model'    => config('sahaja_ai.models.alpha'),
                'endpoint' => config('services.mistral.endpoint'),
                'key'      => config('services.mistral.key'),
                'timeout'  => 300
            ],
            'vision' => [
                'provider' => config('sahaja_ai.providers.vision'),
                'model'    => config('sahaja_ai.models.vision'),
                'endpoint' => config('services.nvidia.endpoint'),
                'key'      => config('services.nvidia.key'),
                'timeout'  => 300
            ],
            'coding' => [
                'provider' => config('sahaja_ai.providers.coding'),
                'model'    => config('sahaja_ai.models.coding'),
                'endpoint' => config('services.nvidia.endpoint'),
                'key'      => config('services.nvidia.key'),
                'timeout'  => 300
            ],
            'workspace' => [
                'provider' => config('sahaja_ai.providers.workspace'),
                'model'    => config('sahaja_ai.models.workspace'),
                'endpoint' => config('services.nvidia.endpoint'),
                'key'      => config('services.nvidia.key'),
                'timeout'  => 300
            ],
            default => [
                'provider' => config('sahaja_ai.providers.fast'),
                'model'    => config('sahaja_ai.models.fast'),
                'endpoint' => config('services.mistral.endpoint'),
                'key'      => config('services.mistral.key'),
                'timeout'  => 300
            ],
        };
    }
}
