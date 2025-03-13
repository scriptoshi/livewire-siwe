<?php

namespace Scriptoshi\LivewireSiwe;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Scriptoshi\LivewireSiwe\Components\SiweLogin;

class LivewireSiweServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        // Register package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'livewire-siwe');

        // Register Livewire components
        $this->registerLivewireComponents();
        
        // Register publishable resources
        $this->registerPublishableResources();
    }

    /**
     * Register Livewire components
     * 
     * @return void
     */
    protected function registerLivewireComponents()
    {
        // Only register the component if Livewire is available
        if (class_exists(Livewire::class)) {
            Livewire::component('siwe-login', SiweLogin::class);
        }
    }

    /**
     * Register publishable resources
     * 
     * @return void
     */
    protected function registerPublishableResources()
    {
        // JavaScript resources
        if ($this->app->runningInConsole()) {
            // JS assets for Vite
            $this->publishes([
                __DIR__.'/../resources/js' => resource_path('vendor/scriptoshi/livewire-siwe/js'),
            ], 'livewire-siwe-assets');

            // Config
            $this->publishes([
                __DIR__.'/../config/livewire-siwe.php' => config_path('livewire-siwe.php'),
            ], 'livewire-siwe-config');

            // Migrations (optional)
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'livewire-siwe-migrations');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        // Register the configuration
        $this->mergeConfigFrom(
            __DIR__.'/../config/livewire-siwe.php', 'livewire-siwe'
        );
    }
}
