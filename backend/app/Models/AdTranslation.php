<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['ad_id', 'locale', 'alt_text'];
}
