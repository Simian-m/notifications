<?php

use Illuminate\Support\Facades\Route;
use Simianbv\Notifications\Http\Controllers\NotificationSubscriptionController;

Route::middleware(['api', 'auth:sanctum'])->group(function () {
    Route::get('subscriptions/{type}/{entityId?}', [NotificationSubscriptionController::class, 'index']);
    Route::post('subscriptions/{type}/{entityId?}', [NotificationSubscriptionController::class, 'store']);
    Route::delete('subscriptions/{type}/{entityId?}', [NotificationSubscriptionController::class, 'destroy']);
    Route::get('subscriptions/{type}/{entityId?}/subscribers', [NotificationSubscriptionController::class, 'subscribers']);
});
