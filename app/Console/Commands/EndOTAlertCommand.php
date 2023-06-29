<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\OTManagement\DailyOtRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\EndOTAlertMail;

class EndOTAlertCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'endotalert:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind to user for end ot';

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

        $dailyots = DailyOtRequest::select("id","user_id","apply_date","ot_type")->where("apply_date","<",$date)->where("end_from_time","=",NULL)->where("monthly_request","=",0)->orderBy("id","asc")->get();

        foreach($dailyots as $key=>$value){
            $user = User::findOrFail($value->user_id);
            if($user->noti_type=="email")
                Mail::to($user->email)->send(new EndOTAlertMail($user,$value));
            else{
                $message = "Dear $user->employee_name\nYou need to request for post OT ($value->apply_date)";
                sendSMS($user->phone,$message);
            }
        }
    }
}
