<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$permissions
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Check if user has any of the required permissions
        foreach ($permissions as $permission) {
            if ($request->user()->hasPermission($permission)) {
                return $next($request);
            }
        }

        // If AJAX request, return JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthorized. You do not have the required permission.'
            ], 403);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
    }
}
