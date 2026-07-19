<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting for API endpoints to prevent abuse.
    |
    */

    'api' => [
        // General API rate limit: 60 requests per minute
        'limit' => 60,
        'decay_minutes' => 1,
    ],

    'api_auth' => [
        // Auth endpoints: 10 requests per minute (prevent brute force)
        'limit' => 10,
        'decay_minutes' => 1,
    ],

    'contact' => [
        // Contact form: 3 requests per minute (prevent spam)
        'limit' => 3,
        'decay_minutes' => 1,
    ],

    'comment' => [
        // Comment submission: 5 requests per minute
        'limit' => 5,
        'decay_minutes' => 1,
    ],
];
