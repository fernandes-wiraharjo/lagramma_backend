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
                'icon' => 'bi bi-gear',
                'url' => null,
                'parent_id' => null,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Product',
                'icon' => 'bi bi-box-seam',
                'url' => null,
                'parent_id' => null,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'User Management',
                'icon' => 'bi bi-person-circle',
                'url' => null,
                'parent_id' => null,
                'sequence' => 3,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
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

        // Get the menu ID where menu is 'product'
        $productMenu = Menu::where('name', 'Product')->first();

        if (!$productMenu) {
            throw new \Exception("Product menu not found. Please seed menus first.");
        }

        // Get the menu ID where menu is 'user management'
        $userManagementMenu = Menu::where('name', 'User Management')->first();

        if (!$userManagementMenu) {
            throw new \Exception("User management menu not found. Please seed menus first.");
        }

        $submenus = [
            [
                'name' => 'Category',
                'url' => 'category',
                'parent_id' => $masterMenu->id,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Modifier',
                'url' => 'modifier',
                'parent_id' => $masterMenu->id,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Modifier Option',
                'url' => 'modifier-option',
                'parent_id' => $masterMenu->id,
                'sequence' => 3,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Sales Type',
                'url' => 'sales-type',
                'parent_id' => $masterMenu->id,
                'sequence' => 4,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Product List',
                'url' => 'product',
                'parent_id' => $productMenu->id,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Hamper Setting',
                'url' => 'hampers-setting',
                'parent_id' => $productMenu->id,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Role',
                'url' => 'role',
                'parent_id' => $userManagementMenu->id,
                'sequence' => 1,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'Role Menu',
                'url' => 'role-menu',
                'parent_id' => $userManagementMenu->id,
                'sequence' => 2,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
            ],
            [
                'name' => 'User',
                'url' => 'user',
                'parent_id' => $userManagementMenu->id,
                'sequence' => 3,
                'is_active' => true,
                'created_by' => null,
                'updated_by' => null,
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
