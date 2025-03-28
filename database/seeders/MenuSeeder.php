<?php

namespace Database\Seeders;

use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        $now = Carbon::now();

        // Main Menus
        $menus = [
            [
                'name' => 'Master',
                'url' => null,
                'parent_id' => null,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                // 'created_at' => $now,
                'updated_by' => null,
                // 'updated_at' => null,
            ],
            [
                'name' => 'User Management',
                'url' => null,
                'parent_id' => null,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                // 'created_at' => $now,
                'updated_by' => null,
                // 'updated_at' => null,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['name' => $menu['name']], // Search for an existing role by name
                $menu // If not found, insert this data
            );
        }

        // Submenus
        // Get the menu ID where menu is 'master'
        $masterMenu = Menu::where('name', 'Master')->first();

        if (!$masterMenu) {
            throw new \Exception("Master menu not found. Please seed menus first.");
        }

        // Get the menu ID where menu is 'user management'
        $userManagementMenu = Menu::where('name', 'User Management')->first();

        if (!$userManagementMenu) {
            throw new \Exception("User management menu not found. Please seed menus first.");
        }

        $submenus = [
            [
                'name' => 'Category',
                'url' => '/category',
                'parent_id' => $masterMenu->id,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                // 'created_at' => $now,
                'updated_by' => null,
                // 'updated_at' => null,
            ],
            [
                'name' => 'Menu',
                'url' => '/menu',
                'parent_id' => $masterMenu->id,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                // 'created_at' => $now,
                'updated_by' => null,
                // 'updated_at' => null,
            ],
            [
                'name' => 'User',
                'url' => '/user',
                'parent_id' => $userManagementMenu->id,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                // 'created_at' => $now,
                'updated_by' => null,
                // 'updated_at' => null,
            ],
            [
                'name' => 'Role',
                'url' => '/role',
                'parent_id' => $userManagementMenu->id,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                // 'created_at' => $now,
                'updated_by' => null,
                // 'updated_at' => null,
            ],
        ];

        foreach ($submenus as $submenu) {
            Menu::updateOrCreate(
                [
                    'name' => $submenu['name'],
                    'parent_id' => $submenu['parent_id']
                ],
                $submenu // If not found, insert this data
            );
        }
    }
}
