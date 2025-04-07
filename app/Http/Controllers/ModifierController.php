<?php

namespace App\Http\Controllers;

use App\Models\Modifier;
use App\Models\ModifierOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class ModifierController extends Controller
{
    public function index()
    {
        return view('modifier');
    }

    public function get(Request $request)
    {
        $query = Modifier::query()
            ->select([
                'id', 'moka_id_modifier', 'name', 'is_active'
            ]);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'moka_id_modifier',
            1 => 'name',
            2 => 'is_active',
        ];

        // Retrieve sorting column index and direction from DataTables request
        $sortColumnIndex = $request->input('order.0.column', 0); // Default to first column
        $sortDirection = $request->input('order.0.dir', 'asc');  // Default to ascending

        // Determine the column name based on the column index
        $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'moka_id_modifier';

        // Get total records count (before filtering)
        $totalRecords = Modifier::count();

         // Apply search filtering
         if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', $searchValue);
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

    public function sync(Request $request)
    {
        try {
            Artisan::call('app:sync-moka-modifiers');
            return response()->json(['success' => true, 'message' => 'Modifiers synced successfully']);
        } catch (\Exception $e) {
            \Log::error('Moka Sync Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Sync failed. Check logs.'], 500);
        }
    }

    public function toggleActive($id, Request $request)
    {
        $data = Modifier::findOrFail($id);
        $data->is_active = $request->input('is_active');
        $data->updated_by = auth()->id();
        $data->save();

        return response()->json(['success' => true]);
    }

    public function indexModifierOption()
    {
        return view('modifier-option');
    }

    public function getModifierOption(Request $request)
    {
        $query = ModifierOption::query()
            ->join('modifiers', 'modifier_options.id_modifier', '=', 'modifiers.id')
            ->select([
                'modifier_options.id', 'modifiers.name as modifier_name', 'moka_id_modifier_option', 'modifier_options.name',
                'price', 'modifier_options.is_active'
            ]);

         // Define sortable columns based on DataTables column index
         $sortableColumns = [
            0 => 'modifiers.name',
            1 => 'moka_id_modifier_option',
            2 => 'modifier_options.name',
            3 => 'price',
            4 => 'modifier_options.is_active'
        ];

         // Apply search filtering
         if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = '%' . $request->search['value'] . '%';

            $query->where(function ($q) use ($searchValue) {
                $q->where('modifiers.name', 'like', $searchValue)
                  ->orWhere('modifier_options.name', 'like', $searchValue);
            });
        }

        // Get total records count (before filtering)
        $totalRecords = ModifierOption::count();

        // Get total filter records count (after filtering)
        $totalFiltered = $query->count();

        // Apply sorting
        if ($request->has('order')) {
            $sortColumnIndex = $request->input('order.0.column', 0);
            $sortDirection = $request->input('order.0.dir', 'asc');
            $sortColumn = $sortableColumns[$sortColumnIndex] ?? 'modifiers.name';

            $query->orderBy($sortColumn, $sortDirection);
        } else {
            // Default sort on initial load
            $query->orderBy('modifiers.name')->orderBy('modifier_options.position');
        }

        // Apply sorting and pagination
        $data = $query
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

    public function toggleActiveModifierOption($id, Request $request)
    {
        $data = ModifierOption::findOrFail($id);
        $data->is_active = $request->input('is_active');
        $data->updated_by = auth()->id();
        $data->save();

        return response()->json(['success' => true]);
    }
}
