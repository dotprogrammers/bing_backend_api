<?php

namespace App\Http\Controllers\Api\Backend\Profile;

use App\Http\Controllers\Controller;
use App\Models\BloodCategory;
use App\Models\User;
use App\Models\UserDetail;
use Faker\Core\Blood;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $user_detail = UserDetail::where('user_id', $user->id)->first();
        if (!$user_detail) {
            $user_detail = new UserDetail();
            $user_detail->user_id = $user->id;
        }

        if ($request->hasFile('profile_picture')) {
            if ($user_detail->profile_picture && file_exists(public_path($user_detail->profile_picture))) {
                unlink(public_path($user_detail->profile_picture));
            }

            $profileImage = $request->file('profile_picture');
            $profileImageName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            $profileDestinationPath = 'uploads/user_profile/profile_picture';

            $profileImage->move(public_path($profileDestinationPath), $profileImageName);
            $user_detail->profile_picture = $profileDestinationPath . '/' . $profileImageName;
        }

        if ($request->hasFile('cover_photo')) {
            if ($user_detail->cover_photo && file_exists(public_path($user_detail->cover_photo))) {
                unlink(public_path($user_detail->cover_photo));
            }

            $coverImage = $request->file('cover_photo');
            $coverImageName = time() . '_' . uniqid() . '.' . $coverImage->getClientOriginalExtension();
            $coverDestinationPath = 'uploads/user_profile/cover_photo';

            $coverImage->move(public_path($coverDestinationPath), $coverImageName);
            $user_detail->cover_photo = $coverDestinationPath . '/' . $coverImageName;
        }

        $user_detail->bio = $request->bio ?? $user_detail->bio;
        $user_detail->date_of_birth = $request->date_of_birth ?? $user_detail->date_of_birth;
        $user_detail->blood_group = $request->blood_group ?? $user_detail->blood_group;
        $user_detail->team = $request->team ?? $user_detail->team;
        $user_detail->location = $request->location ?? $user_detail->location;
        $user_detail->description = $request->description ?? $user_detail->description;
        $user_detail->gender = $request->gender ?? $user_detail->gender;
        $user_detail->city = $request->city ?? $user_detail->city;
        $user_detail->upazila = $request->upazila ?? $user_detail->upazila;
        $user_detail->skill = $request->skill ?? $user_detail->skill;
        $user_detail->education = $request->education ?? $user_detail->education;
        $user_detail->is_available = $request->is_available ?? $user_detail->is_available;

        $user_detail->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Saved successfully',
            'data' => $user_detail
        ]);
    }

    public function show(Request $request)
    {
        $user = auth()->user();
        $user = User::select('users.id as userId', 'users.name', 'users.email', 'users.mobile_number', 'users.email_verified_at', 'users.phone_verified_at', 'user_details.*', 'blood_categories.name as blood_group_name')
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('blood_categories', 'blood_categories.id', '=', 'user_details.blood_group')
            ->where('users.id', $user->id)
            ->first();

        $blood_group = BloodCategory::select('id', 'name')->where('is_delete', 0)->get();

        return response()->json([
            'status' => 'success',
            'user_detail' => $user,
            'blood_group' => $blood_group
        ]);
    }
}
