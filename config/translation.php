<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Connecteur de traduction
    |--------------------------------------------------------------------------
    |
    | CATALOG_TRANSLATION_PROVIDER choisit le service par défaut du bouton
    | Traduire : cursor, openai, deepseek, anthropic, gemini, mistral, groq,
    | azure, openrouter ou ollama. Le menu du bouton permet d’en changer.
    |
    */

    'provider' => env('CATALOG_TRANSLATION_PROVIDER', 'cursor'),

    'providers' => [

        'cursor' => [
            'label' => 'Cursor',
            'protocol' => 'cursor',
        ],

        'openai' => [
            'label' => 'OpenAI',
            'protocol' => 'openai',
            'url' => env('OPENAI_TRANSLATION_URL', 'https://api.openai.com/v1/chat/completions'),
            'key' => env('OPENAI_API_KEY'),
            'key_name' => 'OPENAI_API_KEY',
            'model' => env('OPENAI_TRANSLATION_MODEL', 'gpt-5.4-nano'),
            'auth' => 'bearer',
            'json' => true,
        ],

        'deepseek' => [
            'label' => 'DeepSeek',
            'protocol' => 'openai',
            'url' => env('DEEPSEEK_TRANSLATION_URL', 'https://api.deepseek.com/chat/completions'),
            'key' => env('DEEPSEEK_API_KEY'),
            'key_name' => 'DEEPSEEK_API_KEY',
            'model' => env('DEEPSEEK_TRANSLATION_MODEL', 'deepseek-chat'),
            'auth' => 'bearer',
            'json' => true,
        ],

        'anthropic' => [
            'label' => 'Anthropic',
            'protocol' => 'anthropic',
            'url' => env('ANTHROPIC_TRANSLATION_URL', 'https://api.anthropic.com/v1/messages'),
            'key' => env('ANTHROPIC_API_KEY'),
            'key_name' => 'ANTHROPIC_API_KEY',
            'model' => env('ANTHROPIC_TRANSLATION_MODEL', 'claude-sonnet-4-5'),
            'auth' => 'anthropic',
        ],

        'gemini' => [
            'label' => 'Gemini',
            'protocol' => 'gemini',
            'url' => env('GEMINI_TRANSLATION_URL', 'https://generativelanguage.googleapis.com/v1beta/models'),
            'key' => env('GEMINI_API_KEY'),
            'key_name' => 'GEMINI_API_KEY',
            'model' => env('GEMINI_TRANSLATION_MODEL', 'gemini-2.5-flash'),
            'auth' => 'query',
        ],

        'mistral' => [
            'label' => 'Mistral',
            'protocol' => 'openai',
            'url' => env('MISTRAL_TRANSLATION_URL', 'https://api.mistral.ai/v1/chat/completions'),
            'key' => env('MISTRAL_API_KEY'),
            'key_name' => 'MISTRAL_API_KEY',
            'model' => env('MISTRAL_TRANSLATION_MODEL', 'mistral-small-latest'),
            'auth' => 'bearer',
            'json' => true,
        ],

        'groq' => [
            'label' => 'Groq',
            'protocol' => 'openai',
            'url' => env('GROQ_TRANSLATION_URL', 'https://api.groq.com/openai/v1/chat/completions'),
            'key' => env('GROQ_API_KEY'),
            'key_name' => 'GROQ_API_KEY',
            'model' => env('GROQ_TRANSLATION_MODEL', 'llama-3.3-70b-versatile'),
            'auth' => 'bearer',
        ],

        'azure' => [
            'label' => 'Azure OpenAI',
            'protocol' => 'openai',
            'url' => env('AZURE_OPENAI_URL'),
            'url_name' => 'AZURE_OPENAI_URL',
            'key' => env('AZURE_OPENAI_API_KEY'),
            'key_name' => 'AZURE_OPENAI_API_KEY',
            'model' => env('AZURE_OPENAI_TRANSLATION_MODEL'),
            'auth' => 'api-key',
            'json' => true,
        ],

        'openrouter' => [
            'label' => 'OpenRouter',
            'protocol' => 'openai',
            'url' => env('OPENROUTER_TRANSLATION_URL', 'https://openrouter.ai/api/v1/chat/completions'),
            'key' => env('OPENROUTER_API_KEY'),
            'key_name' => 'OPENROUTER_API_KEY',
            'model' => env('OPENROUTER_TRANSLATION_MODEL', 'openai/gpt-5.4-nano'),
            'auth' => 'bearer',
        ],

        'ollama' => [
            'label' => 'Ollama',
            'protocol' => 'openai',
            'url' => env('OLLAMA_TRANSLATION_URL', 'http://127.0.0.1:11434/v1/chat/completions'),
            'key' => env('OLLAMA_API_KEY'),
            'model' => env('OLLAMA_TRANSLATION_MODEL', 'llama3.2'),
            'auth' => 'none',
        ],

    ],

];
