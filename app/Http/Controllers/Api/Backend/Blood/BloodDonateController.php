<?php

namespace App\Http\Controllers\Api\Backend\Blood;

use App\Http\Controllers\Controller;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class BloodDonateController extends Controller
{
    public function index($id)
    {
        $userDetails = UserDetail::with('bloodGroup:id,name')
            ->select('id', 'profile_picture', 'bio', 'blood_group', 'location', 'description')
            ->where('is_available', 1)
            ->where('blood_group', $id)
            ->where('is_delete', 0)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $userDetails
        ]);
    }
}
