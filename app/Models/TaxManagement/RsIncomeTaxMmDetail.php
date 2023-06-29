<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsIncomeTaxMmDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rs_income_tax_id',
        'user_id',
        'year',
        'month',
        'salary_usd',
        'transfer_to_jp_usd',
        'bonus_usd',
        'oversea_usd',
        'dc_usd',
        'total_salary_usd',
        'exchange_rate',
        'total_salary_mmk'
    ];
}
