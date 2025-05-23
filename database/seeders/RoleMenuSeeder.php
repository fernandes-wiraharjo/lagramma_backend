<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use App\Models\RoleMenu;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    /**
   * Run the database seeds.
   *
   * @return void
   */
    public function run()
    {
        $now = Carbon::now();

        // Get the role ID where role is 'admin'
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            throw new \Exception("Admin role not found. Please seed roles first.");
        }

        // Get the role ID where role is 'customer'
        $customerRole = Role::where('name', 'customer')->first();

        if (!$customerRole) {
            throw new \Exception("Customer role not found. Please seed roles first.");
        }

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
            throw new \Exception("User Management menu not found. Please seed menus first.");
        }

        // Get the menu ID where menu is 'account'
        $accountMenu = Menu::where('name', 'Account')->first();

        if (!$accountMenu) {
            throw new \Exception("Account menu not found. Please seed menus first.");
        }

        // Main Menus
        $roleMenus = [
            [
                'role_id' => $adminRole->id,
                'menu_id' => $masterMenu->id,
                'created_by' => null,
                'created_at' => $now,
                'updated_by' => null,
                'updated_at' => null,
            ],
            [
                'role_id' => $adminRole->id,
                'menu_id' => $productMenu->id,
                'created_by' => null,
                'created_at' => $now,
                'updated_by' => null,
                'updated_at' => null,
            ],
            [
                'role_id' => $adminRole->id,
                'menu_id' => $userManagementMenu->id,
                'created_by' => null,
                'created_at' => $now,
                'updated_by' => null,
                'updated_at' => null,
            ],
            [
                'role_id' => $adminRole->id,
                'menu_id' => $accountMenu->id,
                'created_by' => null,
                'created_at' => $now,
                'updated_by' => null,
                'updated_at' => null,
            ],
            [
                'role_id' => $customerRole->id,
                'menu_id' => $accountMenu->id,
                'created_by' => null,
                'created_at' => $now,
                'updated_by' => null,
                'updated_at' => null,
            ]
        ];

        foreach ($roleMenus as $roleMenu) {
            RoleMenu::firstOrCreate(
                [
                    'role_id' => $roleMenu['role_id'],
                    'menu_id' => $roleMenu['menu_id'],
                ],
                $roleMenu // If not found, insert this data
            );
        }
    }
}
