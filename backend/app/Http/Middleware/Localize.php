<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Localize
{
    public function handle(Request $request, Closure $next): Response
    {
        // Set locale from session (set via LanguageController), default to Kurdish
        if (Session::has('locale') && in_array(Session::get('locale'), ['en', 'ckb'])) {
            App::setLocale(Session::get('locale'));
        } else {
            App::setLocale('ckb');
        }

        return $next($request);
    }
}
