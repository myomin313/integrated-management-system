<?php
namespace App\Repositories\TaxManagement;

use App\Models\TaxManagement\Ssc;
use App\Models\TaxManagement\NsIncomeTax;
use App\Models\TaxManagement\RsIncomeTax;
use App\Models\TaxManagement\TaxRange;
use App\Models\TaxManagement\NsActualTax;
use App\Models\SalaryManagement\Salary;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Stevebauman\Location\Facades\Location;
use DB;
use Carbon\Carbon;
//use DateTime;

class NsTaxRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(NsActualTax $tax)
	{
		$this->model=$tax;
	}

	public function index($name,$order){
		
		return $this->model->orderBy($name,$order);
		
	}

	public function indexOrder($name,$order,$from_date=null,$to_date=null,$employee=null,$n){
		
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;

		$query = $this->model->select('ns_actual_taxes.*',DB::raw("(select $month from ns_salaries where ns_actual_taxes.user_id = ns_salaries.user_id and year=$year) AS salary"));
		$query->orderBy("salary","desc");
		$query->orderBy("ns_actual_taxes.user_id","asc");
		if(Auth::user()->can("tax-read-group")){
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
		    $query->whereIn("ns_actual_taxes.user_id",$user_ids);
		}
		else if(Auth::user()->can("tax-read-one")){
			$query->where("ns_actual_taxes.user_id","=",Auth::user()->id);
		}
		if($from_date!=null){
            $query->whereRaw( " CAST(ns_actual_taxes.pay_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
            $query->whereRaw( " CAST(ns_actual_taxes.pay_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('ns_actual_taxes.user_id','=',$employee);
			});
		}
		
		$query->orderBy($name,$order);
		
		//return $query->paginate($n);
		return $query->get();
	}

	public function store($input){
		$taxes = DB::transaction(function () use ($input){
			$tax_for = $input["tax_for"];
			$user_id = $input["user_id"];
			$tax_amount_mmk = $input["tax_amount_mmk"];
			$tax_amount_usd = $input["tax_amount_usd"];
			$exchange_rate = $input["exchange_rate"];
			$pay_date = $input["pay_date"];

			foreach($user_id as $key=>$id){
				$tax = NsActualTax::where([["user_id","=",$id],["tax_for","=",$tax_for[$key]]])->first();
				if(!$tax)
					$tax = new NsActualTax();
				else{
					$tax->updated_by = Auth::user()->id;
				}
				$tax->user_id = $id;
				$tax->tax_for = $tax_for[$key];
				$tax->tax_period = Carbon::parse($tax_for[$key])->endOfMonth()->format("Y-m-d");
				$tax->tax_amount_mmk = $tax_amount_mmk[$key];
				$tax->exchange_rate = $exchange_rate[$key];
				$tax->tax_amount_usd = $tax_amount_usd[$key];
				$input_date = explode('/', $pay_date[$key]);
				$paydate = $input_date[2].'-'.$input_date[0].'-'.$input_date[1];
				$tax->pay_date = $paydate;
				$tax->created_by = Auth::user()->id;
				$tax->save();

				$end_date = Carbon::parse($tax->tax_for)->endOfMonth()->format("Y-m-d");

				$ns_income_tax = NsIncomeTax::where([["user_id","=",$tax->user_id],["date","=",$end_date]])->first();
				if($ns_income_tax){
					$ns_income_tax->actual_tax_mmk = $tax->tax_amount_mmk;
					$ns_income_tax->actual_exchange_rate = $tax->exchange_rate;
					$ns_income_tax->actual_tax_usd = $tax->tax_amount_usd;
					$ns_income_tax->save();
				}
			}

			return $tax;
		});

		return $taxes;
	}

	public function update($input){
		$taxes = DB::transaction(function () use ($input){
			$id = $input["id"];
			$tax_for = $input["tax_for"];
			$user_id = $input["user_id"];
			$tax_amount_mmk = $input["tax_amount_mmk"];
			$tax_amount_usd = $input["tax_amount_usd"];
			$exchange_rate = $input["exchange_rate"];
			$pay_date = $input["pay_date"];

			
			$tax = NsActualTax::findOrFail($id);
				
			$tax->user_id = $id;
			$tax->tax_for = $tax_for;
			$tax->tax_period = Carbon::parse($tax_for)->endOfMonth()->format("Y-m-d");
			$tax->tax_amount_mmk = $tax_amount_mmk;
			$tax->exchange_rate = $exchange_rate;
			$tax->tax_amount_usd = $tax_amount_usd;
		
			$tax->pay_date = format_dbdate($pay_date);
			$tax->updated_by = Auth::user()->id;
			$tax->save();

			$end_date = Carbon::parse($tax->tax_for)->endOfMonth()->format("Y-m-d");

			$ns_income_tax = NsIncomeTax::where([["user_id","=",$tax->user_id],["date","=",$end_date]])->first();
			if($ns_income_tax){
				$ns_income_tax->actual_tax_mmk = $tax->tax_amount_mmk;
				$ns_income_tax->actual_exchange_rate = $tax->exchange_rate;
				$ns_income_tax->actual_tax_usd = $tax->tax_amount_usd;
				$ns_income_tax->save();
			}

			return $tax;
			
		});
		return $taxes;
	}
	


	
}