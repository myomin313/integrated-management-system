<?php
namespace App\Repositories\AttendanceManagement;

use App\Models\AttendanceManagement\Attendance;
use App\Models\AttendanceManagement\AttendanceLog;
use App\Models\AttendanceManagement\RawAtt;
use App\Models\AttendanceManagement\Late;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use App\Helpers\OTHelper;
use Stevebauman\Location\Facades\Location;
use DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DatePeriod;
use DateInterval;
use DateTime;

class RawAttendanceRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(Attendance $attendance,AttendanceLog $attendance_log)
	{
		$this->model=$attendance;
		$this->model_log=$attendance_log;
	}

	public function index($name,$order){
		if(Auth::user()->can('attendance-read-all'))
			return DB::table('raw_att')->select('raw_att.*')->orderBy($name,$order);
		else if(Auth::user()->can('attendance-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			return DB::table('raw_att')->select('raw_att.*')->whereIn('user_id',$user_ids)->orderBy($name,$order);
		}
		else{
			return DB::table('raw_att')->select('raw_att.*')->where('user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
	}
	
	public function indexHistory($name,$order){
		if(isAdministrator()){
			return $this->model_log->select('*')->orderBy($name,$order);
		}
		else{
			return $this->model_log->select('*')->where('updated_by','=',Auth::user()->id)->orderBy($name,$order);
		}
	}

	public function indexOrder($name,$order,$from_date=null,$to_date=null,$employee=null,$profile=null,$n){
		$query=$this->index($name,$order);
		
		if($from_date!=null){
            $query->whereRaw( " CAST(raw_att.att_Date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
            $query->whereRaw( " CAST(raw_att.att_Date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('raw_att.user_id','=',$employee);
			});
		}
		if($profile!=null){
			$query->where(function($q) use ($profile){
				$q->where('raw_att.att_UserID','=',$profile);
			});
		}
		//$query->orderBy('att_UserID','asc');
		
		//return $query->paginate($n);
		return $query->get();
	}



	public function indexOrderHistory($name,$order,$from_date=null,$to_date=null,$operator=null,$action=null){
		$query=$this->indexHistory($name,$order);
		$time=date("H:i:s a");
		if($from_date!=null){
            $query->whereRaw( " CAST(collect_sender_logs.created_at AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
            $query->whereRaw( " CAST(collect_sender_logs.created_at AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($operator!=null){
			$query->where(function($q) use ($operator){
				$q->where('collect_sender_logs.updated_by','=',$operator);
			});
		}
		if($action!=null){
			if($action=='edit'){
				$method='POST';
			}
			else{
				$method='GET';
			}
			$query->where(function($q) use ($method){
				$q->where('collect_sender_logs.method','=',$method);
			});
		
		}
				

		return $query->get();
	}

	public function getHistory($id){
		$senderlog=$this->model_log->findOrFail($id);
		return $senderlog;
	}
	
	public function store($input,$ip){
		$rawatts= DB::transaction(function () use ($input,$ip) {

			//save raw attendance
			$date = format_dbdate($input['date']).' '.format_dbtime($input['time']);
	    	
	    	$user = User::findOrFail($input['user_id']);

	    	$rawatt = new RawAtt();
			$rawatt->att_UserID = $user->profile_id?$user->profile_id:0;
			$rawatt->user_id = $input['user_id'];
			$rawatt->att_ip = $ip;
			$rawatt->att_serial = 'Manual';
			$rawatt->att_Date = $date;
			$rawatt->branch = $input['branch'];
			$rawatt->reason = $input['reason'];

			$transaction_date = Carbon::now()->format('Y-m-d H:i:s');
			$rawatt->att_UpdateTime = $transaction_date;
			$rawatt->att_CreateTime = $transaction_date;
			$rawatt->save();

			//save attendance
			$att = Attendance::where([['date','=',format_dbdate($input['date'])],['user_id','=',$rawatt->user_id]])->first();
			if($att){
				$att->user_id=$rawatt->user_id;
				$att->date = format_dbdate($input['date']);
				$att->corrected_start_time=getUserFieldWithProfileId($rawatt->user_id,'working_start_time');
				$att->corrected_end_time=getUserFieldWithProfileId($rawatt->user_id,'working_end_time');
				if($att->start_time=="00:00:00"){
					$att->start_time=format_dbtime($input['time']);
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
				else
					$att->end_time=format_dbtime($input['time']);
				$att->branch_id=$input['branch'];
				$att->manual_remark=$att->manual_remark.','.$input['reason'];

				if($rawatt->att_ip!="::1"){
					$currentUserInfo = Location::get($rawatt->att_ip);
					$att->latitude=$att->latitude.','.$currentUserInfo->latitude;
					$att->longitude=$att->longitude.','.$currentUserInfo->longitude;
				}
				
				$att->save();
			}
			else{
				$att=$this->model;
				$att->device='Manual';
				$att->device_ip=$rawatt->att_ip;
				$att->device_serial=$rawatt->att_serial;
				
				$att->user_id=$rawatt->user_id;
				$att->profile_id=$user->profile_id?$user->profile_id:0;
				$att->date = format_dbdate($input['date']);
				$att->start_time=format_dbtime($input['time']);

				$att->type='Working Day';
				$att->type_id=0;

				$att->branch_id=$input['branch'];
				$att->manual_remark=$input['reason'];
				if($rawatt->att_ip!="::1"){
					$currentUserInfo = Location::get($rawatt->att_ip);
					$att->latitude=$currentUserInfo->latitude;
					$att->longitude=$currentUserInfo->longitude;
				}
				$att->corrected_start_time=getUserFieldWithProfileId($rawatt->att_UserID,'working_start_time');
				$att->corrected_end_time=getUserFieldWithProfileId($rawatt->att_UserID,'working_end_time');
				$att->created_by=Auth::user()->id;
				
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
			$rawatt = RawAtt::select('*')->where([['att_UserID','=',$user->profile_id],["user_id","=",$input['user_id']],['att_ip','=',$ip],['att_serial','=','Manual'],['att_Date','=',$date],['branch','=',$input['branch']],['reason','=',$input['reason']],['att_UpdateTime','=',$transaction_date],['att_CreateTime','=',$transaction_date]])->first();		
			return $rawatt;

		});

		return $rawatts;
		
	}

	public function update($input){
		
	    $rawatts= DB::transaction(function () use ($input) {
			
			$rawatt=DB::table('raw_att')->where('att_id','=',$input['id'])->first();

			//add to log
			LogActivity::addToLog('raw-att', $rawatt);

	    	//update raw att
	    	$date = format_dbdate($input['date']).' '.format_dbtime($input['time']);
	    	$user = User::findOrFail($input['user_id']);
	    	$rawatt = DB::table('raw_att')->where('att_id','=',$input['id'])->update([
	    		'att_UserID' => $user->profile_id?$user->profile_id:0,
	    		'user_id' => $input['user_id'],
	    		'att_Date' => $date,
	    		'branch' => $input['branch'],
	    		'reason' => $input['reason'],
	    		'att_UpdateTime' => Carbon::now()->format('Y-m-d H:i:s')

	    	]);
	    	$rawatt=DB::table('raw_att')->select('*')->where('att_id','=',$input['id'])->first();
			// $rawatt->att_UserID=$input['att_UserID'];			
			// $rawatt->att_Date=$date;
			// $rawatt->branch=$input['branch'];
			// $rawatt->reason=$input['reason'];
			// $rawatt->att_UpdateTime=Carbon::now()->format('Y-m-d H:i:s');
			
			// $rawatt->save();

			
			return $rawatt;

		});

		return $rawatts;


	}

	public function indexDetail($name,$order){
		if(Auth::user()->can('attendance-read-all'))
			return $this->model->select('attendances.*')->where("type","not like","RS Leave%")->orderBy($name,$order);
		else if(Auth::user()->can('attendance-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			return $this->model->select('attendances.*')->where("type","not like","RS Leave%")->whereIn('user_id',$user_ids)->orderBy($name,$order);
		}
		else{
			return $this->model->select('attendances.*')->where("type","not like","RS Leave%")->where('user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
	}

	public function indexOrderDetail($name,$order,$from_date=null,$to_date=null,$employee=null,$branch=null,$staff_type=null,$n){
		set_time_limit(4200000);	
		$query = User::select('id','employee_name');
		$query->where("users.check_ns_rs","=",1);
		$query->whereNotIn("users.id",[1,22]);
		if(Auth::user()->can('attendance-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query->whereIn("users.id",$user_ids);
		}
		else if(Auth::user()->can('attendance-read-one')){
			$query->where("users.id","=",Auth::user()->id);
		}
		if($employee!=null){
			$query->where('users.id','=',$employee);	
		}
		if($branch!=null){
			$query->where('users.branch_id','=',$branch);
		}
		$receptionist = EmployeeType::where("type","like","%reception%")->pluck('id');
		if($staff_type=="receptionist"){
			$query->whereIn("users.employee_type_id",$receptionist);
		}
		else if($staff_type=="normal_staff"){
			$query->whereNotIn("users.employee_type_id",$receptionist);
		}
		$users = $query->get();
		$arr = array();

		foreach($users as $key=>$value){
			$period = CarbonPeriod::create(format_dbdate($from_date), format_dbdate($to_date));
			// $period = new DatePeriod(
			//      new DateTime(format_dbdate($from_date)),
			//      new DateInterval('P1D'),
			//      new DateTime(format_dbdate($to_date))
			// );
			foreach ($period as $date){
				$query=Attendance::select("id","user_id","device","device_ip","date","start_time","end_time","type","type_id","leave_form_id","remark","corrected_start_time","corrected_end_time","normal_ot_hr","sat_ot_hr","sunday_ot_hr","public_holiday_ot_hr","change_request_date","change_approve_date","ot_request_date","ot_approve_date","latitude","longitude","morning_ot","evening_ot")->where([["date","=",$date->format('Y-m-d')],["user_id","=",$value->id]]);
				
				$result = $query->first();
				//return $result;

				if($result)
					$arr[$value->employee_name][] = $result;
				else{
					$empty_value = array("id"=>'', "date"=>$date->format('Y-m-d'),"user_id"=>$value->id );
					$arr[$value->employee_name][] = $empty_value;
				}
			}
				
		}

		return $arr;
	}
	public function indexOrderDetailOld($name,$order,$from_date=null,$to_date=null,$employee=null,$branch=null,$n){
					
		$users = User::select('id','employee_name')->get();
		$arr = array();
		foreach($users as $key=>$value){
			$query=$this->indexDetail($name,$order);
			
			if($from_date!=null){
	            $query->whereRaw( " CAST(attendances.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
	        }
	        if($to_date!=null){
	            $query->whereRaw( " CAST(attendances.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
	        }
	        if($employee!=null){
				$query->where(function($q) use ($employee){
					$q->where('attendances.user_id','=',$employee);
				});
			}
			if($branch!=null){
				$query->where(function($q) use ($branch){
					$q->where('attendances.branch_id','=',$branch);
				});
			}
			$query->orderBy('attendances.date','asc');

			$result = $query->where('user_id','=',$value->id)->get();
			$arr[$value->employee_name] = $result;
		}

		return $arr;
	}

	public function updateDetail($input){
		
	    $attendances= DB::transaction(function () use ($input) {
			
			$ids = $input['id'];
			$type = $input['type'];
			$start_time = $input['start_time'];
			$end_time = isset($input['end_time'])?$input['end_time']:[""];
			$corrected_start_time = $input['corrected_start_time'];
			$corrected_end_time = $input['corrected_end_time'];
			$remark = $input['remark'];
			$all_attendance = $input['all_attendance'];
			foreach($ids as $key=>$id){

				if(in_array($id, $all_attendance)){
					$attendance=Attendance::findOrFail($id);

					//add to log
					LogActivity::addToLog('attendance', $attendance);

					if (strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){

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

				    $typestr = $type[$key];
    				$type_arr = explode("_", $typestr);
    				var_dump($typestr);
    				$attendance->type = $type_arr[0];
    				$attendance->type_id = $type_arr[1];
    				
    				$attendance->start_time = $start_time[$key];
    				$attendance->end_time = $end_time[$key]?$end_time[$key]:NULL;
    				$attendance->corrected_start_time = $corrected_start_time[$key];
    				$attendance->corrected_end_time = $corrected_end_time[$key];
    				$attendance->remark = $remark[$key];
    				$attendance->updated_by = Auth::user()->id;

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
    					else if(isReceptionist($attendance->user_id)){
    						OTHelper::updateMonthlyReceptionist($attendance);
    					}
    					
    				}
    				if (strtotime($attendance->start_time) > strtotime($attendance->corrected_start_time)){

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
			
			return $attendance;

		});

		return $attendances;


	}

	public function updateOTRequest($input){
		
	    $attendances= DB::transaction(function () use ($input) {
			
			$ids = $input['id'];
			$all_morning_ot = isset($input['all_morning_ot'])?$input['all_morning_ot']:[];
			$all_evening_ot = isset($input['all_evening_ot'])?$input['all_evening_ot']:[];
			foreach($ids as $key=>$id){

				if(in_array($id, $all_morning_ot)){
					$attendance=Attendance::findOrFail($id);

    				$attendance->morning_ot = 1;

    				$attendance->save();

				}
				else{
					$attendance=Attendance::findOrFail($id);

    				$attendance->morning_ot = 0;

    				$attendance->save();
				}
				if(in_array($id, $all_evening_ot)){
					$attendance=Attendance::findOrFail($id);

    				$attendance->evening_ot = 1;

    				$attendance->save();

				}
				else{
					$attendance=Attendance::findOrFail($id);

    				$attendance->evening_ot = 0;

    				$attendance->save();
				}

			}
			
			return $attendance;

		});

		return $attendances;


	}
	public function lateRecord($name,$order,$from_date=null,$to_date=null,$employee=null,$n){
		$departments = Department::select('id','name')->orderBy('order_no','asc')->get();
		$arr = [];
		foreach($departments as $key=>$value){
			$userids = User::where(function($query) use($value) {
                    $query->whereRaw("find_in_set('".$value->id."',users.department_id)");
                  
            	})->leftJoin("employee_types","employee_types.id","=","users.employee_type_id")->where("employee_types.type","not like","%driver%")->pluck('users.id')->toArray();
			$query=Late::select('lates.*')->orderBy($name,$order);
			$query->whereIn('user_id',$userids);
			$query->whereNotIn('user_id',[1,22]);
			if($from_date!=null){
	            $query->where('year','>=',$from_date);
	        }
	        if($to_date!=null){
	            $query->where('year','<=',$to_date);
	        }
	        if($employee!=null){
				$query->where(function($q) use ($employee){
					$q->where('lates.user_id','=',$employee);
				});
			}
			
			
			//return $query->paginate($n);
			$result = $query->get();

			$arr[$value->name] = $result;
		}

		return $arr;
			
	}

	
}