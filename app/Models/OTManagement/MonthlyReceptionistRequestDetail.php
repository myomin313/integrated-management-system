<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReceptionistRequestDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'monthly_ot_request_id',
        'attendance_id',
        'user_id',
        'branch',
        'ot_type',
        'apply_date',
        'start_from_time',
        'start_to_time',
        'end_from_time',
        'end_to_time',
        'end_break_hour',
        'end_break_minute',
        'reason',
        'manager_status',
        'gm_status',
        'manager_status_reason',
        'gm_status_reason',
        'manager_change_date',
        'manager_change_by',
        'gm_change_date',
        'gm_change_by',
        'attendance',
        'inactive'
    ];
}
