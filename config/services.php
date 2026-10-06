<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // ============ AI Providers ============
    'nvidia' => [
        'key'      => env('NVIDIA_API_KEY'),
        'endpoint' => env('NVIDIA_ENDPOINT', 'https://integrate.api.nvidia.com/v1/chat/completions'),
    ],

    'mistral' => [
        'key'      => env('MISTRAL_API_KEY'),
        'endpoint' => env('MISTRAL_ENDPOINT', 'https://api.mistral.ai/v1/chat/completions'),
    ],

    'cerebras' => [
        'key'      => env('CEREBRAS_API_KEY'),
        'endpoint' => env('CEREBRAS_ENDPOINT', 'https://api.cerebras.ai/v1/chat/completions'),
    ],

    'tavily' => [
        'key' => env('TAVILY_API_KEY'),
    ],

    'cloudflare' => [
        'api_token'       => env('CLOUDFLARE_API_TOKEN'),
        'imagen_endpoint' => env('CLOUDFLARE_IMAGEN_ENDPOINT'),
    ],

    'freetheai' => [
        'key'      => env('FREETHEAI_API_KEY'),
        'base_url' => env('FREETHEAI_BASE_URL'),
        'model'    => env('FREETHEAI_MODEL', 'img/gpt-image-2'),
    ],

    'ssl' => [
        // true = pakai CA bundle default sistem
        // false = matikan verify (untuk local dev)
        // string = path ke cacert.pem custom
        'ca_bundle' => env('SSL_CA_BUNDLE', true),
    ],

];
