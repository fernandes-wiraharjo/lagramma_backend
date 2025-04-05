<?php

namespace App\Http\Controllers;

use App\Models\RoleMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleMenuController extends Controller
{
    public function index()
    {
        return view('role-menu');
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

    public function getByRoleId($role_id)
    {
        //sampai sini
        // $role = Role::find($role_id);

        // if (!$role) {
        //     return response()->json(['message' => 'Role not found'], 404);
        // }

        // return response()->json($role);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'is_active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $role = Role::create([
                'name' => $request->name,
                'is_active' => $request->is_active,
                'created_by' => auth()->id(),
                'updated_at' => null
            ]);

            return response()->json(['success' => true, 'role' => $role], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create role. ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:100',
                'is_active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $role = Role::find($id);
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }

            $role->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
                'updated_by' => auth()->id()
            ]);

            return response()->json(['success' => true, 'role' => $role]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update role. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete role. ' . $e->getMessage()
            ], 500);
        }
    }
}
