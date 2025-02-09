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
        if ($user) {
            $user_detail = UserDetail::where('user_id', $user->id)->first();
        } else {
            $user_detail = new UserDetail();
        }

        if ($request->hasFile('profile_picture')) {
            if ($user_detail->profile_picture && file_exists(public_path($user_detail->profile_picture))) {
                unlink(public_path($user_detail->profile_picture));
            }

            $profileImage = $request->file('profile_picture');
            $profileImageName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            $profileDestinationPath = public_path('uploads/user_profile/profile_picture');

            if (!file_exists($profileDestinationPath)) {
                mkdir($profileDestinationPath, 0777, true);
            }

            $profileImage->move($profileDestinationPath, $profileImageName);
            $user_detail->profile_picture = 'uploads/user_profile/profile_picture/' . $profileImageName;
        }

        if ($request->hasFile('cover_photo')) {
            if ($user_detail->cover_photo && file_exists(public_path($user_detail->cover_photo))) {
                unlink(public_path($user_detail->cover_photo));
            }

            $coverImage = $request->file('cover_photo');
            $coverImageName = time() . '_' . uniqid() . '.' . $coverImage->getClientOriginalExtension();
            $coverDestinationPath = public_path('uploads/user_profile/cover_photo');

            if (!file_exists($coverDestinationPath)) {
                mkdir($coverDestinationPath, 0777, true);
            }

            $coverImage->move($coverDestinationPath, $coverImageName);
            $user_detail->cover_photo = 'uploads/user_profile/cover_photo/' . $coverImageName;
        }

        $user_detail->fill($request->except(['profile_picture', 'cover_photo']));
        $user_detail->save();

        $user_detail->profile_picture_url = url($user_detail->profile_picture);
        $user_detail->cover_photo_url = url($user_detail->cover_photo);

        return response()->json([
            'status' => 'success',
            'message' => 'Saved successfully',
            'data' => $user_detail
        ]);
    }

    public function show(Request $request)
    {
        $user = auth()->user();
        $user = User::select('users.id', 'users.email', 'user_details.*', 'blood_categories.name as blood_group_name')
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
