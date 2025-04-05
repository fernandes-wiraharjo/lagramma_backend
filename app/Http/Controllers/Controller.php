<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
       $this->middleware(function ($request, $next) {
            $user = Auth::user();

            if ($user) {
                // Get menus based on the user's role
                $menus = Menu::whereHas('roles', function ($query) use ($user) {
                    $query->where('name', $user->role->name);
                })
                ->whereNull('parent_id')
                ->with(['children' => function ($query) {
                    $query->orderBy('sequence', 'asc');
                }])
                ->orderBy('sequence', 'asc')
                ->get();

                // Log::debug(json_encode($menus, JSON_PRETTY_PRINT));

                // Share menus globally in all views
                View::share('menus', $menus);
            }

            return $next($request);
        });
    }
}
