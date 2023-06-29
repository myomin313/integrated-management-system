<?php

namespace App\Models\AttendanceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $dates=['deleted_at'];
    protected $fillable = [
        'device',
        'device_ip',
        'device_serial',
        'user_id',
        'profile_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'type_id',
        'leave_form_id',
        'branch_id',
        'remark',
        'manual_remark',
        'status',
        'latitude',
        'longitude',
        'corrected_start_time',
        'corrected_end_time',
        'normal_ot_hr',
        'sat_ot_hr',
        'sunday_ot_hr',
        'public_holiday_ot_hr',
        'change_request_date',
        'change_approve_date',
        'change_approve_by',
        'ot_request_date',
        'ot_approve_date',
        'ot_approve_by',
        'hotel',
        'next_day',
        'created_by',
        'updated_by',
        'deleted_by',
        'monthly_request',
        'monthly_request_id',
        'morning_ot',
        'evening_ot'
    ];
}
