<?php

namespace App\Models\AttendanceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'actual_date',
        'changing_date',
        'changing_start_time',
        'changing_end_time',
        'working_start_time',
        'working_end_time',
        'requested_date',
        'status_change_date',
        'status',
        'status_reason',
        'status_change_by',
        'reason_of_correction',
        'created_by',
        'updated_by'
    ];
}
