<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserManagement extends Authenticatable
{
    use HasFactory;

    // Direct the model to your custom table name
    protected $table = 'rl_users_tbl';

    // Allow these fields to be saved to the database
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile_number',
        'city',
        'constituency',
        'pincode',
        'address',
        'username',
        'password',
        'status',
        'trash',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password',
    ];
}