<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TonerController extends Controller
{
    public function index(Request $request){
        if(view()->exists($request->path())){
            if ($request->path() == "index") {
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

            return view($request->path());
        }
        return view('errors.404');
    }

    public function components(Request $request){
        if(view()->exists('components-pages.'.$request->segment(2))){
            return view('components-pages.'.$request->segment(2));
        }
        return view('errors.404');
    }

    public function logout(){
        if(Auth::check()){
            Auth::logout();
            return redirect('/login');
        }
    }
}
