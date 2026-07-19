<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ArticleController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\AuthorController;
use App\Http\Controllers\Web\CommentController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\RssController;
use App\Models\Article;
use App\Models\Journalist;

Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::group(['middleware' => ['web', 'localize']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

    Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

    Route::get('/search', [SearchController::class, 'index'])->name('search');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

    Route::get('/settings', function () {
        return view('pages.settings');
    })->name('settings');

    Route::get('/notifications', function () {
        return view('pages.notifications');
    })->name('notifications');

    Route::get('/privacy', function () {
        return view('pages.privacy');
    })->name('privacy');

    Route::get('/terms', function () {
        $breakingNews = Article::with(['author', 'categories'])
            ->published()
            ->breaking()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
        return view('pages.terms', compact('breakingNews'));
    })->name('terms');

    Route::get('/about', function () {
        $breakingNews = Article::with(['author', 'categories'])
            ->published()
            ->breaking()
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
        $journalists = Journalist::with('user')
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('user_id')->orWhere('user_id', '!=', 7);
            })
            ->orderBy('sort_order')
            ->get();
        return view('pages.about', compact('breakingNews', 'journalists'));
    })->name('about');

    Route::get('/author/{user}', [AuthorController::class, 'show'])->name('author.show');

    Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');
});

// RSS Feed
Route::get('/rss', [RssController::class, 'index'])->name('rss');

// Admin locale switching (outside panel-khandan path to avoid Filament route conflict)
Route::get('/admin-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ckb'])) {
        session(['admin_locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect('/panel-khandan');
});

// Fallback redirect for /admin in case nginx rewrite doesn't fire
Route::get('/admin', function () {
    return redirect('/panel-khandan');
});
