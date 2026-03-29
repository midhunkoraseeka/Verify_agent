<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandSurveyor extends Model {
    protected $table = 'rl_surveyors_tbl';
    protected $fillable = [
        'full_name', 'mobile', 'constituency', 'district', 'state',
        'survey_services', 'office_location', 'aadhar', 'profile_photo',
        'facebook', 'instagram', 'linkedin', 'status', 'trash', 'created_by'
    ];
}