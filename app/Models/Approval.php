<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'rl_approvals_tbl';

    protected $fillable = [
        'approval_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}