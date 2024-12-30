<?php

namespace App\Http\Controllers\Api\Backend\Jobs;

use App\Http\Controllers\Controller;
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
}
