<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parking extends Model
{
    use HasFactory;

    protected $table = 'rl_parking_tbl';

    protected $fillable = [
        'parking_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}