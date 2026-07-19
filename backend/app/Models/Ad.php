<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title', 'type', 'image_path', 'link_url',
        'is_active', 'sort_order', 'start_date', 'end_date',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::created(function ($ad) {
            \App\Services\NotificationService::adCreated($ad);
        });
    }

    public function translations()
    {
        return $this->hasMany(AdTranslation::class);
    }
}
