<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyOtRequest extends Model
{
    use HasFactory;
    protected $fillable = [
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
        'status',
        'status_reason',
        'monthly_status_reason',
        'status_change_date',
        'status_change_by',
        'monthly_request',
        'monthly_request_id',
        'inactive'
    ];
}
