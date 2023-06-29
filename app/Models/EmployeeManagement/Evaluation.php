<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'year',
        'grade',
        'title',
        'position',
        'competency',
        'performance',
        'net_pay',
        'basic_salary',
        'allowance',
        'ot_rate',
        'water_festival_bonus',
        'thadingyut_bonus',
        'created_by',
        'updated_by'
    ];
}
