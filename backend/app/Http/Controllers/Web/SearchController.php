<?php

namespace App\Http\Controllers\Web;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        $articles = collect();

        if ($query) {
            $articles = Article::with(['author', 'categories'])
                ->published()
                ->where(function ($q) use ($query) {
                    $q->whereHas('translations', function ($t) use ($query) {
                        $t->where('title', 'like', "%{$query}%")
                          ->orWhere('body', 'like', "%{$query}%")
                          ->orWhere('excerpt', 'like', "%{$query}%");
                    });
                })
                ->orderBy('published_at', 'desc')
                ->paginate(12);
        }

        $categories = CacheHelper::getSidebarCategories();
        $popularArticles = CacheHelper::getPopularArticles();
        $recentArticles = CacheHelper::getRecentArticles();

        return view('pages.search', compact(
            'articles', 'query', 'categories', 'popularArticles', 'recentArticles'
        ));
    }
}
