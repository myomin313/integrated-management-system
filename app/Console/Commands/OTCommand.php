<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Helpers\OTHelper;
use Illuminate\Support\Facades\DB;
use App\Models\MasterManagement\EmployeeType;
use App\Models\MasterManagement\Holiday;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTMonthlyRequestMail;
use Illuminate\Support\Facades\Auth;
class OTCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ot:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Store ot from attendance table';

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

        $date = Carbon::now()->subMonth()->format('Y-m');
        
        $rental_drivers = EmployeeType::where("type","like","%Rental Driver%")->pluck('id');

        $drivers = EmployeeType::where("type","not like","%Rental Driver%")->where("type","like","%Driver%")->pluck('id');

        $assistant = EmployeeType::where("type","like","%Assistant%")->pluck('id');

        $rental_user = User::whereIn("employee_type_id",$rental_drivers)->pluck('id');

        $driver_user = User::whereIn("employee_type_id",$drivers)->pluck('id');
        $assistant_user = User::whereIn("employee_type_id",$assistant)->pluck('id');
        DB::beginTransaction();
            OTHelper::storeMonthlyOTRental($date,$rental_user);
            OTHelper::storeMonthlyOTDriver($date,$driver_user);
            OTHelper::storeMonthlyOTAssistant($date,$assistant_user);
        DB::commit();

        //$department_ids = User::whereIn("employee_type_id",$rental_drivers)->orWhereIn("employee_type_id",$drivers)->orWhereIn("employee_type_id",$assistant)->groupBy("department_id")->pluck('department_id');
        //$users = User::permission('change-ot-admin-status')->whereIn("department_id",$department_ids)->get();
        $terms = User::whereIn("employee_type_id",$rental_drivers)->orWhereIn("employee_type_id",$drivers)->orWhereIn("employee_type_id",$assistant)->groupBy("department_id")->pluck('department_id');
        $users=User::permission('change-ot-admin-status')->where(function($query) use($terms) {
                    foreach($terms as $term) {
                        $query->whereRaw("find_in_set('".$term."',users.department_id)");
                    };
                })->select('users.email','users.employee_name','users.noti_type')->get();

        if($users){
            foreach($users as $key=>$value){
                if($value->noti_type=="email")
                    Mail::to($value->email)->send(new OTMonthlyRequestMail($value,Carbon::now()->format("Y-m-d"),Auth::user(),"driver"));
            }
        }
        $this->info("Successfully save overtime for ".$date);
    }
}
