<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsLeaveData extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'year',
        'earned_leaves',
        'refresh_leaves',
    ];
}
