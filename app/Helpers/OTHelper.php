<?php

namespace App\Helpers;

use Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\AttendanceManagement\Attendance;
use App\Models\OTManagement\MonthlyDriverOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequestDetail;
use App\Models\OTManagement\MonthlyReceptionistRequest;
use App\Models\OTManagement\MonthlyReceptionistRequestDetail;
use App\Models\MasterManagement\EmployeeType;
use App\Models\MasterManagement\Holiday;
use App\Models\MasterManagement\HolidayType;
use App\Models\LeaveManagement\LeaveType;
use App\Models\Role;
use App\Models\User;

class OTHelper
{
    public static function storeMonthlyReceptionist($date,$user_ids){
       	
        $query = Attendance::whereIn("user_id",$user_ids)->where("date","like","$date%")->orderBy("date","asc")->orderBy("user_id","asc");
        
		$attendances = $query->get();
		$unpaid_leave = LeaveType::where("type","like","%unpaid%")->pluck("id")->toArray();
		foreach($attendances as $key=>$value){
			if($value->device!="Leave"){
				$monthlyrequest = MonthlyReceptionistRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
				if(!$monthlyrequest){
					$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
					$monthlyrequest = MonthlyReceptionistRequest::create([
							'user_id'=>$value->user_id,
							'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
							'date' => $last_date,
							'hourly_rate'=> getNSFieldWithId($value->user_id,'hourly_rate')
						]);
				}
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->gm_main_status = 0;
				//$monthlyrequest->hourly_rate = getNSFieldWithId($value->user_id,'hourly_rate');
				$monthlyrequest->save();

				$detail_request = MonthlyReceptionistRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

				if(!$detail_request){
					$detail_request = new MonthlyReceptionistRequestDetail();
					$detail_request->monthly_ot_request_id = $monthlyrequest->id;
					$detail_request->attendance_id = $value->id;
				}

				$detail_request->user_id = $value->user_id;
				$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

				$ot_type = "Working Day";

				$detail_request->ot_type = $ot_type;
				$detail_request->apply_date = $value->date;
				$detail_request->start_from_time = $value->start_time;
				$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
				
				$start_time = Carbon::parse($value->start_time)->format("H:i");
                if(strtotime($start_time)<=strtotime($value->corrected_start_time))
                    $start_time = $value->corrected_start_time;

                //$end_time = $value->end_time;
                $end_time = Carbon::parse($value->end_time)->format("H:i");
                if(!$value->end_time or strtotime($end_time)>=strtotime($value->corrected_end_time))
                    $end_time = $value->corrected_end_time;
				$detail_request->end_from_time = $start_time;
				$detail_request->end_to_time = $end_time;

				$user = User::findOrFail($value->user_id);
				if($user->working_day_type=="full")
					$detail_request->end_break_hour = 1;
				else{
					$detail_request->end_break_hour = 0;
					if(getTimeDiff($start_time,$end_time)>5)
						$detail_request->end_break_hour = 1;
						
				}

				$detail_request->end_break_minute = 0;
				$detail_request->manager_status = 0;
				$detail_request->manager_status_reason = '';
				$detail_request->gm_status = 0;
					$detail_request->gm_status_reason = '';
				$detail_request->attendance = 0;
				$detail_request->inactive = 0;
	
				$detail_request->save();
	
	
				$value->monthly_request = 1;
				$value->save();
			}
			else if($value->device=="Leave" && !in_array($value->type_id, $unpaid_leave)){
				$monthlyrequest = MonthlyReceptionistRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
				if(!$monthlyrequest){
					$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
					$monthlyrequest = MonthlyReceptionistRequest::create([
							'user_id'=>$value->user_id,
							'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
							'date' => $last_date,
							'hourly_rate'=> getNSFieldWithId($value->user_id,'hourly_rate')
						]);
				}
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->gm_main_status = 0;
				//$monthlyrequest->hourly_rate = getNSFieldWithId($value->user_id,'hourly_rate');
				$monthlyrequest->save();

				$detail_request = MonthlyReceptionistRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

				if(!$detail_request){
					$detail_request = new MonthlyReceptionistRequestDetail();
					$detail_request->monthly_ot_request_id = $monthlyrequest->id;
					$detail_request->attendance_id = $value->id;
				}

				$detail_request->user_id = $value->user_id;
				$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

				$types = getAttendanceType();
				$ot_type = $types[$value->type.'_'.$value->type_id];

				$detail_request->ot_type = $ot_type;
				$detail_request->apply_date = $value->date;
				$detail_request->start_from_time = "00:00:00";
				$detail_request->start_to_time = "00:00:00";
				
				$start_time = $value->corrected_start_time;

                $end_time = $value->corrected_end_time;
				$detail_request->end_from_time = $start_time;
				$detail_request->end_to_time = $end_time;

				$user = User::findOrFail($value->user_id);
				if($user->working_day_type=="full")
					$detail_request->end_break_hour = 1;
				else{
					$detail_request->end_break_hour = 0;
					if(getTimeDiff($start_time,$end_time)>5)
						$detail_request->end_break_hour = 1;
				}

				$detail_request->end_break_minute = 0;
				$detail_request->manager_status = 0;
				$detail_request->manager_status_reason = '';
				$detail_request->gm_status = 0;
					$detail_request->gm_status_reason = '';
				$detail_request->attendance = 0;
				$detail_request->inactive = 0;
	
				$detail_request->save();
	
	
				$value->monthly_request = 1;
				$value->save();
			}	    	
	    	
		}
		
	}

	public static function storeMonthlyOTAssistant($date,$user_ids){
       	
        $query = Attendance::whereIn("user_id",$user_ids)->where("device_serial","!=","Leave")->where("date","like","$date%")->orderBy("date","asc")->orderBy("user_id","asc");
        
		$attendances = $query->get();
		foreach($attendances as $key=>$value){
			$monthlyrequest = MonthlyDriverOTRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
			if(!$monthlyrequest){
	    		$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
	    		$monthlyrequest = MonthlyDriverOTRequest::create([
	    				'user_id'=>$value->user_id,
	    				'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
	    				'date' => $last_date
	    			]);
	    	}
	    	$monthlyrequest->manager_main_status = 0;
	    	$monthlyrequest->account_main_status = 0;
	    	$monthlyrequest->gm_main_status = 0;
	    	$monthlyrequest->save();

	    	$holiday = Holiday::where("date","=",$value->date)->first();
	    	$att_date = new Carbon($value->date);
	    	if($holiday or $att_date->dayOfWeek == Carbon::SATURDAY or $att_date->dayOfWeek == Carbon::SUNDAY){
	    		$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

		    	if(!$detail_request){
		    		$detail_request = new MonthlyDriverOTRequestDetail();
		    		$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    	}

		    	$detail_request->user_id = $value->user_id;
		    	$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

		    	if($holiday)
		    		$ot_type = "Public Holiday";
		    	else if($att_date->dayOfWeek == Carbon::SATURDAY)
		    		$ot_type = "Saturday";
		    	else
		    		$ot_type = "Sunday";

		    	$detail_request->ot_type = $ot_type;
		    	$detail_request->apply_date = $value->date;
		    	$detail_request->start_from_time = $value->start_time;
		    	$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    	$detail_request->start_break_hour = 0;
		    	$detail_request->start_break_minute = 0;
		    	$detail_request->start_reason = '';
		    	$detail_request->start_hotel = $value->hotel;
		    	$detail_request->start_next_day = 0;

		    	$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    	$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    	$detail_request->end_break_hour = 0;
		    	$detail_request->end_break_minute = 0;
		    	$detail_request->end_reason = '';
		    	$detail_request->end_hotel = $value->hotel;
		    	$detail_request->end_next_day = 0;
		    	$detail_request->manager_status = 0;
		    	$detail_request->manager_status_reason = '';
		    	$detail_request->account_status = 0;
		    	$detail_request->account_status_reason = '';
		    	$detail_request->gm_status = 0;
		    	$detail_request->gm_status_reason = '';
		    	$detail_request->attendance = 0;
		    	$detail_request->day_type = "full";
		    	$detail_request->inactive = 0;

		    	$detail_request->save();


		    	$value->monthly_request = 1;
		    	$value->save();
	    	}
	    	
	    	else{

	    		if(strtotime($value->start_time)<strtotime($value->corrected_start_time) and strtotime($value->end_time)<strtotime($value->corrected_start_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = 0;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = 0;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "morning";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else if(strtotime($value->end_time)>strtotime($value->corrected_end_time) and strtotime($value->start_time)>strtotime($value->corrected_end_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = $value->hotel;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = $value->hotel;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "evening";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else{
	    			if(strtotime($value->start_time)<strtotime($value->corrected_start_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->start_time;
			    		$detail_request->start_to_time = $value->corrected_start_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = 0;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    		$detail_request->end_to_time = $value->corrected_start_time;
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = 0;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "morning";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}

		    		if(strtotime($value->end_time)>strtotime($value->corrected_end_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->corrected_end_time;
			    		$detail_request->start_to_time = $value->end_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = $value->hotel;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = $value->corrected_end_time;
			    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = $value->hotel;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "evening";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}
	    		}
		    		

	    	}
	    	
		}
		
	}
	public static function storeMonthlyOTDriver($date,$user_ids){
       	
        $query = Attendance::whereIn("user_id",$user_ids)->where("device_serial","!=","Leave")->where("date","like","$date%")->orderBy("date","asc")->orderBy("user_id","asc");
        
		$attendances = $query->get();
		foreach($attendances as $key=>$value){
			$monthlyrequest = MonthlyDriverOTRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
			if(!$monthlyrequest){
	    		$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
	    		$monthlyrequest = MonthlyDriverOTRequest::create([
	    				'user_id'=>$value->user_id,
	    				'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
	    				'date' => $last_date
	    			]);
	    	}
	    	$monthlyrequest->manager_main_status = 0;
	    	$monthlyrequest->account_main_status = 0;
	    	$monthlyrequest->gm_main_status = 0;
	    	$monthlyrequest->save();

	    	$holiday = Holiday::where("date","=",$value->date)->first();
	    	$att_date = new Carbon($value->date);
	    	if($holiday or $att_date->dayOfWeek == Carbon::SUNDAY){
	    		$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

		    	if(!$detail_request){
		    		$detail_request = new MonthlyDriverOTRequestDetail();
		    		$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    	}

		    	$detail_request->user_id = $value->user_id;
		    	$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

		    	if($holiday)
		    		$ot_type = "Public Holiday";
		    	else
		    		$ot_type = "Sunday";

		    	$detail_request->ot_type = $ot_type;
		    	$detail_request->apply_date = $value->date;
		    	$detail_request->start_from_time = $value->start_time;
		    	$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    	$detail_request->start_break_hour = 0;
		    	$detail_request->start_break_minute = 0;
		    	$detail_request->start_reason = '';
		    	$detail_request->start_hotel = $value->hotel;
		    	$detail_request->start_next_day = 0;

		    	$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    	$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    	$detail_request->end_break_hour = 0;
		    	$detail_request->end_break_minute = 0;
		    	$detail_request->end_reason = '';
		    	$detail_request->end_hotel = $value->hotel;
		    	$detail_request->end_next_day = 0;
		    	$detail_request->manager_status = 0;
		    	$detail_request->manager_status_reason = '';
		    	$detail_request->account_status = 0;
		    	$detail_request->account_status_reason = '';
		    	$detail_request->gm_status = 0;
		    	$detail_request->gm_status_reason = '';
		    	$detail_request->attendance = 0;
		    	$detail_request->day_type = "full";
		    	$detail_request->inactive = 0;

		    	$detail_request->save();


		    	$value->monthly_request = 1;
		    	$value->save();
	    	}
	    	
	    	else{

	    		if(strtotime($value->start_time)<strtotime($value->corrected_start_time) and strtotime($value->end_time)<strtotime($value->corrected_start_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = 0;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = 0;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "morning";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else if(strtotime($value->end_time)>strtotime($value->corrected_end_time) and strtotime($value->start_time)>strtotime($value->corrected_end_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = $value->hotel;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = $value->hotel;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "evening";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else{
	    			if(strtotime($value->start_time)<strtotime($value->corrected_start_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->start_time;
			    		$detail_request->start_to_time = $value->corrected_start_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = 0;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    		$detail_request->end_to_time = $value->corrected_start_time;
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = 0;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "morning";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}

		    		if(strtotime($value->end_time)>strtotime($value->corrected_end_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->corrected_end_time;
			    		$detail_request->start_to_time = $value->end_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = $value->hotel;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = $value->corrected_end_time;
			    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = $value->hotel;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "evening";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}
	    		}
		    		

	    	}
	    	
		}
		
	}
	public static function storeMonthlyOTRental($date,$user_ids){
       	
        $query = Attendance::whereIn("user_id",$user_ids)->where("device_serial","!=","Leave")->where("date","like","$date%")->orderBy("date","asc")->orderBy("user_id","asc");
        
		$attendances = $query->get();
		foreach($attendances as $key=>$value){
			$monthlyrequest = MonthlyDriverOTRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
			if(!$monthlyrequest){
	    		$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
	    		$monthlyrequest = MonthlyDriverOTRequest::create([
	    				'user_id'=>$value->user_id,
	    				'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
	    				'date' => $last_date
	    			]);
	    	}
	    	$monthlyrequest->manager_main_status = 0;
	    	$monthlyrequest->account_main_status = 0;
	    	$monthlyrequest->gm_main_status = 0;
	    	$monthlyrequest->save();

	    	$marubeni_holiday = HolidayType::where("name","like","%Marubeni%")->first();
	    	$holiday = Holiday::where("date","=",$value->date)->first();
	    	$att_date = new Carbon($value->date);
	    	if(($holiday and $holiday->holiday_type_id!=$marubeni_holiday->id) or $att_date->dayOfWeek == Carbon::SUNDAY){
	    		$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

		    	if(!$detail_request){
		    		$detail_request = new MonthlyDriverOTRequestDetail();
		    		$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    	}

		    	$detail_request->user_id = $value->user_id;
		    	$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

		    	if($holiday)
		    		$ot_type = "Public Holiday";
		    	else
		    		$ot_type = "Sunday";

		    	$detail_request->ot_type = $ot_type;
		    	$detail_request->apply_date = $value->date;
		    	$detail_request->start_from_time = $value->start_time;
		    	$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    	$detail_request->start_break_hour = 0;
		    	$detail_request->start_break_minute = 0;
		    	$detail_request->start_reason = '';
		    	$detail_request->start_hotel = $value->hotel;
		    	$detail_request->start_next_day = 0;

		    	$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    	$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    	$detail_request->end_break_hour = 0;
		    	$detail_request->end_break_minute = 0;
		    	$detail_request->end_reason = '';
		    	$detail_request->end_hotel = $value->hotel;
		    	$detail_request->end_next_day = 0;
		    	$detail_request->manager_status = 0;
		    	$detail_request->manager_status_reason = '';
		    	$detail_request->account_status = 0;
		    	$detail_request->account_status_reason = '';
		    	$detail_request->gm_status = 0;
		    	$detail_request->gm_status_reason = '';
		    	$detail_request->attendance = 0;
		    	$detail_request->day_type = "full";
		    	$detail_request->inactive = 0;

		    	$detail_request->save();


		    	$value->monthly_request = 1;
		    	$value->save();
	    	}
	    	
	    	else{

	    		if(strtotime($value->start_time)<strtotime($value->corrected_start_time) and strtotime($value->end_time)<strtotime($value->corrected_start_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = 0;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = 0;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "morning";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else if(strtotime($value->end_time)>strtotime($value->corrected_end_time) and strtotime($value->start_time)>strtotime($value->corrected_end_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = $value->hotel;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = $value->hotel;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "evening";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else{
	    			if(strtotime($value->start_time)<strtotime($value->corrected_start_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->start_time;
			    		$detail_request->start_to_time = $value->corrected_start_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = 0;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    		$detail_request->end_to_time = $value->corrected_start_time;
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = 0;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "morning";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}

		    		if(strtotime($value->end_time)>strtotime($value->corrected_end_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->corrected_end_time;
			    		$detail_request->start_to_time = $value->end_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = $value->hotel;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = $value->corrected_end_time;
			    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = $value->hotel;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "evening";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}
	    		}
		    		

	    	}
	    	
		}
		
	}

	public static function updateMonthlyReceptionist($attendance){
       	
        $value = Attendance::findOrFail($attendance->id);
        
		$unpaid_leave = LeaveType::where("type","like","%unpaid%")->pluck("id")->toArray();
		$date = Carbon::parse($value->date)->format('Y-m');
		if($value->device!="Leave"){
			$monthlyrequest = MonthlyReceptionistRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
			if(!$monthlyrequest){
				$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
				$monthlyrequest = MonthlyReceptionistRequest::create([
					'user_id'=>$value->user_id,
					'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
					'date' => $last_date,
					'hourly_rate'=> getNSFieldWithId($value->user_id,'hourly_rate')
				]);
			}
			else{
		    	$gm_main_status = $monthlyrequest->gm_main_status;
		    }

		    if($gm_main_status!=1){
		    	$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->gm_main_status = 0;
					//$monthlyrequest->hourly_rate = getNSFieldWithId($value->user_id,'hourly_rate');
				$monthlyrequest->save();

				$detail_request = MonthlyReceptionistRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

				if(!$detail_request){
					$detail_request = new MonthlyReceptionistRequestDetail();
					$detail_request->monthly_ot_request_id = $monthlyrequest->id;
					$detail_request->attendance_id = $value->id;
				}

				$detail_request->user_id = $value->user_id;
				$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

				$ot_type = "Working Day";

				$detail_request->ot_type = $ot_type;
				$detail_request->apply_date = $value->date;
				$detail_request->start_from_time = $value->start_time;
				$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
					
				$start_time = Carbon::parse($value->start_time)->format("H:i");
	            if(strtotime($start_time)<=strtotime($value->corrected_start_time))
	                $start_time = $value->corrected_start_time;

	            //$end_time = $value->end_time;
	            $end_time = Carbon::parse($value->end_time)->format("H:i");
	            if(!$value->end_time or strtotime($end_time)>=strtotime($value->corrected_end_time))
	                $end_time = $value->corrected_end_time;
				$detail_request->end_from_time = $start_time;
				$detail_request->end_to_time = $end_time;

				$user = User::findOrFail($value->user_id);
				if($user->working_day_type=="full")
					$detail_request->end_break_hour = 1;
				else{
					$detail_request->end_break_hour = 0;
					if(getTimeDiff($start_time,$end_time)>5)
						$detail_request->end_break_hour = 1;
							
				}

				$detail_request->end_break_minute = 0;
				$detail_request->manager_status = 0;
				$detail_request->manager_status_reason = '';
				$detail_request->gm_status = 0;
				$detail_request->gm_status_reason = '';
				$detail_request->attendance = 0;
				$detail_request->inactive = 0;
		
				$detail_request->save();
		
		
				$value->monthly_request = 1;
				$value->save();
		    }
				
		}
		else if($value->device=="Leave" && !in_array($value->type_id, $unpaid_leave)){
			$monthlyrequest = MonthlyReceptionistRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
			if(!$monthlyrequest){
				$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
				$monthlyrequest = MonthlyReceptionistRequest::create([
					'user_id'=>$value->user_id,
					'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
					'date' => $last_date,
					'hourly_rate'=> getNSFieldWithId($value->user_id,'hourly_rate')
				]);
			}
			else{
		    	$gm_main_status = $monthlyrequest->gm_main_status;
		    }

		    if($gm_main_status!=1){
		    	$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->gm_main_status = 0;
				//$monthlyrequest->hourly_rate = getNSFieldWithId($value->user_id,'hourly_rate');
				$monthlyrequest->save();

				$detail_request = MonthlyReceptionistRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

				if(!$detail_request){
					$detail_request = new MonthlyReceptionistRequestDetail();
					$detail_request->monthly_ot_request_id = $monthlyrequest->id;
					$detail_request->attendance_id = $value->id;
				}

				$detail_request->user_id = $value->user_id;
				$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

				$types = getAttendanceType();
				$ot_type = $types[$value->type.'_'.$value->type_id];

				$detail_request->ot_type = $ot_type;
				$detail_request->apply_date = $value->date;
				$detail_request->start_from_time = "00:00:00";
				$detail_request->start_to_time = "00:00:00";
					
				$start_time = $value->corrected_start_time;

	            $end_time = $value->corrected_end_time;
				$detail_request->end_from_time = $start_time;
				$detail_request->end_to_time = $end_time;

				$user = User::findOrFail($value->user_id);
				if($user->working_day_type=="full")
					$detail_request->end_break_hour = 1;
				else{
					$detail_request->end_break_hour = 0;
					if(getTimeDiff($start_time,$end_time)>5)
						$detail_request->end_break_hour = 1;
				}

				$detail_request->end_break_minute = 0;
				$detail_request->manager_status = 0;
				$detail_request->manager_status_reason = '';
				$detail_request->gm_status = 0;
						$detail_request->gm_status_reason = '';
				$detail_request->attendance = 0;
				$detail_request->inactive = 0;
		
				$detail_request->save();
		
		
				$value->monthly_request = 1;
				$value->save();
		    }
		    
				
		}	    	
	    	
		
		
	}
	public static function updateMonthlyOTRental($attendance){
       	
        $value = Attendance::findOrFail($attendance->id);
        
		$date = Carbon::parse($value->date)->format('Y-m');
		$monthlyrequest = MonthlyDriverOTRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
		if(!$monthlyrequest){
	    	$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
	    	$monthlyrequest = MonthlyDriverOTRequest::create([
	    			'user_id'=>$value->user_id,
	    			'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
	    			'date' => $last_date
	    	]);

	    	$gm_main_status = $monthlyrequest->gm_main_status;
	    }
	    else{
	    	$gm_main_status = $monthlyrequest->gm_main_status;
	    }

	    if($gm_main_status!=1){
	    	$monthlyrequest->manager_main_status = 0;
		    $monthlyrequest->account_main_status = 0;
		    $monthlyrequest->gm_main_status = 0;
		    $monthlyrequest->save();

		    $marubeni_holiday = HolidayType::where("name","like","%Marubeni%")->first();
		    $holiday = Holiday::where("date","=",$value->date)->first();
		    $att_date = new Carbon($value->date);
		    if(($holiday and $holiday->holiday_type_id!=$marubeni_holiday->id) or $att_date->dayOfWeek == Carbon::SUNDAY){
		    	$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

			    if(!$detail_request){
			    	$detail_request = new MonthlyDriverOTRequestDetail();
			    	$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    	$detail_request->attendance_id = $value->id;
			    }

			    $detail_request->user_id = $value->user_id;
			    $detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

			    if($holiday)
			    	$ot_type = "Public Holiday";
			    else
			    	$ot_type = "Sunday";

			    $detail_request->ot_type = $ot_type;
			    $detail_request->apply_date = $value->date;
			    $detail_request->start_from_time = $value->start_time;
			    $detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
			    $detail_request->start_break_hour = 0;
			    $detail_request->start_break_minute = 0;
			    $detail_request->start_reason = '';
			    $detail_request->start_hotel = $value->hotel;
			    $detail_request->start_next_day = 0;

			    $detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    $detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
			    $detail_request->end_break_hour = 0;
			    $detail_request->end_break_minute = 0;
			    $detail_request->end_reason = '';
			    $detail_request->end_hotel = $value->hotel;
			    $detail_request->end_next_day = 0;
			    $detail_request->manager_status = 0;
			    $detail_request->manager_status_reason = '';
			    $detail_request->account_status = 0;
			    $detail_request->account_status_reason = '';
			    $detail_request->gm_status = 0;
			    $detail_request->gm_status_reason = '';
			    $detail_request->attendance = 0;
			    $detail_request->day_type = "full";
			    $detail_request->inactive = 0;

			    $detail_request->save();


			    $value->monthly_request = 1;
			    $value->save();
		    }

		    else{

	    		if(strtotime($value->start_time)<strtotime($value->corrected_start_time) and strtotime($value->end_time)<strtotime($value->corrected_start_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = 0;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = 0;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "morning";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else if(strtotime($value->end_time)>strtotime($value->corrected_end_time) and strtotime($value->start_time)>strtotime($value->corrected_end_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = $value->hotel;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = $value->hotel;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "evening";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else{
	    			if(strtotime($value->start_time)<strtotime($value->corrected_start_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->start_time;
			    		$detail_request->start_to_time = $value->corrected_start_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = 0;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    		$detail_request->end_to_time = $value->corrected_start_time;
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = 0;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "morning";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}

		    		if(strtotime($value->end_time)>strtotime($value->corrected_end_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->corrected_end_time;
			    		$detail_request->start_to_time = $value->end_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = $value->hotel;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = $value->corrected_end_time;
			    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = $value->hotel;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "evening";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}
	    		}
		    		

	    	}
	    }
		    
	}
	public static function updateMonthlyOTDriver($attendance){
       	
        $value = Attendance::findOrFail($attendance->id);
        $date = Carbon::parse($value->date)->format('Y-m');
		$monthlyrequest = MonthlyDriverOTRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
		if(!$monthlyrequest){
	    	$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
	    	$monthlyrequest = MonthlyDriverOTRequest::create([
	    			'user_id'=>$value->user_id,
	    			'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
	    			'date' => $last_date
	    	]);

	    	$gm_main_status = $monthlyrequest->gm_main_status;
	    }
	    else{
	    	$gm_main_status = $monthlyrequest->gm_main_status;
	    }

	    if($gm_main_status!=1){
		    $monthlyrequest->manager_main_status = 0;
		    $monthlyrequest->account_main_status = 0;
		    $monthlyrequest->gm_main_status = 0;
		    $monthlyrequest->save();

		    $holiday = Holiday::where("date","=",$value->date)->first();
		    $att_date = new Carbon($value->date);
		    if($holiday or $att_date->dayOfWeek == Carbon::SUNDAY){
		    	$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

			    if(!$detail_request){
			    	$detail_request = new MonthlyDriverOTRequestDetail();
			    	$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    	$detail_request->attendance_id = $value->id;
			    }

			    $detail_request->user_id = $value->user_id;
			    $detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

			    if($holiday)
			    	$ot_type = "Public Holiday";
			    else
			    	$ot_type = "Sunday";

			    $detail_request->ot_type = $ot_type;
			    $detail_request->apply_date = $value->date;
			    $detail_request->start_from_time = $value->start_time;
			    $detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
			    $detail_request->start_break_hour = 0;
			    $detail_request->start_break_minute = 0;
			    $detail_request->start_reason = '';
			    $detail_request->start_hotel = $value->hotel;
			    $detail_request->start_next_day = 0;

			    $detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    $detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
			    $detail_request->end_break_hour = 0;
			    $detail_request->end_break_minute = 0;
			    $detail_request->end_reason = '';
			    $detail_request->end_hotel = $value->hotel;
			    $detail_request->end_next_day = 0;
			    $detail_request->manager_status = 0;
			    $detail_request->manager_status_reason = '';
			    $detail_request->account_status = 0;
			    $detail_request->account_status_reason = '';
			    $detail_request->gm_status = 0;
			    $detail_request->gm_status_reason = '';
			    $detail_request->attendance = 0;
			    $detail_request->day_type = "full";
			    $detail_request->inactive = 0;

			    $detail_request->save();


			    $value->monthly_request = 1;
			    $value->save();
		    }
		    	
		    else{

	    		if(strtotime($value->start_time)<strtotime($value->corrected_start_time) and strtotime($value->end_time)<strtotime($value->corrected_start_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = 0;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = 0;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "morning";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else if(strtotime($value->end_time)>strtotime($value->corrected_end_time) and strtotime($value->start_time)>strtotime($value->corrected_end_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = $value->hotel;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = $value->hotel;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "evening";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else{
	    			if(strtotime($value->start_time)<strtotime($value->corrected_start_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->start_time;
			    		$detail_request->start_to_time = $value->corrected_start_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = 0;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    		$detail_request->end_to_time = $value->corrected_start_time;
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = 0;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "morning";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}

		    		if(strtotime($value->end_time)>strtotime($value->corrected_end_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->corrected_end_time;
			    		$detail_request->start_to_time = $value->end_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = $value->hotel;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = $value->corrected_end_time;
			    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = $value->hotel;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "evening";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}
	    		}
		    		

	    	}
		}
	  
		
	}
	public static function updateMonthlyOTAssistant($attendance){
       	
        $value = Attendance::findOrFail($attendance->id);
        $date = Carbon::parse($value->date)->format('Y-m');
        
		$monthlyrequest = MonthlyDriverOTRequest::where([['user_id','=',$value->user_id],['date','like',"$date%"]])->first();
		if(!$monthlyrequest){
	    	$last_date = Carbon::parse($value->date)->endOfMonth()->format('Y-m-d');
	    	$monthlyrequest = MonthlyDriverOTRequest::create([
	    			'user_id'=>$value->user_id,
	    			'branch'=>getUserFieldWithId($value->user_id,'branch_id'),
	    			'date' => $last_date
	    		]);

	    	$gm_main_status = $monthlyrequest->gm_main_status;
	    }
	    else{
	    	$gm_main_status = $monthlyrequest->gm_main_status;
	    }

	    if($gm_main_status!=1){
		    $monthlyrequest->manager_main_status = 0;
		    $monthlyrequest->account_main_status = 0;
		    $monthlyrequest->gm_main_status = 0;
		    $monthlyrequest->save();

		    $holiday = Holiday::where("date","=",$value->date)->first();
		    $att_date = new Carbon($value->date);
		    if($holiday or $att_date->dayOfWeek == Carbon::SATURDAY or $att_date->dayOfWeek == Carbon::SUNDAY){
		    	$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id]])->first();

			    if(!$detail_request){
			    	$detail_request = new MonthlyDriverOTRequestDetail();
			    	$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    	$detail_request->attendance_id = $value->id;
			    }

			    $detail_request->user_id = $value->user_id;
			    $detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');

			    if($holiday)
			    	$ot_type = "Public Holiday";
			    else if($att_date->dayOfWeek == Carbon::SATURDAY)
			    	$ot_type = "Saturday";
			    else
			    	$ot_type = "Sunday";

			    $detail_request->ot_type = $ot_type;
			    $detail_request->apply_date = $value->date;
			    $detail_request->start_from_time = $value->start_time;
			    $detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
			    $detail_request->start_break_hour = 0;
			    $detail_request->start_break_minute = 0;
			    $detail_request->start_reason = '';
			    $detail_request->start_hotel = $value->hotel;
			    $detail_request->start_next_day = 0;

			    $detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    $detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
			    $detail_request->end_break_hour = 0;
			    $detail_request->end_break_minute = 0;
			    $detail_request->end_reason = '';
			    $detail_request->end_hotel = $value->hotel;
			    $detail_request->end_next_day = 0;
			    $detail_request->manager_status = 0;
			    $detail_request->manager_status_reason = '';
			    $detail_request->account_status = 0;
			    $detail_request->account_status_reason = '';
			    $detail_request->gm_status = 0;
			    $detail_request->gm_status_reason = '';
			    $detail_request->attendance = 0;
			    $detail_request->day_type = "full";
			    $detail_request->inactive = 0;

			    $detail_request->save();


			    $value->monthly_request = 1;
			    $value->save();
		    }
		    	
		    else{

	    		if(strtotime($value->start_time)<strtotime($value->corrected_start_time) and strtotime($value->end_time)<strtotime($value->corrected_start_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = 0;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = $value->end_time?Carbon::parse($value->end_time)->format("H:i"):$value->corrected_end_time;
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = 0;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "morning";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else if(strtotime($value->end_time)>strtotime($value->corrected_end_time) and strtotime($value->start_time)>strtotime($value->corrected_end_time)){
	    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

		    		if(!$detail_request){
		    			$detail_request = new MonthlyDriverOTRequestDetail();
		    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
		    			$detail_request->attendance_id = $value->id;
		    		}

		    		$detail_request->user_id = $value->user_id;
		    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
		    		$detail_request->ot_type = "Weekday";
		    		$detail_request->apply_date = $value->date;
		    		$detail_request->start_from_time = $value->start_time;
		    		$detail_request->start_to_time = $value->end_time?$value->end_time:$value->corrected_end_time;
		    		$detail_request->start_break_hour = 0;
		    		$detail_request->start_break_minute = 0;
		    		$detail_request->start_reason = '';
		    		$detail_request->start_hotel = $value->hotel;
		    		$detail_request->start_next_day = 0;

		    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
		    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
		    		$detail_request->end_break_hour = 0;
		    		$detail_request->end_break_minute = 0;
		    		$detail_request->end_reason = '';
		    		$detail_request->end_hotel = $value->hotel;
		    		$detail_request->end_next_day = 0;
		    		$detail_request->manager_status = 0;
		    		$detail_request->manager_status_reason = '';
		    		$detail_request->account_status = 0;
		    		$detail_request->account_status_reason = '';
		    		$detail_request->gm_status = 0;
		    		$detail_request->gm_status_reason = '';
		    		$detail_request->attendance = 0;
		    		$detail_request->day_type = "evening";
		    		$detail_request->inactive = 0;

		    		$detail_request->save();


		    		$value->monthly_request = 1;
		    		$value->save();
	    		}
	    		else{
	    			if(strtotime($value->start_time)<strtotime($value->corrected_start_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","morning"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->start_time;
			    		$detail_request->start_to_time = $value->corrected_start_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = 0;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = Carbon::parse($value->start_time)->format("H:i");
			    		$detail_request->end_to_time = $value->corrected_start_time;
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = 0;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "morning";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}

		    		if(strtotime($value->end_time)>strtotime($value->corrected_end_time)){
		    			$detail_request = MonthlyDriverOTRequestDetail::where([['monthly_ot_request_id','=',$monthlyrequest->id],['attendance_id','=',$value->id],["day_type","evening"]])->first();

			    		if(!$detail_request){
			    			$detail_request = new MonthlyDriverOTRequestDetail();
			    			$detail_request->monthly_ot_request_id = $monthlyrequest->id;
			    			$detail_request->attendance_id = $value->id;
			    		}

			    		$detail_request->user_id = $value->user_id;
			    		$detail_request->branch = getUserFieldWithId($value->user_id,'branch_id');
			    		$detail_request->ot_type = "Weekday";
			    		$detail_request->apply_date = $value->date;
			    		$detail_request->start_from_time = $value->corrected_end_time;
			    		$detail_request->start_to_time = $value->end_time;
			    		$detail_request->start_break_hour = 0;
			    		$detail_request->start_break_minute = 0;
			    		$detail_request->start_reason = '';
			    		$detail_request->start_hotel = $value->hotel;
			    		$detail_request->start_next_day = 0;

			    		$detail_request->end_from_time = $value->corrected_end_time;
			    		$detail_request->end_to_time = Carbon::parse($value->end_time)->format("H:i");
			    		$detail_request->end_break_hour = 0;
			    		$detail_request->end_break_minute = 0;
			    		$detail_request->end_reason = '';
			    		$detail_request->end_hotel = $value->hotel;
			    		$detail_request->end_next_day = 0;
			    		$detail_request->manager_status = 0;
			    		$detail_request->manager_status_reason = '';
			    		$detail_request->account_status = 0;
			    		$detail_request->account_status_reason = '';
			    		$detail_request->gm_status = 0;
			    		$detail_request->gm_status_reason = '';
			    		$detail_request->attendance = 0;
			    		$detail_request->day_type = "evening";
			    		$detail_request->inactive = 0;

			    		$detail_request->save();


			    		$value->monthly_request = 1;
			    		$value->save();
		    		}
	    		}
		    		

	    	}
		}
	    	
		
		
	}
   
}