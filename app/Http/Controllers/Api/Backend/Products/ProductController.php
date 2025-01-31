<?php

namespace App\Http\Controllers\Api\Backend\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $prosucts = Product::with('category:id,name', 'brand:id,name')->where('is_delete', 0);

        if ($prosucts) {
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'brand_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-');
        $product->price = $request->price;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->condition = $request->condition;
        $product->description = $request->description;
        if ($request->hasFile('image')) { 
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/products/image');
            $image->move($destinationPath, $name);
            $product->image = $name;
        }
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
            'brand_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->id);
        $product->name = $request->name;
        $product->slug = Str::slug($request->name, '-');
        $product->price = $request->price;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->condition = $request->condition;
        $product->description = $request->description;
        $product->status = $request->status;
        if ($request->hasFile('image')) { 
            $oldLogo = $product->image;
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/products/image');
            if ($oldLogo) {
                unlink($destinationPath . '/' . $oldLogo);
            }
            $image->move($destinationPath, $name);
            $product->image = $name;
        }
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'pro$product not found'
            ], 404);
        }

        $product->is_delete = 1;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
