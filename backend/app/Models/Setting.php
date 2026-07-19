<?php

namespace App\Models;

use App\Helpers\CacheHelper;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    protected static function booted()
    {
        static::saved(function () {
            CacheHelper::clearSettingsCaches();
        });

        static::deleted(function () {
            CacheHelper::clearSettingsCaches();
        });
    }

    public function translations()
    {
        return $this->hasMany(SettingTranslation::class);
    }
}
