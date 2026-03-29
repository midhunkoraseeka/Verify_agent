<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VasthuConsultant extends Model {
    protected $table = 'rl_vasthu_tbl'; 
    protected $fillable = [
        'full_name', 'mobile', 'constituency', 'district', 'state',
        'vasthu_services', 'office_location', 'aadhar', 'profile_photo',
        'facebook', 'instagram', 'linkedin', 'status', 'trash', 'created_by'
    ];
}
