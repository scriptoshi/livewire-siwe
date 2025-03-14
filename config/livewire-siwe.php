<?php

return [

    'project_id' =>  env('APPKIT_PROJECT_ID'),
    /*
    |--------------------------------------------------------------------------
    | Default Redirect Route
    |--------------------------------------------------------------------------
    |
    | This defines where to redirect the user after successful authentication
    | through SIWE. You can override this in the component if needed.
    |
    */
    'redirect_route' => 'dashboard',

    /*
    |--------------------------------------------------------------------------
    | Message Options
    |--------------------------------------------------------------------------
    |
    | Configure the options for the SIWE message
    |
    */
    'message' => [
        'domain' => env('APP_URL', 'localhost'),
        'statement' => 'Sign in with Ethereum to authenticate with this application.',
        'uri' => env('APP_URL', 'localhost'),
        'version' => '1',
        'chain_id' => env('SIWE_CHAIN_ID', 1), // 1 for Ethereum mainnet
    ],

    /*
    |--------------------------------------------------------------------------
    | Auto Create Users
    |--------------------------------------------------------------------------
    |
    | If enabled, new users will be created automatically when they sign in
    | with Ethereum for the first time.
    |
    */
    'auto_create_users' => true,
];
