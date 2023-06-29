<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RsIncomeTax extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'salary_id',
        'user_id',
        'salary',
        'income_tax_usd',
        'exchange_rate',
        'income_tax_mmk',
        'ssc',
        'percent_allowance',
        'max_allowance',
        'parent_allowance',
        'spouse_allowance',
        'children_allowance',
        'tax_calculation_percent',
        'one_year_tax',
        'remark',
        'actual_tax_mmk',
        'actual_exchange_rate',
        'actual_tax_usd',
        'actual_rate',
        'updated_by'
    ];

    public function rs_jpy_detail() 
    {
        return $this->hasMany('App\Models\TaxManagement\RsIncomeTaxJpyDetail', 'rs_income_tax_id');
    }
    public function rs_mm_detail() 
    {
        return $this->hasMany('App\Models\TaxManagement\RsIncomeTaxMmDetail', 'rs_income_tax_id');
    }
}
