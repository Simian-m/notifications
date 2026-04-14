# Notification Broker

Subscription-based notification broker for Laravel. Employees subscribe to notification types (optionally scoped to a specific entity) and receive database + broadcast notifications when events occur.

## Installation

```bash
composer require simianbv/notifications
```

The service provider is auto-discovered. Run the migration:

```bash
php artisan migrate
```

Or publish it first if you want to customize:

```bash
php artisan vendor:publish --tag=notifications-migrations
php artisan migrate
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=notifications-config
```

This creates `config/notifications.php`:

```php
return [
    // Middleware applied to the subscription routes
    'middleware' => ['api'],

    // Optional route prefix (e.g. 'api/v1')
    'route_prefix' => '',

    // The model used for subscribers
    'employee_model' => \App\Models\Employee::class,
];
```

## Usage

### Sending notifications

```php
use Simianbv\Notifications\Facades\NotificationBroker;

// Notify all subscribers of a type
NotificationBroker::notify('Refund', $refund, 'Terugbetaling verwerkt.');

// With a link
NotificationBroker::notify('Refund', $refund, 'Terugbetaling verwerkt.', '/finance/refunds/42');

// With extra data
NotificationBroker::notify('Order', $order, 'Order verzonden.', '/sales/orders/123', ['status' => 'shipped']);

// Type-only, no specific entity
NotificationBroker::notify('BankImport', null, 'Nieuwe bankimport verwerkt.');
```

The method returns the number of subscribers notified.

### Custom message and link resolvers

Register resolvers in a service provider to generate messages and links dynamically per type:

```php
use Simianbv\Notifications\Notifications\SubscriptionNotification;

SubscriptionNotification::resolveMessageUsing('Refund', function ($refund, $data) {
    return "Refund #{$refund->getKey()} van {$refund->amount} is verwerkt.";
});

SubscriptionNotification::resolveLinkUsing('Refund', function ($refund, $data) {
    return "/finance/refunds/{$refund->getKey()}";
});
```

When resolvers are registered you can omit the `$message` and `$link` arguments:

```php
NotificationBroker::notify('Refund', $refund);
```

Without resolvers or explicit arguments, the default message is `"{Type} #{id} is bijgewerkt."` and the link is `null`.

## API routes

The routes use the middleware and prefix from the config.

| Method   | URI                                             | Description                       |
|----------|-------------------------------------------------|-----------------------------------|
| `GET`    | `/subscriptions/{type}/{entityId?}`             | List current user's subscriptions |
| `POST`   | `/subscriptions/{type}/{entityId?}`             | Subscribe current user            |
| `DELETE` | `/subscriptions/{type}/{entityId?}`             | Unsubscribe current user          |
| `GET`    | `/subscriptions/{type}/{entityId?}/subscribers` | List all subscribers              |

### Examples

```
POST   /subscriptions/Refund          → subscribe to all refunds
POST   /subscriptions/Refund/42       → subscribe to refund #42
DELETE /subscriptions/Refund           → unsubscribe from all refunds
DELETE /subscriptions/Refund/42        → unsubscribe from refund #42
GET    /subscriptions/Refund           → list my refund subscriptions
GET    /subscriptions/Refund/42/subscribers → who is subscribed to refund #42
```

## License

MIT
