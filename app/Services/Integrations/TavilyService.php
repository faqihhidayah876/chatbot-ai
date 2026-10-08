<?php

namespace App\Services\Integrations;

use Illuminate\Support\Facades\Http;

class TavilyService
{
    /**
     * Fetch web search context dari Tavily.
     * Moved from ChatController@fetchTavilyContext
     */
    public function fetchContext(string $query): string
    {
        try {
            $response = Http::withOptions([
                'verify' => config('services.ssl.ca_bundle'),
                'timeout' => 10,
            ])->post('https://api.tavily.com/search', [
                'api_key' => config('services.tavily.key'),
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
                        $context .= "- " . ($res['title'] ?? 'Artikel') . ": " 
                                  . ($res['content'] ?? '') . "\n";
                    }
                    return $context;
                }
            }
        } catch (\Exception $e) {
            return "";
        }

        return "";
    }
}
