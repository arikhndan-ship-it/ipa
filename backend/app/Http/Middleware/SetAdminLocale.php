<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('admin_locale', 'ckb');
        
        if (in_array($locale, ['en', 'ckb'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
