<?php

namespace App\Http\Controllers\SalaryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SalaryManagement\SalaryRepository;
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

class SalaryController extends Controller
{
    public function __construct(SalaryRepository $salary_gestion){
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

        return view("salarymanagement.salary.calculate",compact("employees"));
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
        return redirect()->route("salary.salary-form",["user_id"=>$request->user_id,"month"=>$request->month,"year"=>$request->year,"exchange_rate_usd"=>$payment_exchange_rate->usd,"central_exchange_rate"=>$exchange_rate->dollar,"exchange_rate_yen"=>$exchange_rate->yen,"leave_from_date"=>$request->leave_from_date,"leave_to_date"=>$request->leave_to_date,"salary"=>$salary]);

        
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
        
        // $from_date = Carbon::parse($month_name.", ".$year)->subMonth()->format('Y-m-25');
        // $to_date = Carbon::parse($month_name.", ".$year)->format('Y-m-24');

        //$previous_month = Carbon::parse($month_name.", ".$year)->subMonth()->format('Y-m');

        $start_day = Carbon::parse($month_name.", ".$year)->format('Y-m-01');
        $previous_month = Carbon::parse($start_day)->subMonthsNoOverflow()->format('Y-m');
        $current_month = Carbon::parse($month_name.", ".$year)->format('Y-m');
        $ot_hr = getMonthlyOTHour($user->id,$previous_month);
        $current_ot_hr = getMonthlyOTHour($user->id,$current_month);
        

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
                $ot_payment = getOTPayment($user->id,siteformat_date($previous_month.'-01'));

                $normal_payment = getOTAmount($ot_hr->normal , $ot_payment);
                $sat_payment = getOTAmount($ot_hr->saturday , $ot_payment);
                $sun_payment = getOTAmount($ot_hr->sunday , $ot_payment);
                $public_payment = getOTAmount($ot_hr->public_holiday , $ot_payment);

                $taxi_charge = getTaxiCharge($user->id,siteformat_date($previous_month.'-01'));
                $total_ot_payment = $normal_payment + $sat_payment + $sun_payment + $public_payment + $taxi_charge;

                $ot_exchange_rate = getOTExchangeRate($user->id,Carbon::parse($start_day)->subMonthsNoOverflow()->format('m/Y'));

                $salary_mmk = ($exchange_rate->dollar * $monthly_salary) + ($ot_exchange_rate * $total_ot_payment);
                if($salary_mmk>=300000)
                    $salary_mmk = 300000;

                $ssc_amount = $salary_mmk * 2 /100;

                $unpaid_leave = LeaveType::where("type","=","Unpaid")->pluck("id");
                $total_leave_day = getTotalLeave($user->id,$leave_from_date,$leave_to_date,$unpaid_leave);
                //return $total_leave_day;
                if($evaluation)
                    $performance = $evaluation->performance;
                else
                    $performance = 'D';
                
                if($edit_salary){
                    $old_salary = Salary::findOrFail($edit_salary);
                    return view("salarymanagement.salary.ns-salary-edit",compact("user","month_name","year","ot_hr","current_ot_hr","exchange_rate","salary","monthly_salary","ssc_amount","previous_month","current_month","total_leave_day","performance","leave_from_date","leave_to_date","edit_salary","ot_exchange_rate","old_salary"));
                }
                else
                    return view("salarymanagement.salary.ns-salary",compact("user","month_name","year","ot_hr","current_ot_hr","exchange_rate","salary","monthly_salary","ssc_amount","previous_month","current_month","total_leave_day","performance","leave_from_date","leave_to_date","edit_salary","ot_exchange_rate"));
            }
            else{
                $from_date = Carbon::parse($month_name.", ".$year)->format('Y-m-01');
                $to_date = Carbon::parse($month_name.", ".$year)->endOfMonth()->format('Y-m-d');
                $working_hour = getWorkingHour($user->id,$from_date,$to_date);
                $hourly_rate = getNSFieldWithId($user->id,"hourly_rate");
                return view("salarymanagement.salary.receptionist",compact("user","month_name","year","ot_hr","exchange_rate","salary","monthly_salary","working_hour","leave_from_date","leave_to_date","hourly_rate","edit_salary"));
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
                    return view("salarymanagement.salary.rs-salary-edit",compact("user","month_name","year","ot_hr","exchange_rate","salary","monthly_salary","performance","leave_from_date","leave_to_date","edit_salary","old_salary"));
            }
            else
                return view("salarymanagement.salary.rs-salary",compact("user","month_name","year","ot_hr","exchange_rate","salary","monthly_salary","performance","leave_from_date","leave_to_date","edit_salary"));
        }

        
    }

    function storeSalary(Request $request)
    {
        //return $request->all();
        $salary = Salary::where([["user_id","=",$request->user_id],["year","=",$request->year],["month","=",Carbon::parse($request->month.", ".$request->year)->format('m')]])->first();
        // if($salary)
        //     return redirect()->back()->with("success_update","Salary of '".$request->employee_name."' for ".$request->pay_for." already exists.");

        if($request->employee_type=="receptionist"){
            // $validate_array = [
            //     'hourly_rate_usd'=>'required',
            //     'name'=>'required|string|max:255|unique:users',
            // ];
            // if($request->noti_type=='email')
            //     $validate_array["email"] = "required|string|email|max:255|unique:users";
            // else
            //     $validate_array["phone"] = "required|numeric|regex:/^(09)/i";

            // $this->validate($request,$validate_array, [
            //     'phone.regex' => 'Phone Number must start with 09 and must be number.'
            // ]);
            $this->salary_gestion->storeReceptionist($request->all());
        }
        else if($request->employee_type=="ns"){
            $this->salary_gestion->storeNS($request->all());
        }
        else{
            $this->salary_gestion->storeRS($request->all());
        }

        return redirect("salary-management/calculate-salary")->with('success_create','Successfully calculate the salary !!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salary = Salary::findOrFail($id);
        $exchange_rate = new \stdClass();
        $exchange_rate->dollar = $salary->exchange_rate_usd;
        $exchange_rate->yen = $salary->exchange_rate_yen;
        $user = User::findOrFail($salary->user_id);
        if($salary->employee_type=="ns"){
            $evaluation = Evaluation::select("performance")->where([['user_id','=',$user->id],['year','=',($salary->year-1)]])->first();
            if($evaluation)
                $performance = $evaluation->performance;
            else
                $performance = 'D';
            return view("salarymanagement.salary.ns-salary-edit",compact("salary","exchange_rate","user","performance"));
        }
        else if($salary->employee_type=="rs"){
            return view("salarymanagement.salary.rs-salary-edit",compact("salary","exchange_rate","user"));
        }
        else{
            return view("salarymanagement.salary.receptionist-edit",compact("salary","exchange_rate","user"));
        }
    }
    public function editSalary($user_id,$year)
    {
        $jpn_salary_types = [
        	["type"=>"jpn_salary","label"=>"Salary"],
        	["type"=>"jpn_transfer_from","label"=>"Transfer Salary From Myanmar"],
        	["type"=>"jpn_bonus","label"=>"Bonus"],
        	["type"=>"jpn_adjustment","label"=>"Adjustment"],
        	["type"=>"jpn_income_tax","label"=>"Income Tax Paid in Japan"],
        	["type"=>"jpn_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"jpn_dc","label"=>"DC Contribution"],
        ];
        $mm_salary_types = [
        	["type"=>"mm_salary","label"=>"Salary"],
        	["type"=>"mm_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"mm_dc","label"=>"DC Contribution"],
        ];
       
        $user = User::findOrFail($user_id);
        return view("salarymanagement.rssalary.edit",compact('user','year','jpn_salary_types','mm_salary_types'));
    }

    public function detailSalary($user_id,$year)
    {
        $jpn_salary_types = [
        	["type"=>"jpn_salary","label"=>"Salary"],
        	["type"=>"jpn_transfer_from","label"=>"Transfer Salary From Myanmar"],
        	["type"=>"jpn_bonus","label"=>"Bonus"],
        	["type"=>"jpn_adjustment","label"=>"Adjustment"],
        	["type"=>"jpn_income_tax","label"=>"Income Tax Paid in Japan"],
        	["type"=>"jpn_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"jpn_dc","label"=>"DC Contribution"],
        ];
        $mm_salary_types = [
        	["type"=>"mm_salary","label"=>"Salary"],
        	["type"=>"mm_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"mm_dc","label"=>"DC Contribution"],
        ];
       
        $user = User::findOrFail($user_id);
        return view("salarymanagement.rssalary.detail",compact('user','year','jpn_salary_types','mm_salary_types'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateSalary(Request $request, $user_id, $year)
    {
        //return $request->all();

        $salary = $this->rs_gestion->updateSalary($request->all(),$user_id,$year);

        return redirect()->route("rs-salary.list",["employee"=>"all","year"=>$year])->with("success_create","Successfully update salary information.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function paySlipList(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipList($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        return view('salarymanagement.salary.payslip_list',compact('paylists','employees'));
    }
    public function paySlipDetail($id)
    {
        $salary = Salary::findOrFail($id);
        $user = User::findOrFail($salary->user_id);
        if($salary->employee_type=="rs")
            return view('salarymanagement.salary.rs_payslip_detail',compact('salary','user'));
        else
            return view('salarymanagement.salary.ns_payslip_detail',compact('salary','user'));
    }
    public function paySlipDetailDownload($id)
    {
        
        $salary = Salary::findOrFail($id);
        $user = User::findOrFail($salary->user_id);
        $user_name = $user->employee_name?$user->employee_name:$user->name;
        $filename="Pay_Slip_For_".$user_name."_".Carbon::now()->format('d-m-Y');
        if($salary->employee_type=="rs")
            return Excel::download(new RSPaySlipDetailExport($salary,$user), $filename.'.xlsx');
        else
            return Excel::download(new NSPaySlipDetailExport($salary,$user), $filename.'.xlsx');

        
        
        
    }

    public function monthlySalaryList(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->monthlySalaryList($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        return view('salarymanagement.salary.monthly_salary_list',compact('paylists','employees'));
    }

    public function monthlySalaryListDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->monthlySalaryList($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Monthly_Salary_List".Carbon::now()->format('d-m-Y');
        return Excel::download(new MonthlySalaryListExport($paylists,$from_date,$to_date,$employee), $filename.'.xlsx');
        
        
    }

    public function paySlipBank(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipBank($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        return view('salarymanagement.salary.pay_list_bank',compact('paylists','employees'));
    }

    public function paySlipBankDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipBank($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Pay_List_Bank".Carbon::now()->format('d-m-Y');
        return Excel::download(new PayListBankExport($paylists,$from_date,$to_date,$employee), $filename.'.xlsx');
        
        
    }

    public function paySlipNS(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipNS($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        return view('salarymanagement.salary.pay_list_ns',compact('paylists','employees'));
    }

    public function paySlipNSDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipNS($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Pay_List_NS_Internal".Carbon::now()->format('d-m-Y');
        return Excel::download(new PayListNsExport($paylists,$from_date,$to_date,$employee), $filename.'.xlsx');
        
        
    }

    public function paySlipRS(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipRS($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        return view('salarymanagement.salary.pay_list_rs',compact('paylists','employees'));
    }

    public function paySlipRSDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='salaries.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $paylists=$this->salary_gestion->paySlipRS($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Pay_List_RS_Internal".Carbon::now()->format('d-m-Y');
        return Excel::download(new PayListRsExport($paylists,$from_date,$to_date,$employee), $filename.'.xlsx');
        
        
    }
}
