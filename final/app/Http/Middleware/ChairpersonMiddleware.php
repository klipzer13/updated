<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles; 

class ChairpersonMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id == 2) { // Assuming role_id 1 is for Chairperson
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Access denied.');
    }
}
