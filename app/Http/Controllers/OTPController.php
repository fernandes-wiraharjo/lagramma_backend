<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SendOTP;
use App\Models\User;

class OTPController extends Controller
{
    public function showVerifyForm(Request $request)
    {
        return view('auth-otp-register', ['phone' => $request->query('phone')]);
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|array', 'otp.*' => 'numeric']);

        // Combine the OTP digits into a single string
        $otp = implode('', $request->otp);

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($otp, $user->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // Check OTP expiry (5 minutes)
        if (now()->diffInMinutes($user->otp_created_at) > 5) {
            return back()->withErrors(['otp' => 'OTP has expired.']);
        }

        // Update user status
        $user->is_verified = true;
        $user->otp = null;
        $user->otp_created_at = null;

        if (!$user->save()) {
            return back()->withErrors(['otp' => 'Failed to update user verification status.']);
        }

        // Log in the user after successful verification
        Auth::login($user);

        return redirect()->route('index')->with('success', 'Phone number verified successfully.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Phone number not found.']);
        }

        // Generate OTP
        $otp = rand(1000, 9999);

        // Assign OTP and save
        $user->otp = Hash::make($otp);
        $user->otp_created_at = now();
        $user->save();

        // Send OTP (using Notification)
        Notification::send($user, new SendOTP($otp));

        return back()->with('success', 'OTP has been resent successfully.');
    }
}
