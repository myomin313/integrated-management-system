<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAllowance extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_id',
        'name',
        'amount',
        'currency'
    ];
}
