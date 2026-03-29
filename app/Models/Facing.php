<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facing extends Model
{
    use HasFactory;

    protected $table = 'rl_facing_tbl';

    protected $fillable = [
        'facing_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
    
}