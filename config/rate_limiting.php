<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Limiters registered in AppServiceProvider. Use middleware aliases:
    | throttle:api (60/min), throttle:strict (20/min), throttle:login (5/min).
    |
    */

    'api' => [
        'limit' => (int) env('RATE_LIMIT_API', 60),
    ],

    'strict' => [
        'limit' => (int) env('RATE_LIMIT_STRICT', 20),
    ],

    'login' => [
        'limit' => (int) env('RATE_LIMIT_LOGIN', 5),
    ],
];
