<?php

namespace App\Services\Ai;

class QueryAnalyzerService
{
    /**
     * Deteksi apakah query sederhana (untuk routing ke fast mode).
     */
    public function isSimpleQuery(string $text): bool
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
        return $wordCount <= 15;
    }

    /**
     * Deteksi tipe input user (workspace, github, image, dsb).
     */
    public function detectInputType(array $input): array
    {
        return [
            'has_image' => !empty($input['image_data_array'] ?? []),
            'has_github' => !empty($input['github_repo'] ?? ''),
            'is_workspace' => str_contains($input['message'] ?? '', '[REFERENSI DOKUMEN]'),
        ];
    }

    /**
     * Tentukan mode AI yang aktif berdasarkan input & preferensi user.
     */
    public function determineActiveMode(
        string $userMessage,
        string $manualMode,
        array $inputType
    ): string {
        if ($manualMode !== 'auto') {
            return $manualMode;
        }

        if ($inputType['is_workspace']) return 'workspace';
        if ($inputType['has_image']) return 'fast';
        if ($inputType['has_github']) return 'coding';
        if (!$this->isSimpleQuery($userMessage)) return 'smart';

        return 'fast';
    }
}
