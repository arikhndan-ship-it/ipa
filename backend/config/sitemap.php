<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sitemap Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the sitemap generation.
    |
    */
    'enabled' => env('SITEMAP_ENABLED', true),
    
    'cache_ttl' => env('SITEMAP_CACHE_TTL', 3600), // 1 hour
    
    'url' => env('APP_URL', 'http://localhost') . '/sitemap.xml',
];
