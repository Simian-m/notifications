<?php

namespace Simianbv\Notifications;

use Illuminate\Support\ServiceProvider;

class NotificationBrokerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/notifications.php', 'notifications');

        $this->app->singleton(NotificationBroker::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/notifications.php' => config_path('notifications.php'),
        ], 'notifications-config');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'notifications-migrations');
    }
}
