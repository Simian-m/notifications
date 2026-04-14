<?php

namespace Simianbv\Notifications\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Simianbv\Notifications\Models\NotificationSubscription;

class NotificationSubscriptionController extends Controller
{
    public function me(): JsonResponse
    {
        try {
            $subscriptions = NotificationSubscription::where('employee_id', Auth::user()->getKey())
                ->orderBy('type')
                ->get()
                ->groupBy('type');

            return response()->json(['data' => $subscriptions]);
        } catch (Exception) {
            return response()->json(['data' => []]);
        }
    }

    public function index(string $type, ?int $entityId = null): JsonResponse
    {
        try {
            $query = NotificationSubscription::where('employee_id', Auth::user()->getKey())
                ->where('type', $type);

            if ($entityId) {
                $query->where('entity_id', $entityId);
            } else {
                $query->whereNull('entity_id');
            }

            return response()->json(['data' => $query->get()]);
        } catch (Exception) {
            return response()->json(['data' => []]);
        }
    }

    public function store(string $type, ?int $entityId = null): JsonResponse
    {
        try {
            $subscription = NotificationSubscription::firstOrCreate([
                'employee_id' => Auth::user()->getKey(),
                'type' => $type,
                'entity_id' => $entityId,
            ]);

            return response()->json(['message' => 'Abonnement aangemaakt.', 'subscription' => $subscription], 201);
        } catch (Exception) {
            return response()->json(['data' => []]);
        }
    }

    public function destroy(string $type, ?int $entityId = null): JsonResponse
    {
        try {
            $query = NotificationSubscription::where('employee_id', Auth::user()->getKey())
                ->where('type', $type);

            if ($entityId) {
                $query->where('entity_id', $entityId);
            } else {
                $query->whereNull('entity_id');
            }

            $query->delete();

            return response()->json(['message' => 'Abonnement verwijderd.']);
        } catch (Exception) {
            return response()->json(['data' => []]);
        }
    }

    public function subscribers(string $type, ?int $entityId = null): JsonResponse
    {
        try {
            $subscribers = NotificationSubscription::subscribers($type, $entityId);

            return response()->json(['data' => $subscribers]);
        } catch (Exception) {
            return response()->json(['data' => []]);
        }
    }
}
