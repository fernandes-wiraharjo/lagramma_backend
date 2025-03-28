<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;
use Hash;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = Carbon::now();

    // Get the role ID where name is 'admin'
    $adminRole = Role::where('name', 'admin')->first();

    if (!$adminRole) {
        throw new \Exception("Admin role not found. Please seed roles first.");
    }

    $users = [
      [
        'role_id' => $adminRole->id,
        'email' => 'fernandeswiraharjo@gmail.com',
        'name' => 'admin',
        'password' => Hash::make('12345'),
        'is_active' => true,
        'created_by' => null,
        'created_at' => $now,
        'updated_by' => null,
        'updated_at' => null,
      ],
    ];

    foreach ($users as $user) {
        User::firstOrCreate(
            ['email' => $user['email']],
            $user // If not found, insert this data
        );
    }
  }
}
