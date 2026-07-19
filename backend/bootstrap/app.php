<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'localize' => \App\Http\Middleware\Localize::class,
            'setlocale' => \App\Http\Middleware\SetLocale::class,
            'admin.role' => \App\Http\Middleware\AdminRole::class,
            'filament.admin' => \App\Http\Middleware\FilamentAdminAccess::class,
        ]);
        
        $middleware->statefulApi();
        
        // Apply SetLocale to all API routes
        $middleware->api(prepend: [
            \App\Http\Middleware\SetLocale::class,
        ]);
        
        // Exempt Livewire routes from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'livewire/*',
            'livewire/update',
            'livewire/message/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
