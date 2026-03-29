<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserManagement extends Model
{
    protected $table = 'rl_users_tbl';

    protected $fillable = [
        'user_display_id', 'first_name', 'last_name', 'email', 
        'mobile_number', 'city', 'constituency', 'pincode', 
        'address', 'username', 'password', 'status', 'trash', 
        'created_by', 'updated_by'
    ];
}