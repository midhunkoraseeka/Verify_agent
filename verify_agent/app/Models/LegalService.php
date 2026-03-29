<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LegalService extends Model
{
    use HasFactory;

    protected $table = 'rl_legal_services_tbl';

    protected $fillable = [
        'service_name',
        'status',
        'trash',
        'created_by',
        'updated_by',
    ];
}