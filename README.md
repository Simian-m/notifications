# Notification Broker

Subscription-based notification broker for Laravel. Employees subscribe to notification types (optionally scoped to a specific entity) and receive database + broadcast notifications when events occur.

## Installation

```bash
composer require simianbv/notification-broker
```

The service provider is auto-discovered. Publish and run the migration:

```bash
php artisan vendor:publish --tag=notification-broker-migrations
php artisan migrate
```

## Configuration

By default the package assumes your user model is `App\Models\Employee`. To change this:

```php
use Simianbv\Notifications\Models\NotificationSubscription;

NotificationSubscription::$employeeModel = \App\Models\User::class;
```

## Usage

### Sending notifications

```php
use Simianbv\Notifications\Facades\NotificationBroker;

// Notify all subscribers of a type
NotificationBroker::notify('orders', $order, 'New order placed.');

// With a link and extra data
NotificationBroker::notify('tickets', $ticket, 'Ticket updated.', '/tickets/42', ['priority' => 'high']);
```

### Custom message & link resolvers

Register resolvers in a service provider to generate messages and links dynamically:

```php
use Simianbv\Notifications\Notifications\SubscriptionNotification;

SubscriptionNotification::resolveMessageUsing('orders', fn ($order, $data) => "Order #{$order->id} was updated.");
SubscriptionNotification::resolveLinkUsing('orders', fn ($order, $data) => "/orders/{$order->id}");
```

When resolvers are registered you can omit the `$message` and `$link` arguments in `notify()`.

## API routes

All routes require `auth:sanctum` middleware.

| Method   | URI                                        | Description                           |
|----------|--------------------------------------------|---------------------------------------|
| `GET`    | `/subscriptions/{type}/{entityId?}`        | List current user's subscriptions     |
| `POST`   | `/subscriptions/{type}/{entityId?}`        | Subscribe current user                |
| `DELETE` | `/subscriptions/{type}/{entityId?}`        | Unsubscribe current user              |
| `GET`    | `/subscriptions/{type}/{entityId?}/subscribers` | List all subscribers            |

## License

MIT
