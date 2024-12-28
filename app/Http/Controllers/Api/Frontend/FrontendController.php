<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function categories(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $categories = Category::where('is_delete', 0)->where('status', 'active');

        if ($search) {
            $categories->where('name', 'like', "%$search%");
        }

        $categories = $categories->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    public function subCategories(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $sub_categories = SubCategory::with('category')->where('is_delete', 0)->where('status', 'active');

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

    public function brands(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $brands = Brand::where('is_delete', 0)->where('status', 'active');

        if ($search) {
            $brands->where('name', 'like', "%$search%");
        }

        $brands = $brands->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $brands
        ], 200);
    }

    public function units(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $units = Unit::where('is_delete', 0)->where('status', 'active');

        if ($search) {
            $units->where('name', 'like', "%$search%");
        }

        $units = $units->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $units
        ], 200);
    }
}
