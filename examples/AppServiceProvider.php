<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Example of configuring other SIWE options
        config([
            'livewire-siwe.statement' => 'Sign in to ' . config('app.name') . ' with your Ethereum account',
            'livewire-siwe.chain_id' => 1, // Ethereum Mainnet
        ]);
    }
}
