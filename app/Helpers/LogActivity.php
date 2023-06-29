<?php

namespace App\Helpers;

use Request;
use Illuminate\Support\Facades\DB;
//use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginHistory;
use App\Models\AttendanceManagement\RawAttLog;
use App\Models\AttendanceManagement\AttendanceLog;

class LogActivity
{
    
	public static function addToLog($module,$data){
        
        if($module == 'login'){
            $log = [];
            $log['user_id'] = Auth::user()->id;
            $log['ip_address'] = Request::ip();
            $log['user_agent'] = Request::header('user-agent');

            $insertlog = LoginHistory::create($log);
                      
            return $insertlog->id;
        }
        if($module == 'raw-att'){
            $log = [];
            $log['att_id'] = $data->att_id;
            $log['att_UserID'] = $data->att_UserID;
            $log['user_id'] = $data->user_id;
            $log['att_ip'] = $data->att_ip;
            $log['att_serial'] = $data->att_serial;
            $log['att_Date'] = $data->att_Date;
            $log['branch'] = $data->branch;
            $log['reason'] = $data->reason;
            $log['created_date'] = $data->att_CreateTime;
            $log['updated_by'] = Auth::user()->id;
            $log['ip'] = Request::ip();
            $log['method'] = isset($data->method)?$data->method:Request::method();

            $insertlog = RawAttLog::create($log);
                      
            return $insertlog->id;
        }

        if($module == 'attendance'){
            $log = [];
            $log['attendance_id'] = $data->id;
            $log['device'] = $data->device;
            $log['device_ip'] = $data->device_ip;
            $log['device_serial'] = $data->device_serial;
            $log['user_id'] = $data->user_id;
            $log['profile_id'] = $data->profile_id;
            $log['date'] = $data->date;
            $log['start_time'] = $data->start_time;
            $log['end_time'] = $data->end_time;
            $log['type'] = $data->type;
            $log['type_id'] = $data->type_id;
            $log['branch_id'] = $data->branch_id;
            $log['remark'] = $data->remark;
            $log['manual_remark'] = $data->manual_remark;
            $log['status'] = $data->status;
            $log['latitude'] = $data->latitude;
            $log['longitude'] = $data->longitude;
            $log['corrected_start_time'] = $data->corrected_start_time;
            $log['corrected_end_time'] = $data->corrected_end_time;
            $log['normal_ot_hr'] = $data->normal_ot_hr;
            $log['sunday_ot_hr'] = $data->sunday_ot_hr;
            $log['public_holiday_ot_hr'] = $data->public_holiday_ot_hr;
            $log['change_request_date'] = $data->change_request_date;
            $log['change_approve_date'] = $data->change_approve_date;
            $log['change_approve_by'] = $data->change_approve_by;
            $log['ot_request_date'] = $data->ot_request_date;
            $log['ot_approve_date'] = $data->ot_approve_date;
            $log['ot_approve_by'] = $data->ot_approve_by;
            $log['hotel'] = $data->hotel;
            $log['next_day'] = $data->next_day;
            $log['created_by'] = $data->created_by;
            $log['updated_by'] = Auth::user()->id;
            $log['deleted_by'] = $data->deleted_by;
            $log['attendance_created_date'] = $data->created_at;
            $log['method'] = isset($data->method)?$data->method:Request::method();
            $log['monthly_request'] = $data->monthly_request;
            $log['monthly_request_id'] = $data->monthly_request_id;
            $log['morning_ot'] = $data->morning_ot;
            $log['evening_ot'] = $data->evening_ot;

            $insertlog = AttendanceLog::create($log);
                      
            return $insertlog->id;
        }

	}
   
}