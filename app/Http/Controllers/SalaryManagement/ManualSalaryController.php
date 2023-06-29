<?php

namespace App\Http\Controllers\SalaryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SalaryManagement\ManualSalaryRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterManagement\Branch;
use App\Models\SalaryManagement\Salary;
use App\Models\SalaryManagement\RsSalary;
use App\Models\SalaryManagement\NsSalary;
use App\Models\SalaryManagement\ExchangeRate;
use App\Models\SalaryManagement\PaymentExchangeRate;
use App\Models\LeaveManagement\LeaveType;
use App\Models\LeaveManagement\LeaveForm;
use App\Models\EmployeeManagement\Evaluation;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use DB;
use App\Exports\MonthlySalaryListExport;
use App\Exports\PayListBankExport;
use App\Exports\PayListNsExport;
use App\Exports\PayListRsExport;
use App\Exports\NSPaySlipDetailExport;
use App\Exports\RSPaySlipDetailExport;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Http;
use Excel;

class ManualSalaryController extends Controller
{
    public function __construct(ManualSalaryRepository $salary_gestion){
        $this->salary_gestion=$salary_gestion;
        $this->middleware('permission:calculate-salary', ['only' => ['create','store']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = User::select("id","name","employee_name")->get();

        return view("salarymanagement.manualsalary.calculate",compact("employees"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $salary = Salary::where([["user_id","=",$request->user_id],["year","=",$request->year],["month","=",Carbon::parse($request->month.", ".$request->year)->format('m')]])->first();
        if($salary)
            $salary = $salary->id;
        else
            $salary = 0;
        $date = Carbon::parse($request->month.", ".$request->year)->format('Y-m-01');
        $payment_exchange_rate = PaymentExchangeRate::where("date","=",$date)->first();
        if(!$payment_exchange_rate)
            return redirect()->back()->with("success_update","There is no payment exchange rate for ".Carbon::parse($request->month.", ".$request->year)->format('F, Y'));

        $exchange_rate = ExchangeRate::where("date","=",Carbon::now()->format("Y-m-d"))->first();
        if(!$exchange_rate){
            $response = Http::get('http://forex.cbm.gov.mm/api/latest');
    
            $jsonData = $response->json();

            $exchange_rate = new \stdClass();
            $exchange_rate->dollar = str_replace( ',', '', $jsonData['rates']['USD']);
            $exchange_rate->yen = str_replace( ',', '', $jsonData['rates']['JPY']);;

        }
        return redirect()->route("salary.add-salary-form",["user_id"=>$request->user_id,"month"=>$request->month,"year"=>$request->year,"exchange_rate_usd"=>$payment_exchange_rate->usd,"central_exchange_rate"=>$exchange_rate->dollar,"exchange_rate_yen"=>$exchange_rate->yen,"leave_from_date"=>$request->leave_from_date,"leave_to_date"=>$request->leave_to_date,"salary"=>$salary]);

        
    }

    public function salaryForm(Request $request)
    {
        //return $request;
        $user = User::findOrFail($request->user_id);
        $month_name = $request->month;
        $year = $request->year;
        $usd = $request->exchange_rate_usd;
        $central_usd = $request->central_exchange_rate;
        $yen = $request->exchange_rate_yen;
        $edit_salary = $request->salary;

        $leave_from_date = $request->leave_from_date;
        $leave_to_date = $request->leave_to_date;

        $exchange_rate = new \stdClass();
        $exchange_rate->dollar = $usd;
        $exchange_rate->central_dollar = $central_usd;
        $exchange_rate->yen = $yen;        

        $start_day = Carbon::parse($month_name.", ".$year)->format('Y-m-01');
        $previous_month = Carbon::parse($month_name.", ".$year)->subMonth()->format('Y-m');
        $current_month = Carbon::parse($month_name.", ".$year)->format('Y-m');

        $evaluation = Evaluation::select("performance")->where([['user_id','=',$user->id],['year','=',($year-1)]])->first();
        if($user->check_ns_rs==1){

            $month = strtolower($request->month);
            $year1 = $year;
            if($month=='january' || $month=="february" ||$month=="march")
                $year1 = $year-1;
            
            //return $month."-".$year;
            $salary = NsSalary::select("$month")->where([["user_id","=",$user->id],["year","=",$year1],["salary_type","=","salary"]])->first();
            if($salary)
                $monthly_salary = $salary->$month;
            else
                $monthly_salary = 0;

            if(!isReceptionist($user->id)){
                $ot_exchange_rate = getOTExchangeRate($user->id,Carbon::parse($start_day)->subMonthsNoOverflow()->format('m/Y'));

                $salary_mmk = ($exchange_rate->dollar * $monthly_salary);
                if($salary_mmk>=300000)
                    $salary_mmk = 300000;

                $ssc_amount = $salary_mmk * 2 /100;

                if($evaluation)
                    $performance = $evaluation->performance;
                else
                    $performance = 'D';
                if($edit_salary){
                    $old_salary = Salary::findOrFail($edit_salary);
                    return view("salarymanagement.manualsalary.ns-salary-edit",compact("user","month_name","year","exchange_rate","salary","monthly_salary","ssc_amount","performance","leave_from_date","leave_to_date","edit_salary","previous_month","current_month","old_salary","ot_exchange_rate"));
                }
                else
                    return view("salarymanagement.manualsalary.ns-salary",compact("user","month_name","year","exchange_rate","salary","monthly_salary","ssc_amount","performance","leave_from_date","leave_to_date","edit_salary","previous_month","current_month","ot_exchange_rate"));
            }
            else{
                $from_date = Carbon::parse($month_name.", ".$year)->format('Y-m-01');
                $to_date = Carbon::parse($month_name.", ".$year)->endOfMonth()->format('Y-m-d');
                $working_hour = getWorkingHour($user->id,$from_date,$to_date);
                $hourly_rate = getNSFieldWithId($user->id,"hourly_rate");
                return view("salarymanagement.manualsalary.receptionist",compact("user","month_name","year","exchange_rate","salary","monthly_salary","working_hour","leave_from_date","leave_to_date","hourly_rate","edit_salary"));
            }
                
        }
        else{
            $month = strtolower($request->month);
            $year1 = $year;
            if($month=='january' || $month=="february" ||$month=="march")
                $year1 = $year-1;
            
            //return $month."-".$year;
            $salary = RsSalary::select("$month")->where([["user_id","=",$user->id],["year","=",$year1],["salary_type","=","mm_salary"]])->first();
            if($salary)
                $monthly_salary = $salary->$month;
            else
                $monthly_salary = 0;

            if($evaluation)
                $performance = $evaluation->performance;
            else
                $performance = 'D';
            if($edit_salary){
                $old_salary = Salary::findOrFail($edit_salary);
                return view("salarymanagement.manualsalary.rs-salary-edit",compact("user","month_name","year","exchange_rate","salary","monthly_salary","performance","leave_from_date","leave_to_date","edit_salary","old_salary"));
            }
            else{
                return view("salarymanagement.manualsalary.rs-salary",compact("user","month_name","year","exchange_rate","salary","monthly_salary","performance","leave_from_date","leave_to_date","edit_salary"));
            }
            
        }

        
    }

    function storeSalary(Request $request)
    {
        //return $request->all();
        $salary = Salary::where([["user_id","=",$request->user_id],["year","=",$request->year],["month","=",Carbon::parse($request->month.", ".$request->year)->format('m')]])->first();
        
        if($request->employee_type=="receptionist"){
            
            $this->salary_gestion->storeReceptionist($request->all());
        }
        else if($request->employee_type=="ns"){
            $this->salary_gestion->storeNS($request->all());
        }
        else{
            $this->salary_gestion->storeRS($request->all());
        }

        return redirect("salary-management/add-salary")->with('success_create','Successfully add the salary !!!');
    }


}
