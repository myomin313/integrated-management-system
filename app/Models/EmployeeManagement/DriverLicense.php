<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLicense extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'license_number',
        'start_date',
        'due_date',
        'created_by',
        'updated_by'
    ];
}
