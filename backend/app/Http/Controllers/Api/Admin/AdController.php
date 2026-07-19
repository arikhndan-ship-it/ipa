<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::orderBy('sort_order')->get();
        return response()->json($ads);
    }
    
    public function show(Ad $ad)
    {
        return response()->json($ad);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:banner,sidebar,in-article',
            'image_path' => 'required|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $ad = Ad::create($validated);
        
        return response()->json($ad, 201);
    }
    
    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:banner,sidebar,in-article',
            'image_path' => 'sometimes|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $ad->update($validated);
        
        return response()->json($ad);
    }
    
    public function destroy(Ad $ad)
    {
        $ad->delete();
        return response()->json(['message' => 'Ad deleted']);
    }
}
