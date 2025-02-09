<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function userDetaile(){
        return $this->hasMany(UserDetail::class);
    }
}
