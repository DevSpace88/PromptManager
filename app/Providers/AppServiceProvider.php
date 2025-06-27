<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        Vite::prefetch(concurrency: 3);

        // HTTPS für Production erzwingen
        if ($this->app->environment('production') || config('app.force_https')) {
            URL::forceScheme('https');

            // Proxy-Headers für Railway vertrauen
            request()->server->set('HTTPS', 'on');
            request()->server->set('SERVER_PORT', 443);
        }
    }
}
