<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Constituency extends Model
{
    protected $fillable = ['constituency_name', 'status', 'trash', 'created_by', 'updated_by'];
}
