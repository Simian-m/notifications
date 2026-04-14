<?php

namespace Simianbv\Notifications\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationSubscription extends Model
{
    protected $table = 'notification_subscriptions';

    protected $fillable = ['employee_id', 'type', 'entity_id'];

    /**
     * Override this method in the consuming application to return the correct employee model.
     */
    public static string $employeeModel = 'App\\Models\\Employee';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(static::$employeeModel, 'employee_id');
    }

    /**
     * Get all employees subscribed to a given type, optionally scoped to a specific entity.
     */
    public static function subscribers(string $type, ?int $entityId = null): Collection
    {
        $query = static::where('type', $type)
            ->where(function ($q) use ($entityId) {
                $q->whereNull('entity_id');
                if ($entityId) {
                    $q->orWhere('entity_id', $entityId);
                }
            });

        $employeeIds = $query->pluck('employee_id')->unique()->toArray();

        if (empty($employeeIds)) {
            return new Collection;
        }

        return (static::$employeeModel)::whereIn('id', $employeeIds)->get();
    }
}
