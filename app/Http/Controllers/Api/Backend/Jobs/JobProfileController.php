<?php

namespace App\Http\Controllers\Api\Backend\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobProfile;
use Illuminate\Http\Request;

class JobProfileController extends Controller
{
    public function index(Request$request)
    {
        $categoryId = $request->category_id ?? null;

        $job_profiles = JobProfile::where('category_id', $categoryId)->get();

        return response()->json([
            'status' => 'success',
            'data' => $job_profiles
        ]);
    }

    public function storeOrUpdate(Request $request)
    {
        $job_profile = JobProfile::find($request->id) ?? new JobProfile();

        if ($request->hasFile('profile_picture')) {
            if ($job_profile->profile_picture && file_exists(public_path($job_profile->profile_picture))) {
                unlink(public_path($job_profile->profile_picture));
            }

            $profileImage = $request->file('profile_picture');
            $profileImageName = time() . '_' . uniqid() . '.' . $profileImage->getClientOriginalExtension();
            $profileDestinationPath = public_path('uploads/job_profile/profile_picture');

            if (!file_exists($profileDestinationPath)) {
                mkdir($profileDestinationPath, 0777, true);
            }

            $profileImage->move($profileDestinationPath, $profileImageName);
            $job_profile->profile_picture = 'uploads/job_profile/profile_picture/' . $profileImageName;
        }

        if ($request->hasFile('cover_photo')) {
            if ($job_profile->cover_photo && file_exists(public_path($job_profile->cover_photo))) {
                unlink(public_path($job_profile->cover_photo));
            }

            $coverImage = $request->file('cover_photo');
            $coverImageName = time() . '_' . uniqid() . '.' . $coverImage->getClientOriginalExtension();
            $coverDestinationPath = public_path('uploads/job_profile/cover_photo');

            if (!file_exists($coverDestinationPath)) {
                mkdir($coverDestinationPath, 0777, true);
            }

            $coverImage->move($coverDestinationPath, $coverImageName);
            $job_profile->cover_photo = 'uploads/job_profile/cover_photo/' . $coverImageName;
        }

        $job_profile->fill($request->except(['profile_picture', 'cover_photo']));
        $job_profile->save();

        $job_profile->profile_picture_url = url($job_profile->profile_picture);
        $job_profile->cover_photo_url = url($job_profile->cover_photo);

        return response()->json([
            'status' => 'success',
            'message' => 'Saved successfully',
            'data' => $job_profile
        ]);
    }



    public function show($id)
    {
        $job_profile = JobProfile::with('jobCategory:id,name')->where('id', $id)->first();

        $job_profile->profile_picture_url = url($job_profile->profile_picture);
        $job_profile->cover_photo_url = url($job_profile->cover_photo);

        return response()->json([
            'status' => 'success',
            'job_profile' => $job_profile
        ]);
    }
}
