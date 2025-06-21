<?php

return [

    'project_id' =>  env('APPKIT_PROJECT_ID'),
    /*
    |--------------------------------------------------------------------------
    | Default Redirect URL
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

    'statement' => 'Sign in with Ethereum to authenticate with this application.',
    'chain_id' => env('SIWE_CHAIN_ID', 1),

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |Defaults to App\User\Model;
    |
    */
    'user_model' => \App\Models\User::class
];
