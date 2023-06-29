<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\EmployeeManagement\RsEmployee;
use Illuminate\Support\Facades\Mail;
use App\Mail\StayPermitExpireMail;
use DB;

class StayPermitCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staypermitexpire:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind to user when start  license expire';

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
      $expire_date = date('Y-m-d', strtotime($ddate. ' + 2 months'));
      
    
      
      $users = RsEmployee::join('users','users.id','=','rs_employees.user_id')
               ->where('rs_employees.stay_permit_expire_date','=',$expire_date)
               ->select('users.employee_name','users.email','stay_permit_expire_date')
               ->get();
        if($users){
            foreach($users as $user){

                 $email           =   $user->email;
                 $employee_name   =   $user->employee_name;
                 $stay_permit_expire_date     =   $user->stay_permit_expire_date;
                Mail::to($email)->send(new StayPermitExpireMail($employee_name,$stay_permit_expire_date));

            }
        }
        
        
    }
}
