<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('articles')->orderBy('sort_order')->get();
        return response()->json($categories);
    }
    
    public function show(Category $category)
    {
        $category->loadCount('articles');
        return response()->json($category);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.en.name' => 'required|string|max:255',
            'translations.ckb.name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $category = new Category();
        $category->slug = Str::slug($validated['translations']['en']['name']) . '-' . Str::random(3);
        $category->parent_id = $validated['parent_id'] ?? null;
        $category->sort_order = $validated['sort_order'] ?? 0;
        $category->is_active = $request->boolean('is_active', true);
        $category->save();
        
        foreach ($validated['translations'] as $locale => $translation) {
            $category->translateOrNew($locale)->name = $translation['name'];
            if (isset($translation['description'])) {
                $category->translateOrNew($locale)->description = $translation['description'];
            }
        }
        $category->push();
        
        return response()->json($category, 201);
    }
    
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'translations' => 'sometimes|array',
            'translations.en.name' => 'sometimes|string|max:255',
            'translations.ckb.name' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        if ($request->has('parent_id')) $category->parent_id = $validated['parent_id'];
        if ($request->has('sort_order')) $category->sort_order = $validated['sort_order'];
        if ($request->has('is_active')) $category->is_active = $request->boolean('is_active');
        
        if ($request->has('translations')) {
            foreach ($validated['translations'] as $locale => $translation) {
                if (isset($translation['name'])) $category->translateOrNew($locale)->name = $translation['name'];
                if (isset($translation['description'])) $category->translateOrNew($locale)->description = $translation['description'];
            }
        }
        
        $category->push();
        
        return response()->json($category);
    }
    
    public function destroy(Category $category)
    {
        if ($category->articles()->count() > 0) {
            return response()->json(['message' => 'Cannot delete category with articles'], 409);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
