<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'cover_photo',
        'bio',
        'date_of_birth',
        'phone',
        'is_phone_verified',
        'email',
        'is_email_verified',
        'f_name',
        'l_name',
        'blood_group',
        'team',
        'location',
        'description',
        'gender',
        'city',
        'upazila',
        'skill',
        'education',
        'is_available',
        'is_delete',
        'status'
    ];

    public function bloodGroup()
    {
        return $this->belongsTo(BloodCategory::class, 'blood_group');
    }
}
