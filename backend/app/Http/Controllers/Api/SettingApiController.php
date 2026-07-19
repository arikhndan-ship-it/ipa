<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingApiController extends Controller
{
    public function index()
    {
        $settings = CacheHelper::getApiSettings();
        return response()->json($settings);
    }
}
