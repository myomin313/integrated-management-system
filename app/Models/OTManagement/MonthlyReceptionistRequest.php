<?php

namespace App\Models\OTManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReceptionistRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'branch',
        'date',
        'manager_main_status',
        'gm_main_status',
        'manager_reason',
        'gm_reason',
        'manager_change_date',
        'gm_change_date',
        'hourly_rate'
    ];

    public function monthly_request_details() 
    {
        return $this->hasMany('App\Models\OTManagement\MonthlyReceptionistRequestDetail', 'monthly_ot_request_id');
    }
}
