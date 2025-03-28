<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Ensure user is logged in and has a role
        if (!$user || !$user->role) {
            abort(403, 'Unauthorized Access');
        }

        // Get allowed menu URLs for the user's role
        $allowedMenus = $user->role->menus
            ->pluck('url')
            ->filter() // Remove null values
            ->toArray();

        if (!in_array($request->path(), $allowedMenus)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
