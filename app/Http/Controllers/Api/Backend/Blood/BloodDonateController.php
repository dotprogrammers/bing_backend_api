<?php

namespace App\Http\Controllers\Api\Backend\Blood;

use App\Http\Controllers\Controller;
use App\Models\BloodCategory;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class BloodDonateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? null;
        $location = $request->location ?? null;

        $bloodCategory = BloodCategory::where('is_delete', 0)
            ->when($search, function ($query, $search) {
                return $query->where('blood_categories.name', 'LIKE', "%{$search}%");
            })
            ->get();

        $userDetails = UserDetail::where('is_delete', 0)
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('user_details.phone', 'LIKE', "%{$search}%")
                        ->orWhere('user_details.team', 'LIKE', "%{$search}%")
                        ->orWhere('user_details.f_name', 'LIKE', "%{$search}%");
                });
            })
            ->when($location, function ($query, $location) {
                return $query->where('location', 'LIKE', "%{$location}%");
            })
            ->get();

        return response()->json([
            'status' => true,
            'bloodCategory' => $bloodCategory,
            'data' => $userDetails
        ]);
    }
}
