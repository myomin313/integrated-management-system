<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'relationship',
        'name',
        'family_dob',
        'work',
        'allowance',
        'allowance_fee',
        'created_by',
        'updated_by'
    ];
}
