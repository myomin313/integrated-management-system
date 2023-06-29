<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchAssignHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start_datetime',
        'end_datetime',
        'branch_id',
        'status',
        'created_by',
        'updated_by'
    ];
}
