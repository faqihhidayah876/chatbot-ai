<?php

return [
    'mistral' => [
        'api_key'     => env('MISTRAL_API_KEY'),
        'endpoint'    => env('MISTRAL_ENDPOINT', 'https://api.mistral.ai/v1/chat/completions'),
        'model'       => env('AGENT_MISTRAL_MODEL', 'mistral-small-latest'),
        'max_tokens'  => (int) env('AGENT_MISTRAL_MAX_TOKENS', 800),
        'temperature' => (float) env('AGENT_MISTRAL_TEMPERATURE', 0.7),
    ],
    'history_limit' => (int) env('AGENT_HISTORY_LIMIT', 12),
    'admin_chat_id' => env('TELEGRAM_ADMIN_CHAT_ID'),
];
