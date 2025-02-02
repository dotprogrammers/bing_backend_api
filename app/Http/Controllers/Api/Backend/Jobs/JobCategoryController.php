<?php

namespace App\Http\Controllers\Api\Backend\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $jobs = JobCategory::where('is_delete', 0);
        if ($search) {
            $jobs->where('name', 'like', '%' . $search . '%');
        }
        $jobs = $jobs->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $jobs
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $job_category = new JobCategory();
        $job_category->name = $request->name;
        $job_category->slug = Str::slug($request->name, '-');
        $job_category->save();

        return response()->json([
            'success' => true,
            'message' => 'Job category added successfully',
        ], 200);
    }

    public function show($id)
    {
        $job_category = JobCategory::find($id);

        return response()->json([
            'success' => true,
            'data' => $job_category
        ], 200);
    }

    public function update(Request $request)
    {
        $job_category = JobCategory::find($request->id);
        $job_category->name = $request->name;
        $job_category->slug = Str::slug($request->name, '-');
        $job_category->status = $request->status;
        $job_category->save();

        return response()->json([
            'success' => true,
            'message' => 'Job category updated successfully',
        ], 200);
    }

    public function destroy(Request $request)
    {
        $job_category = JobCategory::find($request->id);
        $job_category->is_delete = 1;
        $job_category->save();

        return response()->json([
            'success' => true,
            'message' => 'Job category deleted successfully',
        ], 200);
    }
}
