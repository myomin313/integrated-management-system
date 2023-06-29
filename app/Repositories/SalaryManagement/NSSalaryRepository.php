<?php
namespace App\Repositories\SalaryManagement;

use App\Models\SalaryManagement\NsSalary;
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

class NSSalaryRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(NsSalary $ns_salary,User $user)
	{
		$this->model=$ns_salary;
		$this->user_model=$user;
	}

	public function index($name,$order){
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		if(Auth::user()->can("salary-read-all"))
			return $this->user_model->select('users.id','users.name','users.employee_name','users.position_id',DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->where("users.check_ns_rs","=",1)->orderBy("salary","desc")->orderBy("id","asc");
		else if(Auth::user()->can("salary-read-group")){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			return $this->user_model->select('users.id','users.name','users.employee_name','users.position_id',DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->where("users.check_ns_rs","=",1)->whereIn('users.id',$user_ids)->orderBy("salary","desc")->orderBy("id","asc");
		}
		else{
			return $this->user_model->select('users.id','users.name','users.employee_name','users.position_id')->where("users.check_ns_rs","=",1)->where("users.id","=",Auth::user()->id)->orderBy($name,$order);
		}
		
	}
	

	public function indexOrder($name,$order,$year=null,$employee=null,$n){

		$query=$this->index($name,$order);
		        
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('users.id','=',$employee);
			});
		}
		//$query->orderBy('att_UserID','asc');
		
		//return $query->paginate($n);
		return $query->get();
	}

	
	public function updateSalary($input,$user_id,$year){
		$salaries= DB::transaction(function () use ($input,$user_id,$year) {

			//save chnage request
	    	$salary = NsSalary::where([["user_id","=",$user_id],["salary_type","=","salary"],["year","=",$year]])->first();
	    	if(!$salary){
	    		$salary = new NsSalary();
	    		$salary->created_by = Auth::user()->id;
	    	}
	    	else
	    		$salary->updated_by = Auth::user()->id;

			$salary->user_id = $user_id;
			$salary->salary_type = "salary";

			$salary->april = $input['apr_salary'];
			$salary->may = $input['may_salary'];
			$salary->june = $input['jun_salary'];
			$salary->july = $input['jul_salary'];
			$salary->august = $input['aug_salary'];
			$salary->september = $input['sep_salary'];
			$salary->october = $input['oct_salary'];
			$salary->november = $input['nov_salary'];
			$salary->december = $input['dec_salary'];
			$salary->january = $input['jan_salary'];
			$salary->february = $input['feb_salary'];
			$salary->march = $input['mar_salary'];

			$salary->year = $year;
			$salary->date = Carbon::now()->format('Y-m-d');
			
			
			$salary->save();
							
			return $salary;

		});

		return $salaries;
		
	}

	
}