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
        if (config('app.force_https', false)) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (request()->header('x-forwarded-proto') == 'https') {
            URL::forceScheme('https');
        }
    }
}
