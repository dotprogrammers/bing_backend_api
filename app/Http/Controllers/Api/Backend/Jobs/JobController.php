<?php

namespace App\Http\Controllers\Api\Backend\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $skill = $request->skill ?? null;
        $limit = $request->limit ?? 10;

        $jobs = Job::where('is_delete', 0);

        if ($jobs) {
            $jobs->where('name', 'like', "%$search%");
        }

        $jobs = $jobs->paginate($limit);

        $jobs->getCollection()->transform(function ($job) {
            $job->image = url('uploads/jobs/image/' . $job->image);
           
            return $job;
        });

        return response()->json([
            'success' => true,
            'data' => $jobs
        ], 200);
    }

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
        $product->unit_id = $request->unit_id;
        $product->sub_category_id = $request->sub_category_id;
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
}
