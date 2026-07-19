<?php

namespace App\Http\Controllers\Web;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Journalist;

class ArticleController extends Controller
{
    public function index()
    {
        $query = Article::with(['author', 'categories'])->published();

        // Filter by category slug if provided
        if ($categorySlug = request()->query('category')) {
            $query->whereHas('categories', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->paginate(12);

        $categories = CacheHelper::getSidebarCategories();
        $popularArticles = CacheHelper::getPopularArticles();
        $recentArticles = CacheHelper::getRecentArticles();

        return view('articles.index', compact(
            'articles', 'categories', 'popularArticles', 'recentArticles'
        ));
    }

    public function show(Article $article)
    {
        if ($article->status !== 'published') {
            abort(404);
        }

        $article->increment('view_count');

        $comments = $article->approvedComments()->latest()->get();

        $relatedArticles = CacheHelper::getRelatedArticles($article);
        $previous = CacheHelper::getPreviousArticle($article);
        $next = CacheHelper::getNextArticle($article);

        $categories = CacheHelper::getSidebarCategories();
        $popularArticles = CacheHelper::getPopularArticles();
        $recentArticles = CacheHelper::getRecentArticles();

        $title = $article->title;
        $description = strip_tags(mb_substr($article->body, 0, 160));
        $ogImage = $article->featured_image;

        // Load journalist profile linked to the article's author
        $journalist = null;
        if ($article->author) {
            $journalist = Journalist::where('user_id', $article->author->id)->first();
        }

        return view('articles.show', compact(
            'article', 'comments', 'relatedArticles', 'previous', 'next',
            'categories', 'popularArticles', 'recentArticles',
            'title', 'description', 'ogImage', 'journalist'
        ));
    }
}
