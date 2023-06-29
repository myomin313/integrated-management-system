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

class ReceptionistCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'receptionist:monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monthly Attendance for Receptionist';

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
        
        $receptionist_employee_ids = EmployeeType::where("type","like","%receptionist%")->pluck('id');

        $receptionists = User::whereIn("employee_type_id",$receptionist_employee_ids)->pluck('id');
        DB::beginTransaction();
            OTHelper::storeMonthlyReceptionist($date,$receptionists);
        DB::commit();

        $terms = User::whereIn("employee_type_id",$receptionist_employee_ids)->groupBy("department_id")->pluck('department_id');
        foreach($terms as $key=>$term){
            $users=User::permission('change-ot-admin-status')->where(function($query) use($term) {
                        
                $query->whereRaw("find_in_set('".$term."',users.department_id)");
                        
            })->select('users.email','users.employee_name','users.noti_type')->get();

            if($users){
                foreach($users as $key=>$value){
                    if($value->noti_type=="email")
                        Mail::to($value->email)->send(new OTMonthlyRequestMail($value,Carbon::now()->format("Y-m-d"),Auth::user(),"receptionist"));
                }
            }
        }
        

        $this->info("Successfully save attendance for ".$terms);
    }
}
