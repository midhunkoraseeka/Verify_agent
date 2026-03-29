<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectType extends Model
{
    use HasFactory;

    protected $table = 'rl_project_types_tbl'; // Points to the new project table

    protected $fillable = [
        'project_type_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}