<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // 本番はHTTPS配信のため、生成されるURL・アセットパスをhttpsに固定する
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
