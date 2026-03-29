<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BhkType extends Model
{
    use HasFactory;

    protected $table = 'rl_bhk_types_tbl';

    protected $fillable = [
        'bhk_type_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}