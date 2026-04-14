<?php

namespace Simianbv\Notifications;

use Illuminate\Database\Eloquent\Model;
use Simianbv\Notifications\Models\NotificationSubscription;
use Simianbv\Notifications\Notifications\SubscriptionNotification;

class NotificationBroker
{
    public function notify(string $type, ?Model $entity = null, ?string $message = null, ?string $link = null, array $data = []): int
    {
        $subscribers = NotificationSubscription::subscribers($type, $entity?->getKey());

        if ($subscribers->isEmpty()) {
            return 0;
        }

        $notification = new SubscriptionNotification($type, $entity, $message, $link, $data);

        foreach ($subscribers as $employee) {
            $employee->notify($notification);
        }

        return $subscribers->count();
    }
}
