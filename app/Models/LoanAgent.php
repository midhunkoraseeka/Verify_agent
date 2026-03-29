<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanAgent extends Model
{
    use HasFactory;

    protected $table = 'rl_loan_agents_tbl';

    protected $fillable = [
        'full_name', 'mobile', 'bank_name', 'bank_type',
        'constituency', 'district', 'state', 'office_address',
        'loan_types', 'aadhar', 'profile_photo',
        'facebook', 'instagram', 'linkedin',
        'status', 'trash', 'created_by'
    ];
}