<?php

namespace App\Models\SalaryManagement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'pay_for',
        'year',
        'month',
        'leave_from_date',
        'leave_to_date',
        'employee_type',
        'exchange_rate_usd',
        'exchange_rate_yen',
        'previous_normal_ot_hr',
        'previous_sat_ot_hr',
        'previous_sunday_ot_hr',
        'previous_public_ot_hr',
        'current_normal_ot_hr',
        'current_sat_ot_hr',
        'current_sunday_ot_hr',
        'current_public_ot_hr',
        'total_ot_hr',
        'total_working_hour',
        'hourly_rate',
        'salary_usd',
        'salary_mmk',
        'kbz_opening_usd',
        'kbz_opening_mmk',
        'transfer_to_japan_usd',
        'transfer_to_japan_mmk',
        'transfer_from_japan_usd',
        'transfer_from_japan_mmk',
        'electricity_usd',
        'electricity_mmk',
        'car_usd',
        'car_mmk',
        'ssc_exchange',
        'mmk_ssc',
        'usd_ssc',
        'ssc_mmk',
        'ssc_usd',
        'ot_rate_usd',
        'previous_normal_ot_payment_usd',
        'previous_sat_ot_payment_usd',
        'previous_sunday_ot_payment_usd',
        'previous_public_ot_payment_usd',
        'previous_taxi_charge_usd',
        'current_normal_ot_payment_usd',
        'current_sat_ot_payment_usd',
        'current_sunday_ot_payment_usd',
        'current_public_ot_payment_usd',
        'current_taxi_charge_usd',
        'total_ot_payment_usd',
        'total_ot_payment_mmk',
        'unpaid_leave_day',
        'daily_rate_usd',
        'leave_amount_usd',
        'leave_amount_mmk',
        'other_adjustment_usd',
        'other_adjustment_mmk',
        'estimated_type',
        'estimated_percent',
        'estimated_tax_usd',
        'estimated_tax_mmk',
        'actual_percent',
        'actual_tax_usd',
        'actual_tax_mmk',
        'usd_allowance_usd',
        'usd_allowance_mmk',
        'mmk_allowance_usd',
        'mmk_allowance_mmk',
        'usd_deduction_usd',
        'usd_deduction_mmk',
        'mmk_deduction_usd',
        'mmk_deduction_mmk',
        'bonus_name',
        'bonus_usd',
        'bonus_mmk',
        'is_retire',
        'retire_fee',
        'net_salary_usd',
        'net_salary_mmk',
        'transfer_mmk_acc',
        'transfer_usd_acc',
        'transfer_mmk_cash',
        'transfer_usd_cash',
        'pay_slip_remark',
        'ssc_remark',
        'monthly_paye_remark',
        'pay_date',
        'status',
        'other_bonus',
        'no_ssc',
        'payment_exchange_rate',
        'created_by',
        'updated_by'
    ];
    public function other_adjustment() 
    {
        return $this->hasMany('App\Models\SalaryManagement\OtherAdjustment', 'salary_id');
    }
    public function other_allowance() 
    {
        return $this->hasMany('App\Models\SalaryManagement\OtherAllowance', 'salary_id');
    }
    public function other_deduction() 
    {
        return $this->hasMany('App\Models\SalaryManagement\OtherDeduction', 'salary_id');
    }
    public function exchange_rate_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id');
    }
    public function exchange_rate_salary_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","salary");
    }
    public function exchange_rate_kbz_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","kbz");
    }
    public function exchange_rate_usd_allowance_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","usd_allowance");
    }
    public function exchange_rate_usd_deduction_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","usd_deduction");
    }
    public function exchange_rate_ssc_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","ssc");
    }
    public function exchange_rate_ot_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","ot");
    }
    public function exchange_rate_leave_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","leave");
    }
    public function exchange_rate_tax_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","tax");
    }
    public function exchange_rate_bonus_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","bonus");
    }
    public function exchange_rate_transfer_to_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","transfer_to");
    }
    public function exchange_rate_transfer_from_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","transfer_from");
    }
    public function exchange_rate_electricity_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","electricity");
    }
    public function exchange_rate_car_detail() 
    {
        return $this->hasMany('App\Models\SalaryManagement\SalaryExchangeDetail', 'salary_id')->where("type","=","car");
    }
   
}
