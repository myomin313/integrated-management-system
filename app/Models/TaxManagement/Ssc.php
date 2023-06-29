<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ssc extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'salary_id',
        'user_id',
        'salary_usd',
        'salary_mmk',
        'employer_first_percent',
        'employee_percent',
        'employer_second_percent',
        'remark'
    ];
}
