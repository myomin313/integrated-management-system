<?php

namespace App\Http\Controllers\OTManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\OTManagement\MonthlyOTRequestRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceManagement\Attendance;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use App\Models\LeaveManagement\LeaveType;
use App\Models\OTManagement\DailyOtRequest;
use App\Models\OTManagement\MonthlyOtRequest;
use App\Models\OTManagement\MonthlyOtRequestDetail;
use Carbon\Carbon;
use DB;
use Excel;
use App\Exports\AnnualOTSummaryExport;
use App\Exports\AnnualOTSummaryHrExport;
use App\Exports\MonthlyOTSummaryExport;
use App\Exports\IndividualOTDetailExport;
use App\Exports\IndividualOTRentalExport;
use App\Exports\IndividualOTSummaryExport;
use App\Exports\MonthlyOTDetailExport;
use App\Exports\MonthlyOTRentalExport;
use App\Exports\ApprovedListExport;
use App\Helpers\LogActivity;

class MonthlyOTRequestController extends Controller
{
    public function __construct(MonthlyOTRequestRepository $request_gestion){
        $this->request_gestion=$request_gestion;

    }

    public function index(Request $request)
    {
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_ot_requests.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $monthlyrequests=$this->request_gestion->indexOrder($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,     
            $request->department=='all'?null:$request->department,
            isset($request->status)?$request->status:'all',$n);
        $employees = User::select('id','name','employee_name')->get();
        $departments = Department::all();
        //return $monthlyrequests;
        return view('otmanagement.monthlyotrequest.index',compact('monthlyrequests','employees','departments'));
    }
    public function indexStaff(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_ot_requests.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $monthlyrequests=$this->request_gestion->indexOrderStaff($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:'',     
            isset($request->to_date)?$request->to_date:'',     
            $request->employee=='all'?null:$request->employee,     
            $request->department=='all'?null:$request->department,$n);
        $employees = User::select('id','name','employee_name')->get();
        $departments = Department::all();
        //return $monthlyrequests;
        return view('otmanagement.monthlyotrequest.index_staff',compact('monthlyrequests','employees','departments'));
    }
    public function indexDeptGM(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_ot_requests.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $monthlyrequests=$this->request_gestion->indexOrderDeptGM($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:'',     
            isset($request->to_date)?$request->to_date:'',     
            $request->employee=='all'?null:$request->employee,     
            $request->department=='all'?null:$request->department,
            isset($request->status)?$request->status:0,$n);
        $employees = User::select('id','name','employee_name')->get();
        $departments = Department::all();
        //return $monthlyrequests;
        return view('otmanagement.monthlyotrequest.index_dept_gm',compact('monthlyrequests','employees','departments'));
    }

    public function indexAccount(Request $request)
    {
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_ot_requests.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $monthlyrequests=$this->request_gestion->indexOrderAccount($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:null,     
            isset($request->to_date)?$request->to_date:null,     
            $request->employee=='all'?null:$request->employee,     
            $request->department=='all'?null:$request->department,
            isset($request->status)?$request->status:0,$n);
        $employees = User::select('id','name','employee_name')->get();
        $departments = Department::all();
        //return $monthlyrequests;
        return view('otmanagement.monthlyotrequest.index_account',compact('monthlyrequests','employees','departments'));
    }

    public function indexAdminGM(Request $request)
    {
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_ot_requests.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $monthlyrequests=$this->request_gestion->indexOrderAdminGM($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:null,     
            isset($request->to_date)?$request->to_date:null,     
            $request->employee=='all'?null:$request->employee,     
            $request->department=='all'?null:$request->department,
            isset($request->status)?$request->status:0,$n);
        $employees = User::select('id','name','employee_name')->get();
        $departments = Department::all();
        //return $monthlyrequests;
        return view('otmanagement.monthlyotrequest.index_admin_gm',compact('monthlyrequests','employees','departments'));
    }

    public function changeStatus(Request $request,$user,$type){
        //return $request->all();
        $validate_array = [
            'id.*' => 'required',
            'm_id'=>'required'
        ];
        $this->validate($request,$validate_array);

        // if(!isset($request->all_request))
        //     return redirect()->back()->with("success_update","Please select at least one record");
        $changestatus = $this->request_gestion->changeStatus($request->all(),$user,$type,$request->ip());
        return redirect()->back()->with("success_create","Successfully change the status");
    }
    public function changeStatusDeptGM(Request $request,$user,$type){
        $approved_id = array();
        if(isset($request->all_accept_request))
            $approved_id = array_merge($approved_id,$request->all_accept_request);
        if(isset($request->all_reject_request))
            $approved_id = array_merge($approved_id,$request->all_reject_request);
        if(count($approved_id)!=count($request->all_id)){
            $monthly_ot = MonthlyOtRequest::findOrFail($request->m_id);
            $user = User::findOrFail($monthly_ot->user_id);
            return redirect()->back()->with("success_update","Please check all accept or reject checkbox for ".$user->employee_name);
        }
        //return $request->all();
        $validate_array = [
            'id.*' => 'required',
            'm_id'=>'required'
        ];

        if(!isset($request->all_accept_request) and !isset($request->all_reject_request))
            return redirect()->back()->with("success_update","Please check Accept or Reject checkbox");

        $this->validate($request,$validate_array);

        // if(!isset($request->all_request))
        //     return redirect()->back()->with("success_update","Please select at least one record");
        $changestatus = $this->request_gestion->changeStatusDeptGM($request->all(),$user,$type,$request->ip());
        return redirect()->back()->with("success_create","Successfully change the status");
    }

    public function changeStatusAccount(Request $request,$user,$type){

        $approved_id = array();
        if(isset($request->all_accept_request))
            $approved_id = array_merge($approved_id,$request->all_accept_request);
        if(isset($request->all_reject_request))
            $approved_id = array_merge($approved_id,$request->all_reject_request);
        if(count($approved_id)!=count($request->all_id)){
            $monthly_ot = MonthlyOtRequest::findOrFail($request->m_id);
            $user = User::findOrFail($monthly_ot->user_id);
            return redirect()->back()->with("success_update","Please check all accept or reject checkbox for ".$user->employee_name);
        }
        //return $request->all();
        $validate_array = [
            'id.*' => 'required',
            'm_id'=>'required'
        ];
        if(!isset($request->all_accept_request) and !isset($request->all_reject_request))
            return redirect()->back()->with("success_update","Please check Accept or Reject checkbox");
        $this->validate($request,$validate_array);

        $changestatus = $this->request_gestion->changeStatusAccount($request->all(),$user,$type,$request->ip());
        return redirect()->back()->with("success_create","Successfully change the status");
    }
    public function changeStatusAdminGM(Request $request,$user,$type){
        $approved_id = array();
        if(isset($request->all_accept_request))
            $approved_id = array_merge($approved_id,$request->all_accept_request);
        if(isset($request->all_reject_request))
            $approved_id = array_merge($approved_id,$request->all_reject_request);
        if(count($approved_id)!=count($request->all_id)){
            $monthly_ot = MonthlyOtRequest::findOrFail($request->m_id);
            $user = User::findOrFail($monthly_ot->user_id);
            return redirect()->back()->with("success_update","Please check all accept or reject checkbox for ".$user->employee_name);
        }
        //return $request->all();
        $validate_array = [
            'id.*' => 'required',
            'm_id'=>'required'
        ];
        if(!isset($request->all_accept_request) and !isset($request->all_reject_request))
            return redirect()->back()->with("success_update","Please check Accept or Reject checkbox");
        $this->validate($request,$validate_array);

        $changestatus = $this->request_gestion->changeStatusAdminGM($request->all(),$user,$type,$request->ip());
        return redirect()->back()->with("success_create","Successfully change the status");
    }

    public function annualOTSummary(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('Y');
        $month = Carbon::now()->format('F');
        $month = strtolower($month);

        if($month=='january' || $month=="february" ||$month=="march")
            $today = $today-1;

        if(!isset($request->name) || $request->name==null){
            $request->name='annual_ot_summaries.user_id';
        }
        if(!isset($request->order) || $request->order==null){
            $request->order='asc';
        }
        if(!isset($request->display_type) || $request->display_type==null){
            $request->display_type='amount';
        }
        $otsummaries=$this->request_gestion->annualOTSummary($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,$n);
        //return $otsummaries;
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();

        if($request->display_type=="amount"){
            return view('otmanagement.monthlyotrequest.annual-summary',compact('otsummaries','employees','branches'));
        }
        else{
            return view('otmanagement.monthlyotrequest.annual-summary-hr',compact('otsummaries','employees','branches'));
        }
        
    }

    public function annualOTSummaryDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('Y');
        $month = Carbon::now()->format('F');
        $month = strtolower($month);

        if($month=='january' || $month=="february" ||$month=="march")
            $today = $today-1;

        if(!isset($request->name) || $request->name==null){
            $request->name='annual_ot_summaries.user_id';
        }
        if(!isset($request->order) || $request->order==null){
            $request->order='asc';
        }
        if(!isset($request->display_type) || $request->display_type==null){
            $request->display_type='amount';
        }
        $otsummaries=$this->request_gestion->annualOTSummary($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Annual_OT_Summary".Carbon::now()->format('d-m-Y');
        if($request->display_type=="amount"){
            return Excel::download(new AnnualOTSummaryExport($otsummaries,$from_date,$to_date,$employee), $filename.'.xlsx');
        }
        else{
            return Excel::download(new AnnualOTSummaryHrExport($otsummaries,$from_date,$to_date,$employee), $filename.'.xlsx');
        }
        
    }

    public function monthlyOTSummary(Request $request)
    {
        
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='user_id';
        }
        if(!isset($request->order) || $request->order==null){
            $request->order='asc';
        }
        if(!isset($request->type) || $request->type==null){
            $request->type = 1;
        }
        $employees = User::select('id','name','employee_name')->get();
        $employee_types = EmployeeType::all();
        if(isRentalEmployeeType($request->type)){
            $otsummaries=$this->request_gestion->monthlyOTDetail($request->name,
                $request->order,
                isset($request->date)?$request->date:$today,     
                isset($request->date)?$request->date:$today,     
                $request->employee=='all'?null:$request->employee,
                isset($request->type)?$request->type:1,$n);
            //return $otsummaries;
            return view('otmanagement.monthlyotrequest.monthly-rental',compact('otsummaries','employees','employee_types'));
        }
        else if(isDriverlEmployeeType($request->type)){
            $otsummaries=$this->request_gestion->monthlyOTDetail($request->name,
                $request->order,
                isset($request->date)?$request->date:$today,     
                isset($request->date)?$request->date:$today,     
                $request->employee=='all'?null:$request->employee,
                isset($request->type)?$request->type:1,$n);
            //return $otsummaries;
            return view('otmanagement.monthlyotrequest.monthly-detail',compact('otsummaries','employees','employee_types'));
        }
        else{
            $otsummaries=$this->request_gestion->monthlyOTSummary($request->name,
                $request->order,
                isset($request->date)?$request->date:$today,     
                isset($request->date)?$request->date:$today,     
                $request->employee=='all'?null:$request->employee,
                isset($request->type)?$request->type:1,$n);
            //return $otsummaries;
            return view('otmanagement.monthlyotrequest.monthly-summary',compact('otsummaries','employees','employee_types'));
        }
        // if($request->type==1 or $request->type==7 or $request->type==5 or $request->type==8 or $request->type==10){
            
        // }
        // else{
            
        // }
            
    }

    public function monthlyOTSummaryDownload(Request $request)
    {
        
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='user_id';
        }
        if(!isset($request->order) || $request->order==null){
            $request->order='asc';
        }
        if(!isset($request->type) || $request->type==null){
            $request->type = 1;
        }

        if(isRentalEmployeeType($request->type)){
            $otsummaries=$this->request_gestion->monthlyOTDetail($request->name,
                $request->order,
                isset($request->date)?$request->date:$today,     
                isset($request->date)?$request->date:$today,     
                $request->employee=='all'?null:$request->employee,
                isset($request->type)?$request->type:1,$n);

            $date = isset($request->date)?$request->date:$today;  
            $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');
            $type = isset($request->type)?getEmployeeType($request->type):getEmployeeType(1);

            $filename="Monthly_OT_Summary".Carbon::now()->format('d-m-Y');
            return Excel::download(new MonthlyOTRentalExport($otsummaries,$date,$employee,$type), $filename.'.xlsx');
        }
        else if(isDriverlEmployeeType($request->type)){
            $otsummaries=$this->request_gestion->monthlyOTDetail($request->name,
                $request->order,
                isset($request->date)?$request->date:$today,     
                isset($request->date)?$request->date:$today,     
                $request->employee=='all'?null:$request->employee,
                isset($request->type)?$request->type:1,$n);

            $date = isset($request->date)?$request->date:$today;  
            $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');
            $type = isset($request->type)?getEmployeeType($request->type):getEmployeeType(1);

            $filename="Monthly_OT_Summary".Carbon::now()->format('d-m-Y');
            return Excel::download(new MonthlyOTDetailExport($otsummaries,$date,$employee,$type), $filename.'.xlsx');
        }
        else{
            $otsummaries=$this->request_gestion->monthlyOTSummary($request->name,
                $request->order,
                isset($request->date)?$request->date:$today,     
                isset($request->date)?$request->date:$today,     
                $request->employee=='all'?null:$request->employee,
                isset($request->type)?$request->type:1,$n);
            $date = isset($request->date)?$request->date:$today;  
            $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');
            $type = isset($request->type)?getEmployeeType($request->type):getEmployeeType(1);

            $filename="Monthly_OT_Summary".Carbon::now()->format('d-m-Y');
            return Excel::download(new MonthlyOTSummaryExport($otsummaries,$date,$employee,$type), $filename.'.xlsx');
        }
        
        
            
    }

    public function monthlyOTIndividual(Request $request){
        $user_id = $request->id;
        $date = $request->date;
        if(isset($request->type)){
            $type = $request->type;
        }
        else{
            if(isDriver($user_id) or isAssistant($user_id))
                $type = "driver";
            else
                $type = "staff";
        }
        //$type = isset($request->type)?$request->type:"staff";
        $otdetails=$this->request_gestion->monthlyOTIndividual($user_id,$date,$type);
        //return $otdetails;
        $user = User::findOrFail($user_id);
        $employees = User::select('id','name','employee_name')->get();
        //return $date;
        //return $otdetails;
        if(isRentalDriver($user->id)){
            return view('otmanagement.monthlyotrequest.individual-rental',compact('otdetails','user','user_id','date','employees',"type"));
        }
        else if(isDriver($user->id)){
            return view('otmanagement.monthlyotrequest.monthly-individual',compact('otdetails','user','user_id','date','employees',"type"));
        }
        else{
            return view('otmanagement.monthlyotrequest.individual-summary',compact('otdetails','user','user_id','date','employees',"type"));
        }
        
    }

    public function monthlyOTIndividualDownload(Request $request){
        $user_id = $request->id;
        $date = $request->date;
        if(isset($request->type)){
            $type = $request->type;
        }
        else{
            if(isDriver($user_id) or isAssistant($user_id))
                $type = "driver";
            else
                $type = "staff";
        }
        $otdetails=$this->request_gestion->monthlyOTIndividual($user_id,$date,$type);
        $user = User::findOrFail($user_id);
        //return $otdetails;

        $filename=$user->employee_name."_OT_Detail_".Carbon::now()->format('d-m-Y');
        if(isRentalDriver($user_id)){
            return Excel::download(new IndividualOTRentalExport($otdetails,$user_id,$date,$user), $filename.'.xlsx');
        }
        else if(isDriver($user_id))
            return Excel::download(new IndividualOTDetailExport($otdetails,$user_id,$date,$user), $filename.'.xlsx');
        else
            return Excel::download(new IndividualOTSummaryExport($otdetails,$user_id,$date,$user), $filename.'.xlsx');
    }

    public function approvedDownload(Request $request,$user,$type){
        
        $index_arr = explode("_", $user);
        $ot_date = $index_arr[0];
        $user_id = $index_arr[1];

        $user = User::findOrFail($user_id);
        $monthlyot = MonthlyOtRequest::where([["user_id","=",$user->id],["date","=",$ot_date]])->first();
        $monthlyotdetail = MonthlyOtRequestDetail::where("monthly_ot_request_id","=",$monthlyot->id)->orderBy("apply_date","asc")->get();

        //return view("otmanagement.monthlyotdriver.export-ot",compact("user","monthlyot","monthlyotdetail"));
        $filename="OT For $user->employee_name $ot_date";
        return Excel::download(new ApprovedListExport($user,$monthlyot,$monthlyotdetail,"staff"), $filename.'.xlsx');
    }

}
