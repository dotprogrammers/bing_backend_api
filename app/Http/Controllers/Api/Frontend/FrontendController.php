<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BloodCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Education;
use App\Models\JobCategory;
use App\Models\Product;
use App\Models\ProductBooking;
use Illuminate\Support\Facades\Validator;
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
        $products = Product::select('products.id', 'products.name', 'products.price', 'products.image', 'products.condition')
            ->where('is_exchange', 0)
            ->where('is_delete', 0)
            ->get();

        $products->map(function ($product) {
            $product->image = url('uploads/products/image/' . $product->image);
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    public function exchangeProducts(Request $request)
    {
        $products = Product::select('products.id', 'products.name', 'products.price', 'products.image', 'products.condition')
            ->where('is_exchange', 1)
            ->where('is_delete', 0)
            ->get();

        $products->map(function ($product) {
            $product->image = url('uploads/products/image/' . $product->image);
            return $product;
        });

        return response()->json([
            'success' => true,
            'data' => $products
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

    public function bloodCategories(Request $request)
    {
        $search = $request->search ?? null;
        $location = $request->location ?? null;

        $query = BloodCategory::with(['userDetaile' => function($query) use ($location) {
            if ($location) {
                $query->where('location', $location);
            }
        }])
        ->where('is_delete', 0);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $result = $query->get();

        return response()->json([
            'status' => true,
            'data' => $result
        ]);
    }

    public function productBooking(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'product_id' => 'required',
            'adult' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $booking = new ProductBooking();
        $booking->product_id = $request->product_id;
        $booking->user_id = $request->user_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->name = $request->name;
        $booking->phone = $request->phone;
        $booking->adult = $request->adult;
        $booking->child = $request->child;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Booking has been completed'
        ], 200);
    }
}
