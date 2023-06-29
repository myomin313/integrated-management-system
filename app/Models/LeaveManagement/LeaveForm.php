<?php

namespace App\Models\LeaveManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveForm extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'day_type',
        'remaining_days',
        'total_days',
        'time',
        'reason',
        'approve_by_dep_manager',
        'approve_by_GM',
        'approve_reason_by_dep_manager',
        'approve_reason_by_GM',
        'request_leave_cancel',
        'cancel_leave_approve_by_dep_manager',
        'cancel_leave_approve_by_admi_manager',
        'cancel_leave_approve_reason_by_dep_manager',
        'cancel_leave_approve_reason_by_admi_manager',
        'leave_cancel_reason',
        'certificate',
        'created_by',
        'updated_by'
    ];
    
}
