<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FilamentAdminAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('FilamentAdminAccess middleware hit', [
            'path' => $request->path(),
            'user' => $request->user()?->email,
            'role' => $request->user()?->role,
            'authenticated' => $request->user() !== null,
            'session_id' => $request->session()->getId(),
        ]);

        // If user is not logged in, let Authenticate middleware handle it
        if (!$request->user()) {
            return $next($request);
        }
        
        // Check if user has a valid role for admin access
        $validRoles = ['admin', 'author'];
        
        if (!in_array($request->user()->role, $validRoles)) {
            \Illuminate\Support\Facades\Log::warning('FilamentAdminAccess: invalid role', [
                'email' => $request->user()->email,
                'role' => $request->user()->role,
            ]);
            // Log out and redirect to login with a message
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->to('/panel-khandan/login')
                ->with('error', 'Your account does not have admin panel access.');
        }

        return $next($request);
    }
}
