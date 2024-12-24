<?php

namespace App\Http\Controllers\Api\Backend\SubCategory;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $sub_categories = SubCategory::with('category')->where('is_delete', 0);

        if ($search) {
            $sub_categories->where('name', 'like', "%$search%")
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
        }

        $sub_categories = $sub_categories->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $sub_categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $sub_category = new SubCategory();
        $sub_category->category_id = $request->category_id;
        $sub_category->name = $request->name;
        $sub_category->slug = Str::slug($request->name, '-');
        $sub_category->save();

        return response()->json([
            'success' => true,
            'message' => 'Sub Category created successfully',
            'data' => $sub_category
        ], 200);
    }

    public function show($id)
    {
        $sub_category = SubCategory::with('category')->find($id);
        if (!$sub_category) {
            return response()->json([
                'success' => false,
                'message' => 'Sub Category not found'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $sub_category], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $sub_category = SubCategory::find($request->id);
        if (!$sub_category) {
            return response()->json(['success' => false, 'message' => 'Sub Category not found'], 404);
        }

        $sub_category->name = $request->name;
        $sub_category->category_id = $request->category_id;
        $sub_category->slug = Str::slug($request->name, '-');
        $sub_category->save();

        return response()->json(['success' => true, 'data' => $sub_category], 200);
    }

    public function destroy($id)
    {
        $sub_category = SubCategory::find($id);
        if (!$sub_category) {
            return response()->json([
                'success' => false,
                'message' => 'Sub Category not found'
            ], 404);
        }

        $sub_category->is_delete = 1;
        $sub_category->save();

        return response()->json([
            'success' => true,
            'message' => 'Sub Category deleted successfully'
        ], 200);
    }
}
