<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnglishSkill extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'test_type',
        'mark',
        'level',
        'date_of_acquition',
        'created_by',
        'updated_by'
    ];
}
