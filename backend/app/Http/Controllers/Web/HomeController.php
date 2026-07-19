<?php

namespace App\Http\Controllers\Web;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredArticles = CacheHelper::getFeaturedArticles();
        $breakingNews = CacheHelper::getBreakingNews();
        $latestArticles = CacheHelper::getLatestArticles();
        $popularArticles = CacheHelper::getPopularArticles();
        $recentArticles = CacheHelper::getRecentArticles();
        $categories = CacheHelper::getSidebarCategories();

        // Group articles by category
        $categoryArticles = [];
        foreach ($categories as $category) {
            $catArticles = CacheHelper::getCategoryArticles($category);
            if ($catArticles->isNotEmpty()) {
                $categoryArticles[$category->name] = $catArticles;
            }
        }

        return view('pages.home', compact(
            'featuredArticles', 'breakingNews', 'latestArticles',
            'popularArticles', 'recentArticles', 'categories', 'categoryArticles'
        ));
    }
}
