<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PassportExpireMail;
use DB;

class PassportExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passportexpire:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind to user when car license expire';

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
        
      $ddate = date('Y-m-d');
      $expire_date = date('Y-m-d', strtotime($ddate. ' + 6 months'));
      $users = User::where('date_of_expire','=',$expire_date)
               ->select('employee_name','email','date_of_expire')
               ->get(); 
        if($users){
            foreach($users as $user){

                 $email           =   $user->email;
                 $employee_name   =   $user->employee_name;
                 $passport_date_of_expire     =   $user->date_of_expire;
                Mail::to($email)->send(new PassportExpireMail($employee_name,$passport_date_of_expire));

            }
        }
        
        
    }
}
