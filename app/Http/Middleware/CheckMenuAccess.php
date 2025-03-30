<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

         // Get all menus assigned to the user's role (including submenus)
        $menus = $user->role->menus()->with('children')->get();

        // Collect allowed URLs
        $allowedMenus = collect();

        foreach ($menus as $menu) {
            if ($menu->url) {
                // If parent has a URL, add it
                $allowedMenus->push(ltrim($menu->url, '/'));
            } elseif ($menu->children->isNotEmpty()) {
                // If parent has submenus, add their URLs instead
                foreach ($menu->children as $childMenu) {
                    if ($childMenu->url) {
                        $allowedMenus->push(ltrim($childMenu->url, '/'));
                    }
                }
            }
        }

        // Convert to array and filter null values
        $allowedMenus = $allowedMenus->filter()->toArray();

        if (!in_array($request->path(), $allowedMenus)) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
