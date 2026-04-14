<?php

namespace Simianbv\Notifications\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static int notify(string $type, ?\Illuminate\Database\Eloquent\Model $entity = null, ?string $message = null, ?string $link = null, array $data = [])
 */
class NotificationBroker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Simianbv\Notifications\NotificationBroker::class;
    }
}
