<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LandType extends Model
{
    use HasFactory;

    protected $table = 'rl_land_types_tbl';

    protected $fillable = [
        'land_type_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}