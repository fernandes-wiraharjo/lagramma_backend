<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                // 'first_name' => ['required', 'string', 'max:255'],
                // 'last_name' => ['required', 'string', 'max:255'],
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone' => ['required', 'string', 'max:50'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                // 'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            ],
            // [
            //     'avatar.max' => 'Photo size not be greater than 2 MB!',
            //     'avatar.max' => 'Photo must be an image!',
            //     'avatar.mimes' => 'Photo must be an jpg, jpeg or png!'
            // ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // if (request()->has('avatar')) {
        //     $avatar = request()->file('avatar');
        //     $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
        //     $avatarPath = public_path('/images/users');
        //     $avatar->move($avatarPath, $avatarName);
        // }
        // Get the role ID where role is 'customer'
        $customerRole = Role::where('name', 'customer')->first();

        if (!$customerRole) {
            abort(500, "Customer role not found. Please seed roles first.");
        }

        return User::create([
            // 'first_name' => $data['first_name'],
            // 'last_name' => $data['last_name'],
            'role_id' => $customerRole->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'updated_at' => null
            // 'avatar' => $avatarName
        ]);
    }
}
