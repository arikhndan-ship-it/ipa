<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index()
    {
        $locale = request()->header('Accept-Language', 'ckb');
        app()->setLocale(in_array($locale, ['en', 'ckb']) ? $locale : 'ckb');

        $categories = CacheHelper::getApiCategories();

        return response()->json($categories);
    }

    public function articles(Category $category)
    {
        $locale = request()->header('Accept-Language', 'ckb');
        app()->setLocale(in_array($locale, ['en', 'ckb']) ? $locale : 'ckb');

        if (!$category->is_active) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $articles = Article::with(['author', 'categories'])
            ->published()
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json($articles);
    }
}
