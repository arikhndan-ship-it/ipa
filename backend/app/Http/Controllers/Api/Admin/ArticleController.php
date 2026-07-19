<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['author', 'categories']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('translations', function ($t) use ($search) {
                    $t->where('title', 'like', "%{$search}%");
                });
            });
        }
        
        $articles = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));
        
        return response()->json($articles);
    }
    
    public function show(Article $article)
    {
        $article->load(['author', 'categories', 'translations']);
        return response()->json($article);
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'translations' => 'required|array',
            'translations.en.title' => 'required|string|max:255',
            'translations.en.body' => 'required|string',
            'translations.ckb.title' => 'required|string|max:255',
            'translations.ckb.body' => 'required|string',
            'categories' => 'sometimes|array',
            'categories.*' => 'exists:categories,id',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
        ]);
        
        $article = new Article();
        $article->author_id = $request->user()->id;
        $article->slug = Str::slug($validated['translations']['en']['title']) . '-' . Str::random(5);
        $article->status = 'draft';
        $article->is_featured = $request->boolean('is_featured', false);
        $article->is_breaking = $request->boolean('is_breaking', false);
        $article->meta_title = $validated['meta_title'] ?? null;
        $article->meta_description = $validated['meta_description'] ?? null;
        $article->og_image = $validated['og_image'] ?? null;
        $article->published_at = null;
        $article->save();
        
        // Set translations and persist them (push() cascades to relations)
        foreach ($validated['translations'] as $locale => $translation) {
            $article->translateOrNew($locale)->title = $translation['title'];
            $article->translateOrNew($locale)->body = $translation['body'];
            $article->translateOrNew($locale)->excerpt = $translation['excerpt'] ?? Str::limit(strip_tags($translation['body']), 200);
        }
        $article->push();
        
        // Attach categories
        if ($request->has('categories')) {
            $article->categories()->attach($validated['categories']);
        }
        
        $article->load(['author', 'categories', 'translations']);

        // Fire notification AFTER translations are persisted
        \App\Services\NotificationService::articleCreated($article);

        return response()->json($article, 201);
    }
    
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'translations' => 'sometimes|array',
            'translations.en.title' => 'sometimes|string|max:255',
            'translations.en.body' => 'sometimes|string',
            'translations.ckb.title' => 'sometimes|string|max:255',
            'translations.ckb.body' => 'sometimes|string',
            'categories' => 'sometimes|array',
            'categories.*' => 'exists:categories,id',
            'status' => 'sometimes|in:draft,published,archived',
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:500',
        ]);
        
        if ($request->has('status')) {
            $article->status = $validated['status'];
            if ($validated['status'] === 'published' && !$article->published_at) {
                $article->published_at = now();
            }
        }
        
        if ($request->has('is_featured')) $article->is_featured = $request->boolean('is_featured');
        if ($request->has('is_breaking')) $article->is_breaking = $request->boolean('is_breaking');
        if ($request->has('meta_title')) $article->meta_title = $validated['meta_title'];
        if ($request->has('meta_description')) $article->meta_description = $validated['meta_description'];
        if ($request->has('og_image')) $article->og_image = $validated['og_image'];
        
        if ($request->has('translations')) {
            foreach ($validated['translations'] as $locale => $translation) {
                if (isset($translation['title'])) $article->translateOrNew($locale)->title = $translation['title'];
                if (isset($translation['body'])) $article->translateOrNew($locale)->body = $translation['body'];
                if (isset($translation['excerpt'])) $article->translateOrNew($locale)->excerpt = $translation['excerpt'];
            }
        }
        
        $article->push();
        
        if ($request->has('categories')) {
            $article->categories()->sync($validated['categories']);
        }
        
        $article->load(['author', 'categories', 'translations']);
        
        return response()->json($article);
    }
    
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }
    
    public function publish(Article $article)
    {
        $article->status = 'published';
        $article->published_at = $article->published_at ?? now();
        $article->save();
        
        return response()->json(['message' => 'Article published successfully', 'article' => $article]);
    }
    
    public function featured(Request $request, Article $article)
    {
        $article->is_featured = $request->boolean('is_featured', !$article->is_featured);
        $article->save();
        
        return response()->json(['is_featured' => $article->is_featured]);
    }
}
