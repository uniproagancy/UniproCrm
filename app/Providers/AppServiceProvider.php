<?php

namespace App\Providers;

use App\Services\SMSSenderService;
use App\Services\SSService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('ss_service', function ($app) {
            return new SSService();
        });
        $this->app->singleton('sms_sender_service', function ($app) {
            return new SMSSenderService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
