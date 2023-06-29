<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterManagement\Holiday;
use App\Models\MasterManagement\HolidayType;
use App\Models\MasterManagement\EmployeeType;
use App\Models\AttendanceManagement\Attendance;
use App\Models\LeaveManagement\LeaveForm;
use Illuminate\Support\Facades\Mail;
use App\Mail\LateRemindMail;

class LateRemindCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lateremind:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind to user when attendance check in lately';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->format('Y-m-d');

        $users = User::select("id","name","employee_name","noti_type","email","phone")->where("check_ns_rs","=",1)->orderBy("id","asc")->get();

        foreach($users as $key=>$value){
            if(isRentalDriver($value->id)){
                $marubeni_holiday = HolidayType::where("name","like","%Marubeni%")->first();
                $holiday = Holiday::where("date","=",$date)->first();
                $att_date = new Carbon($date);
                if(($holiday and $holiday->holiday_type_id!=$marubeni_holiday->id) or $att_date->dayOfWeek == Carbon::SUNDAY)
                    continue;
                else{
                    $attendance = Attendance::where([["user_id","=",$value->id],["date","=",$date]])->first();
                    if(!$attendance){
                        $leave = LeaveForm::where("user_id","=",$value->id)->whereRaw("? between from_date and to_date", [$date])->first();
                        
                        if(!$leave){
                            if($value->noti_type=="email")
                                Mail::to($value->email)->send(new LateRemindMail($value));
                            else{
                                $message = "Dear $value->employee_name\nYou haven't checked in for today. Please check in";
                                sendSMS($value->phone,$message);
                            }
                        }
                        
                    }
                }
            }
            else if(isDriver($value->id) or isQcStaff($value->id)){
                $holiday = Holiday::where("date","=",$date)->first();
                $att_date = new Carbon($date);
                if($holiday or $att_date->dayOfWeek == Carbon::SUNDAY)
                    continue;
                else{
                    $attendance = Attendance::where([["user_id","=",$value->id],["date","=",$date]])->first();
                    if(!$attendance){
                        $leave = LeaveForm::where("user_id","=",$value->id)->whereRaw("? between from_date and to_date", [$date])->first();
                        if(!$leave){
                            if($value->noti_type=="email")
                                Mail::to($value->email)->send(new LateRemindMail($value));
                            else{
                                $message = "Dear $value->employee_name\nYou haven't checked in for today. Please check in";
                                sendSMS($value->phone,$message);
                            }
                        }
                        
                    }
                }
            }
            else{
                $holiday = Holiday::where("date","=",$date)->first();
                $att_date = new Carbon($date);
                if($holiday or $att_date->dayOfWeek == Carbon::SATURDAY or $att_date->dayOfWeek == Carbon::SUNDAY)
                    continue;
                else{
                    $attendance = Attendance::where([["user_id","=",$value->id],["date","=",$date]])->first();
                    if(!$attendance){
                        $leave = LeaveForm::where("user_id","=",$value->id)->whereRaw("? between from_date and to_date", [$date])->first();
                        if(!$leave){
                            if($value->noti_type=="email")
                                Mail::to($value->email)->send(new LateRemindMail($value));
                            else{
                                $message = "Dear $value->employee_name\nYou haven't checked in for today. Please check in";
                                sendSMS($value->phone,$message);
                            }
                        }
                        
                    }
                }
            }
        }
    }
}
