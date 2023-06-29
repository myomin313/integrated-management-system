<?php
namespace App\Repositories\AttendanceManagement;

use App\Models\AttendanceManagement\Attendance;
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
use Stevebauman\Location\Facades\Location;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangeRequestMail;
use App\Mail\ChangeStatusMail;
//use DateTime;

class ChangeRequestRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(ChangeRequest $change_request)
	{
		$this->model=$change_request;
	}

	public function index($name,$order){
		
		return $this->model->select('change_requests.*')->orderBy($name,$order);
		
	}
	

	public function indexOrder($name,$order,$from_date=null,$to_date=null,$employee=null,$n){
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

			$users = User::permission('attendance-change-status')->get();
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

	public function changeStatus($input,$status){
		
	    $changestatuses= DB::transaction(function () use ($input,$status) {
			
			$change=ChangeRequest::whereId($input['id'])->first();

			$change->status = $status;
			$change->status_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$change->status_change_by = Auth::user()->id;
			$change->save();

			if($status==1){
				$attendance = Attendance::where([['user_id','=',$change->user_id],['date','=',$change->actual_date]])->first();
				if($attendance){
					$attendance->date= $change->changing_date;

					if ($change->changing_start_time and strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){
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
				    if($change->changing_start_time)
						$attendance->start_time = $change->changing_start_time;
					if($change->changing_end_time)
						$attendance->end_time = $change->changing_end_time;
					$attendance->remark = $change->reason_of_correction;
					$attendance->change_request_date = $change->requested_date;
					$attendance->change_approve_date = $change->status_change_date;
					$attendance->change_approve_by = $change->status_change_by;
					$attendance->save();

					if ($change->changing_start_time and strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){
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
	    	
			$user = User::findOrFail($change->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new ChangeStatusMail($user,$change));

			return $change;

		});

		return $changestatuses;


	}


	
}