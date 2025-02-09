<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UserDetail extends Authenticatable implements MustVerifyEmail
{
    // use HasFactory;
    use HasFactory, HasRoles, HasApiTokens, Notifiable;

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
}
