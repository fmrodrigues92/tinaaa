<?php

return [
    'ai' => [
        'provider' => env('TINAAA_AI_PROVIDER', 'extractive'),
        'default_model' => env('TINAAA_AI_MODEL', 'gpt-5-mini'),
        'ollama' => [
            'base_url' => env('OLLAMA_BASE_URL', 'http://host.docker.internal:11434'),
            'model' => env('OLLAMA_MODEL', 'llama3.1'),
        ],
    ],
];
