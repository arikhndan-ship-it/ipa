<?php

namespace App\Filament\Pages;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 1;

    public function getTitle(): string
    {
        return __('messages.dashboard_title');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.dashboard_title');
    }

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $actions = [];

        $quickActions = [];

        $quickActions[] = Action::make('new_article')
            ->label(__('messages.new_article'))
            ->icon('heroicon-o-document-plus')
            ->color('primary')
            ->url(route('filament.admin.resources.articles.create'));
        
        if ($user->role === 'author') {
            $quickActions[] = Action::make('new_category')
                ->label(__('messages.new_category'))
                ->icon('heroicon-o-tag')
                ->color('warning')
                ->url(route('filament.admin.resources.categories.create'));
            
            $quickActions[] = Action::make('new_journalist')
                ->label(__('messages.new_journalist'))
                ->icon('heroicon-o-user')
                ->color('info')
                ->url(route('filament.admin.resources.journalists.create'));
            
            $quickActions[] = Action::make('new_ad')
                ->label(__('messages.new_ad'))
                ->icon('heroicon-o-photo')
                ->color('success')
                ->url(route('filament.admin.resources.ads.create'));
        }

        $actions[] = ActionGroup::make($quickActions)
            ->label(__('messages.quick_actions'))
            ->icon('heroicon-o-plus-circle')
            ->color('primary')
            ->button();

        if (in_array($user->role, ['admin', 'author'])) {
            $managementActions = [
                Action::make('all_articles')
                    ->label(__('messages.all_articles'))
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->url(route('filament.admin.resources.articles.index')),
                
                Action::make('view_comments')
                    ->label(__('messages.pending_comments'))
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('warning')
                    ->url(route('filament.admin.resources.comments.index')),
                
                Action::make('contacts')
                    ->label(__('messages.tips_reports'))
                    ->icon('heroicon-o-inbox')
                    ->color('info')
                    ->url(route('filament.admin.resources.contacts.index')),
            ];

            if ($user->role === 'author') {
                $managementActions[] = Action::make('settings')
                    ->label(__('messages.settings'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('gray')
                    ->url(route('filament.admin.resources.settings.index'));
            }

            $actions[] = ActionGroup::make($managementActions)
                ->label(__('messages.management'))
                ->icon('heroicon-o-cog-6-tooth')
                ->color('gray')
                ->button();
        }

        return $actions;
    }

    public function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\WelcomeWidget::class,
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }
}
