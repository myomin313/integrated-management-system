<?php

namespace App\Models\AttendanceManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawAttLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'att_id',
        'att_UserID',
        'user_id',
        'att_ip',
        'att_serial',
        'att_Date',
        'branch',
        'reason',
        'created_date',
        'updated_by',
        'ip',
        'method'
    ];
}
