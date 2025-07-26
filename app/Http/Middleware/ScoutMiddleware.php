<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ScoutMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        if (!auth()->user()->scoutProfile->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Your account is currently inactive. Please contact your admin.');
        }

        return $next($request);
    }
} 