<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority: query param > header > default (ckb)
        $locale = $request->query('locale', $request->header('Accept-Language', 'ckb'));

        if (in_array($locale, ['en', 'ckb'])) {
            App::setLocale($locale);
        } else {
            App::setLocale('ckb');
        }

        return $next($request);
    }
}
