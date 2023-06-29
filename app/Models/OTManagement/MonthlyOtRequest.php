<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyOtRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'branch',
        'date',
        'manager_main_status',
        'account_main_status',
        'gm_main_status',
        'manager_reason',
        'account_reason',
        'gm_reason',
        'manager_change_date',
        'account_change_date',
        'gm_change_date'
    ];

    public function monthly_request_details() 
    {
        return $this->hasMany('App\Models\OTManagement\MonthlyOtRequestDetail', 'monthly_ot_request_id');
    }
}
