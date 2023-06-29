<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NsIncomeTaxDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'ns_income_tax_id',
        'user_id',
        'year',
        'month',
        'salary_usd',
        'ot_usd',
        'ssc_usd',
        'bonus_usd',
        'total_salary_usd',
        'exchange_rate',
        'total_salary_mmk'
    ];
}
