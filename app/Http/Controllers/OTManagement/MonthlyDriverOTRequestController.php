<?php

namespace App\Http\Controllers\OTManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\OTManagement\MonthlyDriverOTRequestRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceManagement\Attendance;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use App\Models\LeaveManagement\LeaveType;
use App\Models\OTManagement\DailyOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequestDetail;
use Carbon\Carbon;
use DB;
use Excel;
use App\Exports\AnnualOTSummaryExport;
use App\Exports\AnnualOTSummaryHrExport;
use App\Exports\MonthlyOTSummaryExport;
use App\Exports\IndividualOTDetailExport;
use App\Exports\IndividualOTSummaryExport;
use App\Exports\MonthlyOTDetailExport;
use App\Exports\ApprovedListExport;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyOTStatusMail;
use App\Mail\AccountAdminMail;
use App\Mail\ManagerAccountMail;

class MonthlyDriverOTRequestController extends Controller
{
    public function __construct(MonthlyDriverOTRequestRepository $request_gestion){
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
            $request->name='monthly_driver_ot_requests.date';
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
        return view('otmanagement.monthlyotdriver.index_staff',compact('monthlyrequests','employees','departments'));
    }

    public function indexDeptGM(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_driver_ot_requests.date';
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
        return view('otmanagement.monthlyotdriver.index_dept_gm',compact('monthlyrequests','employees','departments'));
    }

    public function indexAccount(Request $request)
    {
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_driver_ot_requests.date';
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
        return view('otmanagement.monthlyotdriver.index_account',compact('monthlyrequests','employees','departments'));
    }

    public function indexAdminGM(Request $request)
    {
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='monthly_driver_ot_requests.date';
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
        return view('otmanagement.monthlyotdriver.index_admin_gm',compact('monthlyrequests','employees','departments'));
    }

    public function changeStatusDeptGM(Request $request,$user,$type){
        $approved_id = array();
        if(isset($request->all_accept_request))
            $approved_id = array_merge($approved_id,$request->all_accept_request);
        if(isset($request->all_reject_request))
            $approved_id = array_merge($approved_id,$request->all_reject_request);
        if(count($approved_id)!=count($request->all_id)){
            $monthly_ot = MonthlyDriverOtRequest::findOrFail($request->m_id);
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
        //return $request->all();
        $approved_id = array();
        if(isset($request->all_accept_request))
            $approved_id = array_merge($approved_id,$request->all_accept_request);
        if(isset($request->all_reject_request))
            $approved_id = array_merge($approved_id,$request->all_reject_request);
        if(count($approved_id)!=count($request->all_id)){
            $monthly_ot = MonthlyDriverOtRequest::findOrFail($request->m_id);
            $user = User::findOrFail($monthly_ot->user_id);
            return redirect()->back()->with("success_update","Please check all accept or reject checkbox for ".$user->employee_name);
        }
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
        //return $request->all();
        $approved_id = array();
        if(isset($request->all_accept_request))
            $approved_id = array_merge($approved_id,$request->all_accept_request);
        if(isset($request->all_reject_request))
            $approved_id = array_merge($approved_id,$request->all_reject_request);
        if(count($approved_id)!=count($request->all_id)){
            $monthly_ot = MonthlyDriverOtRequest::findOrFail($request->m_id);
            $user = User::findOrFail($monthly_ot->user_id);
            return redirect()->back()->with("success_update","Please check all accept or reject checkbox for ".$user->employee_name);
        }
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

    public function destroy($id){
        DB::beginTransaction();
    	$detailot = MonthlyDriverOtRequestDetail::findOrFail($id);
        $monthly_ot_id = $detailot->monthly_ot_request_id;
    	$detailot->delete();

        $all_detail_ots = MonthlyDriverOtRequestDetail::select("id")->where([["monthly_ot_request_id","=",$monthly_ot_id],["manager_status","!=",1]])->first();
        if(!$all_detail_ots){
            $monthlyrequest = MonthlyDriverOtRequest::findOrFail($monthly_ot_id);
            $monthlyrequest->manager_main_status = 1;
            $monthlyrequest->save();

            //mail to account
            $users = User::permission('change-ot-account-status')->get();
            if($users){
                foreach($users as $key=>$value){
                    if($value->noti_type=="email")
                        Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'manager',"driver"));
                }
            }
        }

        $remain_detail_ot = MonthlyDriverOtRequestDetail::select("id")->where("monthly_ot_request_id","=",$monthly_ot_id)->first();
        if(!$remain_detail_ot){
            $monthlyot = MonthlyDriverOtRequest::findOrFail($monthly_ot_id);
            $monthlyot->delete();
        }
        
        DB::commit();
    	return redirect()->back()->with("success_create","Successfully delete the ot");
    }

    public function updateEndTime(Request $request)
    {
        $validate_array = [
            'id'=>'required',
            'end_from_time'=>'required',
            'end_to_time'=>'required',
            'end_reason'=>'required'
        ];
        $this->validate($request,$validate_array);

        $monthlyrequest = $this->request_gestion->updateEndTime($request->all());

        $break_hour = $monthlyrequest->end_break_hour?$monthlyrequest->end_break_hour.' hr ':'';
        $break_min = $monthlyrequest->end_break_minute?$monthlyrequest->end_break_minute.' min':'';
        $break_time = $break_hour.$break_min;

        return redirect()->back()->with("success_update","Successfully update the record.");
        return response()->json(['end_from_time'=>siteformat_time24($monthlyrequest->end_from_time),'end_to_time'=>siteformat_time24_nextday($monthlyrequest->end_to_time,$monthlyrequest->start_next_day),'break_time'=>$break_time,'end_reason'=>$monthlyrequest->end_reason,'index'=>$request->index,'end_hotel'=>$monthlyrequest->end_hotel,'end_next_day'=>$monthlyrequest->end_next_day]);
    }

    public function approvedDownload(Request $request,$user,$type){
        
        $index_arr = explode("_", $user);
        $ot_date = $index_arr[0];
        $user_id = $index_arr[1];

        $user = User::findOrFail($user_id);
        $monthlyot = MonthlyDriverOtRequest::where([["user_id","=",$user->id],["date","=",$ot_date]])->first();
        $monthlyotdetail = MonthlyDriverOtRequestDetail::where("monthly_ot_request_id","=",$monthlyot->id)->orderBy("apply_date","asc")->get();

        //return view("otmanagement.monthlyotdriver.export-ot",compact("user","monthlyot","monthlyotdetail"));
        $filename="OT For $user->employee_name $ot_date";
        return Excel::download(new ApprovedListExport($user,$monthlyot,$monthlyotdetail), $filename.'.xlsx');
    }



}
