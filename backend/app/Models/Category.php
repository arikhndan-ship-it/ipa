<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['slug', 'parent_id', 'sort_order', 'is_active'];

    protected $with = ['translations'];

    protected $appends = ['name', 'description'];

    protected static function booted(): void
    {
        static::created(function ($category) {
            \App\Services\NotificationService::categoryCreated($category);
            \App\Helpers\CacheHelper::clearCategoryCaches();
        });
        static::updated(function () {
            \App\Helpers\CacheHelper::clearCategoryCaches();
        });
        static::deleted(function () {
            \App\Helpers\CacheHelper::clearCategoryCaches();
        });
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translateOrNew(string $locale): CategoryTranslation
    {
        $translation = $this->translations()->where('locale', $locale)->first();
        if (!$translation) {
            $translation = $this->translations()->make(['locale' => $locale]);
            $translation->category_id = $this->id;
        }
        return $translation;
    }

    public function getNameAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('name') ?? $this->slug;
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->name ?? $this->slug;
    }

    public function getDescriptionAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('description') ?? '';
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->description ?? '';
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
