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
}
