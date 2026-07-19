<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsHelper
{
    protected static ?array $settings = null;

    public static function get(string $key, mixed $default = null): mixed
    {
        if (static::$settings === null) {
            static::$settings = Cache::remember('all_settings', 86400, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        }

        return static::$settings[$key] ?? $default;
    }

    public static function all(): array
    {
        if (static::$settings === null) {
            static::$settings = Cache::remember('all_settings', 86400, function () {
                return Setting::all()->pluck('value', 'key')->toArray();
            });
        }

        return static::$settings;
    }

    public static function clearCache(): void
    {
        Cache::forget('all_settings');
        static::$settings = null;
    }
}
