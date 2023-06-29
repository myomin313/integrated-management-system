<?php
namespace App\Repositories;

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
use App\Helpers\OTHelper;
use App\Models\MasterManagement\EmployeeType;
use App\Models\MasterManagement\Holiday;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTMonthlyRequestMail;
use App\Mail\ChangeRequestMail;

class HomeRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(Attendance $attendance,AttendanceLog $attendance_log)
	{
		$this->model=$attendance;
		$this->model_log=$attendance_log;
	}
	
	public function store($ip,$ot=0){
		$rawatts= DB::transaction(function () use ($ip,$ot) {

			//save raw attendance

	    	$rawatt = new RawAtt();
			$rawatt->att_UserID = Auth::user()->profile_id?Auth::user()->profile_id:0;
			$rawatt->user_id = Auth::user()->id;
			$rawatt->att_ip = $ip;
			$rawatt->att_serial = 'Mobile';
			$rawatt->att_Date = Carbon::now()->format('Y-m-d H:i:s');
			$rawatt->branch = Auth::user()->branch_id;
			$rawatt->reason = '';
			$rawatt->att_UpdateTime = $rawatt->att_Date;
			$rawatt->att_CreateTime = $rawatt->att_Date;

			$rawatt->save();

			$date = siteformat_date($rawatt->att_Date);
			$time = siteformat_time($rawatt->att_Date);
			//save attendance
			$att = Attendance::where([['date','=',format_dbdate($date)],['user_id','=',$rawatt->user_id],['branch_id','=',$rawatt->branch]])->first();
			if($att){
				$att->user_id=$rawatt->user_id;
				$att->date = format_dbdate($date);
				$att->corrected_start_time=Auth::user()->working_start_time;
				$att->corrected_end_time=Auth::user()->working_end_time;
				if($att->start_time=="00:00:00"){
					$att->start_time=format_dbtime($time);
					if (strtotime($att->start_time) > strtotime($att->corrected_start_time)){

						$holiday = isLateRecord($att);
						if($holiday==0){
							$year = Carbon::parse($att->date)->format('Y');
							$month = Carbon::parse($att->date)->format('F');
	    					$month = strtolower($month);

		    				if($month=='january' || $month=="february" ||$month=="march")
					        		$year = $year-1;

				        	$late = Late::where([['user_id','=',$att->user_id],['year','=',$year]])->first();

					        if($late){
					        	$late->$month = $late->$month + 1;
					        	$late->save();
					        }
					        else{
					        	$late = new Late();
					        	$late->user_id = $att->user_id;
					        	$late->$month = 1;
					        	$late->year = $year;
					        	$late->save();
					        }

						}
						
			    	}
			    	if($ot){
						$att->morning_ot = 1;
					}
				}
				else{
					$att->end_time=format_dbtime($time);
					if($ot){
						$att->evening_ot = 1;
					}
				}
				$att->branch_id=$rawatt->branch;
				//$att->manual_remark=$att->manual_remark.','.$input['reason'];

				if($rawatt->att_ip!="::1"){
					$currentUserInfo = Location::get($rawatt->att_ip);
					$att->latitude=$att->latitude.','.$currentUserInfo->latitude;
					$att->longitude=$att->longitude.','.$currentUserInfo->longitude;
				}
				
				$att->save();
			}
			else{
				$att=$this->model;
				$att->device='Mobile';
				$att->device_ip=$rawatt->att_ip;
				$att->device_serial=$rawatt->att_serial;
				
				$att->user_id=$rawatt->user_id;
				$att->profile_id=$rawatt->att_UserID;
				$att->date = format_dbdate($date);
				$att->start_time=format_dbtime($time);

				$att->type='Working Day';
				$att->type_id=0;

				$att->branch_id=$rawatt->branch;
				$att->manual_remark='';
				if($rawatt->att_ip!="::1"){
					$currentUserInfo = Location::get($rawatt->att_ip);
					$att->latitude=$currentUserInfo->latitude;
					$att->longitude=$currentUserInfo->longitude;
				}
				$att->corrected_start_time=Auth::user()->working_start_time;
				$att->corrected_end_time=Auth::user()->working_end_time;
				$att->created_by=Auth::user()->id;

				if($ot){
					$att->morning_ot = 1;
				}
				
				$att->save();

				if (strtotime($att->start_time) > strtotime($att->corrected_start_time)){

					$holiday = isLateRecord($att);
					if($holiday==0){
						$year = Carbon::parse($att->date)->format('Y');
						$month = Carbon::parse($att->date)->format('F');
	    				$month = strtolower($month);

	    				if($month=='january' || $month=="february" ||$month=="march")
				        		$year = $year-1;

				        $late = Late::where([['user_id','=',$att->user_id],['year','=',$year]])->first();

				        if($late){
				        	$late->$month = $late->$month + 1;
				        	$late->save();
				        }
				        else{
				        	$late = new Late();
				        	$late->user_id = $att->user_id;
				        	$late->$month = 1;
				        	$late->year = $year;
				        	$late->save();
				        }

					}
						
			    }

			}
					
			return $rawatt;

		});

		return $rawatts;
		
	}
	//mobile_att
	// public function store($ip){
	// 	$rawatts= DB::transaction(function () use ($ip) {

	// 		//save raw attendance

	//     	$rawatt = new MobileAtt();
	// 		$rawatt->user_id = Auth::user()->id;
	// 		$rawatt->att_ip = $ip;
	// 		$rawatt->att_serial = 'Mobile';
	// 		$rawatt->att_Date = Carbon::now()->format('Y-m-d H:i:s');
	// 		$rawatt->branch = Auth::user()->branch_id;
	// 		$rawatt->reason = '';

	// 		$rawatt->save();

	// 		$date = siteformat_date($rawatt->att_Date);
	// 		$time = siteformat_time($rawatt->att_Date);
	// 		//save attendance
	// 		$att = Attendance::where([['date','=',format_dbdate($date)],['user_id','=',$rawatt->user_id],['branch_id','=',$rawatt->branch]])->first();
	// 		if($att){
	// 			$att->user_id=$rawatt->user_id;
	// 			$att->date = format_dbdate($date);
	// 			$att->end_time=format_dbtime($time);
	// 			$att->branch_id=$rawatt->branch;
	// 			//$att->manual_remark=$att->manual_remark.','.$input['reason'];

	// 			if($rawatt->att_ip!="::1"){
	// 				$currentUserInfo = Location::get($rawatt->att_ip);
	// 				$att->latitude=$att->latitude.','.$currentUserInfo->latitude;
	// 				$att->longitude=$att->longitude.','.$currentUserInfo->longitude;
	// 			}
	// 			$att->corrected_start_time=Auth::user()->working_start_time;
	// 			$att->corrected_end_time=Auth::user()->working_end_time;
	// 			$att->save();
	// 		}
	// 		else{
	// 			$att=$this->model;
	// 			$att->device='Mobile';
	// 			$att->device_ip=$rawatt->att_ip;
	// 			$att->device_serial=$rawatt->att_serial;
				
	// 			$att->user_id=$rawatt->user_id;
	// 			$att->profile_id=Auth::user()->profile_id;
	// 			$att->date = format_dbdate($date);
	// 			$att->start_time=format_dbtime($time);

	// 			$att->type='Working Day';
	// 			$att->type_id=0;

	// 			$att->branch_id=$rawatt->branch;
	// 			$att->manual_remark='';
	// 			if($rawatt->att_ip!="::1"){
	// 				$currentUserInfo = Location::get($rawatt->att_ip);
	// 				$att->latitude=$currentUserInfo->latitude;
	// 				$att->longitude=$currentUserInfo->longitude;
	// 			}
	// 			$att->corrected_start_time=Auth::user()->working_start_time;
	// 			$att->corrected_end_time=Auth::user()->working_end_time;
	// 			$att->created_by=Auth::user()->id;
				
	// 			$att->save();

	// 			if (strtotime($att->start_time) > strtotime($att->corrected_start_time)){
	// 				$year = Carbon::parse($att->date)->format('Y');
	// 				$month = Carbon::parse($att->date)->format('F');
    // 				$month = strtolower($month);

    // 				if($month=='january' || $month=="february" ||$month=="march")
	// 		        		$year = $year-1;

	// 		        $late = Late::where([['user_id','=',$att->user_id],['year','=',$year]])->first();

	// 		        if($late){
	// 		        	$late->$month = $late->$month + 1;
	// 		        	$late->save();
	// 		        }
	// 		        else{
	// 		        	$late = new Late();
	// 		        	$late->user_id = $att->user_id;
	// 		        	$late->$month = 1;
	// 		        	$late->year = $year;
	// 		        	$late->save();
	// 		        }
	// 		    }

	// 		}
					
	// 		return $rawatt;

	// 	});

	// 	return $rawatts;
		
	// }
	public function otRequest($ip){
		$ots= DB::transaction(function () use ($ip) {

			//save attendance
			$att = Attendance::where([['date','=',Carbon::now()->format("Y-m-d")],['user_id','=',Auth::user()->id]])->first();
			if($att){
				$current_time = Carbon::now()->format("H:i");
				if(strtotime($current_time)>strtotime("12:00"))
					$att->evening_ot = 1;
				else
					$att->morning_ot = 1;
				$att->save();
			}
			
					
			return $att;

		});

		return $ots;
		
	}

	public function storeMonthlyOT(){
		$date = Carbon::now()->subMonth()->format('Y-m');
        
        $rental_drivers = EmployeeType::where("type","like","%Rental Driver%")->pluck('id');

        $drivers = EmployeeType::where("type","not like","%Rental Driver%")->where("type","like","%Driver%")->pluck('id');

        $assistant = EmployeeType::where("type","like","%Assistant%")->pluck('id');

        $rental_user = User::whereIn("employee_type_id",$drivers)->pluck('id');

        $driver_user = User::whereIn("employee_type_id",$drivers)->pluck('id');
        $assistant_user = User::whereIn("employee_type_id",$assistant)->pluck('id');
        DB::beginTransaction();
            OTHelper::storeMonthlyOTRental($date,$rental_user);
            OTHelper::storeMonthlyOTDriver($date,$driver_user);
            OTHelper::storeMonthlyOTAssistant($date,$assistant_user);
        DB::commit();


        $terms = User::whereIn("employee_type_id",$rental_drivers)->orWhereIn("employee_type_id",$drivers)->orWhereIn("employee_type_id",$assistant)->groupBy("department_id")->pluck('department_id');
        //$users = User::permission('change-ot-admin-status')->whereIn("department_id",$department_ids)->get();
        $users=User::permission('change-ot-admin-status')->where(function($query) use($terms) {
                    foreach($terms as $term) {
                        $query->whereRaw("find_in_set('".$term."',users.department_id)");
                    };
            	})->select('users.email','users.employee_name','users.noti_type')->get();

    	if($users){
    		foreach($users as $key=>$value){
    			if($value->noti_type=="email")
    				Mail::to($value->email)->send(new OTMonthlyRequestMail($value,Carbon::now()->format("Y-m-d"),Auth::user(),"driver"));
    		}
    	}
	}

	public function storeChangeRequest($input){
		$changes= DB::transaction(function () use ($input) {

			//save chnage request
	    	
	    	$change = new ChangeRequest();
			$change->user_id = Auth::user()->id;
			$change->actual_date = format_dbdate($input['changing_date']);
			$change->changing_date = format_dbdate($input['changing_date']);
			if($input['changing_start_time'])
				$change->changing_start_time = format_dbtime($input['changing_start_time']);
			if($input['changing_end_time'])
				$change->changing_end_time = format_dbtime($input['changing_end_time']);

			if($input['working_start_time'])
				$change->working_start_time = format_dbtime($input['working_start_time']);
			if($input['working_end_time'])
				$change->working_end_time = format_dbtime($input['working_end_time']);
			
			$change->requested_date = Carbon::now()->format('Y-m-d H:i:s');
			$change->reason_of_correction = $input['reason_of_correction'];

			$change->created_by = Auth::user()->id;
			$change->save();

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
    					Mail::to($value->email)->send(new ChangeRequestMail($value,$change));
    			}
    		}
							
			return $change;

		});

		return $changes;
		
	}
	
	public function storeHotelUsage($input){
		$usages= DB::transaction(function () use ($input) {

			$attendance = Attendance::where([['date','=',format_dbdate($input['usage_date'])],['user_id','=',Auth::user()->id]])->first();
			
			
			$attendance->hotel = 1;
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

			return $attendance;

		});

		return $usages;
		
	}

	
}