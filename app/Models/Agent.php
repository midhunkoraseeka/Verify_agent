<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use Notifiable;

    protected $table = 'rl_agent_tbl';

    protected $fillable = [
        'usertype', 'agent_id', 'priority', 'agent_name', 'father_name', 
        'date_of_birth', 'location', 'constituency', 'district', 
        'mobile_number', 'address', 'rera_no', 'username', 
        'password', 'status', 'trash', 'created_by', 'updated_by', 
        'agent_aadhar', 'agent_photo'
    ];

    protected $hidden = [
        'password',
    ];
}