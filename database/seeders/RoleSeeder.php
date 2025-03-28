<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $now = Carbon::now();

    $roles = [
      [
        'name' => 'admin',
        'is_active' => true,
        'created_by' => null,
        'created_at' => $now,
        'updated_by' => null,
        'updated_at' => null,
      ],
      [
        'name' => 'kitchen',
        'is_active' => true,
        'created_by' => null,
        'created_at' => $now,
        'updated_by' => null,
        'updated_at' => null,
      ]
    ];

    foreach ($roles as $role) {
        Role::firstOrCreate(
            ['name' => $role['name']], // Search for an existing role by name
            $role // If not found, insert this data
        );
    }
  }
}
