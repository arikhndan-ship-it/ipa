<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\Notification;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    public function index()
    {
        $notifications = CacheHelper::getApiNotifications();
        $unreadCount = CacheHelper::getUnreadNotificationCount();

        // The notifications already have localized_title and localized_body via model appends
        return response()->json([
            'data' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);
        CacheHelper::clearNotificationCaches();

        return response()->json(['success' => true]);
    }

    public function count()
    {
        return response()->json([
            'unread_count' => CacheHelper::getUnreadNotificationCount(),
        ]);
    }

    public function registerDevice(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:500',
            'platform' => 'nullable|string|in:android,ios',
            'locale' => 'nullable|string|in:en,ckb',
        ]);

        $device = PushNotificationService::registerDevice(
            token: $validated['token'],
            platform: $validated['platform'] ?? 'android',
            locale: $validated['locale'] ?? 'ckb',
        );

        return response()->json(['success' => true, 'id' => $device->id]);
    }

    public function unregisterDevice(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|max:500',
        ]);

        PushNotificationService::unregisterDevice($validated['token']);

        return response()->json(['success' => true]);
    }
}
