<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'education_type',
        'school_name',
        'date_of_graduation',
        'major',
        'created_by',
        'updated_by'
    ];
}
