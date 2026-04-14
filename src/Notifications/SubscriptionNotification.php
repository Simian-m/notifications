<?php

namespace Simianbv\Notifications\Notifications;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SubscriptionNotification extends Notification
{
    private string $type;

    private ?int $entityId;

    private array $data;

    private string $message;

    private ?string $link;

    private static array $messageResolvers = [];

    private static array $linkResolvers = [];

    public function __construct(string $type, ?Model $entity = null, ?string $message = null, ?string $link = null, array $data = [])
    {
        $this->type = $type;
        $this->entityId = $entity?->getKey();
        $this->data = $data;

        if ($message) {
            $this->message = $message;
        } elseif (isset(static::$messageResolvers[$type])) {
            $this->message = call_user_func(static::$messageResolvers[$type], $entity, $data);
        } else {
            $this->message = "{$type} #{$this->entityId} is bijgewerkt.";
        }

        if ($link) {
            $this->link = $link;
        } elseif (isset(static::$linkResolvers[$type])) {
            $this->link = call_user_func(static::$linkResolvers[$type], $entity, $data);
        } else {
            $this->link = null;
        }
    }

    /**
     * Register a custom message resolver for a given type.
     */
    public static function resolveMessageUsing(string $type, Closure $resolver): void
    {
        static::$messageResolvers[$type] = $resolver;
    }

    /**
     * Register a custom link resolver for a given type.
     */
    public static function resolveLinkUsing(string $type, Closure $resolver): void
    {
        static::$linkResolvers[$type] = $resolver;
    }

    public function via(mixed $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(mixed $notifiable): array
    {
        return [
            'type' => $this->type,
            'entity_id' => $this->entityId,
            'message' => $this->message,
            'link' => $this->link,
            ...$this->data,
        ];
    }

    public function toBroadcast(mixed $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
