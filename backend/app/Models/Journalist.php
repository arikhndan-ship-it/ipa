<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journalist extends Model
{
    protected $fillable = ['user_id', 'image', 'sort_order', 'is_active'];

    protected $with = ['translations'];
    protected $appends = ['name', 'bio', 'image_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::created(function ($journalist) {
            \App\Services\NotificationService::journalistCreated($journalist);
            \App\Helpers\CacheHelper::clearJournalistCaches();
        });
        static::updated(function () {
            \App\Helpers\CacheHelper::clearJournalistCaches();
        });
        static::deleted(function ($journalist) {
            if ($journalist->user_id) {
                $journalist->user?->delete();
            }
            \App\Helpers\CacheHelper::clearJournalistCaches();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function translations()
    {
        return $this->hasMany(JournalistTranslation::class);
    }

    public function translateOrNew(string $locale): JournalistTranslation
    {
        $translation = $this->translations()->where('locale', $locale)->first();
        if (!$translation) {
            $translation = $this->translations()->make(['locale' => $locale]);
            $translation->journalist_id = $this->id;
        }
        return $translation;
    }

    public function getNameAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('name') ?? '';
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->name ?? '';
    }

    public function getBioAttribute()
    {
        if (!$this->relationLoaded('translations')) {
            return $this->getAttributeFromArray('bio') ?? '';
        }
        $locale = app()->getLocale();
        $translation = $this->translations->firstWhere('locale', $locale)
            ?? $this->translations->firstWhere('locale', 'en');
        return $translation?->bio ?? '';
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        if (str_starts_with($this->image, '/')) {
            return url($this->image);
        }
        return url('storage/' . $this->image);
    }
}
