<?php
namespace App\Repositories\OTManagement;

use App\Models\AttendanceManagement\Attendance;
use App\Models\OTManagement\DailyOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequestDetail;
use App\Models\OTManagement\AnnualOtSummary;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyOTStatusMail;
use App\Mail\AccountAdminMail;
use App\Mail\ManagerAccountMail;
use DB;
use Carbon\Carbon;
//use DateTime;

class MonthlyDriverOTRequestRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(MonthlyDriverOtRequest $ot_request)
	{
		$this->model=$ot_request;
	}

	public function updateEndTime($input){
		$monthlyrequests= DB::transaction(function () use ($input) {

			//save chnage request
	    	
	    	$monthlyrequest = MonthlyDriverOtRequestDetail::findOrFail($input['id']);
			$monthlyrequest->end_from_time = format_dbtime($input['end_from_time']);
			$monthlyrequest->end_to_time = format_dbtime($input['end_to_time']);
			$monthlyrequest->end_break_hour = $input['end_break_hour']?$input['end_break_hour']:0;
			$monthlyrequest->end_break_minute = $input['end_break_minute']?$input['end_break_minute']:0;
			$monthlyrequest->end_reason = $input['end_reason'];
			$monthlyrequest->end_hotel = isset($input['start_hotel'])?1:0;
			$monthlyrequest->end_next_day = isset($input['start_next_day'])?1:0;

			$monthlyrequest->save();
							
			return $monthlyrequest;

		});

		return $monthlyrequests;
		
	}

	public function indexStaff($name,$order){

		if(Auth::user()->can('ot-read-all')){
			
			return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->orderBy($name,$order);
			
		}
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

			return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->whereIn('monthly_driver_ot_request_details.user_id',$user_ids)->orderBy($name,$order);
			
		}
		else{
			return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}

	public function indexOrderStaff($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$n){
		$query=$this->indexStaff("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_driver_ot_request_details','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_driver_ot_request_details.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_driver_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexDeptGM($name,$order){

		if(Auth::user()->can('ot-read-all')){
			
			if(Auth::user()->can('change-ot-admin-status')){
				return MonthlyDriverOtRequestDetail::select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->orderBy($name,$order);
			}
			else{
				return MonthlyDriverOtRequestDetail::select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
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

			if(Auth::user()->can('change-ot-admin-status')){
				return MonthlyDriverOtRequestDetail::select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->whereIn('monthly_driver_ot_request_details.user_id',$user_ids)->orderBy($name,$order);
			}
			
			else{
				return MonthlyDriverOtRequestDetail::select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return MonthlyDriverOtRequestDetail::select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}

	public function indexOrderDeptGM($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status="0",$n){
		$query=$this->indexDeptGM("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		//$query->leftJoin('monthly_driver_ot_request_details','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('monthly_driver_ot_requests','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_driver_ot_request_details.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_driver_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_driver_ot_requests.manager_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_driver_ot_requests.manager_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_driver_ot_requests.manager_main_status','=',0);
		}
		//$query->groupBy('monthly_ot_requests.id');
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexAccount($name,$order){

		if(Auth::user()->can('ot-read-all')){
			if(Auth::user()->can('change-ot-account-status')){
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			
			else{
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
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
			if(Auth::user()->can('change-ot-account-status')){
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->whereIn('monthly_driver_ot_request_details.user_id',$user_ids)->where('monthly_driver_ot_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrderAccount($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status=0,$n){
		$query=$this->indexAccount("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_driver_ot_request_details','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_driver_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_driver_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_driver_ot_requests.account_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_driver_ot_requests.account_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_driver_ot_requests.account_main_status','=',0);
		}
		//$query->groupBy('monthly_ot_requests.id');
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexAdminGM($name,$order){

		if(Auth::user()->can('ot-read-all')){
			if(Auth::user()->can('change-ot-gm-status')){
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_requests.manager_main_status','=',1)->where('monthly_driver_ot_requests.account_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
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
			if(Auth::user()->can('change-ot-gm-status')){
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->whereIn('monthly_driver_ot_request_details.user_id',$user_ids)->where('monthly_driver_ot_requests.manager_main_status','=',1)->where('monthly_driver_ot_requests.account_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_driver_ot_requests.id as m_id','monthly_driver_ot_requests.date','monthly_driver_ot_requests.user_id as userid','monthly_driver_ot_request_details.*')->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrderAdminGM($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status=0,$n){
		$query=$this->indexAdminGM("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_driver_ot_request_details','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_driver_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_driver_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_driver_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_driver_ot_requests.gm_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_driver_ot_requests.gm_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_driver_ot_requests.gm_main_status','=',0);
		}
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function changeStatusDeptGM($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];

			$all_morning_requests = isset($input['all_morning_request'])?$input['all_morning_request']:[];
			$all_evening_requests = isset($input['all_evening_request'])?$input['all_evening_request']:[];

			$overtime = array();
			
			$monthlyrequest = MonthlyDriverOtRequest::findOrFail($m_id);

			
			$monthlyrequest->manager_reason = $reason;
			$monthlyrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyDriverOtRequestDetail::findOrFail($id);

		    		
					$detailrequest->manager_status = $status;
					$detailrequest->manager_status_reason = $reason;
					$detailrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->manager_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];
					if($status==2){
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

					}
					
	    		}

	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyDriverOtRequestDetail::findOrFail($id);

		    		
					$detailrequest->manager_status = $status;
					$detailrequest->manager_status_reason = $reason;
					$detailrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->manager_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];
					if($status==2){
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

					}
					
	    		}

	    		if(in_array($id, $all_morning_requests)){
	    			$detailrequest->morning_taxi_time = 1;
	    			$detailrequest->save();
	    		}
	    		if(in_array($id, $all_evening_requests)){
	    			$detailrequest->evening_taxi_time = 1;
	    			$detailrequest->save();
	    		}

	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "OT Status:\n";
    			foreach($overtime as $key=>$value){
    				$status_name = $value['status']==1?'Accept':'Reject';
    				$message .= $value['date']."-".$value['ot_type']."-".$status_name;
    				if(count($overtime)!=($key+1)){
    					$message .= "&";
    				}
    			}
    			sendSMS($user->phone,$message);
    		}

	    	//change main status
	    	
			$detail = MonthlyDriverOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["manager_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->manager_main_status = 1;
				$monthlyrequest->save();

				//mail to account
				$users = User::permission('change-ot-account-status')->get();
		    	if($users){
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'manager',"driver"));
		    		}
		    	}
			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->save();
			}
			
			
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	public function changeStatusAccount($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyDriverOtRequest::findOrFail($m_id);

			$monthlyrequest->account_reason = $reason;
			$monthlyrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyDriverOtRequestDetail::findOrFail($id);

	    		
					$detailrequest->account_status = $status;
					$detailrequest->account_status_reason = $reason;
					$detailrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->account_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						
					}
	    		}
	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyDriverOtRequestDetail::findOrFail($id);

	    		
					$detailrequest->account_status = $status;
					$detailrequest->account_status_reason = $reason;
					$detailrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->account_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						
					}
	    		}
	    			
	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "OT Status:\n";
    			foreach($overtime as $key=>$value){
    				$status_name = $value['status']==1?'Accept':'Reject';
    				$message .= $value['date']."-".$value['ot_type']."-".$status_name;
    				if(count($overtime)!=($key+1)){
    					$message .= "&";
    				}
    			}
    			sendSMS($user->phone,$message);
    		}

	    	//change main status
	    	
			$detail = MonthlyDriverOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["account_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->account_main_status = 1;
				$monthlyrequest->save();

					//mail to gm
				$users = User::permission('change-ot-gm-status')->get();
		    	if($users){
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'accountant',"driver"));
		    		}
		    	}
			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->account_main_status = 0;
				$monthlyrequest->save();

				$detail = MonthlyDriverOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["account_status","=",2]])->get();
					//return mail to manager
				//$users = User::permission('change-ot-admin-status')->get();
				$terms = explode(',',$user->department_id);
				$user_ids = array();
				foreach($terms as $term){
					$users=User::permission('change-ot-admin-status')->where(function($query) use($term) {
		                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		            	})->pluck("id")->toArray();
					
			        $user_ids = array_merge($user_ids,$users);
				}
				$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
				
		    	if($users){
		    		$applicant = User::findOrFail($monthlyrequest->user_id);
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'accountant',"driver"));
		    		}
		    	}
			}
				
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	public function changeStatusAdminGM($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyDriverOtRequest::findOrFail($m_id);

			$monthlyrequest->gm_reason = $reason;
			$monthlyrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyDriverOtRequestDetail::findOrFail($id);

	    			$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->account_status = 2;
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						
					}
	    		}
	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyDriverOtRequestDetail::findOrFail($id);

	    			$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->account_status = 2;
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						
					}
	    		}

	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "OT Status:\n";
    			foreach($overtime as $key=>$value){
    				$status_name = $value['status']==1?'Accept':'Reject';
    				$message .= $value['date']."-".$value['ot_type']."-".$status_name;
    				if(count($overtime)!=($key+1)){
    					$message .= "&";
    				}
    			}
    			sendSMS($user->phone,$message);
    		}

	    	//change main status
	    	$detail = MonthlyDriverOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->gm_main_status = 1;
				$monthlyrequest->save();

				//calculate ot for attendance table
				$otdetail = MonthlyDriverOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",1]])->get();
				foreach($otdetail as $key=>$detailrequest)
				{
					$attendance = Attendance::where([['user_id','=',$detailrequest->user_id],['date','=',$detailrequest->apply_date]])->first();
					if($attendance){

						$ot_hr = getOTHour($detailrequest->id,"driver");
						if($detailrequest->ot_type=='Weekday'){
							$attendance->normal_ot_hr += $ot_hr;
						}
						else if($detailrequest->ot_type=='Saturday'){
							$attendance->sat_ot_hr += $ot_hr;
						}
						else if($detailrequest->ot_type=='Sunday'){
							$attendance->sunday_ot_hr += $ot_hr;
						}
						else {
							$attendance->public_holiday_ot_hr += $ot_hr;
						}
						$attendance->ot_request_date = $detailrequest->created_at;
						$attendance->ot_approve_date = $detailrequest->gm_change_date;
						$attendance->ot_approve_by = $detailrequest->gm_change_by;
						$attendance->save();
					}
					else{
						if($detailrequest->end_from_time){
							$ot_start_time = $detailrequest->end_from_time; 
							$ot_end_time = $detailrequest->end_to_time;
							$breaktime = $detailrequest->end_break_hour.":".$detailrequest->end_break_minute;
						}
						else{
							$ot_start_time = $detailrequest->start_from_time; 
							$ot_end_time = $detailrequest->start_to_time;
							$breaktime = $detailrequest->start_break_hour.":".$detailrequest->start_break_minute;
						}

						$ot_hr = getOTHour($detailrequest->id,"driver");

						$attendance = new Attendance();
						$attendance->device = "OT";
						$attendance->device_ip = $ip;
						$attendance->device_serial = "OT";
						$attendance->user_id = $detailrequest->user_id;
						$attendance->profile_id = getUserFieldWithId($detailrequest->user_id,'profile_id');
						$attendance->branch_id = getUserFieldWithId($detailrequest->user_id,'branch_id');
						$attendance->date = $detailrequest->apply_date;
						$attendance->start_time = $ot_start_time;
						$attendance->end_time = $ot_end_time;
						$attendance->type = 'OT Type';
						if($detailrequest->ot_type=="Weekday"){
							$attendance->normal_ot_hr = $ot_hr;
							$attendance->type_id = 0;
						}
						else if($detailrequest->ot_type=="Saturday"){
							$attendance->sat_ot_hr = $ot_hr;
							$attendance->type_id = 1;
						}
						else if($detailrequest->ot_type=='Sunday'){
							$attendance->sunday_ot_hr = $ot_hr;
							$attendance->type_id = 2;
						}
						else {
							$attendance->public_holiday_ot_hr = $ot_hr;
							$attendance->type_id = 3;
						}
						$working_start_time = getUserFieldWithId($detailrequest->user_id,'working_start_time');
						$working_end_time = getUserFieldWithId($detailrequest->user_id,'working_end_time');
						$attendance->corrected_start_time = $working_start_time?$working_start_time:$ot_start_time;
						$attendance->corrected_end_time = $working_end_time?$working_end_time:$ot_end_time;

						$attendance->ot_request_date = $detailrequest->created_at;
						$attendance->ot_approve_date = $detailrequest->gm_change_date;
						$attendance->ot_approve_by = $detailrequest->gm_change_by;
						$attendance->created_by = Auth::user()->id;
						$attendance->save();
								
					}

					//update annual ot summary
					$year = Carbon::parse($detailrequest->apply_date)->format('Y');
					$month = Carbon::parse($detailrequest->apply_date)->format('F');
		    		$month = strtolower($month);

		    		if($month=='january' || $month=="february" ||$month=="march")
					    $year = $year-1;

					$annualotsummary = AnnualOtSummary::where([['user_id','=',$detailrequest->user_id],['year','=',$year]])->first();

					$ot_hr = getOTHour($detailrequest->id,"driver");
					if($annualotsummary){
					    $annualotsummary->$month = $annualotsummary->$month + $ot_hr;
					    $annualotsummary->save();
					}
					else{
					    $annualotsummary = new AnnualOtSummary();
					    $annualotsummary->user_id = $detailrequest->user_id;
					    $annualotsummary->branch = $detailrequest->branch;
					    $annualotsummary->$month = $ot_hr;
					    $annualotsummary->year = $year;
					    $annualotsummary->save();
					}
				}

			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->account_main_status = 0;
				$monthlyrequest->save();
					
				$detail = MonthlyDriverOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",2]])->get();
				//return mail to manager
				//$users = User::permission('change-ot-admin-status')->get();
				$terms = explode(',',$user->department_id);

				$user_ids = array();
				foreach($terms as $term){
					$users=User::permission('change-ot-admin-status')->where(function($query) use($term) {
		                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		            	})->pluck("id")->toArray();
					
			        $user_ids = array_merge($user_ids,$users);
				}
				$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
		    	if($users){
		    		$applicant = User::findOrFail($monthlyrequest->user_id);
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'gm-manager',"driver"));
		    		}
		    	}
		    	$users = User::permission('change-ot-account-status')->get();
		    	if($users){
		    		$applicant = User::findOrFail($monthlyrequest->user_id);
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'gm-account',"driver"));
		    		}
		    	}
			}

				
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	
	
}