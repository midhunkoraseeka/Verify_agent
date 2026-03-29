<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $table = 'rl_loan_types_tbl';
    protected $fillable = ['loan_type_name', 'status', 'trash', 'created_by', 'updated_by'];
}
