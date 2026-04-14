<?php

use Illuminate\Support\Facades\Route;
use Simianbv\Notifications\Http\Controllers\NotificationSubscriptionController;

Route::middleware(config('notifications.middleware', ['api']))
    ->prefix(config('notifications.route_prefix', ''))
    ->group(function () {
        Route::get('subscriptions', [NotificationSubscriptionController::class, 'me']);
        Route::get('subscriptions/{type}/{entityId?}', [NotificationSubscriptionController::class, 'index']);
        Route::post('subscriptions/{type}/{entityId?}', [NotificationSubscriptionController::class, 'store']);
        Route::delete('subscriptions/{type}/{entityId?}', [NotificationSubscriptionController::class, 'destroy']);
        Route::get('subscriptions/{type}/{entityId?}/subscribers', [NotificationSubscriptionController::class, 'subscribers']);
    });
