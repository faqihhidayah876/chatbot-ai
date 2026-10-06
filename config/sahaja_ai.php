<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SAHAJA AI — Model & Provider Mapping
    |--------------------------------------------------------------------------
    | Konfigurasi model dan provider untuk setiap mode AI.
    | Dibaca dari .env supaya mudah diganti tanpa ubah kode.
    */

    'models' => [
        'fast'      => env('MODEL_FAST', 'ministral-14b-latest'),
        'smart'     => env('MODEL_SMART', 'poolside/laguna-xs-2.1'),
        'coding'    => env('MODEL_CODING', 'poolside/laguna-xs-2.1'),
        'alpha'     => env('MODEL_ALPHA', 'ministral-14b-latest'),
        'vision'    => env('MODEL_VISION', ''),
        'workspace' => env('SAHAJA_LLM_MODEL', 'poolside/laguna-xs-2.1'),
    ],

    'providers' => [
        'fast'      => env('PROVIDER_FAST', 'mistral'),
        'smart'     => env('PROVIDER_SMART', 'nvidia'),
        'coding'    => env('PROVIDER_CODING', 'nvidia'),
        'alpha'     => env('PROVIDER_ALPHA', 'mistral'),
        'vision'    => env('PROVIDER_VISION', 'nvidia'),
        'workspace' => env('SAHAJA_LLM_PROVIDER', 'nvidia'),
    ],

];
