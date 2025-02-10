<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\JobCategory;
use Illuminate\Support\Str;
use App\Models\BloodCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $bloodCategories = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        foreach ($bloodCategories as $blood) {
            BloodCategory::create([
                'name' => $blood,
            ]);
        }

        $categories = ['Technology', 'Health', 'Education', 'Sports', 'Entertainment'];
        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category)
            ]);
        }

        $jobCategories = ['Software Engineer', 'Doctor', 'Teacher', 'Marketing Manager', 'Data Analyst'];
        foreach ($jobCategories as $job) {
            JobCategory::create([
                'name' => $job,
                'slug' => Str::slug($job)
            ]);
        }
    }
}
