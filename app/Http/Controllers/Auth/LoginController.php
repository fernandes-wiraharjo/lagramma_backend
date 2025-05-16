<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SendOTP;

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
     * Override credentials to add is_active = 1 and is_verified = 1 check
     */
    // protected function credentials(Request $request)
    // {
    //     return array_merge(
    //         $request->only($this->username(), 'password'),
    //         ['is_active' => 1, 'is_verified' => 1]
    //     );
    // }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)
                    ->where('is_active', 1)
                    ->where('is_verified', 1)
                    ->first();

        // if (!$user) {
        //     return back()->withErrors(['email' => 'These credentials do not match our records or user not active/verified.']);
        // }

         if (!$user) {
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.failed')],
            ]);
        }

        // Generate OTP and send
        $otp = rand(1000, 9999);
        $user->update([
            'otp' => Hash::make($otp),
            'otp_created_at' => now(),
        ]);

        Notification::send($user, new SendOTP($otp));

        // Store user id or phone in session to identify for OTP verify
        session([
            'otp_user_id' => $user->id,
            'otp_user_phone' => $user->phone,
        ]);

        return redirect()->route('login.otp.form');
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
