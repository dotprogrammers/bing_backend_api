<?php

namespace App\Http\Controllers\Api\Backend\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class BrandController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $brands = Brand::where('is_delete', 0);

        if ($brands) {
            $brands->where('name', 'like', "%$search%");
        }

        $brands = $brands->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $brands
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:brands,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name, '-');
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Brand created successfully',
            'data' => $brand
        ], 200);
    }

    public function show($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        return response()->json(['success' => true, 'data' => $brand], 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $brand = Brand::find($request->id);
        if (!$brand) {
            return response()->json(['success' => false, 'message' => 'Brand not found'], 404);
        }

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name, '-');
        $brand->save();

        return response()->json(['success' => true, 'data' => $brand], 200);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            return response()->json([
                'success' => false,
                'message' => 'Brand not found'
            ], 404);
        }

        $brand->is_delete = 1;
        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Brand deleted successfully'
        ], 200);
    }
}
