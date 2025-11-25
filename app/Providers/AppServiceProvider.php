<?php

namespace App\Providers;
use App\Services\SmsService;
use App\Services\OrderSmsService;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
{
    $this->app->singleton(SmsService::class, function ($app) {
        return new SmsService();
    });

    $this->app->singleton(OrderSmsService::class, function ($app) {
        return new OrderSmsService($app->make(SmsService::class));
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
