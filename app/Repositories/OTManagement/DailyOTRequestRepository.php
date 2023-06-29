<?php
namespace App\Repositories\OTManagement;

use App\Models\AttendanceManagement\Attendance;
use App\Models\OTManagement\DailyOtRequest;
use App\Models\OTManagement\MonthlyOtRequest;
use App\Models\OTManagement\MonthlyOtRequestDetail;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTRequestMail;
use App\Mail\OTStatusMail;
use App\Mail\OTMonthlyRequestMail;
//use DateTime;

class DailyOTRequestRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(DailyOtRequest $ot_request)
	{
		$this->model=$ot_request;
	}

	public function index($name,$order){

		if(Auth::user()->can('ot-read-all'))
			return $this->model->select('daily_ot_requests.*')->orderBy($name,$order);
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');

			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }

			return $this->model->select('daily_ot_requests.*')->whereIn('user_id',$user_ids)->orderBy($name,$order);
		}
		else
			return $this->model->select('daily_ot_requests.*')->where('user_id','=',Auth::user()->id)->orderBy($name,$order);
		
		
		
	}
	

	public function indexOrder($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status='0',$monthly_request=0,$n){
		$query=$this->index($name,$order);

		$query->leftJoin('users','users.id','=','daily_ot_requests.user_id');
		
		if($from_date!=null){
            $query->whereRaw( " CAST(daily_ot_requests.apply_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
            $query->whereRaw( " CAST(daily_ot_requests.apply_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('daily_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('daily_ot_requests.status',[0,1,2]);
			}
			else{
				$query->where('daily_ot_requests.status','=',$status);
			}
			
		}
		else{
			$query->where('daily_ot_requests.status','=',0);
		}

		if($monthly_request=="0")
			$query->where('daily_ot_requests.monthly_request','=',0);
		else if($monthly_request=="1")
			$query->where('daily_ot_requests.monthly_request','=',1);
		
		return $query->get();
	}

	
	public function store($input){
		$dailyrequests= DB::transaction(function () use ($input) {

			//save chnage request
	    	
	    	$dailyrequest = new DailyOtRequest();
			$dailyrequest->user_id = Auth::user()->id;
			$dailyrequest->branch = getUserFieldWithId(Auth::user()->id,'branch_id');
			$dailyrequest->ot_type = $input['ot_type'];
			$dailyrequest->apply_date = format_dbdate($input['apply_date']);
			$dailyrequest->start_from_time = format_dbtime($input['start_from_time']);
			$dailyrequest->start_to_time = format_dbtime($input['start_to_time']);
			$dailyrequest->start_break_hour = $input['start_break_hour']?$input['start_break_hour']:0;
			$dailyrequest->start_break_minute = $input['start_break_minute']?$input['start_break_minute']:0;
			$dailyrequest->start_reason = $input['start_reason'];
			$dailyrequest->start_hotel = isset($input['start_hotel'])?1:0;
			$dailyrequest->start_next_day = isset($input['start_next_day'])?1:0;
			$dailyrequest->save();

			//$users = User::permission('change-ot-manager-status')->where("department_id","=",getUserFieldWithId($dailyrequest->user_id,"department_id"))->get();

			$terms = explode(',',auth()->user()->department_id);
			$user_ids = array();
			foreach($terms as $term){
				$users=User::permission('change-ot-manager-status')->where(function($query) use($term) {
	                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
	                    
	            	})->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
			}
			$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
    		if($users){
    			foreach($users as $key=>$value){
    				if($value->noti_type=="email")
    					Mail::to($value->email)->send(new OTRequestMail($value,$dailyrequest,"start"));
    			}
    		}
							
			return $dailyrequest;

		});

		return $dailyrequests;
		
	}

	public function updateEndTime($input){
		$dailyrequests= DB::transaction(function () use ($input) {

			//save chnage request
	    	
	    	$dailyrequest = DailyOtRequest::findOrFail($input['id']);
	    	$dailyrequest->ot_type = $input['ot_type'];
			$dailyrequest->apply_date = format_dbdate($input['apply_date']);
			$dailyrequest->end_from_time = format_dbtime($input['end_from_time']);
			$dailyrequest->end_to_time = format_dbtime($input['end_to_time']);
			$dailyrequest->end_break_hour = $input['end_break_hour']?$input['end_break_hour']:0;
			$dailyrequest->end_break_minute = $input['end_break_minute']?$input['end_break_minute']:0;
			$dailyrequest->end_reason = $input['end_reason'];
			$dailyrequest->end_hotel = isset($input['start_hotel'])?1:0;
			$dailyrequest->end_next_day = isset($input['start_next_day'])?1:0;
			$dailyrequest->status = 0;
			$dailyrequest->status_reason = '';

			$dailyrequest->save();

			//$users = User::permission('change-ot-manager-status')->where("department_id","=",getUserFieldWithId($dailyrequest->user_id,"department_id"))->get();
			$terms = explode(',',auth()->user()->department_id);
			$user_ids = array();
			foreach($terms as $term){
				$users=User::permission('change-ot-manager-status')->where(function($query) use($term) {
	                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
	                    
	            	})->pluck("id")->toArray();
				
		        $user_ids = array_merge($user_ids,$users);
			}
			$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
				
    		if($users){
    			foreach($users as $key=>$value){
    				if($value->noti_type=="email")
    					Mail::to($value->email)->send(new OTRequestMail($value,$dailyrequest,"end"));
    			}
    		}
							
			return $dailyrequest;

		});

		return $dailyrequests;
		
	}

	public function changeStatus($input,$status){
		
	    $changestatuses= DB::transaction(function () use ($input,$status) {
			
			$change=DailyOtRequest::whereId($input['id'])->first();

			$change->status = $status;
			$change->status_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$change->status_reason = $input['status_reason'];
			$change->status_change_by = Auth::user()->id;
			if($status==1){
				$change->monthly_request_id = 0;
			}
			$change->save();
   		
			$user = User::findOrFail($change->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new OTStatusMail($user,$change,'manager'));
    		else{
    			$message = "Daily OT:\n";
    			if($status==1)
    				$message .= "Accept your OT for ".$change->apply_date;
    			else
    				$message .= "Reject your OT for ".$change->apply_date;
    			
    			sendSMS($user->phone,$message);
    		}
			return $change;

		});

		return $changestatuses;


	}

	public function storeMonthlyRequest($input){
		$monthlyrequests= DB::transaction(function () use ($input) {

			//save chnage request
			$request_for = $input["request_for"];
			$request_date = array();
	    	$user_id = Auth::user()->id;
	    	$dailyrequests = DailyOtRequest::where([['user_id','=',$user_id],['status','=',1],['monthly_request','=',0]])->where("apply_date","like","$request_for%")->orderBy('id','asc')->get();
	    	foreach($dailyrequests as $key=>$dailyrequest){
	    		$request_date [] = $dailyrequest->apply_date;
	    		$date = Carbon::parse($dailyrequest->apply_date)->format('Y-m');

	    		$monthlyrequest = MonthlyOtRequest::where([['user_id','=',$user_id],['date','like',"$date%"]])->first();
	    		if(!$monthlyrequest){
	    			$last_date = Carbon::parse($dailyrequest->apply_date)->endOfMonth()->format('Y-m-d');
	    			$monthlyrequest = MonthlyOtRequest::create([
	    				'user_id'=>$user_id,
	    				'branch'=>getUserFieldWithId($user_id,'branch_id'),
	    				'date' => $last_date
	    			]);
	    		}
	    		$monthlyrequest->manager_main_status = 0;
	    		$monthlyrequest->account_main_status = 0;
	    		$monthlyrequest->gm_main_status = 0;
	    		$monthlyrequest->save();

	    		$detail_request = MonthlyOtRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['daily_ot_request_id','=',$dailyrequest->id]])->first();
	    		if(!$detail_request){
	    			$detail_request = new MonthlyOtRequestDetail();
	    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
	    			$detail_request->daily_ot_request_id = $dailyrequest->id;
	    		}

	    		$detail_request->user_id = $user_id;
	    		$detail_request->branch = getUserFieldWithId($user_id,'branch_id');
	    		$detail_request->ot_type = $dailyrequest->ot_type;
	    		$detail_request->apply_date = $dailyrequest->apply_date;
	    		$detail_request->start_from_time = $dailyrequest->start_from_time;
	    		$detail_request->start_to_time = $dailyrequest->start_to_time;
	    		$detail_request->start_break_hour = $dailyrequest->start_break_hour;
	    		$detail_request->start_break_minute = $dailyrequest->start_break_minute;
	    		$detail_request->start_reason = $dailyrequest->start_reason;
	    		$detail_request->start_hotel = $dailyrequest->start_hotel;
	    		$detail_request->start_next_day = $dailyrequest->start_next_day;

	    		$detail_request->end_from_time = $dailyrequest->end_from_time;
	    		$detail_request->end_to_time = $dailyrequest->end_to_time;
	    		$detail_request->end_break_hour = $dailyrequest->end_break_hour;
	    		$detail_request->end_break_minute = $dailyrequest->end_break_minute;
	    		$detail_request->end_reason = $dailyrequest->end_reason;
	    		$detail_request->end_hotel = $dailyrequest->end_hotel;
	    		$detail_request->end_next_day = $dailyrequest->end_next_day;
	    		$detail_request->manager_status = 0;
	    		$detail_request->manager_status_reason = '';
	    		$detail_request->account_status = 0;
	    		$detail_request->account_status_reason = '';
	    		$detail_request->gm_status = 0;
	    		$detail_request->gm_status_reason = '';
	    		$detail_request->daily_request = 0;
	    		$detail_request->inactive = 0;

	    		$detail_request->save();


	    		$dailyrequest->monthly_request = 1;
	    		$dailyrequest->save();

	    	}			
			
			//$users = User::permission('change-ot-manager-status')->where("department_id","=",getUserFieldWithId($user_id,"department_id"))->get();
			$terms = explode(',',auth()->user()->department_id);
			$user_ids = array();
			foreach($terms as $term){
				$users=User::permission('change-ot-manager-status')->where(function($query) use($term) {
	                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
	                    
	            	})->pluck("id")->toArray();
				
		        $user_ids = array_merge($user_ids,$users);
			}
			$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
				
    		if($users){
    			foreach($users as $key=>$value){
    				if($value->noti_type=="email")
    					Mail::to($value->email)->send(new OTMonthlyRequestMail($value,$request_date,Auth::user(),"staff"));
    			}
    		}

			return $dailyrequests;

		});

		return $monthlyrequests;
		
	}


	
}