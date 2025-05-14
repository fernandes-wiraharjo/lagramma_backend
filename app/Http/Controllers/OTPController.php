<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
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
        $user->update(['is_verified' => true, 'otp' => null, 'otp_created_at' => null]);

        return redirect()->route('index')->with('success', 'Phone number verified successfully.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Phone number not found.']);
        }

        // Generate a new OTP
        $otp = rand(1000, 9999);

        // Update the OTP and timestamp in the database
        $user->update([
            'otp' => Hash::make($otp),
            'otp_created_at' => now()
        ]);

        // Send OTP (using Notification)
        Notification::send($user, new SendOTP($otp));

        return back()->with('success', 'OTP has been resent successfully.');
    }
}
