<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user has any of the required roles
        foreach ($roles as $role) {
            if ($request->user()->hasRole($role)) {
                return $next($request);
            }
        }

        // If AJAX request, return JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthorized. You do not have the required role to access this resource.'
            ], 403);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
