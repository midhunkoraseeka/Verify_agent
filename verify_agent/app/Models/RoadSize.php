<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoadSize extends Model
{
    use HasFactory;

    protected $table = 'rl_road_sizes_tbl';

    protected $fillable = [
        'road_size_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}