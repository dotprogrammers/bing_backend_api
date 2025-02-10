<?php

namespace App\Http\Controllers\Api\Backend\Jobs;

use App\Http\Controllers\Controller;
use App\Models\EducationDetails;
use App\Models\JobCategory;
use App\Models\JobExperience;
use App\Models\JobProfile;
use App\Models\JobSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobProfileController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->category_id ?? null;

        $users = User::select(
            'users.id as userId',
            'users.name',
            'users.email',
            'users.mobile_number',
            'users.email_verified_at',
            'users.phone_verified_at',
            'user_details.cover_photo',
            'user_details.profile_picture',
            'user_details.date_of_birth',
            'user_details.bio',
            'user_details.blood_group',
            'user_details.location',
            'job_profiles.profile_picture as job_profile_picture',
            'job_profiles.cover_photo as job_cover_photo',
            'job_profiles.bio as job_bio',
            'job_profiles.date_of_birth as job_date_of_birth',
            'job_profiles.name as job_name',
            'job_profiles.age',
            'job_profiles.price',
            'job_profiles.height',
            'job_profiles.work_type as job_work_type',
            'job_profiles.keyword',
            'job_profiles.job_category_id'
        )
            ->leftJoin('user_details', 'users.id', '=', 'user_details.user_id')
            ->leftJoin('job_profiles', 'users.id', '=', 'job_profiles.user_id');

        if ($categoryId) {
            $users->where('job_profiles.job_category_id', $categoryId);
        }


        $job_profiles = JobProfile::when($categoryId, function ($query) use ($categoryId) {
            return $query->where('job_category_id', $categoryId);
        })->get();

        if ($job_profiles->isEmpty()) {
            $job_profiles = $users;
        }

        $categories = JobCategory::where('is_delete', 0)->get();

        $skills = JobSkill::whereIn('user_id', $users->pluck('userId'))->get();
        $educations = EducationDetails::whereIn('user_id', $users->pluck('userId'))->get();
        $experiences = JobExperience::whereIn('user_id', $users->pluck('userId'))->get();

        return response()->json([
            'status' => 'success',
            'data' => $job_profiles,
            'categories' => $categories,
            'skills' => $skills,
            'educations' => $educations,
            'experiences' => $experiences
        ]);
    }

    public function storeOrUpdate(Request $request)
    {
        DB::beginTransaction();

        try {

            $user = auth()->user();
            if ($user) {
                $job_profile = JobProfile::where('user_id', $user->id)->first();
            } else {
                $job_profile = new JobProfile();
            }

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

            $job_profile->user_id = $user->user_id;
            $job_profile->job_category_id = $request->job_category_id;
            $job_profile->bio = $request->bio;
            $job_profile->date_of_birth = $request->date_of_birth;
            $job_profile->name = $request->name;
            $job_profile->age = $request->age;
            $job_profile->price = $request->price;
            $job_profile->height = $request->height;
            $job_profile->work_type = $request->work_type;
            $job_profile->keyword = $request->keyword;
            $job_profile->is_favourite = $request->is_favourite;
            $job_profile->save();

            $education_detail = new EducationDetails();
            $education_detail->user_id = $user->user_id;
            $education_detail->education_id = $request->education_id;
            $education_detail->institute_name = $request->institute_name;
            $education_detail->board = $request->board;
            $education_detail->gpa = $request->gpa;
            $education_detail->passing_year = $request->passing_year;
            $education_detail->subject = $request->subject;
            $education_detail->save();

            $job_skill = new JobSkill();
            $job_skill->user_id = $user->user_id;
            $job_skill->title = $request->title;
            $job_skill->skill_category = $request->skill_category;
            $job_skill->description = $request->description;
            $job_skill->link_type = $request->link_type;
            $job_skill->link = $request->link;
            $job_skill->save();

            $job_experience = new JobExperience();
            $job_experience->user_id = $user->user_id;
            $job_experience->job_category = $request->job_category;
            $job_experience->company_name = $request->company_name;
            $job_experience->company_location = $request->company_location;
            $job_experience->experience = $request->experience;
            $job_experience->save();


            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Saved successfully',
                'data' => $job_profile
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
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
