<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalistTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['journalist_id', 'locale', 'name', 'bio'];
}
