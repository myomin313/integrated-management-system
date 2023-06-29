<?php

namespace App\Models\LeaveManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $fillable = [
        'leave_type_name',
        'leave_day',
        'type',
        'status',
        'created_by',
        'updated_by'
    ];
 
}
