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
use Illuminate\Support\Facades\Http;

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
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'region_id' => 'required',
            'region_label' => 'required',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['created_by'] = auth()->id();
        $validated['updated_at'] = null;

        $address = UserAddress::create($validated);

        return response()->json(['success' => true]);
    }

    public function updateAddress(Request $request, UserAddress $userAddress)
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'address' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'region_id' => 'required|string',
            'region_label' => 'required|string'
        ]);

        $validated['updated_by'] = auth()->id();

        $userAddress->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroyAddress(UserAddress $userAddress)
    {
        $userAddress->delete();

        return response()->json(['success' => true]);
    }

    public function editAddress(UserAddress $userAddress)
    {
        return response()->json($userAddress);
    }

    public function searchRegion(Request $request)
    {
        $keyword = $request->input('keyword');
        $baseUrl = env('KOMERCE_API_URL');

        if (strlen($keyword) < 3) {
            return response()->json(['error' => 'Minimum 3 characters required.'], 422);
        }

        $response = Http::withHeaders([
            'x-api-key' => config('services.komerce.api_key'),
        ])->get("{$baseUrl}/tariff/api/v1/destination/search", [
            'keyword' => $keyword
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch region.'], 500);
        }

        return $response->json();
    }
}
