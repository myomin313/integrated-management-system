<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CarManagement\CarLicense;
use App\Models\CarManagement\Car;
use App\Models\MasterManagement\CarLicenseExpireNoti;
use Illuminate\Support\Facades\Mail;
use App\Mail\CarLicenseExpire;
use DB;

class CarLicenseExpireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carlicenseexpire:daily';

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
      //  $ddate = Carbon::now()->format('Y-m-d');

           $ddate = date('Y-m-d');
      $expire_date = date('Y-m-d', strtotime($ddate. ' + 1 months'));
       // dd($newDate);exit(); 
       $users  = CarLicense::select('users.name','cars.car_number','car_licenses.due_date','users.email')
              ->join('cars','cars.id','=','car_licenses.car_id')
              ->join('users','users.id','=','cars.main_user')
              ->where('car_licenses.due_date','=',$expire_date)
              ->groupBy('car_licenses.car_id')
              ->orderBy('car_licenses.due_date','DESC')
              ->get();
              
               $email_data = CarLicenseExpireNoti::select()->first();
               $first_person_email =$email_data->first_person_email;
               $second_person_email = $email_data->second_person_email;
               
              
        if($users){
            foreach($users as $user){
              // $email = $user->email;
               $name = $user->name;
               $car_number = $user->car_number;
               $expire_date = $expire_date;

                Mail::to($first_person_email)->send(new CarLicenseExpire($name,$car_number,$expire_date));
                Mail::to($second_person_email)->send(new CarLicenseExpire($name,$car_number,$expire_date));
            }
        }
        
    }
}
