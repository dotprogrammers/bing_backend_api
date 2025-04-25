<?php

namespace App\Http\Controllers\Api\Backend\Rent;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $limit = $request->limit ?? 10;

        $rents = Rent::where('is_delete', 0);

        if ($search) {
            $rents->where('title', 'like', '%' . $search . '%');
        }

        if ($request->has('category')) {
            $rents->where('category_id', $request->category);
        }

        if ($request->has('price')) {
            $rents->where('price', '<=', $request->price);
        }

        if ($request->has('area_size')) {
            $rents->where('area_size', '>=', $request->area_size);
        }
        
        if ($request->has('floor_no')) {
            $rents->where('floor_no', '>=', $request->floor_no);
        }

        if ($request->has('bedroom')) {
            $rents->where('bedroom', '>=', $request->bedroom);
        }

        if ($request->has('bathroom')) {
            $rents->where('bathroom', '>=', $request->bathroom);
        }

        if ($request->has('location')) {
            $rents->where('LOWER(TRIM(location)) LIKE ?', ["%" . strtolower($request->location) . "%"]);
        }

        $rents = $rents->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $rents
        ], 200);
    }


    public function show($id)
    {
        $rent = Rent::find($id);
        return response()->json([
            'success' => true,
            'data' => $rent
        ], 200);
    }

    public function update(Request $request, Rent $rent)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'rent_type' => 'required|string',
            'property_type' => 'required|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'keyword' => 'nullable|string',
            'location' => 'required|string',
            'area_size' => 'nullable|string',
            'floor_no' => 'nullable|string',
            'bedroom' => 'nullable|integer',
            'bathroom' => 'nullable|integer',
            'balcony' => 'nullable|integer',
            'kitchen' => 'nullable|integer',
            'type' => 'nullable|string',
            'available_date' => 'nullable|date',
            'image.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    
        if ($rent->image) {
            $oldImages = json_decode($rent->image, true);
            if (is_array($oldImages)) {
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('uploads/rents/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
            }
        }
    
        $rent->fill($validated);
    
        if ($request->hasFile('image')) {
            $imageArray = [];
            foreach ($request->file('image') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/rents/'), $filename);
                $imageArray[] = $filename;
            }
            $rent->image = json_encode($imageArray);
        }
    
        $rent->save();
    
        return response()->json(['message' => 'Rent updated successfully', 'rent' => $rent], 200);
    }

    public function destroy(Request $request, Rent $rent)
    {
        $rent->is_delete = 1;
        $rent->save();
        return response()->json(['message' => 'Rent deleted successfully'], 200);
    }

}
