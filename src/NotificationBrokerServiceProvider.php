<?php

namespace Simianbv\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationBrokerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(NotificationBroker::class);

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'notification-broker-migrations');
    }
}
