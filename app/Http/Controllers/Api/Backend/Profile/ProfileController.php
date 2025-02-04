<?php

namespace App\Http\Controllers\Api\Backend\Profile;

use App\Http\Controllers\Controller;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // public function storeOrUpdate(Request $request)
    // {
    //     $user_detail = UserDetail::find($request->id) ?? new UserDetail();

    //     $user_detail->fill($request->only([
    //         'profile_picture', 'cover_photo', 'bio', 'date_of_birth', 'phone',
    //         'is_phone_verified', 'email', 'is_email_verified', 'f_name', 'l_name',
    //         'age', 'price', 'height', 'work_type', 'educations', 'skills',
    //         'experiences', 'keyword', 'is_favourite'
    //     ]));

    //     $user_detail->save();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Saved successfully',
    //         'user_detail' => $user_detail
    //     ]);
    // }

    public function storeOrUpdate(Request $request)
    {
        $user_detail = UserDetail::find($request->id) ?? new UserDetail();

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

    public function show($id)
    {
        $user_detail = UserDetail::find($id);

        $user_detail->profile_picture_url = url($user_detail->profile_picture);
        $user_detail->cover_photo_url = url($user_detail->cover_photo);

        return response()->json([
            'status' => 'success',
            'user_detail' => $user_detail
        ]);
    }
}
