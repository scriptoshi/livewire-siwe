<?php

return [
    /**
     * The AppKit project ID from reown.xyz
     */
    'project_id' => env('APPKIT_PROJECT_ID', ''),

    /**
     * The statement that will be displayed to users during login
     */
    'statement' => env('SIWE_STATEMENT', 'Sign in with your Ethereum account to access this application'),

    /**
     * The Ethereum chain ID to use for SIWE
     * 1 = Ethereum Mainnet
     * 5 = Goerli Testnet
     * 11155111 = Sepolia Testnet
     */
    'chain_id' => env('SIWE_CHAIN_ID', 1),

    /**
     * The route or path to redirect to after successful authentication
     */
    'redirect_url' => env('SIWE_REDIRECT_URL', 'dashboard'),

    /**
     * Whether the redirect_url should be treated as a route name (true) or a direct path (false)
     */
    'redirect_is_route' => env('SIWE_REDIRECT_IS_ROUTE', true),

    /**
     * The User model to use for authentication
     */
    'user_model' => env('SIWE_USER_MODEL', \App\Models\User::class),

    /**
     * The required fields for the user model
     * These will be included in the user creation
     */
    'required_user_fields' => ['name', 'address'],

    /**
     * Whether to fire the Registered event after user creation
     */
    'fire_registered_event' => true,

    /**
     * Optional callback to customize user data before registration
     * 
     * Example:
     * 'user_data_callback' => function(string $address, array $userData) {
     *     $userData['name'] = 'ETH User: ' . substr($address, 0, 6);
     *     return $userData;
     * }
     */
    'user_data_callback' => null,

    /**
     * Optional callback that runs after successful login
     * 
     * Example:
     * 'post_login_callback' => function($user) {
     *     // Update last login timestamp, etc.
     * }
     */
    'post_login_callback' => null,

    /**
     * Optional callback that runs after successful registration
     * 
     * Example:
     * 'post_register_callback' => function($user) {
     *     // Assign default role, send welcome email, etc.
     * }
     */
    'post_register_callback' => null,
];
