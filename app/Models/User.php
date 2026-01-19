<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['username','password','name','role','is_active'];
    protected $hidden = ['password'];
    protected $casts = ['is_active' => 'boolean'];
}
