<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Send push notification to a specific device token.
     */
    public static function sendToDevice(
        string $token,
        string $titleEn,
        string $titleCkb,
        ?string $bodyEn = null,
        ?string $bodyCkb = null,
        array $data = [],
        string $locale = 'ckb',
    ): bool {
        $title = $locale === 'ckb' ? $titleCkb : $titleEn;
        $body = $locale === 'ckb' ? ($bodyCkb ?? $bodyEn) : ($bodyEn ?? $bodyCkb);

        return self::sendFcmMessage($token, $title, $body, $data);
    }

    /**
     * Send push notification to all registered devices.
     * Each device gets the notification in its registered locale.
     */
    public static function sendToAllDevices(
        string $titleEn,
        string $titleCkb,
        ?string $bodyEn = null,
        ?string $bodyCkb = null,
        array $data = [],
    ): void {
        // Get devices grouped by locale for efficiency
        $ckbDevices = DeviceToken::where('locale', 'ckb')->pluck('token');
        $enDevices = DeviceToken::where('locale', 'en')->pluck('token');

        foreach ($ckbDevices as $token) {
            try {
                self::sendFcmMessage($token, $titleCkb, $bodyCkb ?? $bodyEn, $data);
            } catch (\Exception $e) {
                Log::error("FCM send failed for token {$token}: " . $e->getMessage());
            }
        }

        foreach ($enDevices as $token) {
            try {
                self::sendFcmMessage($token, $titleEn, $bodyEn ?? $bodyCkb, $data);
            } catch (\Exception $e) {
                Log::error("FCM send failed for token {$token}: " . $e->getMessage());
            }
        }
    }

    /**
     * Send an FCM message via Firebase HTTP v1 API.
     * Uses server key from config (FCM legacy API for simplicity).
     */
    private static function sendFcmMessage(
        string $token,
        string $title,
        ?string $body,
        array $data = [],
    ): bool {
        $serverKey = config('services.fcm.server_key');
        if (empty($serverKey) || str_contains($serverKey, 'placeholder')) {
            Log::warning('FCM not configured with real key. Skipping push.');
            return false;
        }

        // Build notification payload with data for navigation
        $payload = [
            'to' => $token,
            'notification' => [
                'title' => $title,
                'body' => $body ?? '',
                'sound' => 'default',
                'badge' => '1',
            ],
            'data' => array_merge([
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'sound' => 'default',
            ], $data),
            'priority' => 'high',
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        if (!$response->successful()) {
            Log::error('FCM API error: ' . $response->body());
            return false;
        }

        return true;
    }

    /**
     * Register a new device token.
     */
    public static function registerDevice(
        string $token,
        string $platform = 'android',
        string $locale = 'ckb',
    ): DeviceToken {
        return DeviceToken::updateOrCreate(
            ['token' => $token],
            ['platform' => $platform, 'locale' => $locale]
        );
    }

    /**
     * Unregister a device token.
     */
    public static function unregisterDevice(string $token): void
    {
        DeviceToken::where('token', $token)->delete();
    }
}
