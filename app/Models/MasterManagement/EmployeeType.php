<?php

namespace App\Models\MasterManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeType extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'status',
        'created_by',
        'updated_by'
    ];
}
