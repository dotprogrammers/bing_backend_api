<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BloodCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Education;
use App\Models\JobCategory;
use App\Models\Product;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function categories(Request $request)
    {
        $categories = Category::where('is_delete', 0)->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    public function brands(Request $request)
    {
        $brands = Brand::where('is_delete', 0)->get();

        return response()->json([
            'success' => true,
            'data' => $brands
        ], 200);
    }

    public function jobCategories(Request $request)
    {
        $jobCategories = JobCategory::where('is_delete', 0)->get();
        return response()->json([
            'success' => true,
            'data' => $jobCategories
        ], 200);
    }


    public function products(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $prosucts = Product::select('products.id','products.name','products.price', 'products.image', 'products.condition')->where('is_delete', 0);

        if ($search) {
            $prosucts->where('name', 'like', "%$search%");
        }

        $prosucts = $prosucts->paginate($limit);

        $prosucts->getCollection()->transform(function ($product) {
            $product->image = url('uploads/products/image/' . $product->image);
           
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $prosucts
        ], 200);
    }

    public function productDetail($id)
    {
        $product = Product::with('brand:id,name')->where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->image = url('uploads/products/image/' . $product->image);

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }

    public function education()
    {
        $query = Education::where('is_delete', 0)->get();
        return response()->json([
            'status' => true,
            'data' => $query
        ]);
    }

    public function bloodCategories()
    {
        $query = BloodCategory::where('is_delete', 0)->get();
        return response()->json([
            'status' => true,
            'data' => $query
        ]);
    }

}
