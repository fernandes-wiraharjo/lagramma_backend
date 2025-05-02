<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('my-account', compact('user'));
    }

    public function indexSetting()
    {
        $user = auth()->user();
        return view('account-setting', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->name  = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->updated_by = $user->id;
        $user->save();

        return redirect()->route('index-setting-account')->with('success', 'Profile updated successfully.');
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'region_id' => 'required|string',
            'region_label' => 'required|string',
        ]);

        Auth::user()->addresses()->create($request->all());

        return redirect()->back()->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, UserAddress $userAddress)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'region_id' => 'required|string',
            'region_label' => 'required|string',
        ]);

        $userAddress->update($request->all());

        return redirect()->back()->with('success', 'Address updated successfully.');
    }

    public function destroyAddress(UserAddress $userAddress)
    {
        $userAddress->delete();

        return redirect()->back()->with('success', 'Address deleted successfully.');
    }
}
