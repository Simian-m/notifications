<?php

namespace Simianbv\Notifications\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Simianbv\Notifications\Models\NotificationSubscription;

class NotificationSubscriptionController extends Controller
{
    public function index(string $type, ?int $entityId = null): JsonResponse
    {
        $query = NotificationSubscription::where('employee_id', Auth::user()->getKey())
            ->where('type', $type);

        if ($entityId) {
            $query->where('entity_id', $entityId);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function store(string $type, ?int $entityId = null): JsonResponse
    {
        $subscription = NotificationSubscription::firstOrCreate([
            'employee_id' => Auth::user()->getKey(),
            'type' => $type,
            'entity_id' => $entityId,
        ]);

        return response()->json(['message' => 'Abonnement aangemaakt.', 'subscription' => $subscription], 201);
    }

    public function destroy(string $type, ?int $entityId = null): JsonResponse
    {
        $query = NotificationSubscription::where('employee_id', Auth::user()->getKey())
            ->where('type', $type);

        if ($entityId) {
            $query->where('entity_id', $entityId);
        } else {
            $query->whereNull('entity_id');
        }

        $query->delete();

        return response()->json(['message' => 'Abonnement verwijderd.']);
    }

    public function subscribers(string $type, ?int $entityId = null): JsonResponse
    {
        $subscribers = NotificationSubscription::subscribers($type, $entityId);

        return response()->json(['data' => $subscribers]);
    }
}
