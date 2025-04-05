<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function index()
    {
        return view('role');
    }

    public function getRoles(Request $request)
    {
        $query = Role::query();

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'id',
            1 => 'name',
            2 => 'is_active',
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 1); // Default to second column (name)
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'name';

        // Get total records count (before filtering)
        $totalRecords = Role::count();

         // Apply search filtering
         if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';
            $query->where('name', 'like', $searchValue);
        }

        // Get total filter records count (after filtering)
        $totalFiltered = $query->count();

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

    public function getById($id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        return response()->json($role);
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
