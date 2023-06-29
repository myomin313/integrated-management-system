<?php

namespace App\Models\AttendanceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceTemp extends Model
{
    use HasFactory;
    protected $fillable = [
        'change_request_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        "time_in",
        "time_out"
    ];
}
