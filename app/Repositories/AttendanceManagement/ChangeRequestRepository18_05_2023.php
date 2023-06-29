<?php
namespace App\Repositories\AttendanceManagement;

use App\Models\AttendanceManagement\Attendance;
use App\Models\AttendanceManagement\AttendanceTemp;
use App\Models\AttendanceManagement\ChangeRequest;
use App\Models\AttendanceManagement\AttendanceLog;
use App\Models\AttendanceManagement\RawAtt;
use App\Models\AttendanceManagement\Late;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Helpers\OTHelper;
use Stevebauman\Location\Facades\Location;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangeRequestMail;
use App\Mail\ChangeStatusMail;
use App\Mail\AttendanceCancelMail;
//use DateTime;

class ChangeRequestRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(ChangeRequest $change_request)
	{
		$this->model=$change_request;
	}

	public function index($name,$order){
		if(Auth::user()->can('attendance-read-all')){
			return $this->model->select('change_requests.*')->orderBy($name,$order);
		}
		else if(Auth::user()->can('attendance-read-group')){
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();

		        
		        $user_ids = array_merge($user_ids,$users);
		        
		        
		    }
			return $this->model->select('change_requests.*')->whereIn('user_id',$user_ids)->orderBy($name,$order);
		}
		else{
			return $this->model->select('change_requests.*')->where('user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
	}
	

	public function indexOrder($name,$order,$from_date=null,$to_date=null,$employee=null,$status="0",$n){
		$query=$this->index($name,$order);
		
		if($from_date!=null){
            $query->whereRaw( " CAST(change_requests.requested_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
            $query->whereRaw( " CAST(change_requests.requested_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('change_requests.user_id','=',$employee);
			});
		}

		if($status){
			if($status=="all"){
				$query->whereIn('change_requests.status',[0,1,2]);
			}
			else{
				$query->where('change_requests.status','=',$status);
			}
			
		}
		else{
			$query->where('change_requests.status','=',0);
		}
		//$query->orderBy('att_UserID','asc');
		
		//return $query->paginate($n);
		return $query->get();
	}

	
	public function store($input){
		$changes= DB::transaction(function () use ($input) {

			//save chnage request
	    	
	    	$change = new ChangeRequest();
			$change->user_id = Auth::user()->id;
			$change->actual_date = format_dbdate($input['changing_date']);
			$change->changing_date = format_dbdate($input['changing_date']);
			if($input['changing_start_time'])
				$change->changing_start_time = format_dbtime($input['changing_start_time']);
			if($input['changing_end_time'])
				$change->changing_end_time = format_dbtime($input['changing_end_time']);;
			$change->requested_date = Carbon::now()->format('Y-m-d H:i:s');
			$change->reason_of_correction = $input['reason_of_correction'];

			$change->created_by = Auth::user()->id;
			$change->save();

			$terms = explode(',',auth()->user()->department_id);

			$users=User::permission('attendance-change-status')->where(function($query) use($terms) {
                    foreach($terms as $term) {
                        $query->whereRaw("find_in_set('".$term."',users.department_id)");
                    };
            	})->select('users.email','users.employee_name','users.noti_type')->get();

			//$users = User::permission('attendance-change-status')->where("department_id","=",getUserFieldWithId($change->user_id,"department_id"))->get();
    		if($users){
    			foreach($users as $key=>$value){
    				if($value->noti_type=="email")
    					Mail::to($value->email)->send(new ChangeRequestMail($value,$change));
    			}
    		}
							
			return $change;

		});

		return $changes;
		
	}

	public function changeStatus($input,$status,$ip){
		
	    $changestatuses= DB::transaction(function () use ($input,$status,$ip) {
			
			$change=ChangeRequest::whereId($input['id'])->first();

			$change->status = $status;
			if($input["status_reason"])
				$change->status_reason = $input["status_reason"];
			$change->status_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$change->status_change_by = Auth::user()->id;
			$change->save();

			if($status==1){
				$attendance = Attendance::where([['user_id','=',$change->user_id],['date','=',$change->actual_date]])->first();
				if($attendance){

					$temp = new AttendanceTemp();
					$temp->change_request_id = $change->id;
					$temp->user_id = $attendance->user_id;
					$temp->date = $attendance->date;
					$temp->start_time = $attendance->start_time;
					$temp->end_time = $attendance->end_time;
					if($change->changing_start_time)
						$temp->time_in = 1;
					if($change->changing_end_time)
						$temp->time_out = 1;
					$temp->save();

					$attendance->date= $change->changing_date;

					if ($change->changing_start_time and strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){

						$holiday = isLateRecord($attendance);
						if($holiday==0){
							$year = Carbon::parse($attendance->date)->format('Y');
							$month = Carbon::parse($attendance->date)->format('F');
							$month = strtolower($month);
							if($month=='january' || $month=="february" ||$month=="march")
					        	$year = $year-1;
					        $late = Late::where([['user_id','=',$attendance->user_id],['year','=',$year]])->first();

					        if($late){
					        	$late->$month = $late->$month - 1;
					        	$late->save();
					        }
						}
							
				    }
				    if($change->changing_start_time)
						$attendance->start_time = $change->changing_start_time;
					if($change->changing_end_time)
						$attendance->end_time = $change->changing_end_time;
					$attendance->remark = $change->reason_of_correction;
					$attendance->change_request_date = $change->requested_date;
					$attendance->change_approve_date = $change->status_change_date;
					$attendance->change_approve_by = $change->status_change_by;
					$attendance->save();

					$current_date = Carbon::now()->subMonth()->format("Y-m");
    				$attendance_date = Carbon::parse($attendance->date)->format('Y-m');
    				if($current_date==$attendance_date){
    					if(isRentalDriver($attendance->user_id)){
    						OTHelper::updateMonthlyOTRental($attendance);
    					}
    					else if(isDriver($attendance->user_id)){
    						OTHelper::updateMonthlyOTDriver($attendance);
    					}
    					else if(isAssistant($attendance->user_id)){
    						OTHelper::updateMonthlyOTAssistant($attendance);
    					}
    					
    				}

					if ($change->changing_start_time and strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){

						$holiday = isLateRecord($attendance);
						if($holiday==0){
							$year = Carbon::parse($attendance->date)->format('Y');
							$month = Carbon::parse($attendance->date)->format('F');
		    				$month = strtolower($month);
							if($month=='january' || $month=="february" ||$month=="march")
					        	$year = $year-1;
					        $late = Late::where([['user_id','=',$attendance->user_id],['year','=',$year]])->first();

					        
					        if($late){
					        	$late->$month = $late->$month + 1;
					        	$late->save();
					        }
					        else{
					        	$late = new Late();
					        	$late->user_id = $attendance->user_id;
					        	$late->$month = 1;
					        	$late->year = $year;
					        	$late->save();
					        }
						}
							
				    }
				}
				else{
					$attendance = new Attendance();
					$attendance->device = "Change Request";
					$attendance->device_ip = $ip;
					$attendance->device_serial = "Change Request";
					$attendance->user_id = $change->user_id;
					$attendance->profile_id = getUserFieldWithId($change->user_id,'profile_id');
					$attendance->branch_id = getUserFieldWithId($change->user_id,'branch_id');
					$attendance->date = $change->changing_date;
					$attendance->start_time = $change->changing_start_time?$change->changing_start_time:$change->changing_end_time;
					$attendance->end_time = $change->changing_end_time;
					$attendance->type = 'Working Day';
					$attendance->type_id = 0;
						
					$working_start_time = getUserFieldWithId($change->user_id,'working_start_time');
					$working_end_time = getUserFieldWithId($change->user_id,'working_end_time');
					if($working_start_time)
						$attendance->corrected_start_time = $working_start_time;
					else{
						$attendance->corrected_start_time = $change->changing_start_time?$change->changing_start_time:$change->changing_end_time;
					}

					if($working_end_time)
						$attendance->corrected_end_time = $working_end_time;
					else{
						$attendance->corrected_end_time = $change->changing_end_time?$change->changing_end_time:$change->changing_start_time;
					}

					$attendance->remark = $change->reason_of_correction;
					$attendance->change_request_date = $change->requested_date;
					$attendance->change_approve_date = $change->status_change_date;
					$attendance->change_approve_by = $change->status_change_by;

					$attendance->created_by = Auth::user()->id;
					$attendance->save();

					$current_date = Carbon::now()->subMonth()->format("Y-m");
    				$attendance_date = Carbon::parse($attendance->date)->format('Y-m');
    				if($current_date==$attendance_date){
    					if(isRentalDriver($attendance->user_id)){
    						OTHelper::updateMonthlyOTRental($attendance);
    					}
    					else if(isDriver($attendance->user_id)){
    						OTHelper::updateMonthlyOTDriver($attendance);
    					}
    					else if(isAssistant($attendance->user_id)){
    						OTHelper::updateMonthlyOTAssistant($attendance);
    					}
    					
    				}

					if ($change->changing_start_time and strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){

						$holiday = isLateRecord($attendance);
						if($holiday==0){
							$year = Carbon::parse($attendance->date)->format('Y');
							$month = Carbon::parse($attendance->date)->format('F');
		    				$month = strtolower($month);
							if($month=='january' || $month=="february" ||$month=="march")
					        	$year = $year-1;
					        $late = Late::where([['user_id','=',$attendance->user_id],['year','=',$year]])->first();

					        
					        if($late){
					        	$late->$month = $late->$month + 1;
					        	$late->save();
					        }
					        else{
					        	$late = new Late();
					        	$late->user_id = $attendance->user_id;
					        	$late->$month = 1;
					        	$late->year = $year;
					        	$late->save();
					        }
						}
							
				    }
				}

					
			}
	    	
			$user = User::findOrFail($change->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new ChangeStatusMail($user,$change));
    		else{
    			$message = "Attendance Change:\n";
    			if($status==1)
    				$message .= "Accept your Request for ".$change->changing_date;
    			else
    				$message .= "Reject your Request for ".$change->changing_date;
    			if($change->status_reason)
    				$message .= "\nReason:".$change->status_reason;
    			
    			sendSMS($user->phone,$message);
    		}

			return $change;

		});

		return $changestatuses;


	}

	public function destroy($input){
		
	    $changestatuses= DB::transaction(function () use ($input) {
			
			$change=ChangeRequest::whereId($input['id'])->first();
						
			//$users = User::permission('attendance-change-status')->where("department_id","=",getUserFieldWithId($change->user_id,"department_id"))->get();
			$terms = explode(',',auth()->user()->department_id);

			$users=User::permission('attendance-change-status')->where(function($query) use($terms) {
                    foreach($terms as $term) {
                        $query->whereRaw("find_in_set('".$term."',users.department_id)");
                    };
            	})->select('users.email','users.employee_name','users.noti_type')->get();
		    if($users){
		        foreach($users as $key=>$value){
		            if($value->noti_type=="email")
		                Mail::to($value->email)->send(new AttendanceCancelMail($value,$change,$input["reason"]));
		        }
		    }

		    $change->delete();
			


			return "success";

		});

		return $changestatuses;


	}


	
}