<?php
namespace App\Repositories\OTManagement;

use App\Models\AttendanceManagement\Attendance;
use App\Models\OTManagement\DailyOtRequest;
use App\Models\OTManagement\MonthlyReceptionistRequest;
use App\Models\OTManagement\MonthlyReceptionistRequestDetail;
use App\Models\OTManagement\AnnualOtSummary;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyReceptionistStatusMail;
use App\Mail\ReceptionistAdminMail;
use App\Mail\ReceptionistManagerMail;
use DB;
use Carbon\Carbon;
//use DateTime;

class MonthlyReceptionistRequestRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(MonthlyReceptionistRequest $ot_request)
	{
		$this->model=$ot_request;
	}

	public function updateEndTime($input){
		$monthlyrequests= DB::transaction(function () use ($input) {

			//save chnage request
	    	
	    	$monthlyrequest = MonthlyReceptionistRequestDetail::findOrFail($input['id']);
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
			
			return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->orderBy($name,$order);
			
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

			return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->whereIn('monthly_receptionist_request_details.user_id',$user_ids)->orderBy($name,$order);
			
		}
		else{
			return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}

	public function indexOrderStaff($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$n){
		$query=$this->indexStaff("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_receptionist_request_details','monthly_receptionist_requests.id','=','monthly_receptionist_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_receptionist_request_details.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_receptionist_requests.user_id','=',$employee);
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
				return MonthlyReceptionistRequestDetail::select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->orderBy($name,$order);
			}
			else{
				return MonthlyReceptionistRequestDetail::select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
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
				return MonthlyReceptionistRequestDetail::select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->whereIn('monthly_receptionist_request_details.user_id',$user_ids)->orderBy($name,$order);
			}
			
			else{
				return MonthlyReceptionistOtRequestDetail::select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return MonthlyReceptionistOtRequestDetail::select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}

	public function indexOrderDeptGM($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status="0",$n){
		$query=$this->indexDeptGM("apply_date","asc");
		
        $query->leftJoin('monthly_receptionist_requests','monthly_receptionist_requests.id','=','monthly_receptionist_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_receptionist_request_details.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_receptionist_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_receptionist_requests.manager_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_receptionist_requests.manager_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_receptionist_requests.manager_main_status','=',0);
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
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			
			else{
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
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
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->whereIn('monthly_receptionist_request_details.user_id',$user_ids)->where('monthly_receptionist_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrderAccount($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status=0,$n){
		$query=$this->indexAccount("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_receptionist_request_details','monthly_receptionist_requests.id','=','monthly_receptionist_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_receptionist_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_receptionist_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_receptionist_requests.account_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_receptionist_requests.account_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_receptionist_requests.account_main_status','=',0);
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
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
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
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->whereIn('monthly_receptionist_request_details.user_id',$user_ids)->where('monthly_receptionist_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_receptionist_requests.id as m_id','monthly_receptionist_requests.date','monthly_receptionist_requests.user_id as userid','monthly_receptionist_request_details.*')->where('monthly_receptionist_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrderAdminGM($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status=0,$n){
		$query=$this->indexAdminGM("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_receptionist_request_details','monthly_receptionist_requests.id','=','monthly_receptionist_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_receptionist_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_receptionist_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_receptionist_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_receptionist_requests.gm_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_receptionist_requests.gm_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_receptionist_requests.gm_main_status','=',0);
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


			$overtime = array();
			
			$monthlyrequest = MonthlyReceptionistRequest::findOrFail($m_id);

			
			$monthlyrequest->manager_reason = $reason;
			$monthlyrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyReceptionistRequestDetail::findOrFail($id);

		    		
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
	    			$detailrequest=MonthlyReceptionistRequestDetail::findOrFail($id);

		    		
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

	    	}		

	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyReceptionistStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "Attendance Status:\n";
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
	    	
			$detail = MonthlyReceptionistRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["manager_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->manager_main_status = 1;
				$monthlyrequest->save();

				//mail to account
				$users = User::permission('change-ot-gm-status')->get();
		    	if($users){
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ReceptionistAdminMail($value,$monthlyrequest,Auth::user()->id,'manager',"driver"));
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
	
	public function changeStatusAdminGM($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyReceptionistRequest::findOrFail($m_id);

			$monthlyrequest->gm_reason = $reason;
			$monthlyrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyReceptionistRequestDetail::findOrFail($id);

	    			$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						//$detailrequest->account_status = 2;
						$detailrequest->attendance = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						
					}
	    		}
	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyReceptionistRequestDetail::findOrFail($id);

	    			$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						//$detailrequest->account_status = 2;
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
    			Mail::to($user->email)->send(new MonthlyReceptionistStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "Attendance Status:\n";
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
	    	$detail = MonthlyReceptionistRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->gm_main_status = 1;
				$monthlyrequest->save();

			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->save();
					
				$detail = MonthlyReceptionistRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",2]])->get();
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
		    				Mail::to($value->email)->send(new ReceptionistManagerMail($value,$detail,Auth::user()->id,$applicant,'gm-manager',"driver"));
		    		}
		    	}
		    	
			}

				
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	
	
}