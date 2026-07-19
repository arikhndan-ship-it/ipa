<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Notification extends Model
{
    protected $fillable = [
        'type', 'action',
        'title', 'title_en', 'title_ckb',
        'body', 'body_en', 'body_ckb',
        'notifiable_type', 'notifiable_id',
        'user_id', 'is_read',
    ];

    protected $appends = ['localized_title', 'localized_body'];

    protected static function booted()
    {
        static::saved(function () {
            \App\Helpers\CacheHelper::clearNotificationCaches();
        });
        static::deleted(function () {
            \App\Helpers\CacheHelper::clearNotificationCaches();
        });
    }

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    /**
     * Return the title in the current app locale.
     */
    public function getLocalizedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        if ($locale === 'ckb' && $this->title_ckb) {
            return $this->title_ckb;
        }
        if ($locale === 'en' && $this->title_en) {
            return $this->title_en;
        }
        return $this->title ?? '';
    }

    /**
     * Return the body in the current app locale.
     */
    public function getLocalizedBodyAttribute(): ?string
    {
        $locale = app()->getLocale();
        if ($locale === 'ckb' && $this->body_ckb) {
            return $this->body_ckb;
        }
        if ($locale === 'en' && $this->body_en) {
            return $this->body_en;
        }
        return $this->body;
    }
}
