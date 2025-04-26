<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override credentials to add is_active = 1 check
     */
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['is_active' => 1]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->has('redirect')) {
            return redirect($request->get('redirect'));
        }

        // fallback to intended URL or default
        return redirect()->intended($this->redirectTo);
    }
}
