<?php

namespace Scriptoshi\LivewireSiwe;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Scriptoshi\LivewireSiwe\Components\SiweLogin;

class LivewireSiweServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Register components
        Livewire::component('siwe-auth', SiweLogin::class);

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'livewire-siwe-migrations');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/livewire-siwe.php' => config_path('livewire-siwe.php'),
        ], 'livewire-siwe-config');

        // Load config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/livewire-siwe.php',
            'livewire-siwe'
        );

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/livewire-siwe'),
        ], 'livewire-siwe-views');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'livewire-siwe');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
