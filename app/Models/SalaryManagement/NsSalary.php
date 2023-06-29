<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NsSalary extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'salary_type',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december',
        'january',
        'february',
        'march',
        'year',
        'date',
        'created_by',
        'updated_by'
    ];
}
