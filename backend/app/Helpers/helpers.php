<?php

use App\Helpers\SettingsHelper;

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return SettingsHelper::get($key, $default);
    }
}

if (!function_exists('all_settings')) {
    function all_settings(): array
    {
        return SettingsHelper::all();
    }
}
