<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user(); // Get the authenticated user

        // Check if user is authenticated
        if ($user) {
            // Get menus based on the user's role
            $menus = Menu::whereHas('roles', function ($query) use ($user) {
                $query->where('name', $user->role->name);
            })
            ->whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->orderBy('sequence', 'asc'); // Order children by sequence
            }])
            ->orderBy('sequence', 'asc') // Order parent menus by sequence
            ->get();

            // Check if the user is a customer
            if ($user->role->name === 'customer') {
                return view('index', compact('menus'));
            }

            return view('index', compact('menus'));
        }

        return redirect()->route('login'); // Redirect if not authenticated
    }

    public function lang($locale) {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }
}
