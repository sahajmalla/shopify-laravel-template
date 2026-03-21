<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shopify API Credentials
    |--------------------------------------------------------------------------
    |
    | Provided by the Shopify CLI when running `shopify app dev`. Do not
    | commit these to version control. For secret rotation, set the old
    | secret in SHOPIFY_OLD_API_SECRET so existing sessions can migrate.
    |
    */

    'api_key' => env('SHOPIFY_API_KEY', ''),
    'api_secret' => env('SHOPIFY_API_SECRET', ''),
    'old_api_secret' => env('SHOPIFY_OLD_API_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | App Home
    |--------------------------------------------------------------------------
    */

    'app_home_patch_id_token_path' => '/auth/patch-id-token',

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | Shopify API version for GraphQL and webhooks (e.g. 2025-01).
    |
    */

    'api_version' => env('SHOPIFY_API_VERSION', '2026-04'),

];
