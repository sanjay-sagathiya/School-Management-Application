<?php

namespace App\Providers;

use App\Actions\Notification\Contracts\Notification as ContractsNotification;
use App\Actions\Notification\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContractsNotification::class,
        Notification::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
