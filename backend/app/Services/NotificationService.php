<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function notify(
        string $type,
        string $action,
        string $title,
        ?string $body = null,
        ?string $titleEn = null,
        ?string $titleCkb = null,
        ?string $bodyEn = null,
        ?string $bodyCkb = null,
        ?object $notifiable = null,
        ?User $user = null
    ): Notification {
        return Notification::create([
            'type' => $type,
            'action' => $action,
            'title' => $title,
            'title_en' => $titleEn ?? $title,
            'title_ckb' => $titleCkb ?? $title,
            'body' => $body,
            'body_en' => $bodyEn ?? $body,
            'body_ckb' => $bodyCkb ?? $body,
            'notifiable_type' => $notifiable ? get_class($notifiable) : null,
            'notifiable_id' => $notifiable?->id,
            'user_id' => $user?->id ?? auth()->id(),
            'is_read' => false,
        ]);
    }

    public static function articleCreated($article): void
    {
        $titleEn = $article->translations->firstWhere('locale', 'en')?->title ?? 'Untitled';
        $titleCkb = $article->translations->firstWhere('locale', 'ckb')?->title ?? $titleEn;

        $notification = self::notify(
            type: 'article',
            action: 'created',
            title: "New Article: {$titleEn}",
            titleEn: "New Article: {$titleEn}",
            titleCkb: "بابەتی نوێ: {$titleCkb}",
            body: "A new article was created by " . (auth()->user()?->name ?? 'System'),
            bodyEn: "A new article was created by " . (auth()->user()?->name ?? 'System'),
            bodyCkb: "بابەتێکی نوێ لەلایەن " . (auth()->user()?->name ?? 'سیستەم') . " دروست کرا",
            notifiable: $article,
        );

        // Send push notification to all devices
        try {
            PushNotificationService::sendToAllDevices(
                titleEn: $notification->title_en,
                titleCkb: $notification->title_ckb,
                bodyEn: $notification->body_en,
                bodyCkb: $notification->body_ckb,
                data: ['type' => 'article', 'action' => 'created', 'article_id' => (string)$article->id],
            );
        } catch (\Exception $e) {
            // Fail silently - notifications still saved in DB
            logger()->error('Push notification failed: ' . $e->getMessage());
        }
    }

    public static function articlePublished($article): void
    {
        $titleEn = $article->translations->firstWhere('locale', 'en')?->title ?? 'Untitled';
        $titleCkb = $article->translations->firstWhere('locale', 'ckb')?->title ?? $titleEn;

        $notification = self::notify(
            type: 'article',
            action: 'published',
            title: "Article Published: {$titleEn}",
            titleEn: "Article Published: {$titleEn}",
            titleCkb: "بابەت بڵاوکرایەوە: {$titleCkb}",
            body: "Article is now live on the website.",
            bodyEn: "Article is now live on the website.",
            bodyCkb: "بابەتەکە ئێستا لە ماڵپەڕدا بڵاوە.",
            notifiable: $article,
        );

        // Send push notification to all devices
        try {
            PushNotificationService::sendToAllDevices(
                titleEn: $notification->title_en,
                titleCkb: $notification->title_ckb,
                bodyEn: $notification->body_en,
                bodyCkb: $notification->body_ckb,
                data: ['type' => 'article', 'action' => 'published', 'article_id' => (string)$article->id],
            );
        } catch (\Exception $e) {
            logger()->error('Push notification failed: ' . $e->getMessage());
        }
    }

    public static function journalistCreated($journalist): void
    {
        $nameEn = $journalist->translations->firstWhere('locale', 'en')?->name ?? 'Unknown';
        $nameCkb = $journalist->translations->firstWhere('locale', 'ckb')?->name ?? $nameEn;

        self::notify(
            type: 'journalist',
            action: 'created',
            title: "New Journalist: {$nameEn}",
            titleEn: "New Journalist: {$nameEn}",
            titleCkb: "ڕۆژنامەنووسی نوێ: {$nameCkb}",
            body: "A new journalist profile was added.",
            bodyEn: "A new journalist profile was added.",
            bodyCkb: "پڕۆفایلێکی ڕۆژنامەنووسی نوێ زیاد کرا.",
            notifiable: $journalist,
        );
    }

    public static function categoryCreated($category): void
    {
        $nameEn = $category->translations->firstWhere('locale', 'en')?->name ?? $category->slug;
        $nameCkb = $category->translations->firstWhere('locale', 'ckb')?->name ?? $nameEn;

        self::notify(
            type: 'category',
            action: 'created',
            title: "New Category: {$nameEn}",
            titleEn: "New Category: {$nameEn}",
            titleCkb: "پۆلی نوێ: {$nameCkb}",
            body: "A new report category was created.",
            bodyEn: "A new report category was created.",
            bodyCkb: "پۆلێکی ڕاپۆرتی نوێ دروست کرا.",
            notifiable: $category,
        );
    }

    public static function adCreated($ad): void
    {
        self::notify(
            type: 'ad',
            action: 'created',
            title: "New Ad: {$ad->title}",
            titleEn: "New Ad: {$ad->title}",
            titleCkb: "ڕیکلامی نوێ: {$ad->title}",
            body: "A new advertisement was added.",
            bodyEn: "A new advertisement was added.",
            bodyCkb: "ڕیکلامێکی نوێ زیاد کرا.",
            notifiable: $ad,
        );
    }

    public static function commentReceived($comment): void
    {
        $commentSnippet = mb_substr(strip_tags($comment->body), 0, 100) . '...';

        self::notify(
            type: 'comment',
            action: 'created',
            title: "New Comment by {$comment->author_name}",
            titleEn: "New Comment by {$comment->author_name}",
            titleCkb: "بۆچوونی نوێ لەلایەن {$comment->author_name}",
            body: $commentSnippet,
            bodyEn: $commentSnippet,
            bodyCkb: $commentSnippet,
            notifiable: $comment,
        );
    }
}
