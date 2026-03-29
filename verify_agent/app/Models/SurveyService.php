<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyService extends Model
{
    protected $table = 'rl_survey_services_tbl';

    protected $fillable = [
        'service_name',
        'status',
        'trash',
        'created_by',
        'updated_by'
    ];
}