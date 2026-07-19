<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Journalist;

class JournalistApiController extends Controller
{
    public function index()
    {
        $journalists = Journalist::with('user')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', '!=', 7);
            })
            ->orderBy('sort_order')
            ->get();
        return response()->json($journalists);
    }
}
