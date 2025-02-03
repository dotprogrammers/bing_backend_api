<?php

namespace App\Http\Controllers\Api\Backend\Education;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $queries = Education::where('is_delete', 0)->paginate($limit);

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

        $query = new Education();
        $query->name = $request->name;
        $query->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been saved'
        ]);
    }

    public function show($id)
    {
        $query = Education::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $query
        ]);
    }

    public function update(Request $request)
    {
        $query = Education::find($request->id);
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
        $query = Education::find($id);
        $query->is_delete = 1;
        $query->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been deleted'
        ]);
    }
}
