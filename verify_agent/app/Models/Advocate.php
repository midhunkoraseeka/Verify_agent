<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advocate extends Model {
    protected $table = 'rl_advocates_tbl';
    protected $fillable = [
        'full_name', 'mobile', 'constituency', 'district', 'state',
        'legal_services', 'office_location', 'aadhar', 'profile_photo',
        'facebook', 'instagram', 'linkedin', 'status', 'trash', 'created_by'
    ];
}
