<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::where('is_active', true)->get(['id', 'name']);

        return view('user', compact('roles'));
    }

    public function get(Request $request)
    {
        $query = User::query()
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select([
                'users.id', 'users.name', 'users.email', 'users.phone', 'users.is_active',
                'roles.id as role_id',
                'roles.name as role_name'
            ]);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'users.id',
            1 => 'users.name',
            2 => 'users.email',
            3 => 'users.phone',
            4 => 'roles.name',
            5 => 'users.is_active',
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'users.name';

        // Get total records count (before filtering)
        $totalRecords = User::count();

         // Apply search filtering
         if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', $searchValue)
                  ->orWhere('users.email', 'like', $searchValue)
                  ->orWhere('users.phone', 'like', $searchValue)
                  ->orWhere('roles.name', 'like', $searchValue);
            });
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

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'email' => 'required|email|max:255|unique:users,email',
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:50',
                'password' => 'required|string|min:5|confirmed',
                'is_active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $user = User::create([
                'role_id' => $request->role_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'is_active' => $request->is_active,
                'is_verified' => true,
                'created_by' => auth()->id(),
                'updated_at' => null
            ]);

            return response()->json(['success' => true, 'data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user. ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'role_id' => 'required|exists:roles,id',
                'email' => 'required|email|max:255|unique:users,email,' . $id,
                'name' => 'required|string|max:100',
                'phone' => 'required|string|max:50',
                'password' => 'nullable|string|min:5|confirmed',
                'is_active' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()], 422);
            }

            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->role_id = $request->role_id;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->is_active = $request->is_active;
            $user->updated_by = auth()->id();

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json(['success' => true, 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user. ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            User::where('id', $id)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user. ' . $e->getMessage()
            ], 500);
        }
    }
}
