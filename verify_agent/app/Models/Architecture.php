<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Architecture extends Model {
    protected $table = 'rl_architectures_tbl';
    protected $fillable = [
        'project_name', 'project_type', 'architect_name', 'license_no',
        'project_status', 'submission_date', 'approval_date', 'project_address',
        'city', 'state', 'pincode', 'description', 'plans', 'trash', 'created_by'
    ];
}
