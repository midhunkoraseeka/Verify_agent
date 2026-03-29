<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Floor extends Model
{
    use HasFactory;

    protected $table = 'rl_floors_tbl';

    protected $fillable = [
        'floor_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}