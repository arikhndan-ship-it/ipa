<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ArticleTranslation;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'author_id', 'slug', 'status', 'is_featured', 'is_breaking',
        'view_count', 'meta_title', 'meta_description', 'og_image', 'published_at',
    ];

    protected $with = ['translations'];
    protected $appends = ['title', 'body', 'excerpt', 'featured_image', 'category_name', 'author_name', 'author_image', 'published_date'];

    protected static function booted(): void
    {
        static::created(function ($article) {
            \App\Helpers\CacheHelper::clearArticleCaches();
        });
        static::updated(function ($article) {
            if ($article->wasChanged('status') && $article->status === 'published') {
                \App\Services\NotificationService::articlePublished($article);
            }
            \App\Helpers\CacheHelper::clearArticleCaches();
        });
        static::deleted(function () {
            \App\Helpers\CacheHelper::clearArticleCaches();
        });
    }

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_breaking' => 'boolean',
            'published_at' => 'datetime',
            'view_count' => 'integer',
        ];
    }

    public function translations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    public function translateOrNew(string $locale): ArticleTranslation
    {
        $translation = $this->translations()->where('locale', $locale)->first();
        if (!$translation) {
            $translation = $this->translations()->make(['locale' => $locale]);
            $translation->article_id = $this->id;
        }
        return $translation;
    }

    /**
     * Get translated title from the loaded translations relationship.
     * Falls back to English, then to an empty string.
     */
    public function getTitleAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('title') ?? '';
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->title ?? '';
    }

    public function getBodyAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('body') ?? '';
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->body ?? '';
    }

    public function getExcerptAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('excerpt') ?? '';
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->excerpt ?? Str::limit(strip_tags($this->body), 200);
    }

    public function getFeaturedImageAttribute()
    {
        $url = $this->og_image;
        if ($url && !str_starts_with($url, 'http') && !str_starts_with($url, '/')) {
            return asset('storage/' . $url);
        }
        return $url ?: null;
    }

    public function getCategoryNameAttribute()
    {
        return $this->categories->first()?->name ?? null;
    }

    public function getAuthorNameAttribute()
    {
        return $this->author?->name ?? null;
    }

    public function getAuthorImageAttribute()
    {
        if (!$this->author) return null;
        
        // First try to get image from linked journalist
        $journalist = \App\Models\Journalist::where('user_id', $this->author->id)->first();
        if ($journalist && $journalist->image) {
            if (str_starts_with($journalist->image, 'http://') || str_starts_with($journalist->image, 'https://')) {
                return $journalist->image;
            }
            if (str_starts_with($journalist->image, '/')) {
                return url($journalist->image);
            }
            return url('storage/' . $journalist->image);
        }
        
        // Fall back to user's avatar field
        if ($this->author->avatar) {
            if (str_starts_with($this->author->avatar, 'http://') || str_starts_with($this->author->avatar, 'https://')) {
                return $this->author->avatar;
            }
            return url($this->author->avatar);
        }
        
        return null;
    }

    public function getPublishedDateAttribute()
    {
        return $this->published_at?->toIso8601String();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBreaking($query)
    {
        return $query->where('is_breaking', true);
    }
}
