<?php

use App\Models\User;
use App\Models\Role;
use App\Models\MasterManagement\Bank;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use App\Models\MasterManagement\Holiday;
use App\Models\MasterManagement\HolidayType;
use App\Models\LeaveManagement\LeaveType;
use App\Models\SalaryManagement\NsSalary;
use App\Models\SalaryManagement\RsSalary;
use App\Models\OTManagement\MonthlyOtRequestDetail;
use App\Models\OTManagement\MonthlyDriverOtRequestDetail;
use App\Models\OTManagement\MonthlyOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequest;
use App\Models\OTManagement\MonthlyReceptionistRequest;
use App\Models\OTManagement\MonthlyReceptionistRequestDetail;
use App\Models\OTManagement\OtRate;
use App\Models\AttendanceManagement\Attendance;
use App\Models\EmployeeManagement\NsEmployee;
use App\Models\EmployeeManagement\RsEmployee;
use App\Models\EmployeeManagement\Family;
use App\Models\EmployeeManagement\LifeAssurance;
use App\Models\TaxManagement\RsActualTax;
use App\Models\TaxManagement\NsActualTax;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Carbon\Carbon;
use App\Models\SalaryManagement\Salary;
use App\Models\SalaryManagement\SalaryExchangeDetail;
use App\Models\SalaryManagement\PaymentExchangeRate;
//use DB;
define('NUMBER_PER_PAGE', 25);

function isRentalEmployeeType($id){
    
    $emp_type = EmployeeType::findOrFail($id);
    
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'rental')!== false or strtolower($emp_type->type)=='rental')
            return true;
    }  

    return false;
}
function isDriverlEmployeeType($id){
    
    $emp_type = EmployeeType::findOrFail($id);
    
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'driver')!== false or strtolower($emp_type->type)=='driver')
            return true;
    }  

    return false;
}

function getOTExchangeRate($user_id,$date){
    //date format  m/Y;
    $pay_for = explode("/", $date);
    $pre_month = Carbon::parse($pay_for[1]."-".$pay_for[0]."-01")->addMonth()->format("m/Y");
    $pay_date = explode("/", $pre_month);
    $salary = Salary::where([["user_id","=",$user_id],["year","=",$pay_date[1]],["month","=",$pay_date[0]]])->first();

    $date = $pay_date[1]."-".$pay_date[0]."-01";
    $ot_ex_rate = PaymentExchangeRate::where("date","=",$date)->first();
    if($salary){
        $detail = SalaryExchangeDetail::where([["salary_id","=",$salary->id],["type","=","ot"]])->first();
        if($detail)
            return $detail->exchange_rate;
        return 0;
    }
    else if($ot_ex_rate){
        return $ot_ex_rate->ot_exchange_rate;
    }
    else if($date=="04/2023"){
        return 2100;
    }
    else{
        return 0;
    }
}
function isLateRecord($data){
    $att_date = new Carbon($data->date);
    if($att_date->dayOfWeek == Carbon::SATURDAY){
        $holiday = 1;
        if(isQcStaff($data->user_id))
            $holiday = 0;
    }
    else if($att_date->dayOfWeek == \Carbon\Carbon::SUNDAY){
        $holiday = 1;
    }
    else if(getPublicHoliday($data->date)){
        $holiday = 1;
    }
    else{
        $holiday = 0;
        if(isDriver($data->user_id))
            $holiday = 1;
    }

    return $holiday;
}

function getLocation($ip){
    $currentUserInfo = Location::get($ip);
    $location = [
        "ip"=>$currentUserInfo->ip,
        "country"=>$currentUserInfo->countryName,
        "country_code"=>$currentUserInfo->countryCode,
        "region"=>$currentUserInfo->regionName,
        "region_code"=>$currentUserInfo->regionCode,
        "city"=>$currentUserInfo->cityName,
        "zip_code"=>$currentUserInfo->zipCode,
        "latitude"=>$currentUserInfo->latitude,
        "longitude"=>$currentUserInfo->longitude,
    ];
    return $location;
}
function getHost(){
    $host = request()->getHost();
    return $host;
}
function sendSMS($phone,$message){
    $SMS_SENDER = "Marubeni";
    $RESPONSE_TYPE = 'json';
    $SMS_USERNAME = 'YGNADMI';
    $SMS_PASSWORD = '111111';

    if(substr($phone,0,2) == "09")
        $phoneNumber=substr_replace($phone, '+95', 0,1);
    else
        $phoneNumber=$phone;
    $ress = [
        "to" => $phoneNumber,
        "message" =>$message
    ]; 

    $ch = curl_init('https://smspoh.com/api/v2/send');                  
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                  
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ress));          
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                              
        'Authorization: Bearer cjmZl7dtFpXPduxx0xEjsq0I-1kRmszhCLxly3WG9RCR-1zNe5nDHza2ZOTqHVCJ',
        'Content-Type: application/json',                                                                    
    ));
     
    curl_exec($ch); 

}

function getNumberOfChildren($user_id){
    $family = Family::select("id")->where("user_id","=",$user_id)->where(function($q) use ($user_id) {
            $q->where('relationship', 'like', "%children%")
            ->orwhere('relationship', 'like', "%son%")
                ->orwhere('relationship', 'like', "%daughter%");
        })->get();
    if(count($family))
        return count($family);
    else
        return 0;
}

function isHoliday($date){

    $ex_date = new Carbon($date);

    $marubeni_holiday = HolidayType::where("name","like","%Marubeni%")->first();
    $holiday = Holiday::where("date","=",$date)->first();

    if($ex_date->dayOfWeek == Carbon::SATURDAY or $ex_date->dayOfWeek == Carbon::SUNDAY){
        return true;
    }
    else if($holiday and $holiday->holiday_type_id!=$marubeni_holiday->id){
        return true;
    }

    return false;

}
function getDateName($date){
    $holiday = Holiday::where("date","=",$date)->first();
    if($holiday)
        return $holiday->title;

    $ex_date = new Carbon($date);
    if($ex_date->dayOfWeek == Carbon::SATURDAY)
        return "Saturday";
    else if($ex_date->dayOfWeek == Carbon::SUNDAY)
        return "Sunday";
    else if($ex_date->dayOfWeek == Carbon::MONDAY)
        return "Monday";
    else if($ex_date->dayOfWeek == Carbon::TUESDAY)
        return "Tuesday";
    else if($ex_date->dayOfWeek == Carbon::WEDNESDAY)
        return "Wednesday";
    else if($ex_date->dayOfWeek == Carbon::THURSDAY)
        return "Thursday";
    else if($ex_date->dayOfWeek == Carbon::FRIDAY)
        return "Friday";
}
function getPublicHoliday($date){

    $ex_date = new Carbon($date);

    $marubeni_holiday = HolidayType::where("name","like","%Marubeni%")->first();
    $holiday = Holiday::where("date","=",$date)->first();

    if($holiday){
        return $holiday->title;
    }

    return false;

}

function getNsSalary($user_id,$date){
    $year = Carbon::parse($date)->format('Y');
    $month = Carbon::parse($date)->format('F');
    $month = strtolower($month);
    if($month=="january" or $month=="february" or $month=="march")
        $year = $year - 1;

    $salary = NsSalary::select("$month")->where([["user_id","=",$user_id],["year","=",$year],["salary_type","=","salary"]])->first();
    if($salary)
        return $salary->$month;
    else
        return 0;
}

function getRsSalary($user_id,$date){
    $year = Carbon::parse($date)->format('Y');
    $month = Carbon::parse($date)->format('F');
    $month = strtolower($month);
    if($month=="january" or $month=="february" or $month=="march")
        $year = $year - 1;

    $salary = RsSalary::select("$month")->where([["user_id","=",$user_id],["year","=",$year],["salary_type","=","mm_salary"]])->first();
    if($salary)
        return $salary->$month;
    else
        return 0;
}

function getLifeAssuredAllowance($user_id,$year){
    $assurance = LifeAssurance::select(DB::raw("SUM(premium_amount) as amount"))->where([["user_id","=",$user_id],["year","=",$year]])->first();
    if($assurance)
        return $assurance->amount;
    return 0;
}

function getRsSalaryFor($user_id,$salary_type,$field,$year){
    $salary = RsSalary::where([["user_id","=",$user_id],["salary_type","=",$salary_type],["year","=",$year]])->first();
    if($salary)
        return $salary->$field;
    else
        return 0;
}

function getRequestIp(){
    $ip = \Request::ip();
    // return "profilepassword";
    if($ip=="136.228.161.174")
        return "profilepassword";
    else
        return "2fa";
}

function getTotalLeave($user_id,$from_date,$to_date,$unpaid_leave){
    $query = Attendance::select("leave_forms.user_id","leave_forms.day_type")->where([["leave_forms.user_id","=",$user_id],["attendances.date",">=",format_dbdate($from_date)],["attendances.date","<=",format_dbdate($to_date)],["attendances.leave_form_id","!=","NULL"],["leave_forms.approve_by_GM","=",1]])->whereIn("leave_forms.leave_type_id",$unpaid_leave);
    $query->leftJoin("leave_forms","leave_forms.id","=","attendances.leave_form_id");

    $leaves = $query->get();

    $total_leave = 0;
    foreach($leaves as $key=>$value){
        if($value->day_type)
            $total_leave += 0.5;
        else
            $total_leave += 1;
    }
    return $total_leave;
}


function getSiteName(){
	return "Integrated Management System";
}
function getWorkingHour($user_id,$from_date,$to_date,$id=null)
{   
    if($id){
        $attendance = Attendance::select("device_serial","type_id","date","start_time","end_time","corrected_start_time","corrected_end_time")->where("id","=",$id)->get();
    }
    else{
        $attendance = Attendance::select("device_serial","type_id","date","start_time","end_time","corrected_start_time","corrected_end_time")->where([["user_id","=",$user_id],["date",">=",$from_date],["date","<=",$to_date]])->get();
    }
    
    $working_hour = 0;

    $unpaid_leave = LeaveType::where("type","like","%unpaid%")->pluck("id")->toArray();
    
    $user = User::findOrFail($user_id);
    if($user->working_day_type=="full"){
        foreach($attendance as $key=>$value){
            if($value->device_serial!="Leave"){
                $start_time = Carbon::parse($value->start_time)->format("H:i");
                if(strtotime($value->start_time)<=strtotime($value->corrected_start_time))
                    $start_time = $value->corrected_start_time;

                //$end_time = $value->end_time;
                $end_time = Carbon::parse($value->end_time)->format("H:i");
                if(!$value->end_time or strtotime($value->end_time)>=strtotime($value->corrected_end_time))
                    $end_time = $value->corrected_end_time;
                $working_hour += getTimeDiff($start_time,$end_time);

                $working_hour -= 1;
                
            }
            else if($value->device_serial=="Leave" && !in_array($value->type_id, $unpaid_leave)){
                $start_time = $value->corrected_start_time;

                $end_time = $value->corrected_end_time;
                
                $working_hour += getTimeDiff($start_time,$end_time);

                $working_hour -= 1;
            }
                
        }
    }
    else{
        foreach($attendance as $key=>$value){

            if($value->device_serial!="Leave"){
                $start_time = Carbon::parse($value->start_time)->format("H:i");
                if(strtotime($value->start_time)<=strtotime($value->corrected_start_time))
                    $start_time = $value->corrected_start_time;

                $end_time = Carbon::parse($value->end_time)->format("H:i");
                if(!$value->end_time or strtotime($value->end_time)>=strtotime($value->corrected_end_time))
                    $end_time = $value->corrected_end_time;
                $working_hour += getTimeDiff($start_time,$end_time);
                if(getTimeDiff($start_time,$end_time)>5)
                    $working_hour -= 1;
            }
            else if($value->device_serial=="Leave" && !in_array($value->type_id, $unpaid_leave)){
                $start_time = $value->corrected_start_time;
                

                $end_time = $value->corrected_end_time;
                
                $working_hour += getTimeDiff($start_time,$end_time);
                if(getTimeDiff($start_time,$end_time)>5)
                    $working_hour -= 1;
            }
                
        }
    }
        
    return $working_hour;
}
function getMonthlyOTHour($user_id,$year_month,$total=null){
    $attendance = Attendance::select("normal_ot_hr","sat_ot_hr","sunday_ot_hr","public_holiday_ot_hr")->where([["user_id","=",$user_id],["date","like","$year_month%"]])->get();
    $normal_hour = 0;
    $normal_minute = 0;
    $saturday_hour = 0;
    $saturday_minute = 0;
    $sunday_hour = 0;
    $sunday_minute = 0;
    $public_holiday_hour = 0;
    $public_holiday_minute = 0;
    foreach($attendance as $key=>$value){
        $normal = explode(":",convertTime($value->normal_ot_hr));
        $normal_hour += $normal[0];
        $normal_minute += $normal[1];

        $saturday = explode(":",convertTime($value->sat_ot_hr));
        $saturday_hour += $saturday[0];
        $saturday_minute += $saturday[1];

        $sunday = explode(":",convertTime($value->sunday_ot_hr));
        $sunday_hour += $sunday[0];
        $sunday_minute += $sunday[1];

        $public_holiday = explode(":",convertTime($value->public_holiday_ot_hr));
        $public_holiday_hour += $public_holiday[0];
        $public_holiday_minute += $public_holiday[1];
    }
    if($total){
        $total_hour = $normal_hour + $saturday_hour + $sunday_hour + $public_holiday_hour;
        $total_minute = $normal_minute + $saturday_minute + $sunday_minute + $public_holiday_minute;

        $total_hour += floor($total_minute / 60);
        $total_minute = ($total_minute -   floor($total_minute / 60) * 60);
        $total_time = str_pad($total_hour, 2, '0', STR_PAD_LEFT).":".str_pad($total_minute, 2, '0', STR_PAD_LEFT);
        return $total_time;
    }
    else{
        $normal_hour += floor($normal_minute / 60);
        $normal_minute = ($normal_minute -   floor($normal_minute / 60) * 60);
        $normal_time = str_pad($normal_hour, 2, '0', STR_PAD_LEFT).":".str_pad($normal_minute, 2, '0', STR_PAD_LEFT);

        $saturday_hour += floor($saturday_minute / 60);
        $saturday_minute = ($saturday_minute -   floor($saturday_minute / 60) * 60);
        $saturday_time = str_pad($saturday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($saturday_minute, 2, '0', STR_PAD_LEFT);

        $sunday_hour += floor($sunday_minute / 60);
        $sunday_minute = ($sunday_minute -   floor($sunday_minute / 60) * 60);
        $sunday_time = str_pad($sunday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_minute, 2, '0', STR_PAD_LEFT);

        $public_holiday_hour += floor($public_holiday_minute / 60);
        $public_holiday_minute = ($public_holiday_minute -   floor($public_holiday_minute / 60) * 60);
        $public_holiday_time = str_pad($public_holiday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($public_holiday_minute, 2, '0', STR_PAD_LEFT);

        $attendance = new \stdClass();
        $attendance->normal = $normal_time;
        $attendance->saturday = $saturday_time;
        $attendance->sunday = $sunday_time;
        $attendance->public_holiday = $public_holiday_time;
        return $attendance;
    }
    if(!$attendance){
        
    }
    return $attendance;
}

function sumTime($time_array) {
    $minutes = 0;
    foreach ($time_array as $time) {
        if($time==0){
            $time = "00:00";
        }
        list($hour, $minute) = explode(':', $time);
        $minutes += $hour * 60;
        $minutes += $minute;
    }

    $hours = floor($minutes / 60);
    $minutes -= $hours * 60;

    return sprintf('%02d:%02d', $hours, $minutes);
}

function getNsSalaryField($user_id,$year,$field){
    $nssalary = NsSalary::where([["user_id","=",$user_id],["year","=",$year]])->first();
    if($nssalary)
        return $nssalary->$field;
    return 0;
}
function getRsSalaryField($user_id,$year,$type,$field){
    $rssalary = RsSalary::where([["user_id","=",$user_id],["year","=",$year],['salary_type','=',$type]])->first();
    if($rssalary)
        return $rssalary->$field;
    return 0;
}

function getMainStatus($date,$user_id){
    $monthlyrequest = MonthlyOtRequest::select("manager_main_status","account_main_status","gm_main_status")->where([["date","=",$date],["user_id","=",$user_id]])->first();
    if($monthlyrequest){
        $result = [$monthlyrequest->manager_main_status,$monthlyrequest->account_main_status,$monthlyrequest->gm_main_status];
        return $result;
    }
    return [0,0,0];

}
function getMainStatusDriver($date,$user_id){
    $monthlyrequest = MonthlyDriverOtRequest::select("manager_main_status","account_main_status","gm_main_status")->where([["date","=",$date],["user_id","=",$user_id]])->first();
    if($monthlyrequest){
        $result = [$monthlyrequest->manager_main_status,$monthlyrequest->account_main_status,$monthlyrequest->gm_main_status];
        return $result;
    }
    return [0,0,0];

}

function getMainStatusReceptionist($date,$user_id){
    $monthlyrequest = MonthlyReceptionistRequest::select("manager_main_status","gm_main_status")->where([["date","=",$date],["user_id","=",$user_id]])->first();
    if($monthlyrequest){
        $result = [$monthlyrequest->manager_main_status,$monthlyrequest->gm_main_status];
        return $result;
    }
    return [0,0];

}

function getCardTime($date,$user_id,$field){
    
    $attendance = Attendance::where([["date",'=',$date],["user_id","=",$user_id]])->first();
    if($attendance){
        if($field=="start_time" or $field=="end_time")
            return $attendance->$field?siteformat_time24($attendance->$field):'';
        return $attendance->$field;
    }
}

function convertTime($dec)
{
    // start by converting to seconds
    $seconds = ($dec * 3600);
    // we're given hours, so let's get those the easy way
    $hours = floor($dec);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = round($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes);
}

// lz = leading zero
function lz($num)
{
    return (strlen($num) < 2) ? "0{$num}" : $num;
}

function getTimeDiff($start_time,$end_time){
    $start_datetime = new DateTime($start_time); 
    $diff = $start_datetime->diff(new DateTime($end_time));


    $newtime = $diff->h.":".$diff->i;
    $hr = $diff->h + ($diff->i/60);
    return round($hr,2);
}

function getOTHour($ot_id,$emp_type="staff"){
    if($emp_type=="staff")
        $detailrequest=MonthlyOtRequestDetail::findOrFail($ot_id);
    else
        $detailrequest=MonthlyDriverOtRequestDetail::findOrFail($ot_id);
    if($detailrequest->end_from_time){
        $ot_start_time = $detailrequest->end_from_time; 
        $ot_end_time = $detailrequest->end_to_time;
        $breaktime = $detailrequest->end_break_hour.":".$detailrequest->end_break_minute;
        $next_day = $detailrequest->end_next_day;
    }
    else{
        $ot_start_time = $detailrequest->start_from_time; 
        $ot_end_time = $detailrequest->start_to_time;
        $breaktime = $detailrequest->start_break_hour.":".$detailrequest->start_break_minute;
        $next_day = $detailrequest->start_next_day;
    }
         
    if($next_day){
        $start_datetime = new DateTime($ot_start_time); 
        $diff = $start_datetime->diff(new DateTime("24:00"));

        $time = $diff->h.":".$diff->i;
        $time2 = $ot_end_time;

        $secs = strtotime($time2)-strtotime("00:00:00");
        // $result = date("H:i",strtotime($time)+$secs);
        // return $result;
        $newtime = date("H:i",strtotime($time)+$secs);

        $start_datetime = new DateTime($newtime); 
        $diff = $start_datetime->diff(new DateTime($breaktime));

        $ot_hr = $diff->h + ($diff->i/60);

        return round($ot_hr,2);
    }
    else{
        $start_datetime = new DateTime($ot_start_time); 
        $diff = $start_datetime->diff(new DateTime($ot_end_time));


        $newtime = $diff->h.":".$diff->i;

        $start_datetime = new DateTime($newtime); 
        $diff = $start_datetime->diff(new DateTime($breaktime));

        $ot_hr = $diff->h + ($diff->i/60);

        return round($ot_hr,2);
    }
    
}

function getOTAmount($ot_hr,$ot_rate){
    $ot_time = explode(":", $ot_hr);
    $ot_amount = ($ot_time[0] * $ot_rate) + (($ot_time[1] / 60) * $ot_rate );
    return floor_up($ot_amount,2);
}

function getOTPayment($user_id,$date,$annual=false){
    
    $date = format_dbdate($date);
    $salary = Salary::where([["user_id","=",$user_id],["month","=",Carbon::parse($date)->format("m")],["year","=",Carbon::parse($date)->format("Y")]])->first();
    if($salary)
        return $salary->ot_rate_usd;
    $ot = OtRate::where([["user_id","=",$user_id],["date","=",Carbon::parse($date)->endOfMonth()->format("Y-m-d")]])->first();
    if($ot and $ot->ot_rate>0)
        return $ot->ot_rate;

    $year = Carbon::parse($date)->format('Y');
    $month = Carbon::parse($date)->format('F');
    $month = strtolower($month);
    if(!$annual){
        if($month=='january' || $month=="february" ||$month=="march")
            $year = $year-1;
    }
        

    $user = User::findOrFail($user_id);
    if($user->check_ns_rs==1){
        $nssalary = NsSalary::select("$month")->where([['user_id','=',$user_id],['salary_type','=','salary'],['year','=',$year]])->first();
        $salary = 0;
        if($nssalary)
            $salary = $nssalary->$month;

    }
    else{
        $rssalary = RsSalary::select("$month")->where([['user_id','=',$user_id],['salary_type','=','salary'],['year','=',$year]])->first();
        $salary = 0;
        if($rssalary)
            $salary = $rssalary->$month;
    }

    $start_datetime = new DateTime($user->working_start_time); 
    $diff = $start_datetime->diff(new DateTime($user->working_end_time));

    $working_hour_per_day = $diff->h + ($diff->i/60);
    $working_day_per_week = $user->working_day_per_week;

    $deducted_hr = 1;
    if(isDriver($user_id)){
        $deducted_hr = 2;
    }
    if(isRentalDriver($user_id)){
        return number_format(getNSFieldWithId($user->id,"ot_rate"),2);
    }
    else if(isQcStaff($user_id)){
        if($user->working_start_time and $user->working_end_time and $user->working_day_per_week)
            $ot_payment = ( ($salary * 12) / ( ( ( ($working_hour_per_day - $deducted_hr) * ($working_day_per_week-0.5) ) + ( 3.5 * 1 ) ) * 52 ) ) *2;
        else
            $ot_payment = 0;
    }
    else{
        if($user->working_start_time and $user->working_end_time and $user->working_day_per_week)
            $ot_payment = ( ($salary * 12) / (($working_hour_per_day - $deducted_hr) * $working_day_per_week * 52) ) *2;
        else
            $ot_payment = 0;
    }
        

    return round_up($ot_payment,2);
}

// function getTaxiCharge($user_id,$date,$count=null){
//     if(isDriver($user_id) or isAssistant($user_id)){
//         $date = format_dbdate($date);

//         $year = Carbon::parse($date)->format('Y');
//         $month = Carbon::parse($date)->format('m');
//         $year_month = $year.'-'.$month;

//         $morningrequest = MonthlyOtRequestDetail::select(DB::raw("COUNT(id) as total_count"))->where([['user_id','=',$user_id],['apply_date','like',"$year_month%"]])->where('end_from_time',"<","6:30")->groupBy('apply_date')->get();
//         $eveningrequest = MonthlyOtRequestDetail::select(DB::raw("COUNT(id) as total_count"))->where([['user_id','=',$user_id],['apply_date','like',"$year_month%"]])->where('end_to_time',">","21:30")->groupBy('apply_date')->get();

//         $taxi_charge = 0;
//         if(count($morningrequest))
//             $taxi_charge += count($morningrequest) * 3;
//         if(count($eveningrequest))
//             $taxi_charge += count($eveningrequest) * 3;

//         if($count){
//             $total_count = count($morningrequest) + count($eveningrequest);
//             return $total_count;
//         }
//         return $taxi_charge;
//     }
//     else{
//         return 0;
//     }
        
    
// }
function getTaxiCharge($user_id,$date,$count=null){
    if(isDriver($user_id) or isAssistant($user_id)){
        $date = format_dbdate($date);

        $year = Carbon::parse($date)->format('Y');
        $month = Carbon::parse($date)->format('m');
        $year_month = $year.'-'.$month;

        $staffot = MonthlyOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','like',"$year_month%"]])->get();

        $taxi_charge = 0;
        $taxi_count = 0;

        foreach($staffot as $key=>$value){
            if($value->end_from_time){
                $start_time = $value->end_from_time;
                $end_time = $value->end_to_time;
                $next_day = $value->end_next_day;
                $hotel = $value->end_hotel;
            }
            else{
                $start_time = $value->start_from_time;
                $end_time = $value->start_to_time;
                $next_day = $value->start_next_day;
                $hotel = $value->start_hotel;
            }

            if($next_day){
                if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30") and !$hodel)
                    $taxi_count += 2;
                else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30") and $hodel)
                    $taxi_count += 1;
            }
            else{
                if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                    $taxi_count += 1;
                else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30"))
                    $taxi_count += 2;
            }
        }

        $driverot = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','like',"$year_month%"]])->get();

        //$taxi_charge = 0;
        //$taxi_count = 0;

        foreach($driverot as $key=>$value){
            if($value->morning_taxi_time or $value->evening_taxi_time){
                $taxi_count += ($value->morning_taxi_time + $value->evening_taxi_time);
            }
            else{
                if($value->end_from_time){
                    $start_time = $value->end_from_time;
                    $end_time = $value->end_to_time;
                    $next_day = $value->end_next_day;
                    $hotel = $value->end_hotel;
                }
                else{
                    $start_time = $value->start_from_time;
                    $end_time = $value->start_to_time;
                    $next_day = $value->start_next_day;
                    $hotel = $value->start_hotel;
                }

                if($next_day){
                    if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30")){
                        $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                        $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                        if(!$previous_day)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                    {
                        if($value->end_hotel==0)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30")){
                        $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                        $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                        if(!$previous_day)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30")){
                        if($value->end_hotel==0)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30")){

                        $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                        $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                        if(!$previous_day)
                            $taxi_count += 1;

                        if($value->end_hotel==0)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30") and $hodel)
                        $taxi_count += 1;
                }
                else{
                    if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30")){
                        $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                        $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                        if(!$previous_day)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                    {
                        if($value->end_hotel==0)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30")){
                        $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                        $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                        if(!$previous_day)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30")){
                        if($value->end_hotel==0)
                            $taxi_count += 1;
                    }
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30")){

                        $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                        $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                        if(!$previous_day)
                            $taxi_count += 1;

                        if($value->end_hotel==0)
                            $taxi_count += 1;
                    }
                }
            }
                
        }

        if($count)
            return $taxi_count;
        $taxi_charge = $taxi_count * 3;
        
        return $taxi_charge;
    }
    else{
        return 0;
    }
        
    
}

function getTaxiTime($user_id,$detailot){
    if(isDriver($user_id) or isAssistant($user_id)){
        if(isset($detailot->daily_ot_request_id)){
            $staffot = MonthlyOtRequestDetail::select('*')->where([['user_id','=',$user_id],['id','=',$detailot->id]])->get();

            $taxi_count = 0;

            foreach($staffot as $key=>$value){
                if($value->end_from_time){
                    $start_time = $value->end_from_time;
                    $end_time = $value->end_to_time;
                    $next_day = $value->end_next_day;
                    $hotel = $value->end_hotel;
                }
                else{
                    $start_time = $value->start_from_time;
                    $end_time = $value->start_to_time;
                    $next_day = $value->start_next_day;
                    $hotel = $value->start_hotel;
                }

                if($next_day){
                    if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30") and !$hodel)
                        $taxi_count += 2;
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30") and $hodel)
                        $taxi_count += 1;
                }
                else{
                    if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                        $taxi_count += 1;
                    else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30"))
                        $taxi_count += 2;
                }
            }
        }
        else if(isset($detailot->attendance_id)){
            $driverot = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['id','=',$detailot->id]])->get();

            $taxi_count = 0;

            foreach($driverot as $key=>$value){

                if($value->morning_taxi_time or $value->evening_taxi_time){
                    $taxi_count += ($value->morning_taxi_time + $value->evening_taxi_time);
                }
                //sssss
                else{
                    if($value->end_from_time){
                        $start_time = $value->end_from_time;
                        $end_time = $value->end_to_time;
                        $next_day = $value->end_next_day;
                        $hotel = $value->end_hotel;
                    }
                    else{
                        $start_time = $value->start_from_time;
                        $end_time = $value->start_to_time;
                        $next_day = $value->start_next_day;
                        $hotel = $value->start_hotel;
                    }

                    if($next_day){
                        if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30")){
                            $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                            $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                            if(!$previous_day)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                        {
                            if($value->end_hotel==0)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30")){
                            $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                            $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                            if(!$previous_day)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30")){
                            if($value->end_hotel==0)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30")){

                            $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                            $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                            if(!$previous_day)
                                $taxi_count += 1;

                            if($value->end_hotel==0)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30") and $hodel)
                            $taxi_count += 1;
                    }
                    else{
                        if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) < strtotime("6:30")){
                            $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                            $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                            if(!$previous_day)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) > strtotime("21:30") and strtotime($end_time) > strtotime("21:30"))
                        {
                            if($value->end_hotel==0)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("6:30") and strtotime($end_time) < strtotime("21:30")){
                            $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                            $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                            if(!$previous_day)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) > strtotime("6:30") and strtotime($start_time) < strtotime("21:30") and strtotime($end_time) > strtotime("21:30")){
                            if($value->end_hotel==0)
                                $taxi_count += 1;
                        }
                        else if(strtotime($start_time) < strtotime("6:30") and strtotime($end_time) > strtotime("21:30")){

                            $sub_day = Carbon::parse($value->apply_date)->subDay()->format("Y-m-d");
                            $previous_day = MonthlyDriverOtRequestDetail::select('*')->where([['user_id','=',$user_id],['apply_date','=',$sub_day],["end_hotel","=",1]])->first();
                            if(!$previous_day)
                                $taxi_count += 1;

                            if($value->end_hotel==0)
                                $taxi_count += 1;
                        }
                    }
                }

                //ensssss  
            }
        }   
        if($taxi_count)
            return $taxi_count;
        else
            return '';
        
    }
    else{
        return '';
    }
        
    
}

function round_up ( $value, $precision ) {
    if(is_decimal($value)){
        $dec_value = explode('.', $value);
        if(strlen($dec_value[1])==$precision){
            return $value;
        }
        else if(strlen($dec_value[1])<$precision){
            return $dec_value[0].'.'.str_pad($dec_value[1], $precision, '0', STR_PAD_RIGHT);
        }

        $pow = pow ( 10, $precision ); 
        $result = ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow;
        if(is_decimal($result)){
            $dec_result = explode('.', $result);
            if(strlen($dec_result[1])<$precision){
                return $dec_result[0].'.'.str_pad($dec_result[1], $precision, '0', STR_PAD_RIGHT);
            }
            else{
                return $result;
            }
        }
        else
            return $result.".00";
            
    }
    else{
        return $value.".00";
    }
        
        
}

function round_up_nodecimal($value){
    $amount = explode('.',$value);
    if(isset($amount[1]) and $amount[1]>0){
        return $amount[0]+1;
    }
    else{
        return $amount[0];
    }
} 

// function floor_up ( $value, $precision ) { 
//     $pow = pow ( 10, $precision ); 
//     return ( floor ( $pow * $value ) + floor ( $pow * $value - floor ( $pow * $value ) ) ) / $pow; 
// }

function floor_up ( $value, $precision ) {
    if(is_decimal($value)){
        $dec_value = explode('.', $value);
        if(strlen($dec_value[1])==$precision){
            return $value;
        }
        else if(strlen($dec_value[1])<$precision){
            return $dec_value[0].'.'.str_pad($dec_value[1], $precision, '0', STR_PAD_RIGHT);
        }

        $pow = pow ( 10, $precision ); 
        $result = ( floor ( $pow * $value ) + floor ( $pow * $value - floor ( $pow * $value ) ) ) / $pow;
        if(is_decimal($result)){
            $dec_result = explode('.', $result);
            if(strlen($dec_result[1])<$precision){
                return $dec_result[0].'.'.str_pad($dec_result[1], $precision, '0', STR_PAD_RIGHT);
            }
            else{
                return $result;
            }
        }
        else
            return $result.".00";
            
    }
    else{
        return $value.".00";
    }
        
        
}


function getAttendanceType(){
    $types = [];
    $types["Working Day_0"] = "Working Day";
    $types["OT Type_0"] = "Weekday";
    $types["OT Type_1"] = "Saturday";
    $types["OT Type_2"] = "Sunday";
    $types["OT Type_3"] = "Public Holiday";

    $types["RS Leave Type_1"] = "Earned Leave";
    $types["RS Leave Type_2"] = "Refresh Leave";

    $leavetypes = LeaveType::select("id","leave_type_name")->get();
    foreach($leavetypes as $key=>$value){
        $types["NS Leave Type_".$value->id] = $value->leave_type_name;
    }

    return $types;
}

function getLeaveDay($user_id,$from_date,$to_date)
{
    $unpaid_leave = LeaveType::where("type","like","%unpaid%")->pluck("id")->toArray();
    $attendances = Attendance::select("date")->where([["user_id","=",$user_id],["date",">=",$from_date],["date","<=",$to_date],["type","=","NS Leave Type"]])->whereIn("type_id",$unpaid_leave)->orderBy("date","asc")->get();
    $result = "";
    foreach($attendances as $key=>$value){
        if($key==0)
            $result = $value->date;
        else
            $result .= ", ".$value->date;
    }
    
    return $result;

}

function getRSActualTax($user_id,$date){
    $tax = RsActualTax::where([["user_id","=",$user_id],["tax_period","=",$date]])->first();
    if($tax)
        return $tax;
    else
        return 0;
}

function getNSActualTax($user_id,$date){
    $tax = NsActualTax::where([["user_id","=",$user_id],["tax_period","=",$date]])->first();
    if($tax)
        return $tax;
    else
        return 0;
}

function classActivePath($path)
{
    return Request::is($path) ? 'active' : '';
}

function classActiveSegment($segment, $value)
{
    if(!is_array($value)) {
        return Request::segment($segment) == $value ? 'menu-open active' : '';
    }
    foreach ($value as $v) {
        if(Request::segment($segment) == $v) return 'menu-open active';
    }
    return '';
}

function siteformat_date($item)
{

    return \Carbon\Carbon::parse($item)->format('d/m/Y');

}

function siteformat_time($item)
{

    return \Carbon\Carbon::parse($item)->format('h:i A');

}

function siteformat_time24($item)
{

    return \Carbon\Carbon::parse($item)->format('H:i');

}

function siteformat_time24_nextday($item,$next_day){
    if($next_day){
        $time = explode(':', $item);
        $hr = $time[0]+24;
        return $hr.":".$time[1];
    }
    return \Carbon\Carbon::parse($item)->format('H:i');
}

function siteformat_datetime($item)
{

    return \Carbon\Carbon::parse($item)->format('d/m/Y g:i:s A');

}

function siteformat_datetime24($item)
{

    return \Carbon\Carbon::parse($item)->format('d/m/Y H:i:s');

}

function format_dbdate($item)
{
    if($item == ''){
        return '';
    }
        //16/08/2017 03:18:06
        $datetime = explode(" ",$item);
        $date = explode("/",$datetime[0]);
        $dt = \Carbon\Carbon::create($date[2], $date[1], $date[0], 0, 0, 0);

        return $dt->format('Y-m-d');

}
function format_dbtime($item)
{   
    $time = \Carbon\Carbon::parse($item)->format('H:i');

    return $time;

}
function format_dbdatetime($item)
{   
    if(trim($item) == ""){
        return '';
    }
    $datetime = explode(" ",$item);
    $date = explode("/",$datetime[0]);
    $time = explode(":",$datetime[1]);
    if(isset($datetime[2]) && $time[0]!=12 && $datetime[2] == "PM")
        $dt = \Carbon\Carbon::create($date[2], $date[1], $date[0], $time[0]+12, $time[1], $time[2]);
    else if(isset($datetime[2]) && $time[0]==12 && $datetime[2] == "AM")
        $dt = \Carbon\Carbon::create($date[2], $date[1], $date[0], $time[0]-12, $time[1], $time[2]);
    else
        $dt = \Carbon\Carbon::create($date[2], $date[1], $date[0], $time[0], $time[1], $time[2]);

    return $dt->format('Y-m-d H:i:s');

}

function siteformat_number($number)
{
    if(is_decimal($number))
        return number_format($number,2);
    else
        return number_format($number,2);

}


function siteformat_number_without_separator($number)
{
    if(is_decimal($number))
        return $number;
    else
        return (int) $number;
}

function is_decimal($val)
{
    return is_numeric($val) && floor($val) != $val;
}

function getPositionName($id)
{
    $role=Role::whereId($id)->first();
    if($role)
        return $role->name;
    else
        return "---";
}
function getBankName($id)
{
    $bank=Bank::whereId($id)->first();
    if($bank)
        return $bank->name;
    else
        return "";
}
function getEmployeeType($id)
{
    $emptype=EmployeeType::whereId($id)->first();
    if($emptype)
        return $emptype->type;
    else
        return "---";
}
function getProfileName($id,$serial)
{
    $profile=DB::table('raw_profile')->where('pro_UserID','=',$id)->where('pro_Serial','=',$serial)->first();
    if($profile)
        return $profile->pro_UserName;
    else
        return "---";
}
function getProfileNameWithId($id)
{
    $profile=DB::table('raw_profile')->where('pro_id','=',$id)->first();
    if($profile)
        return $profile->pro_UserName;
    else
        return "---";
}
function getFingerPrintSerial()
{
    $serials=DB::table('fingerprintdevice')->select('fig_SerialNo')->get();
    $serial_arr = array();
    if(count($serials)){
        foreach($serials as $key=>$value){
            $serial_arr [] = $value->fig_SerialNo;
        }
        return $serial_arr;
    }
    else
        return [];
}
function getFingerPrintName($ip)
{
    $fig=DB::table('fingerprintdevice')->select('fig_Name')->where('fig_IP','=',$ip)->first();
    if($fig){
        
        return $fig->fig_Name;
    }
    else
        return '---';
}
function getBranchField($id,$field)
{
    $branch=Branch::whereId($id)->first();
    if($branch){
        return $branch->$field;
    }
    else{
        return "---";
    }
        
}
function getDepartmentField($id,$field)
{
    $ids = explode(',', $id);
    $department_name = "";
    if($ids){
        foreach($ids as $key=>$value){
            $department=Department::whereId($value)->first();
            if($department){
                if($key==0)
                    $department_name .= $department->$field;
                else
                    $department_name .= ", ".$department->$field;
            }
        }
    }
    return $department_name;
        
}
function getBranchId($name)
{
    $branch=Branch::where('name','=',$name)->first();
    if($branch){
        return $branch->id;
    }
    else{
        return 0;
    }
        
}
function getUserFieldWithId($id,$field)
{
    $user=User::whereId($id)->first();
    if($user){
        if($field=="position_id"){
            if($user->position_id)
                return getPositionName($user->position_id);
            else
                return $user->position;
        }
        else if($field=="profile_id" or $field=="branch_id" or $field=="employee_type_id" or $field=="department_id")
            return $user->$field?$user->$field:0;
        return $user->$field;
    }
    else{
        if($field=="profile_id" or $field=="branch_id" or $field=="employee_type_id" or $field=="department_id")
            return 0;
        else if($field=="position_id")
            return "";
        return "---";
    }
        
}
function getNSFieldWithId($id,$field)
{
    $user=NsEmployee::where("user_id","=",$id)->first();
    if($user){
        if($field=="hourly_rate")
            return $user->$field?$user->$field:0;
        else if($field=="ot_rate")
            return $user->$field?$user->$field:0;
        return $user->$field;
    }
    else{
        if($field=="hourly_rate")
            return 0;
        if($field=="ot_rate")
            return 0;
        return "---";
    }
        
}
function getRSFieldWithId($id,$field)
{
    $user=RsEmployee::where("user_id","=",$id)->first();
    if($user){
        
        return $user->$field;
    }
    else{
        
        return "---";
    }
        
}
function getUserNameWithPrefix($id){
    $user=User::where('id','=',$id)->first();
    if($user){
        if($user->check_ns_rs==1){
            if(strtolower($user->name)=="iijima"){
                return $user->employee_name?"Ms. ".$user->employee_name:"Ms. ".$user->name;
            }
            else{
                if($user->gender=="female")
                    return $user->employee_name?"Daw ".$user->employee_name:"Daw ".$user->name;
                else if($user->gender=="male")
                    return $user->employee_name?"U ".$user->employee_name:"U ".$user->name;
                else
                    return $user->employee_name?$user->employee_name:$user->name;
            }
                
        }
        else{
            if($user->gender=="female")
                return $user->employee_name?"Ms. ".$user->employee_name:"Ms. ".$user->name;
            else if($user->gender=="male")
                return $user->employee_name?"Mr. ".$user->employee_name:"Mr. ".$user->name;
            else
                return $user->employee_name?$user->employee_name:$user->name;
        }
            
    }
    return "";
}
function getUserFieldWithProfileId($id,$field)
{
    $user=User::where('profile_id','=',$id)->first();
    if($user){
        if($field=="profile_id" or $field=="branch_id" or $field=="employee_type_id" or $field=="department_id" or $field=="position_id")
            return $user->$field?$user->$field:0;
        return $user->$field;
    }
    else{
        if($field=="profile_id" or $field=="branch_id" or $field=="employee_type_id" or $field=="department_id" or $field=="position_id")
            return 0;
        return 0;
    }
        
}

function isAdministrator(){
    $roles = Auth::user()->roles()->get();
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'administrator')!== false or strtolower($value->name)=='administrator' or strpos(strtolower($value->name), 'admin')!== false or strtolower($value->name)=='admin')
            return true;
        
    }
        
    return false;
}

function isSupervisor(){
    $roles = Auth::user()->roles()->get();
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'supervisor')!== false or strtolower($value->name)=='supervisor')
            return true;
        
    }
        
    return false;
}

function isManager(){
    $roles = Auth::user()->roles()->get();
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'manager')!== false or strtolower($value->name)=='manager')
            return true;
        
    }
        
    return false;
}
function isAccountant(){
    $roles = Auth::user()->roles()->get();
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'account')!== false or strtolower($value->name)=='account')
            return true;
        
    }
        
    return false;
}

// function isAssistant($id=null){
//     if($id){
//         $user = User::findOrFail($id);
//         $roles = $user->roles()->get();
//     }
//     else{
//         $roles = Auth::user()->roles()->get();
//     }
        
//     foreach ($roles as $key => $value) {
//         if(strpos(strtolower($value->name), 'assistant')!== false or strtolower($value->name)=='assistant')
//             return true;
        
//     }
        
//     return false;
// }

// function isDriver($id=null){
    // if($id){
    //     $user = User::findOrFail($id);
    //     $roles = $user->roles()->get();
    // }
    // else{
    //     $roles = Auth::user()->roles()->get();
    // }
        
    // foreach ($roles as $key => $value) {
    //     if(strpos(strtolower($value->name), 'driver')!== false or strtolower($value->name)=='driver')
    //         return true;
        
    // }
        
//     return false;
// }

// function isReceptionist($id=null){
//     if($id){
//         $user = User::findOrFail($id);
//         $roles = $user->roles()->get();
//     }
//     else{
//         $roles = Auth::user()->roles()->get();
//     }
        
//     foreach ($roles as $key => $value) {
//         if(strpos(strtolower($value->name), 'reception')!== false or strtolower($value->name)=='reception')
//             return true;
        
//     }
        
//     return false;
// }
function isAssistant($id=null){
    if($id){
        $user = User::findOrFail($id);
        $emp_type = EmployeeType::whereId($user->employee_type_id)->first();
    }
    else{
        $emp_type = EmployeeType::whereId(Auth::user()->employee_type_id)->first();
    }
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'assistant')!== false or strtolower($emp_type->type)=='assistant')
            return true;
    }  

    if($id){
        $roles = $user->roles()->get();
    }
    else{
        $roles = Auth::user()->roles()->get();
    }
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'assistant')!== false or strtolower($value->name)=='assistant')
            return true;
        
    }
    return false;
}

function isDriver($id=null){
    if($id){
        $user = User::findOrFail($id);
        $emp_type = EmployeeType::whereId($user->employee_type_id)->first();
    }
    else{
        $emp_type = EmployeeType::whereId(Auth::user()->employee_type_id)->first();
    }
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'driver')!== false or strtolower($emp_type->type)=='driver')
            return true;
    }  

    if($id){
        $roles = $user->roles()->get();
    }
    else{
        $roles = Auth::user()->roles()->get();
    }
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'driver')!== false or strtolower($value->name)=='driver')
            return true;
        
    }
    return false;
}

function isRentalDriver($id=null){
    if($id){
        $user = User::findOrFail($id);
        $emp_type = EmployeeType::whereId($user->employee_type_id)->first();
    }
    else{
        $emp_type = EmployeeType::whereId(Auth::user()->employee_type_id)->first();
    }
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'rental')!== false or strtolower($emp_type->type)=='rental')
            return true;
    }  

    if($id){
        $roles = $user->roles()->get();
    }
    else{
        $roles = Auth::user()->roles()->get();
    }
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'rental')!== false or strtolower($value->name)=='rental')
            return true;
        
    }
    return false;
}

function isQcStaff($id=null){
    if($id){
        $user = User::findOrFail($id);
        $emp_type = EmployeeType::whereId($user->employee_type_id)->first();
    }
    else{
        $emp_type = EmployeeType::whereId(Auth::user()->employee_type_id)->first();
    }
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'qc')!== false or strtolower($emp_type->type)=='qc')
            return true;
    }  

    if($id){
        $roles = $user->roles()->get();
    }
    else{
        $roles = Auth::user()->roles()->get();
    }
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'qc')!== false or strtolower($value->name)=='qc')
            return true;
        
    }
    return false;
}

function isReceptionist($id=null){
    if($id){
        $user = User::findOrFail($id);
        $emp_type = EmployeeType::whereId($user->employee_type_id)->first();
    }
    else{
        $emp_type = EmployeeType::whereId(Auth::user()->employee_type_id)->first();
    }
    if($emp_type){
        if(strpos(strtolower($emp_type->type), 'reception')!== false or strtolower($emp_type->type)=='reception')
            return true;
    } 

    if($id){
        $roles = $user->roles()->get();
    }
    else{
        $roles = Auth::user()->roles()->get();
    }
        
    foreach ($roles as $key => $value) {
        if(strpos(strtolower($value->name), 'reception')!== false or strtolower($value->name)=='reception')
            return true;
        
    }
     
    return false;
}


function getDifferentDate($from_date,$field){
    $sdate = $from_date;
    $edate = Carbon::now()->format("Y-m-d");

    $date_diff = abs(strtotime($edate) - strtotime($sdate));

    $year = floor($date_diff / (365*60*60*24));
    $month = floor(($date_diff - $year * 365*60*60*24) / (30*60*60*24));
    $day = floor(($date_diff - $year * 365*60*60*24 - $month*30*60*60*24)/ (60*60*24));

    return $$field;
}

function getPerformanceNumber($cha){
    if($cha=="S")
        return 2;
    else if($cha=="A")
        return 1.5;
    else if($cha=="B")
        return 1;
    else if($cha=="C")
        return 0.5;
    else if($cha=="D")
        return 0;
}