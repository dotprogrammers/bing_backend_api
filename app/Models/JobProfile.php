<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'profile_picture', 'cover_photo', 'bio', 'date_of_birth', 'phone',
        'is_phone_verified', 'email', 'is_email_verified', 'f_name', 'l_name',
        'age', 'price', 'height', 'work_type', 'educations', 'skills',
        'experiences', 'keyword', 'is_favourite'
    ];

    public function jobCategory()
    {
        return $this->belongsTo(JobCategory::class, 'category_id');
    }
}
