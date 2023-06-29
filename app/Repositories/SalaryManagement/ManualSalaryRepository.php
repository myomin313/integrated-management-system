<?php
namespace App\Repositories\SalaryManagement;

use App\Models\SalaryManagement\RsSalary;
use App\Models\SalaryManagement\NsSalary;
use App\Models\SalaryManagement\ExchangeRate;
use App\Models\SalaryManagement\OtherAdjustment;
use App\Models\SalaryManagement\OtherAllowance;
use App\Models\SalaryManagement\OtherDeduction;
use App\Models\SalaryManagement\Salary;
use App\Models\SalaryManagement\SalaryExchangeDetail;
use App\Models\SalaryManagement\PaymentExchangeRate;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Stevebauman\Location\Facades\Location;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Helpers\TaxHelper;
//use DateTime;

class ManualSalaryRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(Salary $salary,User $user)
	{
		$this->model=$salary;
		$this->user_model=$user;
	}
	
	public function storeReceptionist($input){
		$salaries= DB::transaction(function () use ($input) {

			//save salary
			$salary = new Salary();	
			if($input["edit_salary"]!="0"){

				$salary = Salary::findOrFail($input["edit_salary"]);
				$salary->updated_by = Auth::user()->id;
				OtherAdjustment::where("salary_id",$salary->id)->delete();
				OtherAllowance::where("salary_id",$salary->id)->delete();
				OtherDeduction::where("salary_id",$salary->id)->delete();
				SalaryExchangeDetail::where("salary_id",$salary->id)->delete();
				
			}
			$salary->user_id = $input['user_id'];
			$salary->pay_for = $input['pay_for'];
			$salary->year = $input['year'];
			$salary->month = Carbon::parse($input['month'].", ".$input['year'])->format('m');
			$salary->leave_from_date = format_dbdate($input["leave_from_date"]);
			$salary->leave_to_date = format_dbdate($input["leave_to_date"]);
			$salary->employee_type = $input['employee_type'];
			$salary->exchange_rate_usd = $input['exchange_rate_usd'];
			$salary->payment_exchange_rate = $input['payment_exchange_rate'];
			$salary->exchange_rate_yen = $input['exchange_rate_yen'];
			$salary->total_working_hour = $input['working_hour'];
			$salary->hourly_rate = $input['hourly_rate_usd'];
			$salary->salary_usd = $input['salary_usd'];
			$salary->salary_mmk = $input['salary_mmk'];

			$salary->kbz_opening_usd = $input['kbz_opening_usd']?$input['kbz_opening_usd']:0;
			$salary->kbz_opening_mmk = $input['kbz_opening_mmk']?$input['kbz_opening_mmk']:0;

			$salary->usd_allowance_usd = $input['usd_allowance_usd']?$input['usd_allowance_usd']:0;
			$salary->usd_allowance_mmk = $input['usd_allowance_mmk']?$input['usd_allowance_mmk']:0;
			$salary->mmk_allowance_usd = 0;
			$salary->mmk_allowance_mmk = $input['total_allowance_mmk']?$input['total_allowance_mmk']:0;

			$salary->usd_deduction_usd = $input['usd_deduction_usd']?$input['usd_deduction_usd']:0;
			$salary->usd_deduction_mmk = $input['usd_deduction_mmk']?$input['usd_deduction_mmk']:0;
			$salary->mmk_deduction_usd = 0;
			$salary->mmk_deduction_mmk = $input['total_deduction_mmk']?$input['total_deduction_mmk']:0;

			$salary->is_retire = isset($input['is_retire'])?1:0;
			$salary->retire_fee = isset($input['retire_fee'])?$input['retire_fee']:0;

			$salary->net_salary_usd = $input["net_salary_usd"];
			$salary->net_salary_mmk = $input["net_salary_mmk"];

			$salary->transfer_mmk_acc = $input["transfer_mmk_acc"]?$input["transfer_mmk_acc"]:0;	
			$salary->transfer_usd_acc = $input["transfer_usd_acc"]?$input["transfer_usd_acc"]:0;	
			$salary->transfer_mmk_cash = $input["transfer_mmk_cash"]?$input["transfer_mmk_cash"]:0;	
			$salary->transfer_usd_cash = $input["transfer_usd_cash"]?$input["transfer_usd_cash"]:0;

			$date = Carbon::parse($salary->pay_for)->format('Y-m-01');
        	$payment_exchange_rate = PaymentExchangeRate::where("date","=",$date)->first();
        	if($payment_exchange_rate)
				$salary->pay_date = $payment_exchange_rate->payment_date?$payment_exchange_rate->payment_date:Carbon::parse($salary->pay_for)->format("Y-m-d");		
			else
				$salary->pay_date = Carbon::parse($salary->pay_for)->format("Y-m-d");		

			$salary->created_by = Auth::user()->id;
			$salary->save();

			//other allowance record
			$allowance_name = isset($input["allowance_name"])?$input["allowance_name"]:[];
			$allowance_amount = isset($input["allowance_amount"])?$input["allowance_amount"]:[];
			$allowance_currency = isset($input["allowance_currency"])?$input["allowance_currency"]:[];
			foreach($allowance_name as $key=>$value){
				if($value or $allowance_amount[$key]){
					$allowance = new OtherAllowance();
					$allowance->salary_id = $salary->id;
					$allowance->name = $value;
					$allowance->amount = $allowance_amount[$key];
					$allowance->currency = $allowance_currency[$key];
					$allowance->save();
				}
			}

			//other deduction record
			$deduction_name = isset($input["deduction_name"])?$input["deduction_name"]:[];
			$deduction_amount = isset($input["deduction_amount"])?$input["deduction_amount"]:[];
			$deduction_currency = isset($input["deduction_currency"])?$input["deduction_currency"]:[];
			foreach($deduction_name as $key=>$value){
				if($value or $deduction_amount[$key]){
					$deduction = new OtherDeduction();
					$deduction->salary_id = $salary->id;
					$deduction->name = $value;
					$deduction->amount = $deduction_amount[$key];
					$deduction->currency = $deduction_currency[$key];
					$deduction->save();
				}
			}

			//salary exchange detail
			$salary_exchanges = $input["salary_exchange_rate"];
			$salary_usd_amount = $input["salary_usd_amount"];
			$salary_mmk_amount = $input["salary_mmk_amount"];
			foreach($salary_exchanges as $key=>$value){
				$detail = new SalaryExchangeDetail();
				$detail->salary_id = $salary->id;
				$detail->type = "salary";
				$detail->from_to = "usd_mmk";
				$detail->exchange_rate = $value?$value:0;
				$detail->usd_amount = $salary_usd_amount[$key]?$salary_usd_amount[$key]:0;
				$detail->mmk_amount = $salary_mmk_amount[$key]?$salary_mmk_amount[$key]:0;
				$detail->save();

			}

			//kbz opening exchange detail
			if($input['kbz_opening_usd'] and $input['kbz_opening_usd']>0){
				$kbz_exchanges = $input["kbz_exchange_rate"];
				$kbz_usd_amount = $input["kbz_usd_amount"];
				$kbz_mmk_amount = $input["kbz_mmk_amount"];
				foreach($kbz_exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "kbz";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $kbz_usd_amount[$key]?$kbz_usd_amount[$key]:0;
					$detail->mmk_amount = $kbz_mmk_amount[$key]?$kbz_mmk_amount[$key]:0;
					$detail->save();

				}
			}
				
			//usd allowance exchange detail
			if($input['usd_allowance_usd'] and $input['usd_allowance_usd']>0){
				$exchanges = $input["usd_allowance_exchange_rate"];
				$usd_amount = $input["usd_allowance_usd_amount"];
				$mmk_amount = $input["usd_allowance_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "usd_allowance";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//mmk allowance exchange detail
			// if($input['mmk_allowance_mmk'] and $input['mmk_allowance_mmk']>0){
			// 	$exchanges = $input["mmk_allowance_exchange_rate"];
			// 	$usd_amount = $input["mmk_allowance_usd_amount"];
			// 	$mmk_amount = $input["mmk_allowance_mmk_amount"];
			// 	foreach($exchanges as $key=>$value){
			// 		$detail = new SalaryExchangeDetail();
			// 		$detail->salary_id = $salary->id;
			// 		$detail->type = "mmk_allowance";
			// 		$detail->from_to = "mmk_usd";
			// 		$detail->exchange_rate = $value?$value:0;
			// 		$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
			// 		$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
			// 		$detail->save();

			// 	}
			// }

			//usd deduction exchange detail
			if($input['usd_deduction_usd'] and $input['usd_deduction_usd']>0){
				$exchanges = $input["usd_deduction_exchange_rate"];
				$usd_amount = $input["usd_deduction_usd_amount"];
				$mmk_amount = $input["usd_deduction_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "usd_deduction";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//mmk deduction exchange detail
			// if($input['mmk_deduction_mmk'] and $input['mmk_deduction_mmk']>0){
			// 	$exchanges = $input["mmk_deduction_exchange_rate"];
			// 	$usd_amount = $input["mmk_deduction_usd_amount"];
			// 	$mmk_amount = $input["mmk_deduction_mmk_amount"];
			// 	foreach($exchanges as $key=>$value){
			// 		$detail = new SalaryExchangeDetail();
			// 		$detail->salary_id = $salary->id;
			// 		$detail->type = "mmk_deduction";
			// 		$detail->from_to = "mmk_usd";
			// 		$detail->exchange_rate = $value?$value:0;
			// 		$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
			// 		$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
			// 		$detail->save();

			// 	}
			// }
			

			return $salary;

		});

		return $salaries;
	}

	public function storeNS($input){
		$salaries= DB::transaction(function () use ($input) {

			//save salary
			$salary = new Salary();
			if($input["edit_salary"]!="0"){

				$salary = Salary::findOrFail($input["edit_salary"]);
				$salary->updated_by = Auth::user()->id;
				OtherAdjustment::where("salary_id",$salary->id)->delete();
				OtherAllowance::where("salary_id",$salary->id)->delete();
				OtherDeduction::where("salary_id",$salary->id)->delete();
				SalaryExchangeDetail::where("salary_id",$salary->id)->delete();
				
			}
				
			$salary->user_id = $input['user_id'];
			$salary->pay_for = $input['pay_for'];
			$salary->year = $input['year'];
			$salary->month = Carbon::parse($input['month'].", ".$input['year'])->format('m');
			$salary->leave_from_date = format_dbdate($input["leave_from_date"]);
			$salary->leave_to_date = format_dbdate($input["leave_to_date"]);
			$salary->employee_type = $input['employee_type'];
			$salary->exchange_rate_usd = $input['exchange_rate_usd'];
			$salary->exchange_rate_yen = $input['exchange_rate_yen'];
			$salary->payment_exchange_rate = $input['payment_exchange_rate'];

			$salary->previous_normal_ot_hr = "00:00";
			$salary->previous_sat_ot_hr = "00:00";
			$salary->previous_sunday_ot_hr = "00:00";
			$salary->previous_public_ot_hr = "00:00";

			$salary->current_normal_ot_hr = "00:00";
			$salary->current_sat_ot_hr = "00:00";
			$salary->current_sunday_ot_hr = "00:00";
			$salary->current_public_ot_hr = "00:00";
			$salary->total_ot_hr = "00:00";

			$salary->salary_usd = $input['salary_usd'];
			$salary->salary_mmk = $input['salary_mmk'];

			$salary->kbz_opening_usd = $input['kbz_opening_usd']?$input['kbz_opening_usd']:0;
			$salary->kbz_opening_mmk = $input['kbz_opening_mmk']?$input['kbz_opening_mmk']:0;

			$salary->no_ssc = isset($input["no_ssc"])?1:0;
			$salary->ssc_exchange = $input['ssc_exchange']?$input['ssc_exchange']:0;
			$salary->mmk_ssc = $input['mmk_ssc']?$input['mmk_ssc']:0;
			$salary->usd_ssc = $input['usd_ssc']?$input['usd_ssc']:0;
			$salary->ssc_usd = $input['ssc_usd']?$input['ssc_usd']:0;
			$salary->ssc_mmk = $input['ssc_mmk']?$input['ssc_mmk']:0;

			$salary->ot_rate_usd = $input["ot_rate_usd"]?$input["ot_rate_usd"]:0;

			$salary->previous_normal_ot_payment_usd = $input["previous_total_ot_payment"]?$input["previous_total_ot_payment"]:0;
			$salary->previous_sat_ot_payment_usd = 0;
			$salary->previous_sunday_ot_payment_usd = 0;
			$salary->previous_public_ot_payment_usd = 0;
			$salary->previous_taxi_charge_usd = $input['previous_taxi_charge_usd']?$input['previous_taxi_charge_usd']:0;

			$salary->current_normal_ot_payment_usd = 0;
			$salary->current_sat_ot_payment_usd = 0;
			$salary->current_sunday_ot_payment_usd = 0;
			$salary->current_public_ot_payment_usd = 0;
			$salary->current_taxi_charge_usd = 0;

			$salary->total_ot_payment_usd = $input["ot_usd"]?$input["ot_usd"]:0;
			$salary->total_ot_payment_mmk = $input["ot_mmk"]?$input["ot_mmk"]:0;

			$salary->unpaid_leave_day = $input["unpaid_leave_day"]?$input["unpaid_leave_day"]:0;
			$salary->daily_rate_usd = $input["daily_rate_usd"];

			$salary->leave_amount_usd = $input["leave_usd"]?$input["leave_usd"]:0;
			$salary->leave_amount_mmk = $input["leave_mmk"]?$input["leave_mmk"]:0;

			$salary->other_adjustment_usd = $input["adjustment_usd"]?$input["adjustment_usd"]:0;
			$salary->other_adjustment_mmk = $input["adjustment_mmk"]?$input["adjustment_mmk"]:0;

			$salary->estimated_type = $input["estimated_type"];
			if($salary->estimated_type=="percent")
				$salary->estimated_percent = $input["estimated_percent"];
			else
				$salary->estimated_percent = 0;

			$salary->estimated_tax_usd = $input["tax_usd_amount"]?$input["tax_usd_amount"]:0;
			$salary->estimated_tax_mmk = $input["tax_mmk_amount"]?$input["tax_mmk_amount"]:0;

			$salary->usd_allowance_usd = $input['usd_allowance_usd']?$input['usd_allowance_usd']:0;
			$salary->usd_allowance_mmk = $input['usd_allowance_mmk']?$input['usd_allowance_mmk']:0;
			$salary->mmk_allowance_usd = 0;
			$salary->mmk_allowance_mmk = $input['total_allowance_mmk']?$input['total_allowance_mmk']:0;

			$salary->usd_deduction_usd = $input['usd_deduction_usd']?$input['usd_deduction_usd']:0;
			$salary->usd_deduction_mmk = $input['usd_deduction_mmk']?$input['usd_deduction_mmk']:0;
			$salary->mmk_deduction_usd = 0;
			$salary->mmk_deduction_mmk = $input['total_deduction_mmk']?$input['total_deduction_mmk']:0;

			$salary->bonus_name = $input["bonus_name"];
			if($input["bonus_description"])
				$salary->other_bonus = $input["bonus_description"];
			$salary->bonus_usd = $input["bonus_usd"]?$input["bonus_usd"]:0;
			$salary->bonus_mmk = $input["bonus_mmk"]?$input["bonus_mmk"]:0;

			$salary->is_retire = isset($input['is_retire'])?1:0;
			$salary->retire_fee = isset($input['retire_fee'])?$input['retire_fee']:0;

			$salary->net_salary_usd = $input["net_salary_usd"];
			$salary->net_salary_mmk = $input["net_salary_mmk"];

			$salary->transfer_mmk_acc = isset($input["transfer_mmk_acc"])?$input["transfer_mmk_acc"]:0;	
			$salary->transfer_usd_acc = isset($input["transfer_usd_acc"])?$input["transfer_usd_acc"]:0;	
			$salary->transfer_mmk_cash = isset($input["transfer_mmk_cash"])?$input["transfer_mmk_cash"]:0;	
			$salary->transfer_usd_cash = isset($input["transfer_usd_cash"])?$input["transfer_usd_cash"]:0;

			$salary->ssc_remark = $input["ssc_remark"];
			$salary->monthly_paye_remark = $input["monthly_paye_remark"];

			$date = Carbon::parse($salary->pay_for)->format('Y-m-01');
        	$payment_exchange_rate = PaymentExchangeRate::where("date","=",$date)->first();
        	if($payment_exchange_rate)
				$salary->pay_date = $payment_exchange_rate->payment_date?$payment_exchange_rate->payment_date:Carbon::parse($salary->pay_for)->format("Y-m-d");		
			else
				$salary->pay_date = Carbon::parse($salary->pay_for)->format("Y-m-d");	

			$salary->created_by = Auth::user()->id;
			$salary->save();

			//other allowance record
			$allowance_name = isset($input["allowance_name"])?$input["allowance_name"]:[];
			$allowance_amount = isset($input["allowance_amount"])?$input["allowance_amount"]:[];
			$allowance_currency = isset($input["allowance_currency"])?$input["allowance_currency"]:[];
			foreach($allowance_name as $key=>$value){
				if($value or $allowance_amount[$key]){
					$allowance = new OtherAllowance();
					$allowance->salary_id = $salary->id;
					$allowance->name = $value?$value:'';
					$allowance->amount = $allowance_amount[$key]?$allowance_amount[$key]:0;
					$allowance->currency = $allowance_currency[$key];
					$allowance->save();
				}
			}

			//other deduction record
			$deduction_name = isset($input["deduction_name"])?$input["deduction_name"]:[];
			$deduction_amount = isset($input["deduction_amount"])?$input["deduction_amount"]:[];
			$deduction_currency = isset($input["deduction_currency"])?$input["deduction_currency"]:[];
			foreach($deduction_name as $key=>$value){
				if($value or $deduction_amount[$key]){
					$deduction = new OtherDeduction();
					$deduction->salary_id = $salary->id;
					$deduction->name = $value?$value:'';
					$deduction->amount = $deduction_amount[$key]?$deduction_amount[$key]:0;
					$deduction->currency = $deduction_currency[$key];
					$deduction->save();
				}
			}

			//other adjustment record
			//$adjustment_name = isset($input["adjustment_name"])?$input["adjustment_name"]:[];
			$adjustment_month = isset($input["adjustment_month"])?$input["adjustment_month"]:[];
			$adjustment_type = isset($input["adjustment_type"])?$input["adjustment_type"]:[];
			$adjustment_exchange_rate = isset($input["adjustment_exchange_rate"])?$input["adjustment_exchange_rate"]:[];
			$adjustment_usd_amount = isset($input["adjustment_usd_amount"])?$input["adjustment_usd_amount"]:[];
			$adjustment_mmk_amount = isset($input["adjustment_mmk_amount"])?$input["adjustment_mmk_amount"]:[];
			foreach($adjustment_month as $key=>$value){
				if($value or ($adjustment_exchange_rate[$key] and $adjustment_usd_amount[$key])){
					$adjustment = new OtherAdjustment();
					$adjustment->salary_id = $salary->id;
					$adjustment->date = $value?Carbon::parse($value)->endOfMonth()->format("Y-m-d"):NULL;
					$adjustment->type = $adjustment_type[$key];
					$name = $adjustment_type[$key]." For ".Carbon::parse($value)->format("F' Y");
					$adjustment->name = $name;
					$adjustment->exchange_rate = $adjustment_exchange_rate[$key]?$adjustment_exchange_rate[$key]:0;
					$adjustment->usd_amount = $adjustment_usd_amount[$key]?$adjustment_usd_amount[$key]:0;
					$adjustment->mmk_amount = $adjustment_mmk_amount[$key]?$adjustment_mmk_amount[$key]:0;
					$adjustment->save();
				}
			}

			//salary exchange detail
			$salary_exchanges = $input["salary_exchange_rate"];
			$salary_usd_amount = $input["salary_usd_amount"];
			$salary_mmk_amount = $input["salary_mmk_amount"];
			foreach($salary_exchanges as $key=>$value){
				$detail = new SalaryExchangeDetail();
				$detail->salary_id = $salary->id;
				$detail->type = "salary";
				$detail->from_to = "usd_mmk";
				$detail->exchange_rate = $value?$value:0;
				$detail->usd_amount = $salary_usd_amount[$key]?$salary_usd_amount[$key]:0;
				$detail->mmk_amount = $salary_mmk_amount[$key]?$salary_mmk_amount[$key]:0;
				$detail->save();

			}

			//kbz opening exchange detail
			if($input['kbz_opening_usd'] and $input['kbz_opening_usd']>0){
				$kbz_exchanges = $input["kbz_exchange_rate"];
				$kbz_usd_amount = $input["kbz_usd_amount"];
				$kbz_mmk_amount = $input["kbz_mmk_amount"];
				foreach($kbz_exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "kbz";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $kbz_usd_amount[$key]?$kbz_usd_amount[$key]:0;
					$detail->mmk_amount = $kbz_mmk_amount[$key]?$kbz_mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//ssc exchange detail
			if($input['ssc_usd']){
				$exchanges = $input["ssc_exchange_rate"];
				$usd_amount = $input["ssc_usd_amount"];
				$mmk_amount = $input["ssc_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "ssc";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//ot exchange detail
			if($input['ot_usd'] and $input['ot_usd']>0){
				$exchanges = $input["ot_exchange_rate"];
				$usd_amount = $input["ot_usd_amount"];
				$mmk_amount = $input["ot_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "ot";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//leave exchange detail
			if($input['leave_usd'] and $input['leave_usd']>0){
				$exchanges = $input["leave_exchange_rate"];
				$usd_amount = $input["leave_usd_amount"];
				$mmk_amount = $input["leave_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "leave";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//tax exchange detail
			if($input["tax_usd_amount"] and $input["tax_usd_amount"]>0){
				$exchanges = $input["tax_exchange_rate"];
				$usd_amount = $input["tax_usd_amount"];
				$mmk_amount = $input["tax_mmk_amount"];
				
				$detail = new SalaryExchangeDetail();
				$detail->salary_id = $salary->id;
				$detail->type = "tax";
				$detail->from_to = "usd_mmk";
				$detail->exchange_rate = $exchanges?$exchanges:0;
				$detail->usd_amount = $usd_amount?$usd_amount:0;
				$detail->mmk_amount = $mmk_amount?$mmk_amount:0;
				$detail->save();

				
			}
			//bonus exchange detail
			if($input['bonus_usd'] and $input['bonus_usd']>0){
				$exchanges = $input["bonus_exchange_rate"];
				$usd_amount = $input["bonus_usd_amount"];
				$mmk_amount = $input["bonus_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "bonus";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}
				
			//usd allowance exchange detail
			if($input['usd_allowance_usd'] and $input['usd_allowance_usd']>0){
				$exchanges = $input["usd_allowance_exchange_rate"];
				$usd_amount = $input["usd_allowance_usd_amount"];
				$mmk_amount = $input["usd_allowance_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "usd_allowance";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}


			//usd deduction exchange detail
			if($input['usd_deduction_usd'] and $input['usd_deduction_usd']>0){
				$exchanges = $input["usd_deduction_exchange_rate"];
				$usd_amount = $input["usd_deduction_usd_amount"];
				$mmk_amount = $input["usd_deduction_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "usd_deduction";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			
			//save ssc
			TaxHelper::storeSSC($salary);

			//save income tax
			$salary->tax_exchange_rate = $input["tax_exchange_rate"];
			TaxHelper::storeNsIncomeTax($salary);

			return $salary;

		});

		return $salaries;
	}

	public function storeRS($input){
		$salaries= DB::transaction(function () use ($input) {

			//save salary
			$salary = new Salary();

			if($input["edit_salary"]!="0"){

				$salary = Salary::findOrFail($input["edit_salary"]);
				$salary->updated_by = Auth::user()->id;
				OtherAdjustment::where("salary_id",$salary->id)->delete();
				OtherAllowance::where("salary_id",$salary->id)->delete();
				OtherDeduction::where("salary_id",$salary->id)->delete();
				SalaryExchangeDetail::where("salary_id",$salary->id)->delete();
				
			}
				
			$salary->user_id = $input['user_id'];
			$salary->pay_for = $input['pay_for'];
			$salary->year = $input['year'];
			$salary->month = Carbon::parse($input['month'].", ".$input['year'])->format('m');
			$salary->leave_from_date = format_dbdate($input["leave_from_date"]);
			$salary->leave_to_date = format_dbdate($input["leave_to_date"]);
			$salary->employee_type = $input['employee_type'];
			$salary->exchange_rate_usd = $input['exchange_rate_usd'];
			$salary->exchange_rate_yen = $input['exchange_rate_yen'];
			$salary->payment_exchange_rate = $input['payment_exchange_rate'];

			$salary->salary_usd = $input['salary_usd'];
			$salary->salary_mmk = $input['salary_mmk'];

			$salary->kbz_opening_usd = $input['kbz_opening_usd']?$input['kbz_opening_usd']:0;
			$salary->kbz_opening_mmk = $input['kbz_opening_mmk']?$input['kbz_opening_mmk']:0;

			$salary->transfer_to_japan_usd = $input['transfer_to_usd']?$input['transfer_to_usd']:0;
			$salary->transfer_to_japan_mmk = $input['transfer_to_mmk']?$input['transfer_to_mmk']:0;

			$salary->transfer_from_japan_usd = $input['transfer_from_usd']?$input['transfer_from_usd']:0;
			$salary->transfer_from_japan_mmk = $input['transfer_from_mmk']?$input['transfer_from_mmk']:0;

			$salary->electricity_usd = $input['electricity_usd']?$input['electricity_usd']:0;
			$salary->electricity_mmk = $input['electricity_mmk']?$input['electricity_mmk']:0;

			$salary->car_usd = $input['car_usd']?$input['car_usd']:0;
			$salary->car_mmk = $input['car_mmk']?$input['car_mmk']:0;

			$salary->usd_allowance_usd = $input['usd_allowance_usd']?$input['usd_allowance_usd']:0;
			$salary->usd_allowance_mmk = $input['usd_allowance_mmk']?$input['usd_allowance_mmk']:0;
			$salary->mmk_allowance_usd = 0;
			$salary->mmk_allowance_mmk = $input['total_allowance_mmk']?$input['total_allowance_mmk']:0;

			$salary->usd_deduction_usd = $input['usd_deduction_usd']?$input['usd_deduction_usd']:0;
			$salary->usd_deduction_mmk = $input['usd_deduction_mmk']?$input['usd_deduction_mmk']:0;
			$salary->mmk_deduction_usd = 0;
			$salary->mmk_deduction_mmk = $input['total_deduction_mmk']?$input['total_deduction_mmk']:0;


			$salary->is_retire = isset($input['is_retire'])?1:0;
			$salary->retire_fee = isset($input['retire_fee'])?$input['retire_fee']:0;

			$salary->net_salary_usd = $input["net_salary_usd"];
			$salary->net_salary_mmk = $input["net_salary_mmk"];

			$salary->transfer_mmk_acc = isset($input["transfer_mmk_acc"])?$input["transfer_mmk_acc"]:0;	
			$salary->transfer_usd_acc = isset($input["transfer_usd_acc"])?$input["transfer_usd_acc"]:0;	
			$salary->transfer_mmk_cash = isset($input["transfer_mmk_cash"])?$input["transfer_mmk_cash"]:0;	
			$salary->transfer_usd_cash = isset($input["transfer_usd_cash"])?$input["transfer_usd_cash"]:0;

			$date = Carbon::parse($salary->pay_for)->format('Y-m-01');
        	$payment_exchange_rate = PaymentExchangeRate::where("date","=",$date)->first();
        	if($payment_exchange_rate)
				$salary->pay_date = $payment_exchange_rate->payment_date?$payment_exchange_rate->payment_date:Carbon::parse($salary->pay_for)->format("Y-m-d");		
			else
				$salary->pay_date = Carbon::parse($salary->pay_for)->format("Y-m-d");	

			$salary->created_by = Auth::user()->id;
			$salary->save();

			//other allowance record
			$allowance_name = isset($input["allowance_name"])?$input["allowance_name"]:[];
			$allowance_amount = isset($input["allowance_amount"])?$input["allowance_amount"]:[];
			$allowance_currency = isset($input["allowance_currency"])?$input["allowance_currency"]:[];
			foreach($allowance_name as $key=>$value){
				if($value or $allowance_amount[$key]){
					$allowance = new OtherAllowance();
					$allowance->salary_id = $salary->id;
					$allowance->name = $value?$value:'';
					$allowance->amount = $allowance_amount[$key]?$allowance_amount[$key]:0;
					$allowance->currency = $allowance_currency[$key];
					$allowance->save();
				}
			}

			//other deduction record
			$deduction_name = isset($input["deduction_name"])?$input["deduction_name"]:[];
			$deduction_amount = isset($input["deduction_amount"])?$input["deduction_amount"]:[];
			$deduction_currency = isset($input["deduction_currency"])?$input["deduction_currency"]:[];
			foreach($deduction_name as $key=>$value){
				if($value or $deduction_amount[$key]){
					$deduction = new OtherDeduction();
					$deduction->salary_id = $salary->id;
					$deduction->name = $value?$value:'';
					$deduction->amount = $deduction_amount[$key]?$deduction_amount[$key]:0;
					$deduction->currency = $deduction_currency[$key];
					$deduction->save();
				}
			}

			//salary exchange detail
			$salary_exchanges = $input["salary_exchange_rate"];
			$salary_usd_amount = $input["salary_usd_amount"];
			$salary_mmk_amount = $input["salary_mmk_amount"];
			foreach($salary_exchanges as $key=>$value){
				$detail = new SalaryExchangeDetail();
				$detail->salary_id = $salary->id;
				$detail->type = "salary";
				$detail->from_to = "usd_mmk";
				$detail->exchange_rate = $value?$value:0;
				$detail->usd_amount = $salary_usd_amount[$key]?$salary_usd_amount[$key]:0;
				$detail->mmk_amount = $salary_mmk_amount[$key]?$salary_mmk_amount[$key]:0;
				$detail->save();

			}

			//kbz opening exchange detail
			if($input['kbz_opening_usd'] and $input['kbz_opening_usd']>0){
				$kbz_exchanges = $input["kbz_exchange_rate"];
				$kbz_usd_amount = $input["kbz_usd_amount"];
				$kbz_mmk_amount = $input["kbz_mmk_amount"];
				foreach($kbz_exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "kbz";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $kbz_usd_amount[$key]?$kbz_usd_amount[$key]:0;
					$detail->mmk_amount = $kbz_mmk_amount[$key]?$kbz_mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//transfer to exchange detail
			if($input['transfer_to_usd'] and $input['transfer_to_usd']>0){
				$exchanges = $input["transfer_to_exchange_rate"];
				$usd_amount = $input["transfer_to_usd_amount"];
				$mmk_amount = $input["transfer_to_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "transfer_to";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//transfer from exchange detail
			if($input['transfer_from_usd'] and $input['transfer_from_usd']>0){
				$exchanges = $input["transfer_from_exchange_rate"];
				$usd_amount = $input["transfer_from_usd_amount"];
				$mmk_amount = $input["transfer_from_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "transfer_from";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//electricity exchange detail
			if($input['electricity_usd'] and $input['electricity_usd']>0){
				$exchanges = $input["electricity_exchange_rate"];
				$usd_amount = $input["electricity_usd_amount"];
				$mmk_amount = $input["electricity_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "electricity";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

			//car exchange detail
			if($input['car_usd'] and $input['car_usd']>0){
				$exchanges = $input["car_exchange_rate"];
				$usd_amount = $input["car_usd_amount"];
				$mmk_amount = $input["car_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "car";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}
				
			//usd allowance exchange detail
			if($input['usd_allowance_usd'] and $input['usd_allowance_usd']>0){
				$exchanges = $input["usd_allowance_exchange_rate"];
				$usd_amount = $input["usd_allowance_usd_amount"];
				$mmk_amount = $input["usd_allowance_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "usd_allowance";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}


			//usd deduction exchange detail
			if($input['usd_deduction_usd'] and $input['usd_deduction_usd']>0){
				$exchanges = $input["usd_deduction_exchange_rate"];
				$usd_amount = $input["usd_deduction_usd_amount"];
				$mmk_amount = $input["usd_deduction_mmk_amount"];
				foreach($exchanges as $key=>$value){
					$detail = new SalaryExchangeDetail();
					$detail->salary_id = $salary->id;
					$detail->type = "usd_deduction";
					$detail->from_to = "usd_mmk";
					$detail->exchange_rate = $value?$value:0;
					$detail->usd_amount = $usd_amount[$key]?$usd_amount[$key]:0;
					$detail->mmk_amount = $mmk_amount[$key]?$mmk_amount[$key]:0;
					$detail->save();

				}
			}

		

			//save income tax
			TaxHelper::storeRsIncomeTax($salary);

			return $salary;

		});

		return $salaries;
	}



	
}