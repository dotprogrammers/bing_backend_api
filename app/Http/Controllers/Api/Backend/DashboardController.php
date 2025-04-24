<?php

namespace App\Http\Controllers\Api\Backend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Job;
use App\Models\BloodCategory;
use App\Models\Category;
use App\Models\RentCategory;
use App\Models\JobCategory;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Education;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $allModelCount = [
            'categories' => Category::count(),
            'blood_categories' => BloodCategory::count(),
            'rent_categories' => RentCategory::count(),
            'job_categories' => JobCategory::count(),
            'brands' => Brand::count(),
            'products' => Product::count(),
            'educations' => Education::count(),
            'jobs' => Job::count(),
            'bookings' => Booking::count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $allModelCount
        ], 200);
    }
}
