<?php

namespace App\Http\Controllers\Api\Backend\Unit;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $units = Unit::where('is_delete', 0);

        if ($units) {
            $units->where('name', 'like', "%$search%");
        }

        $units = $units->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $units
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:units,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $unit = new Unit();
        $unit->name = $request->name;
        $unit->slug = Str::slug($request->name, '-');
        $unit->save();

        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully',
            'data' => $unit
        ], 200);
    }

    public function show($id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Unit not found'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $unit], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:units,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $unit = Unit::find($request->id);
        if (!$unit) {
            return response()->json(['success' => false, 'message' => 'Unit not found'], 404);
        }

        $unit->name = $request->name;
        $unit->slug = Str::slug($request->name, '-');
        $unit->save();

        return response()->json(['success' => true, 'data' => $unit], 200);
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);
        if (!$unit) {
            return response()->json([
                'success' => false,
                'message' => 'Unit not found'
            ], 404);
        }

        $unit->is_delete = 1;
        $unit->save();

        return response()->json([
            'success' => true,
            'message' => 'Unit deleted successfully'
        ], 200);
    }
}
