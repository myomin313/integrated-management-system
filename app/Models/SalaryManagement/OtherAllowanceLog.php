<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAllowanceLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_log_id',
        'salary_id',
        'name',
        'amount',
        'currency'
    ];
}
