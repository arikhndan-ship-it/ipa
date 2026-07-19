<?php

namespace App\Http\Controllers\Web;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Support\Facades\App;

class RssController extends Controller
{
    public function index()
    {
        $articles = CacheHelper::getRssArticles();
        $locale = App::getLocale();
        
        return response()->view('rss.index', compact('articles', 'locale'))->header('Content-Type', 'application/rss+xml');
    }
}
