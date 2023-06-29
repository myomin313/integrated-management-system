<?php

namespace App\Models\TaxManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NsIncomeTax extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'salary_id',
        'user_id',
        'salary_usd',
        'ot_usd',
        'bonus_usd',
        'leave_usd',
        'adjustment_usd',
        'total_income_usd',
        'estimated_type',
        'estimated_percent',
        'estimated_usd',
        'estimated_income_tax',
        'estimated_income_tax_round',
        'exchange_rate',
        'remark',
        'basic_allowance_percent',
        'basic_max_allowance',
        'parent_allowance',
        'spouse_allowance',
        'children_allowance',
        'life_assured',
        'one_year_tax',
        'one_month_tax',
        'deducted_tax_rate',
        'actual_tax_mmk',
        'actual_exchange_rate',
        'actual_tax_usd',
        'actual_rate',
        'updated_by'
    ];

    public function ns_detail() 
    {
        return $this->hasMany('App\Models\TaxManagement\NsIncomeTaxDetail', 'ns_income_tax_id');
    }
}
