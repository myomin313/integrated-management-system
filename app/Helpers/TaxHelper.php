<?php

namespace App\Helpers;

use Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\TaxManagement\Ssc;
use App\Models\TaxManagement\NsIncomeTax;
use App\Models\TaxManagement\NsActualTax;
use App\Models\TaxManagement\NsIncomeTaxDetail;
use App\Models\TaxManagement\RsIncomeTax;
use App\Models\TaxManagement\RsActualTax;
use App\Models\TaxManagement\RsIncomeTaxJpyDetail;
use App\Models\TaxManagement\RsIncomeTaxMmDetail;
use App\Models\TaxManagement\TaxRange;

use App\Models\SalaryManagement\Salary;
use App\Models\SalaryManagement\NsSalary;
use App\Models\SalaryManagement\RsSalary;
use App\Models\SalaryManagement\ExchangeRate;

use App\Models\EmployeeManagement\Family;

class TaxHelper
{
    
	public static function storeSSC($data){
       
        $ssc = Ssc::where("salary_id","=",$data->id)->first();
        if(!$ssc)
            $ssc = new Ssc();

        $last_day = Carbon::parse($data->year."-".$data->month)->endOfMonth()->format("Y-m-d");
        $ssc->date = $last_day;
        $ssc->salary_id = $data->id;
        $ssc->user_id = $data->user_id;
        $ssc->salary_usd = $data->salary_usd + $data->ot_usd;

        //$mmk_amount = ($data->salary_usd + $data->ot_usd) * $data->ssc_exchange;
        $mmk_amount = ($data->salary_mmk + $data->total_ot_payment_mmk);
        if($mmk_amount>=300000)
       	    $ssc->salary_mmk = 300000;
        else
       	    $ssc->salary_mmk = $mmk_amount;
        $ssc->employer_first_percent = 2;
        $ssc->employee_percent = 2;
        $ssc->employer_second_percent = 2;
        $ssc->remark = $data->ssc_remark;
        $ssc->save();

	}

    public static function storeNsIncomeTax($data){
        $ns_tax = NsIncomeTax::where("salary_id","=",$data->id)->first();
        if(!$ns_tax)
            $ns_tax = new NsIncomeTax();

        $last_day = Carbon::parse($data->year."-".$data->month)->endOfMonth()->format("Y-m-d");
        $ns_tax->date = $last_day;
        $ns_tax->salary_id = $data->id;
        $ns_tax->user_id = $data->user_id;
        $ns_tax->salary_usd = $data->salary_usd;
        $ns_tax->ot_usd = ($data->total_ot_payment_usd - $data->current_taxi_charge_usd - $data->previous_taxi_charge_usd);
        $ns_tax->bonus_usd = $data->bonus_usd;
        $ns_tax->leave_usd = $data->leave_amount_usd;
        $ns_tax->adjustment_usd = $data->other_adjustment_usd;

        $ot_usd = ($data->total_ot_payment_usd - $data->current_taxi_charge_usd - $data->previous_taxi_charge_usd);

        $total_income_usd = $data->salary_usd + ($data->total_ot_payment_usd - $data->current_taxi_charge_usd - $data->previous_taxi_charge_usd) + $data->bonus_usd - $data->leave_amount_usd + $data->other_adjustment_usd;

        $ns_tax->total_income_usd = $total_income_usd;
        $ns_tax->estimated_type = $data->estimated_type;
        $ns_tax->estimated_percent = $data->estimated_percent;

        $ns_tax->estimated_usd = $data->estimated_type=="usd"?$data->estimated_tax_usd:0;

        if($data->estimated_type=="percent"){
            $income_tax = $total_income_usd * $data->estimated_percent / 100;
        }
        else{
            $income_tax = $data->estimated_tax_usd;
        }

        $ns_tax->estimated_income_tax = round_up($income_tax,2);
        $ns_tax->estimated_income_tax_round = $data->estimated_tax_usd;
        $ns_tax->exchange_rate = $data->tax_exchange_rate;

        $ns_tax->basic_allowance_percent = 20;
        $ns_tax->save();

        $pay_for = explode(", ", $data->pay_for);
        $month = $pay_for[0];
        $year = $pay_for[1];
        $month1 = strtolower($month);

        if($month1=="january" || $month1=="february" || $month1=="march")
            $year = $year-1;

        $month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];

        $all_total_usd = 0; $all_total_mmk = 0;
        foreach($month_arr as $month_number=>$value){

            $ns_detail = NsIncomeTaxDetail::where([["ns_income_tax_id","=",$ns_tax->id],["year","=",$year],["month","=",$value]])->first();
            if(!$ns_detail)
                $ns_detail = new NsIncomeTaxDetail();

            $salary_year = $year;
            if($month_number=="01" or $month_number=="02" or $month_number=="03"){
                $salary_year = $year+1;
            }
            $salary = Salary::where([["user_id","=",$data->user_id],["year","=",$salary_year],["month","=",$month_number]])->first();

            if($salary){
                $salary_usd = $salary->salary_usd;

                $ot_usd = ($salary->total_ot_payment_usd - $salary->current_taxi_charge_usd - $salary->previous_taxi_charge_usd);

                //calculate ssc
                $salary_mmk = $salary->salary_mmk + $salary->total_ot_payment_mmk;
                if($salary_mmk>=300000)
                    $salary_mmk = 300000;
                $ssc_amount = $salary_mmk * 2 /100;
                $ssc_usd_amount = round($ssc_amount / $salary->ssc_exchange);

                $ssc_usd = $ssc_usd_amount;
                $bonus_usd = $salary->bonus_usd;

                $total_usd = $salary_usd + $ot_usd - $ssc_usd + $bonus_usd;
                $exchange_rate = $salary->exchange_rate_usd;
                $total_mmk = $total_usd * $exchange_rate;

            }
            else{
                $ns_salary = NsSalary::where([["user_id","=",$data->user_id],["year","=",$year]])->first();
                if($ns_salary){
                    $select_col = strtolower($value);
                    $salary_usd = $ns_salary->$select_col;
                }
                else{
                    $salary_usd = 0;
                }
                
                $ot_usd = 0;
                $ssc_usd = 0;
                $bonus_usd = 0;
                $total_usd = $salary_usd;
                $exchange_rate = $data->exchange_rate_usd;
                $total_mmk = $total_usd * $exchange_rate;
            }

            $ns_detail->ns_income_tax_id = $ns_tax->id;
            $ns_detail->user_id = $ns_tax->user_id;
            $ns_detail->year = $year;
            $ns_detail->month = $value;
            $ns_detail->salary_usd = $salary_usd;
            $ns_detail->ot_usd = $ot_usd;
            $ns_detail->ssc_usd = $ssc_usd;
            $ns_detail->bonus_usd = $bonus_usd;
            $ns_detail->total_salary_usd = $total_usd;
            $ns_detail->exchange_rate = $exchange_rate;
            $ns_detail->total_salary_mmk = $total_mmk;
            $ns_detail->save();

            $all_total_usd += $total_usd;
            $all_total_mmk += $total_mmk;

        }

        foreach($month_arr as $month_number=>$value){

            $salary_year = $year;
            if($month_number=="01" or $month_number=="02" or $month_number=="03"){
                $salary_year = $year+1;
            }
            $salary = Salary::where([["user_id","=",$data->user_id],["year","=",$salary_year],["month","=",$month_number]])->first();

            if($salary){
                
                $adjustment_detail = $salary->other_adjustment;
                if(count($adjustment_detail)){
                    foreach($adjustment_detail as $key=>$detail){
                        $adjustment_month = Carbon::parse($detail->date)->format("F");
                        $adjustment_year = Carbon::parse($detail->date)->format("Y");
                        $adjustment_month_lower = strtolower($adjustment_month);

                        if($adjustment_month_lower=="january" || $adjustment_month_lower=="february" || $adjustment_month_lower=="march")
                            $adjustment_year = $adjustment_year-1;
                        $pre_income_tax = NsIncomeTaxDetail::where([["user_id","=",$data->user_id],["year","=",$adjustment_year],["month","=",$adjustment_month]])->orderBy("id","desc")->first();
                        if($pre_income_tax){
                            if($detail->type=="Salary"){
                                $pre_income_tax->salary_usd = $pre_income_tax->salary_usd + $detail->usd_amount;
                                $pre_income_tax->total_salary_usd = $pre_income_tax->total_salary_usd + $detail->usd_amount;
                                $pre_income_tax->total_salary_mmk = ($pre_income_tax->total_salary_usd * $pre_income_tax->exchange_rate);
                                $pre_income_tax->save();
                                
                            }
                            else{
                                $pre_income_tax->ot_usd = $pre_income_tax->ot_usd + $detail->usd_amount;
                                $pre_income_tax->total_salary_usd = $pre_income_tax->total_salary_usd + $detail->usd_amount;
                                $pre_income_tax->total_salary_mmk = ($pre_income_tax->total_salary_usd * $pre_income_tax->exchange_rate);
                                $pre_income_tax->save();


                            }

                            $all_total_usd += $detail->usd_amount;
                            $all_total_mmk += ($detail->usd_amount * $pre_income_tax->exchange_rate);
                        }
                    }
                }
            }
            

        }

        $basic_max_allowance = $all_total_mmk * 20 / 100;

        if($basic_max_allowance>10000000)
            $basic_max_allowance = 10000000;

        $parent_allowance = 0;
        $parent = Family::where("user_id","=",$data->user_id)->where("allowance","like","parent%")->first();
        if($parent)
            $parent_allowance = 1000000;

        $spouse_allowance = 0;
        $spouse = Family::where("user_id","=",$data->user_id)->where("allowance","like","spouse%")->first();
        if($spouse)
            $spouse_allowance = 1000000;

        $children_allowance = 0;
        $children = Family::where("user_id","=",$data->user_id)->where("allowance","like","children%")->get();
        if(count($children))
            $children_allowance = 500000 * count($children);

        $life_assured_allowance = getLifeAssuredAllowance($data->user_id,$year);

        $net_salary_mmk = $all_total_mmk - $basic_max_allowance - $parent_allowance - $spouse_allowance - $children_allowance - $life_assured_allowance;

        $tax_ranges = TaxRange::orderBy("id","asc")->get();

        $one_year_tax = 0;
        foreach($tax_ranges as $key=>$value){
            if($net_salary_mmk >= $value->to_kyat){
                $tax = ( ($value->to_kyat - $value->from_kyat + 1) * $value->percent ) / 100;
                $one_year_tax += $tax;
            }
            else if($net_salary_mmk >= $value->from_kyat){
                $tax = ( ($net_salary_mmk - $value->from_kyat + 1) * $value->percent ) / 100;
                $one_year_tax += $tax;

            }
            else{
                $tax = 0;
            }
        }

        $one_month_tax = $one_year_tax / 12;
        $deducted_tax_rate = round(($one_year_tax/$net_salary_mmk)*100);

        $ns_tax->basic_max_allowance = $basic_max_allowance;
        $ns_tax->parent_allowance = $parent_allowance;
        $ns_tax->spouse_allowance = $spouse_allowance;
        $ns_tax->children_allowance = $children_allowance;
        $ns_tax->life_assured = $life_assured_allowance;
        $ns_tax->one_year_tax = $one_year_tax;
        $ns_tax->one_month_tax = $one_month_tax;
        $ns_tax->deducted_tax_rate = $deducted_tax_rate;
        $ns_tax->save();

        $year_month = Carbon::parse($data->year."-".$data->month)->endOfMonth()->format("Y-m");
        $ns_actual_tax = NsActualTax::where([["user_id","=",$data->user_id],["tax_for","=",$year_month]])->first();
        if($ns_actual_tax){
            $ns_tax->actual_tax_mmk = $ns_actual_tax->tax_amount_mmk;
            $ns_tax->actual_exchange_rate = $ns_actual_tax->exchange_rate;
            $ns_tax->actual_tax_usd = $ns_actual_tax->tax_amount_usd;
            $ns_tax->save();
        }



    }

    public static function storeRsIncomeTax($data){
        $rs_tax = RsIncomeTax::where("salary_id","=",$data->id)->first();
        if(!$rs_tax)
            $rs_tax = new RsIncomeTax();

        $last_day = Carbon::parse($data->year."-".$data->month)->endOfMonth()->format("Y-m-d");
        $rs_tax->date = $last_day;
        $rs_tax->salary_id = $data->id;
        $rs_tax->user_id = $data->user_id;
        $rs_tax->salary = $data->salary_usd;
        
        $rs_tax->exchange_rate_usd = $data->exchange_rate_usd;
        $rs_tax->exchange_rate_yen = $data->exchange_rate_yen;
        $rs_tax->ssc = 6000*12;

        $rs_tax->percent_allowance = 20;
        $rs_tax->max_allowance = 10000000;
        $rs_tax->parent_allowance = 0;
        $rs_tax->spouse_allowance = 0;
        $rs_tax->children_allowance = 0;
        $rs_tax->tax_calculation_percent = 0.75;
        $rs_tax->save();

        $pay_for = explode(", ", $data->pay_for);
        $month = $pay_for[0];
        $year = $pay_for[1];
        $month1 = strtolower($month);

        if($month1=="january" || $month1=="february" || $month1=="march")
            $year = $year-1;

        $month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];

        $all_total_usd = 0;$all_total_yen = 0; $all_total_usd_mmk = 0;$all_total_yen_mmk = 0;

        //jpn salary
        foreach($month_arr as $month_number=>$value){

            $rs_detail = RsIncomeTaxJpyDetail::where([["rs_income_tax_id","=",$rs_tax->id],["year","=",$year],["month","=",$value]])->first();
            if(!$rs_detail)
                $rs_detail = new RsIncomeTaxJpyDetail();

            
            $salary_yen = getRsSalaryFor($data->user_id,"jpn_salary",strtolower($value),$year);
            $transfer_from_mm_yen = getRsSalaryFor($data->user_id,"jpn_transfer_from",strtolower($value),$year);
            $adjustment_yen = getRsSalaryFor($data->user_id,"jpn_adjustment",strtolower($value),$year);
            $income_tax_jpy_yen = getRsSalaryFor($data->user_id,"jpn_income_tax",strtolower($value),$year);
            $bonus_yen = getRsSalaryFor($data->user_id,"jpn_bonus",strtolower($value),$year);
            $oversea_yen = getRsSalaryFor($data->user_id,"jpn_oversea",strtolower($value),$year);
            $dc_yen = getRsSalaryFor($data->user_id,"jpn_dc",strtolower($value),$year);

            $total_yen = $salary_yen + $transfer_from_mm_yen + $adjustment_yen - $income_tax_jpy_yen + $bonus_yen + $oversea_yen + $dc_yen;
            $exchange_rate = $data->exchange_rate_yen;
            $total_yen_mmk = ($total_yen * $exchange_rate) / 100;
         

            $rs_detail->rs_income_tax_id = $rs_tax->id;
            $rs_detail->user_id = $rs_tax->user_id;
            $rs_detail->year = $year;
            $rs_detail->month = $value;
            $rs_detail->salary_yen = $salary_yen;
            $rs_detail->transfer_from_mm_yen = $transfer_from_mm_yen;
            $rs_detail->adjustment_yen = $adjustment_yen;
            $rs_detail->income_tax_jpy_yen = $income_tax_jpy_yen;
            $rs_detail->bonus_yen = $bonus_yen;
            $rs_detail->oversea_yen = $oversea_yen;
            $rs_detail->dc_yen = $dc_yen;
            $rs_detail->total_salary_yen = $total_yen;
            $rs_detail->exchange_rate = $exchange_rate;
            $rs_detail->total_salary_mmk = $total_yen_mmk;
            $rs_detail->save();

            $all_total_yen += $total_yen;
            $all_total_yen_mmk += $total_yen_mmk;

        }

        //mm salary
        foreach($month_arr as $month_number=>$value){

            $rs_detail = RsIncomeTaxMmDetail::where([["rs_income_tax_id","=",$rs_tax->id],["year","=",$year],["month","=",$value]])->first();
            if(!$rs_detail)
                $rs_detail = new RsIncomeTaxMmDetail();

            $salary_year = $year;
            if($month_number=="01" or $month_number=="02" or $month_number=="03"){
                $salary_year = $year+1;
            }
            $salary = Salary::where([["user_id","=",$data->user_id],["year","=",$salary_year],["month","=",$month_number]])->first();

            if($salary){
                $salary_usd = $salary->salary_usd;
                $transfer_to_jp_usd = $salary->transfer_to_japan_usd;
                $bonus_usd = $salary->bonus_usd;
                $oversea_usd = getRsSalaryFor($data->user_id,"mm_oversea",strtolower($value),$year);
                $dc_usd = getRsSalaryFor($data->user_id,"mm_dc",strtolower($value),$year);

                $total_usd = $salary_usd - $transfer_to_jp_usd + $bonus_usd + $oversea_usd + $dc_usd;
                $exchange_rate = $salary->exchange_rate_usd;
                $total_usd_mmk = $total_usd * $exchange_rate;
            }
            else{
                $salary_usd = getRsSalaryFor($data->user_id,"mm_salary",strtolower($value),$year);
                $transfer_to_jp_usd = 0;
                $bonus_usd = 0;
                $oversea_usd = getRsSalaryFor($data->user_id,"mm_oversea",strtolower($value),$year);
                $dc_usd = getRsSalaryFor($data->user_id,"mm_dc",strtolower($value),$year);

                $total_usd = $salary_usd - $transfer_to_jp_usd + $bonus_usd + $oversea_usd + $dc_usd;
                $exchange_rate = $data->exchange_rate_usd;
                $total_usd_mmk = $total_usd * $exchange_rate;
            }

            $rs_detail->rs_income_tax_id = $rs_tax->id;
            $rs_detail->user_id = $rs_tax->user_id;
            $rs_detail->year = $year;
            $rs_detail->month = $value;
            $rs_detail->salary_usd = $salary_usd;
            $rs_detail->transfer_to_jp_usd = $transfer_to_jp_usd;
            $rs_detail->bonus_usd = $bonus_usd;
            $rs_detail->oversea_usd = $oversea_usd;
            $rs_detail->dc_usd = $dc_usd;
            $rs_detail->total_salary_usd = $total_usd;
            $rs_detail->exchange_rate = $exchange_rate;
            $rs_detail->total_salary_mmk = $total_usd_mmk;
            $rs_detail->save();

            $all_total_usd += $total_usd;
            $all_total_usd_mmk += $total_usd_mmk;

        }

        $total_salary_income = $all_total_usd_mmk + $all_total_yen_mmk;
        $total_salary_income += (6000*12);

        $basic_max_allowance = $total_salary_income * 20 / 100;
        //if($basic_max_allowance>=10000000)
            $basic_max_allowance = 10000000;

        $parent_allowance = 0;

        $spouse_allowance = 0;

        $children_allowance = 0;

        $net_salary_mmk = $total_salary_income - $basic_max_allowance - $parent_allowance - $spouse_allowance - $children_allowance;

        $tax_ranges = TaxRange::orderBy("id","asc")->get();

        $one_year_tax = 0;
        foreach($tax_ranges as $key=>$value){
            if($net_salary_mmk >= $value->to_kyat){
                $tax = ( ($value->to_kyat - $value->from_kyat + 1) * $value->percent ) / 100;
                $one_year_tax += $tax;
            }
            else if($net_salary_mmk >= $value->from_kyat){
                $tax = ( ($net_salary_mmk - $value->from_kyat + 1) * $value->percent ) / 100;
                $one_year_tax += $tax;

            }
            else{
                $tax = 0;
            }
        }

        $year_tax = $one_year_tax / 0.75;

        $one_month_tax = $year_tax / 12;

        $rs_tax->income_tax_usd = round($one_month_tax/$rs_tax->exchange_rate_usd);
        $rs_tax->income_tax_mmk = $one_month_tax;
        $rs_tax->one_year_tax = $year_tax;
        $rs_tax->save();

        $year_month = Carbon::parse($data->year."-".$data->month)->endOfMonth()->format("Y-m");
        $rs_actual_tax = RsActualTax::where([["user_id","=",$data->user_id],["tax_for","=",$year_month]])->first();
        if($rs_actual_tax){
            $rs_tax->actual_tax_mmk = $rs_actual_tax->tax_amount_mmk;
            $rs_tax->actual_exchange_rate = $rs_actual_tax->exchange_rate;
            $rs_tax->actual_tax_usd = $rs_actual_tax->tax_amount_usd;
            $rs_tax->save();
        }


    }

    public static function getNsTotalSalary($id){

        $ns_tax = NsIncomeTax::findOrFail($id);

        $total_salary = 0;
        foreach($ns_tax->ns_detail as $key=>$value){
            //var_dump($value->total_salary_mmk);
            $total_salary += $value->total_salary_mmk;
        }

        return $total_salary;
    }

    public static function getTotalSalaryForNs($user_id,$year){
        $salary = NsSalary::where("user_id","=",$user_id)->where("year","=",$year)->first();

        if($salary){
            $total_salary = $salary->april + $salary->may + $salary->june + $salary->july + $salary->august + $salary->september + $salary->october + $salary->november + $salary->december + $salary->january + $salary->february + $salary->march;
        }
        else
            $total_salary = 0;

        return $total_salary;
    }

    public static function getExchangeRateSummary($month,$year){

        $query_date = $year."-".$month;
        $rates = ExchangeRate::where("exchange_rates.date","like","$query_date%")->orderBy("date","asc")->get();
        $no_of_day = 0;
        $total_exchange_usd = 0;
        $total_exchange_yen = 0;

        foreach($rates as $key=>$value){
            $ex_date = Carbon::parse($value->date)->format("d-m");
            if(isHoliday($value->date))
                continue;
            else if($ex_date=="01-04" or $ex_date=="01-08")
                continue;
            else{
                $no_of_day += 1;
                $total_exchange_usd += $value->dollar;
                $total_exchange_yen += $value->yen;
            }
        }

        if($no_of_day>0){
            $average_usd = round($total_exchange_usd / $no_of_day);
            $average_yen = round($total_exchange_yen / $no_of_day);
        }
        else{
            $average_usd = 0;
            $average_yen = 0;
        }
        return [$average_usd,$average_yen];

    }

    public static function getExtimatedTax($user_id,$month,$year){
        $tax_date = $year."-".$month;
        $estimated_tax = NsIncomeTax::select("estimated_income_tax_round")->where("user_id","=",$user_id)->where("date","like","$tax_date%")->first();
        if($estimated_tax)
            return $estimated_tax->estimated_income_tax_round;

        return 0;
    }
    public static function getActualTax($user_id,$month,$year){
        $tax_date = $year."-".$month;
        $actual_tax = NsActualTax::select("tax_amount_mmk","tax_amount_usd")->where("user_id","=",$user_id)->where("tax_period","like","$tax_date%")->first();
        if($actual_tax)
            return [$actual_tax->tax_amount_usd,$actual_tax->tax_amount_mmk];

        return [0,0];
    }
   
}