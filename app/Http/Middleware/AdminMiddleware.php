<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // If user is not logged in, redirect to login
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Get the current user
        $user = auth()->user();

        // Check if this is an admin user
        if ($user->is_admin) {
            return $next($request);
        }

        // If not admin, check if we're in the process of stopping impersonation
        if (session()->has('impersonator_id')) {
            $admin = User::where('id', session('impersonator_id'))
                        ->where('is_admin', true)
                        ->first();
            
            if ($admin) {
                // Clear impersonation session
                session()->forget('impersonator_id');
                
                // Login as admin
                auth()->login($admin);
                
                // Regenerate session
                session()->regenerate();
                
                return $next($request);
            }
        }

        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }
} 