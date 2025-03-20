<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'], // Added storage/* to allow image access

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5173'], // Specify your frontend URL instead of wildcard

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Change to true for credentials support
];