<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsIncomeTaxJpyDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rs_income_tax_id',
        'user_id',
        'year',
        'month',
        'salary_yen',
        'transfer_from_mm_yen',
        'adjustment_yen',
        'income_tax_jpy_yen',
        'bonus_yen',
        'oversea_yen',
        'dc_yen',
        'total_salary_yen',
        'exchange_rate',
        'total_salary_mmk'
    ];
}
