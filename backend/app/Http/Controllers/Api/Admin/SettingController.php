<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = CacheHelper::getApiSettings();
        return response()->json($settings);
    }
    
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:settings,key',
            'settings.*.value' => 'nullable|string',
        ]);
        
        foreach ($validated['settings'] as $setting) {
            Setting::where('key', $setting['key'])->update(['value' => $setting['value'] ?? '']);
        }
        
        CacheHelper::clearSettingsCaches();
        
        return response()->json(['message' => 'Settings updated']);
    }
}
