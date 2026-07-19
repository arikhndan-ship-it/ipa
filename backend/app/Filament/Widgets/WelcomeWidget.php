<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Notification;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static string $view = 'filament.widgets.welcome';

    protected static ?int $sort = 0;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->check();
    }

    public function isAuthor(): bool
    {
        return auth()->user()?->role === 'author';
    }

    public function getUserName(): string
    {
        return auth()->user()?->name ?? 'User';
    }

    public function getGreeting(): string
    {
        $hour = Carbon::now()->hour;
        if ($hour < 12) return __('messages.good_morning');
        if ($hour < 17) return __('messages.good_afternoon');
        return __('messages.good_evening');
    }

    public function getStats(): array
    {
        $totalArticles = Article::count();
        $publishedToday = Article::where('status', 'published')
            ->whereDate('published_at', today())
            ->count();
        $pendingComments = Comment::where('is_approved', false)->count();
        $unreadContacts = Contact::where('is_read', false)->count();
        $unreadNotifications = Notification::where('is_read', false)->count();

        return [
            'totalArticles' => $totalArticles,
            'publishedToday' => $publishedToday,
            'pendingComments' => $pendingComments,
            'unreadContacts' => $unreadContacts,
            'unreadNotifications' => $unreadNotifications,
        ];
    }

    public function getRecentArticles()
    {
        return Article::with(['author', 'translations'])
            ->latest()
            ->take(5)
            ->get();
    }
}
