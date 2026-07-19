<?php

namespace App\Helpers;

use App\Helpers\SettingsHelper;
use App\Models\Article;
use App\Models\Category;
use App\Models\Journalist;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class CacheHelper
{
    // Cache durations
    const ONE_MINUTE = 60;
    const FIVE_MINUTES = 300;
    const ONE_HOUR = 3600;
    const ONE_DAY = 86400;

    // ==================== SIDEBAR DATA ====================

    public static function getSidebarCategories()
    {
        return Cache::remember('sidebar.categories', self::ONE_DAY, function () {
            return Category::withCount('articles')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function getPopularArticles(int $take = 5)
    {
        return Cache::remember('sidebar.popular.' . $take, self::ONE_HOUR, function () use ($take) {
            return Article::with(['author', 'categories'])
                ->published()
                ->orderBy('view_count', 'desc')
                ->take($take)
                ->get();
        });
    }

    public static function getRecentArticles(int $take = 5)
    {
        return Cache::remember('sidebar.recent.' . $take, self::ONE_HOUR, function () use ($take) {
            return Article::with(['author', 'categories'])
                ->published()
                ->orderBy('published_at', 'desc')
                ->take($take)
                ->get();
        });
    }

    // ==================== HOME PAGE DATA ====================

    public static function getFeaturedArticles(int $take = 5)
    {
        return Cache::remember('home.featured', self::ONE_HOUR, function () use ($take) {
            return Article::with(['author', 'categories'])
                ->published()
                ->featured()
                ->orderBy('published_at', 'desc')
                ->take($take)
                ->get();
        });
    }

    public static function getBreakingNews(int $take = 5)
    {
        return Cache::remember('home.breaking_news', self::FIVE_MINUTES, function () use ($take) {
            return Article::with(['author', 'categories'])
                ->published()
                ->breaking()
                ->orderBy('published_at', 'desc')
                ->take($take)
                ->get();
        });
    }

    public static function getLatestArticles(int $perPage = 12)
    {
        $page = request()->get('page', 1);
        return Cache::remember('home.latest.page.' . $page, self::FIVE_MINUTES, function () use ($perPage) {
            return Article::with(['author', 'categories'])
                ->published()
                ->orderBy('published_at', 'desc')
                ->paginate($perPage);
        });
    }

    public static function getCategoryArticles(Category $category, int $take = 3)
    {
        return Cache::remember('home.category_articles.' . $category->id, self::ONE_HOUR, function () use ($category, $take) {
            return Article::with(['author', 'categories'])
                ->published()
                ->whereHas('categories', function ($q) use ($category) {
                    $q->where('categories.id', $category->id);
                })
                ->orderBy('published_at', 'desc')
                ->take($take)
                ->get();
        });
    }

    // ==================== ARTICLE DETAILS ====================

    public static function getRelatedArticles(Article $article, int $take = 4)
    {
        return Cache::remember('article.' . $article->id . '.related', self::ONE_HOUR, function () use ($article, $take) {
            return Article::with(['author', 'categories'])
                ->published()
                ->whereHas('categories', function ($query) use ($article) {
                    $query->whereIn('categories.id', $article->categories->pluck('id'));
                })
                ->where('id', '!=', $article->id)
                ->take($take)
                ->get();
        });
    }

    public static function getPreviousArticle(Article $article)
    {
        return Cache::remember('article.' . $article->id . '.prev', self::ONE_HOUR, function () use ($article) {
            return Article::published()
                ->where('published_at', '<', $article->published_at)
                ->orderBy('published_at', 'desc')
                ->first();
        });
    }

    public static function getNextArticle(Article $article)
    {
        return Cache::remember('article.' . $article->id . '.next', self::ONE_HOUR, function () use ($article) {
            return Article::published()
                ->where('published_at', '>', $article->published_at)
                ->orderBy('published_at', 'asc')
                ->first();
        });
    }

    // ==================== RSS ====================

    public static function getRssArticles(int $limit = 50)
    {
        return Cache::remember('rss.articles', self::ONE_HOUR, function () use ($limit) {
            return Article::with(['author', 'categories'])
                ->published()
                ->orderBy('published_at', 'desc')
                ->limit($limit)
                ->get();
        });
    }

    // ==================== API DATA ====================

    public static function getApiSettings()
    {
        return Cache::remember('api.settings', self::ONE_DAY, function () {
            return Setting::all()->pluck('value', 'key');
        });
    }

    public static function getApiCategories()
    {
        return Cache::remember('api.categories', self::ONE_DAY, function () {
            return Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function getApiJournalists()
    {
        return Cache::remember('api.journalists', self::ONE_DAY, function () {
            return Journalist::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function getApiNotifications()
    {
        return Cache::remember('api.notifications.' . app()->getLocale(), self::ONE_MINUTE, function () {
            return Notification::orderBy('created_at', 'desc')
                ->limit(20)
                ->get()
                ->map(function ($n) {
                    $url = null;
                    if ($n->notifiable_type && $n->notifiable_id) {
                        if ($n->type === 'article' && $n->notifiable_type === 'App\\Models\\Article') {
                            $article = \App\Models\Article::find($n->notifiable_id);
                            if ($article) {
                                $url = url('/articles/' . $article->slug);
                            }
                        } elseif ($n->type === 'journalist') {
                            $url = url('/author/' . $n->notifiable_id);
                        } elseif ($n->type === 'category' && $n->notifiable_type === 'App\\Models\\Category') {
                            $category = \App\Models\Category::find($n->notifiable_id);
                            if ($category) {
                                $url = url('/categories/' . $category->slug);
                            }
                        }
                    }
                    return [
                        'id' => $n->id,
                        'type' => $n->type,
                        'action' => $n->action,
                        'title' => $n->localized_title,
                        'body' => $n->localized_body,
                        'title_en' => $n->title_en ?? $n->title,
                        'title_ckb' => $n->title_ckb ?? $n->title,
                        'body_en' => $n->body_en ?? $n->body,
                        'body_ckb' => $n->body_ckb ?? $n->body,
                        'notifiable_type' => $n->notifiable_type,
                        'notifiable_id' => $n->notifiable_id,
                        'url' => $url,
                        'is_read' => $n->is_read,
                        'created_at' => $n->created_at->toISOString(),
                    ];
                });
        });
    }

    public static function getUnreadNotificationCount()
    {
        return Cache::remember('api.notifications.unread_count', self::ONE_MINUTE, function () {
            return Notification::where('is_read', false)->count();
        });
    }

    // ==================== CACHE INVALIDATION ====================

    public static function clearArticleCaches(): void
    {
        Cache::forget('home.featured');
        Cache::forget('home.breaking_news');
        Cache::forget('sidebar.popular.5');
        Cache::forget('sidebar.recent.5');
        Cache::forget('rss.articles');
        Cache::forget('api.settings');
        // Clear paginated home pages
        for ($i = 1; $i <= 5; $i++) {
            Cache::forget('home.latest.page.' . $i);
        }
    }

    public static function clearSettingsCaches(): void
    {
        Cache::forget('api.settings');
        SettingsHelper::clearCache();
    }

    public static function clearCategoryCaches(): void
    {
        Cache::forget('sidebar.categories');
        Cache::forget('api.categories');
    }

    public static function clearJournalistCaches(): void
    {
        Cache::forget('api.journalists');
    }

    public static function clearNotificationCaches(): void
    {
        Cache::forget('api.notifications.en');
        Cache::forget('api.notifications.ckb');
        Cache::forget('api.notifications.unread_count');
    }

    public static function clearAll(): void
    {
        self::clearArticleCaches();
        self::clearCategoryCaches();
        self::clearJournalistCaches();
        self::clearNotificationCaches();
        Cache::forget('all_settings');
    }
}
