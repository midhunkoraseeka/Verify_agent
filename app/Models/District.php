<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = [
    'state_id', 
    'district_name', 
    'status', 
    'trash', 
    'created_by', 
    'updated_by'
];

public function state() {
    return $this->belongsTo(State::class, 'state_id');
}
}
