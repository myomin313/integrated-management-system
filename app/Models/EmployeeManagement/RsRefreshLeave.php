<?php

namespace App\Models\EmployeeManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsRefreshLeave extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'year',
        'start_date',
        'end_date',
        'earned_leaves',
        'refresh_leaves',
        'other',
        'airfare',
    ];
}
