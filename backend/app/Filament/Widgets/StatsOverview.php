<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Ad;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();
        $isAuthor = $user && $user->role === 'author';

        $totalArticles = Article::count();
        $publishedArticles = Article::where('status', 'published')->count();
        $totalViews = Article::sum('view_count');
        $totalComments = Comment::count();
        $pendingComments = Comment::where('is_approved', false)->count();

        $stats = [
            Stat::make(__('messages.total_articles'), $totalArticles)
                ->description($publishedArticles . ' ' . __('messages.published') . ' · ' . ($totalArticles - $publishedArticles) . ' ' . __('messages.drafts'))
                ->descriptionIcon('heroicon-o-document-text')
                ->color('primary')
                ->chart([7, 3, 8, 5, 9, 6, $totalArticles]),

            Stat::make(__('messages.total_views'), number_format($totalViews))
                ->description(__('messages.cumulative_views'))
                ->descriptionIcon('heroicon-o-eye')
                ->color('success')
                ->chart([65, 120, 85, 180, 95, 150, $totalViews]),

            Stat::make(__('messages.comments'), $totalComments)
                ->description($pendingComments > 0 ? $pendingComments . ' ' . __('messages.pending_approval') : __('messages.all_approved'))
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color($pendingComments > 0 ? 'warning' : 'success')
                ->chart([3, 5, 2, 7, 4, 6, $totalComments]),
        ];

        if ($isAuthor) {
            $totalCategories = Category::count();
            $totalAds = Ad::count();
            $activeAds = Ad::where('is_active', true)->count();
            $totalUsers = User::count();

            $stats[] = Stat::make(__('messages.categories'), $totalCategories)
                ->description(__('messages.content_organization'))
                ->descriptionIcon('heroicon-o-tag')
                ->color('info');

            $stats[] = Stat::make(__('messages.active_ads'), $activeAds . ' / ' . $totalAds)
                ->description($totalAds > 0 ? round(($activeAds / $totalAds) * 100) . '% ' . __('messages.active') : __('messages.no_ads_yet'))
                ->descriptionIcon('heroicon-o-photo')
                ->color('primary');

            $stats[] = Stat::make(__('messages.users'), $totalUsers)
                ->description(User::where('role', 'admin')->count() . ' ' . __('messages.admins') . ' · ' . 
                    User::where('role', 'author')->count() . ' ' . __('messages.authors'))
                ->descriptionIcon('heroicon-o-users')
                ->color('info');
        }

        return $stats;
    }
}
