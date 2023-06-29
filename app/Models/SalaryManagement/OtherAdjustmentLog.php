<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAdjustmentLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_log_id',
        'salary_id',
        'name',
        'exchange_rate',
        'usd_amount',
        'mmk_amount'
    ];
}
