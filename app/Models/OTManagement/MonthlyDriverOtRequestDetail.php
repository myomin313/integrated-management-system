<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyDriverOtRequestDetail extends Model
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
        'start_break_hour',
        'start_break_minute',
        'start_reason',
        'start_hotel',
        'start_next_day',
        'end_from_time',
        'end_to_time',
        'end_break_hour',
        'end_break_minute',
        'end_reason',
        'end_hotel',
        'end_next_day',
        'morning_taxi_time',
        'evening_taxi_time',
        'manager_status',
        'account_status',
        'gm_status',
        'manager_status_reason',
        'account_status_reason',
        'gm_status_reason',
        'manager_change_date',
        'manager_change_by',
        'account_change_date',
        'account_change_by',
        'gm_change_date',
        'gm_change_by',
        'attendance',
        'day_type',
        'inactive'
    ];
}
