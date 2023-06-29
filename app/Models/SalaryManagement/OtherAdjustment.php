<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAdjustment extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_id',
        'date',
        'type',
        'name',
        'exchange_rate',
        'usd_amount',
        'mmk_amount'
    ];
}
