<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['article_id', 'locale', 'title', 'body', 'excerpt'];
}
