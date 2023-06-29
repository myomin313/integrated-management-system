<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OTManagement\OtRate;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class OTRateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otrate:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save OT Rate and Hourly Rate in every month';

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
        $date = Carbon::now()->endOfMonth()->format("Y-m-d");
        //$date = Carbon::parse("May, 2023")->endOfMonth()->format("Y-m-d");
        $users = User::whereNotIn("id",[1,22])->orderBy('id','asc')->get();
        foreach($users as $key=>$user){
            $otrate = OtRate::where([["user_id","=",$user->id],["date","=",$date]])->first();
            if(!$otrate){
                $otrate = new OtRate();
            }
            $otrate->user_id = $user->id;
            if(isReceptionist($user->id))
                $otrate->hourly_rate = getNSFieldWithId($user->id,"hourly_rate");
            else{
                if(isRentalDriver($user->id))
                    $otrate->ot_rate = getNSFieldWithId($user->id,"ot_rate");
                else
                    $otrate->ot_rate = getOTPayment($user->id,siteformat_date($date));
            }
            
            
            $otrate->date = $date;
            $otrate->save();
        }
        $this->info("Successfully save the rate");
    }
}
