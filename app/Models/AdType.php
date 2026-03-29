<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdType extends Model
{
    use HasFactory;

    protected $table = 'rl_ad_types_tbl';

    protected $fillable = [
        'ad_type_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}