<?php

namespace App\Http\Controllers\Web;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Article;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $articles = Article::with(['author', 'categories'])
            ->published()
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('categories.id', $category->id);
            })
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = CacheHelper::getSidebarCategories();
        $popularArticles = CacheHelper::getPopularArticles();
        $recentArticles = CacheHelper::getRecentArticles();

        return view('categories.show', compact(
            'category', 'articles', 'categories', 'popularArticles', 'recentArticles'
        ));
    }
}
