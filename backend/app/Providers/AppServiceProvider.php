<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Define API rate limiters (must be here for config caching to work)
        RateLimiter::for('api', function () {
            return Limit::perMinute(config('ratelimit.api.limit', 60));
        });
        RateLimiter::for('api-auth', function () {
            return Limit::perMinute(config('ratelimit.api_auth.limit', 10));
        });
        RateLimiter::for('api-contact', function () {
            return Limit::perMinute(config('ratelimit.contact.limit', 3));
        });
        RateLimiter::for('api-comment', function () {
            return Limit::perMinute(config('ratelimit.comment.limit', 5));
        });

        // Share breaking news with frontend views only (not Filament admin)
        View::composer('*', function ($view) {
            $currentRoute = request()->route();
            $path = request()->path();
            
            // Skip for admin panel and API routes
            if (str_contains($path, 'panel-khandan') || 
                str_contains($path, 'api/') ||
                str_contains($path, '_debugbar') ||
                $view->getName() === null ||
                str_starts_with($view->getName(), 'filament')) {
                return;
            }
            
            if (!$view->offsetExists('breakingNews')) {
                $breakingNews = \App\Helpers\CacheHelper::getBreakingNews(5);
                $view->with('breakingNews', $breakingNews);
            }
        });

        $this->app['events']->listen(RequestHandled::class, function ($event) {
            if (str_contains($event->request->path(), 'panel-khandan')) {
                // Only inject CSS on successful responses with HTML content
                $response = $event->response;
                if ($response->isSuccessful() && method_exists($response, 'getContent')) {
                    $content = $response->getContent();
                    if (! empty($content) && is_string($content) && str_contains($content, '</head>')) {
                        $cssLink = "\n" . '<link rel="stylesheet" href="' . asset('admin/css/admin.css') . '">' . "\n";
                        $response->setContent(str_replace('</head>', $cssLink . '</head>', $content));
                    }
                }
            }
        });
    }
}
