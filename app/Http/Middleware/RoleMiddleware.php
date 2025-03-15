<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            abort(403, 'User belum login.');
        }

        $user = Auth::user();
        if (!$user->hasRole($role)) {
            abort(403, "User tidak memiliki role yang dibutuhkan. Role saat ini: " . implode(', ', $user->getRoleNames()->toArray()));
        }

        return $next($request);
    }

}
