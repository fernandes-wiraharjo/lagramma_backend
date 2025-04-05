<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleMenuController extends Controller
{
    public function index()
    {
        $roles = Role::where('is_active', true)->get(['id', 'name']);
        $menus = Menu::where('is_active', true)->whereNull('parent_id')->get(['id', 'name']);

        return view('role-menu', compact('roles', 'menus'));
    }

    public function get(Request $request)
    {
        $query = RoleMenu::query()
            ->join('roles', 'role_menus.role_id', '=', 'roles.id')
            ->join('menus', 'role_menus.menu_id', '=', 'menus.id')
            ->groupBy('role_menus.role_id', 'roles.name')
            ->select([
                'roles.id as role_id',
                'roles.name as role_name',
                DB::raw('GROUP_CONCAT(menus.name ORDER BY menus.name SEPARATOR ", ") as menu_names')
            ]);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'roles.name',
            1 => 'menu_names'
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'roles.name';

        // Get total records count (before filtering)
        $totalRecords = RoleMenu::select('role_id')->distinct()->count();

         // Apply search filtering
         if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';
            $query->havingRaw('roles.name LIKE ? OR menu_names LIKE ?', [$searchValue, $searchValue]);
        }

        // Get total filter records count (after filtering)
        $totalFiltered = $query->get()->count();

        // Apply sorting and pagination
        $data = $query
            ->orderBy($sortColumn, $sortDirection)
            ->offset($request->input('start', 0))
            ->limit($request->input('length', 10))
            ->get();

        // Prepare response data
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }

    public function getByRoleId($roleId)
    {
        $menuIds = RoleMenu::where('role_id', $roleId)->pluck('menu_id');

        return response()->json([
            'role_id' => $roleId,
            'menu_ids' => $menuIds
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'menu_ids' => 'required|array',
                'menu_ids.*' => 'exists:menus,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            foreach ($request->menu_ids as $menuId) {
                RoleMenu::create([
                    'role_id' => $request->role_id,
                    'menu_id' => $menuId,
                    'created_by' => auth()->id(),
                    'updated_at' => null
                ]);
            }

            return response()->json(['success' => true], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create role-menu mapping. ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $roleId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'menu_ids' => 'required|array',
                'menu_ids.*' => 'exists:menus,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $existingMenuIds = RoleMenu::where('role_id', $roleId)->pluck('menu_id')->toArray();
            $newMenuIds = $request->menu_ids;

            // Get menus to add (in request but not in DB)
            $menusToAdd = array_diff($newMenuIds, $existingMenuIds);

            // Get menus to delete (in DB but not in request)
            $menusToDelete = array_diff($existingMenuIds, $newMenuIds);

            // Add new RoleMenu entries
            foreach ($menusToAdd as $menuId) {
                RoleMenu::create([
                    'role_id' => $roleId,
                    'menu_id' => $menuId,
                    'created_by' => auth()->id(),
                    'updated_at' => null
                ]);
            }

            // Delete removed RoleMenu entries
            if (!empty($menusToDelete)) {
                RoleMenu::where('role_id', $roleId)
                    ->whereIn('menu_id', $menusToDelete)
                    ->delete();
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update role-menu mapping. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($roleId)
    {
        try {
            RoleMenu::where('role_id', $roleId)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete role-menu mapping. ' . $e->getMessage()
            ], 500);
        }
    }
}
