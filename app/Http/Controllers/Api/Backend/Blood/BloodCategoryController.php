<?php

namespace App\Http\Controllers\Api\Backend\Blood;

use App\Http\Controllers\Controller;
use App\Models\BloodCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BloodCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $queries = BloodCategory::where('is_delete', 0)->paginate($limit);

        if ($search) {
            $queries->where('name', 'like', '%' . $search . '%');
        }

        return response()->json([
            'status' => true,
            'data' => $queries
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ]);
        }

        $query = new BloodCategory();
        $query->name = $request->name;
        $query->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been saved'
        ]);
    }

    public function show($id)
    {
        $query = BloodCategory::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $query
        ]);
    }

    public function update(Request $request)
    {
        $query = BloodCategory::find($request->id);
        $query->name = $request->name;
        $query->status = $request->status;
        $query->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been updated'
        ]);
    }

    public function destroy($id)
    {
        $query = BloodCategory::find($id);
        $query->is_delete = 1;
        $query->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been deleted'
        ]);
    }
}
