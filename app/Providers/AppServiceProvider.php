<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        /*
         * Livewire's SupportNavigate adds data-navigate-track="reload" to Vite script/style tags so
         * assets re-run after each wire:navigate. That re-injects the full app bundle on every
         * navigation and delays livewire:navigated (NProgress stays up). This app uses a single
         * global entry (resources/js/app.js); dropping the attribute keeps assets loaded across
         * navigations for faster transitions. Override Livewire by merging null (last resolver wins).
         *
         * @see \Livewire\Features\SupportNavigate\SupportNavigate::provide()
         */
        Vite::useScriptTagAttributes(fn () => [
            'data-navigate-track' => null,
        ]);
        Vite::useStyleTagAttributes(fn () => [
            'data-navigate-track' => null,
        ]);
    }
}
