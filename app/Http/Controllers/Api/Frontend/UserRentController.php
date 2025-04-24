<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $rents = Rent::where('is_delete', 0)
            ->where('user_id', '!=', $userId);

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

        $rents = $rents->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $rents
        ], 200);
    }


    public function store(Request $request)
    {
        $rent = new Rent();
        $rent->user_id = Auth::user()->id;
        $rent->category_id = $request->category_id;
        $rent->title = $request->title;
        $rent->name = $request->name;
        $rent->rent_type = $request->rent_type;
        $rent->property_type = $request->property_type;
        $rent->price = $request->price;
        $rent->discount_price = $request->discount_price;
        $rent->description = $request->description;
        $rent->keyword = $request->keyword;
        $rent->location = $request->location;
        $rent->area_size = $request->area_size;
        $rent->floor_no = $request->floor_no;
        $rent->bedroom = $request->bedroom;
        $rent->bathroom = $request->bathroom;
        $rent->balcony = $request->balcony;
        $rent->kitchen = $request->kitchen;
        $rent->type = $request->type;
        $rent->available_date = $request->available_date;

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

        return response()->json(['message' => 'Rent added successfully'], 201);
    }

    public function show($id)
    {
        $rent = Rent::find($id);
        return response()->json([
            'success' => true,
            'data' => $rent
        ], 200);
    }

    public function update(Request $request)
    {
        $rent = Rent::findOrFail($request->id);

        if ($rent->image) {
            $oldImages = json_decode($rent->image, true);
            if (is_array($oldImages)) {
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path('uploads/rents/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        }

        $rent->user_id = Auth::user()->id;
        $rent->category_id = $request->category_id;
        $rent->title = $request->title;
        $rent->name = $request->name;
        $rent->rent_type = $request->rent_type;
        $rent->property_type = $request->property_type;
        $rent->price = $request->price;
        $rent->discount_price = $request->discount_price;
        $rent->description = $request->description;
        $rent->keyword = $request->keyword;
        $rent->location = $request->location;
        $rent->area_size = $request->area_size;
        $rent->floor_no = $request->floor_no;
        $rent->bedroom = $request->bedroom;
        $rent->bathroom = $request->bathroom;
        $rent->balcony = $request->balcony;
        $rent->kitchen = $request->kitchen;
        $rent->type = $request->type;
        $rent->available_date = $request->available_date;

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

        return response()->json(['message' => 'Rent updated successfully'], 200);
    }

    public function destroy($id)
    {
        $rent = Rent::find($id);
        $rent->is_delete = 1;
        $rent->save();
        return response()->json(['message' => 'Rent deleted successfully'], 200);
    }

    public function markAsFavouriteRent($id)
    {
        $rent = Rent::find($id);
        $rent->is_favourite = 1;
        $rent->save();
        return response()->json(['message' => 'Rent marked as favourite successfully'], 200);
    }

    public function removedFavouriteRent($id)
    {
        $rent = Rent::find($id);
        $rent->is_favourite = 0;
        $rent->save();
        return response()->json(['message' => 'Rent removed from favourite successfully'], 200);
    }

    public function rentList(Request $request)
    {
        $search = $request->search ?? null;

        $rents = Rent::where('is_delete', 0)
            ->where('is_favourite', 1)
            ->where('user_id', Auth::user()->id);

        if ($search) {
            $rents->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%")
                    ->orWhere('name', 'LIKE', "%$search%")
                    ->orWhere('price', $search);
            });
        }

        $rents = $rents->orderBy('created_at', 'desc')->paginate(10);

        return response()->json(['data' => $rents], 200);
    }
}
