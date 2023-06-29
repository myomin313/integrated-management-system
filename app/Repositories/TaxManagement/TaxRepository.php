<?php
namespace App\Repositories\TaxManagement;

use App\Models\TaxManagement\Ssc;
use App\Models\TaxManagement\NsIncomeTax;
use App\Models\TaxManagement\NsActualTax;
use App\Models\TaxManagement\NsIncomeTaxDetail;
use App\Models\TaxManagement\RsIncomeTax;
use App\Models\TaxManagement\RsActualTax;
use App\Models\TaxManagement\RsIncomeTaxJpyDetail;
use App\Models\TaxManagement\RsIncomeTaxMmDetail;
use App\Models\TaxManagement\TaxRange;
use App\Models\SalaryManagement\ExchangeRate;
use App\Models\SalaryManagement\Salary;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Helpers\TaxHelper;
use Stevebauman\Location\Facades\Location;
use DB;
use Carbon\Carbon;
//use DateTime;

class TaxRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(Ssc $ssc,NsIncomeTax $ns_income_tax,RsIncomeTax $rs_income_tax,NsActualTax $ns_actual_tax,RsActualTax $rs_actual_tax)
	{
		$this->ssc_model=$ssc;
		$this->ns_income_tax_model=$ns_income_tax;
		$this->rs_income_tax_model=$rs_income_tax;
		$this->ns_actual_tax_model=$ns_actual_tax;
		$this->rs_actual_tax_model=$rs_actual_tax;
	}

	public function index($name,$order){
		
		return DB::table('raw_att')->select('raw_att.*')->orderBy($name,$order);
		
	}
	

	public function sscReport($name,$order,$from_date=null,$to_date=null,$employee=null,$branch=null,$n){

		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;

		if(Auth::user()->can("tax-read-all"))
			$query = $this->ssc_model->select("*",DB::raw("(select $month from ns_salaries where sscs.user_id = ns_salaries.user_id and year=$year) AS salary"));
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = $this->ssc_model->whereIn('sscs.user_id',$user_ids)->select("*",DB::raw("(select $month from ns_salaries where sscs.user_id = ns_salaries.user_id and year=$year) AS salary"));
		}
		else
			$query = $this->ssc_model->where('sscs.user_id',"=",Auth::user()->id)->select("*",DB::raw("(select $month from ns_salaries where sscs.user_id = ns_salaries.user_id and year=$year) AS salary"));
		
		$query->leftJoin("users","users.id","=","sscs.user_id");
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(sscs.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(sscs.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('sscs.user_id','=',$employee);
			});
		}
		if($branch!=null){
			$query->where("users.branch_id","=",$branch);
		}
		$query->orderBy("employee_type_id","asc");
		$query->orderBy("salary","desc");
		$query->orderBy("sscs.user_id","asc");
		$result = $query->orderBy('users.employee_type_id',"asc")->get()->groupBy(function($item) {
		            return $item->employee_type_id;
		    });
		//return $query->paginate($n);
		return $result;
	}

	public function monthlyTaxStatement($name,$order,$from_date=null,$to_date=null,$employee=null,$n){

		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		
		if(Auth::user()->can("tax-read-all"))
			$query = $this->ns_income_tax_model->select("*",DB::raw("(select $month from ns_salaries where ns_income_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = $this->ns_income_tax_model->whereIn('ns_income_taxes.user_id',$user_ids)->select("*",DB::raw("(select $month from ns_salaries where ns_income_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		}
		else
			$query = $this->ns_income_tax_model->where('ns_income_taxes.user_id',"=",Auth::user()->id)->select("*",DB::raw("(select $month from ns_salaries where ns_income_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));

		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(ns_income_taxes.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(ns_income_taxes.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('ns_income_taxes.user_id','=',$employee);
			});
		}
		
		$query->orderBy("salary","desc");
		$query->orderBy("ns_income_taxes.user_id","asc");
		
		//return $query->paginate($n);
		return $query->get();
	}

	public function monthlyPaye($name,$order,$from_date=null,$to_date=null,$employee=null,$n){
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		
		if(Auth::user()->can("tax-read-all"))
			$query = $this->ns_income_tax_model->select("*",DB::raw("(select $month from ns_salaries where ns_income_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = $this->ns_income_tax_model->whereIn('ns_income_taxes.user_id',$user_ids)->select("*",DB::raw("(select $month from ns_salaries where ns_income_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		}
		else
			$query = $this->ns_income_tax_model->where('ns_income_taxes.user_id',"=",Auth::user()->id)->select("*",DB::raw("(select $month from ns_salaries where ns_income_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));

		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(ns_income_taxes.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(ns_income_taxes.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('ns_income_taxes.user_id','=',$employee);
			});
		}
		
		$query->orderBy("salary","desc");
		$query->orderBy("ns_income_taxes.user_id","asc");
		
		//return $query->paginate($n);
		return $query->get();
	}

	public function nsActualTaxPayment($name,$order,$from_date=null,$to_date=null,$employee=null,$n){
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		
		if(Auth::user()->can("tax-read-all"))
			$query = $this->ns_actual_tax_model->select("*",DB::raw("(select $month from ns_salaries where ns_actual_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = $this->ns_actual_tax_model->whereIn('ns_actual_taxes.user_id',$user_ids)->select("*",DB::raw("(select $month from ns_salaries where ns_actual_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		}
		else
			$query = $this->ns_actual_tax_model->where('ns_actual_taxes.user_id',"=",Auth::user()->id)->select("*",DB::raw("(select $month from ns_salaries where ns_actual_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));

		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(ns_actual_taxes.tax_period AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(ns_actual_taxes.tax_period AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('ns_actual_taxes.user_id','=',$employee);
			});
		}
		
		$query->orderBy("salary","desc");
		$query->orderBy("ns_actual_taxes.user_id","asc");
		
		//return $query->paginate($n);
		return $query->get();
	}

	public function rsActualTaxPayment($name,$order,$from_date=null,$to_date=null,$employee=null,$n){
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		
		if(Auth::user()->can("tax-read-all"))
			$query = $this->rs_actual_tax_model->select("*",DB::raw("(select $month from rs_salaries where rs_actual_taxes.user_id = rs_salaries.user_id and salary_type='mm_salary' and year=$year) AS salary"));
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = $this->rs_actual_tax_model->whereIn('rs_actual_taxes.user_id',$user_ids)->select("*",DB::raw("(select $month from rs_salaries where rs_actual_taxes.user_id = rs_salaries.user_id and salary_type='mm_salary' and year=$year) AS salary"));
		}
		else
			$query = $this->rs_actual_tax_model->where('rs_actual_taxes.user_id',"=",Auth::user()->id)->select("*",DB::raw("(select $month from rs_salaries where rs_actual_taxes.user_id = rs_salaries.user_id and salary_type='mm_salary' and year=$year) AS salary"));


		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(rs_actual_taxes.tax_period AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(rs_actual_taxes.tax_period AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('rs_actual_taxes.user_id','=',$employee);
			});
		}
		
		$query->orderBy("salary","desc");
		$query->orderBy("rs_actual_taxes.user_id","asc");
		
		//return $query->paginate($n);
		return $query->get();
	}

	public function exchangeRateDetail($name,$order,$from_date=null,$n){
		$query = ExchangeRate::select("*")->orderBy($name,$order);
		
		$date = Carbon::parse($from_date)->format("Y-m");
		if($from_date!=null){
			$query->where("exchange_rates.date","like","$from_date%");
        }
		
		//return $query->paginate($n);
		return $query->get();
	}

	public function exchangeRateSummary($from_date){
		$month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];

		$exchange_rates = array();
		foreach($month_arr as $key=>$value){
			$year = $from_date;
			if($key=="01" or $key=="02" or $key=="03")
				$year = $year + 1;
			$ex_rate = TaxHelper::getExchangeRateSummary($key,$year);

			$exchange_rates[$value."' ".$year] = ["usd"=>$ex_rate[0],"yen"=>$ex_rate[1]];
		}
        
		//return $query->paginate($n);
		return $exchange_rates;
	}

	public function paidPersonal($from_date){
		$month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];

		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;

		if(Auth::user()->can("tax-read-all"))
			$users = User::where("check_ns_rs","=",1)->select("id","name",DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("users.id","asc")->get();
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$users = User::where("check_ns_rs","=",1)->whereIn('id',$user_ids)->select("id","name",DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("users.id","asc")->get();
		}
		else{
			$users = User::where("check_ns_rs","=",1)->where('id',Auth::user()->id)->select("id","name",DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("users.id","asc")->get();
		}

		$taxes = array();
		foreach($users as $index=>$user){
			$estimated_tax_arr = array();
			$actual_tax_usd = array();
			$actual_tax_mmk = array();
			foreach($month_arr as $key=>$value){
				$year = $from_date;
				if($key=="01" or $key=="02" or $key=="03")
					$year = $year + 1;
				

				$estimated_tax_arr []= TaxHelper::getExtimatedTax($user->id,$key,$year);
				$actual_tax = TaxHelper::getActualTax($user->id,$key,$year);
				$actual_tax_usd[] = $actual_tax[0];
				$actual_tax_mmk[] = $actual_tax[1];
			}
			$taxes[$user->id]["estimated"]["usd"] = $estimated_tax_arr;
			$taxes[$user->id]["actual"]["usd"] = $actual_tax_usd;
			$taxes[$user->id]["actual"]["mmk"] = $actual_tax_mmk;
		}
			
        
		//return $query->paginate($n);
		return $taxes;
	}

	public function taxOfficeSubmission($from_date,$employee=null){
		$month_arr = ["4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December","1"=>"January","2"=>"February","3"=>"March"];

		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		
		if(Auth::user()->can("tax-read-all"))
			$query = User::select("id","name")->where("check_ns_rs","=",1)->select("id","name",DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("users.id","asc");
		else if(Auth::user()->can("tax-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = User::select("id","name")->where("check_ns_rs","=",1)->whereIn('id',$user_ids)->select("id","name",DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("users.id","asc");
		}
		else{
			$query = User::select("id","name")->where("check_ns_rs","=",1)->where('id',Auth::user()->id)->select("id","name",DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("users.id","asc");
		}

		if($employee)
			$query->where("id","=",$employee);

		$users = $query->get();

		$taxes = array();
		$monthly_tax = array();
		foreach($users as $index=>$user){
			
			foreach($month_arr as $key=>$value){
				$year = $from_date;
				if($key=="1" or $key=="2" or $key=="3")
					$year = $year + 1;

				$salary = Salary::select("pay_for","salary_mmk","total_ot_payment_mmk","bonus_mmk","ssc_mmk","estimated_tax_mmk")->where([["user_id","=",$user->id],["year","=",$year],["month","=",$key]])->first();
				if($salary){
					$monthly_tax[$user->id][] = [
						"salary"=>$salary->salary_mmk,
						"ot"=>$salary->total_ot_payment_mmk,
						"bonus"=>$salary->bonus_mmk,
						"ssc"=>$salary->ssc_mmk,
						"tax"=>$salary->estimated_tax_mmk,
						"pay_for"=>$salary->pay_for

					];
				}
				else{
					$monthly_tax[$user->id][] = [
						"salary"=>0,
						"ot"=>0,
						"bonus"=>0,
						"ssc"=>0,
						"tax"=>0,
						"pay_for"=>$value.", ".$year

					];
				}
				//$taxes[] = $monthly_tax;

			}
			
		}
			
        
		//return $query->paginate($n);
		return $monthly_tax;
	}


	
}