<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleApiController;
use App\Http\Controllers\Api\AuthorApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\SearchApiController;
use App\Http\Controllers\Api\SettingApiController;
use App\Http\Controllers\Api\JournalistApiController;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Api\Admin\AdController as AdminAdController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;

// Public API endpoints
Route::prefix('v1')->middleware(['api', 'throttle:api'])->group(function () {
    Route::get('articles', [ArticleApiController::class, 'index']);
    Route::get('articles/{article}', [ArticleApiController::class, 'show']);
    Route::post('articles/{id}/increment-view', [ArticleApiController::class, 'incrementView']);
    
    Route::get('categories', [CategoryApiController::class, 'index']);
    Route::get('categories/{category}/articles', [CategoryApiController::class, 'articles']);
    
    Route::get('authors/{user}', [AuthorApiController::class, 'show']);

    Route::get('search', [SearchApiController::class, 'index']);
    
    Route::get('settings', [SettingApiController::class, 'index']);

    Route::get('journalists', [JournalistApiController::class, 'index']);
    Route::post('contact', [ContactApiController::class, 'send'])->middleware('throttle:api-contact');

    // Notifications
    Route::get('notifications', [NotificationApiController::class, 'index']);
    Route::get('notifications/count', [NotificationApiController::class, 'count']);
    Route::post('notifications/{id}/read', [NotificationApiController::class, 'markRead']);

    // Device token registration (for push notifications)
    Route::post('devices/register', [NotificationApiController::class, 'registerDevice']);
    Route::post('devices/unregister', [NotificationApiController::class, 'unregisterDevice']);

    Route::get('comments/{article}', [CommentApiController::class, 'index']);
    Route::post('comments', [CommentApiController::class, 'store'])->middleware('throttle:api-comment');

    // Auth endpoints
    Route::post('auth/login', [AuthController::class, 'login'])->middleware('throttle:api-auth');
});

// Admin protected API endpoints (require Sanctum token + admin role)
Route::prefix('v1/admin')->middleware(['api', 'auth:sanctum', 'admin.role'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'stats']);
    
    // Auth
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/user', [AuthController::class, 'user']);
    
    // Articles
    Route::get('articles', [AdminArticleController::class, 'index']);
    Route::get('articles/{article}', [AdminArticleController::class, 'show']);
    Route::post('articles', [AdminArticleController::class, 'store']);
    Route::put('articles/{article}', [AdminArticleController::class, 'update']);
    Route::delete('articles/{article}', [AdminArticleController::class, 'destroy']);
    Route::post('articles/{article}/publish', [AdminArticleController::class, 'publish']);
    Route::post('articles/{article}/featured', [AdminArticleController::class, 'featured']);
    
    // Categories
    Route::get('categories', [AdminCategoryController::class, 'index']);
    Route::get('categories/{category}', [AdminCategoryController::class, 'show']);
    Route::post('categories', [AdminCategoryController::class, 'store']);
    Route::put('categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy']);
    
    // Users
    Route::get('users', [AdminUserController::class, 'index']);
    Route::get('users/{user}', [AdminUserController::class, 'show']);
    Route::post('users', [AdminUserController::class, 'store']);
    Route::put('users/{user}', [AdminUserController::class, 'update']);
    Route::delete('users/{user}', [AdminUserController::class, 'destroy']);
    
    // Comments
    Route::get('comments', [AdminCommentController::class, 'index']);
    Route::get('comments/{comment}', [AdminCommentController::class, 'show']);
    Route::post('comments/{comment}/approve', [AdminCommentController::class, 'approve']);
    Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy']);
    
    // Ads
    Route::get('ads', [AdminAdController::class, 'index']);
    Route::get('ads/{ad}', [AdminAdController::class, 'show']);
    Route::post('ads', [AdminAdController::class, 'store']);
    Route::put('ads/{ad}', [AdminAdController::class, 'update']);
    Route::delete('ads/{ad}', [AdminAdController::class, 'destroy']);
    
    // Settings
    Route::get('settings', [AdminSettingController::class, 'index']);
    Route::put('settings', [AdminSettingController::class, 'update']);
});
