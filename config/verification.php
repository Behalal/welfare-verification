<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Verification Driver
    |--------------------------------------------------------------------------
    |
    | Supported: "identitypass", "null"
    |
    */
    'default' => env('VERIFICATION_DRIVER', 'identitypass'),

    /*
    |--------------------------------------------------------------------------
    | Verification Drivers
    |--------------------------------------------------------------------------
    */
    'drivers' => [
        'identitypass' => [
            'base_url' => env('IDENTITY_PASS_BASE_URL', 'https://api.myidentitypass.com/'),
            'api_key' => env('IDENTITY_PASS_API_KEY', ''),
            'app_id' => env('IDENTITY_PASS_APP_ID', ''),
            'timeout' => (int) env('IDENTITY_PASS_TIMEOUT', 30),
            'sandbox' => env('IDENTITY_PASS_SANDBOX', true),
        ],

        'null' => [
            // Returns successful mock responses for testing/local environments
            'simulate_success' => env('VERIFICATION_SIMULATE_SUCCESS', true),
        ],
    ],
];
