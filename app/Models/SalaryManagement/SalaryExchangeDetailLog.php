<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryExchangeDetailLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_log_id',
        'salary_id',
        'type',
        'from_to',
        'exchange_rate',
        'usd_amount',
        'mmk_amount'
    ];
}
