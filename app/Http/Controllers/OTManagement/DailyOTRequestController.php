<?php

namespace App\Http\Controllers\OTManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OTManagement\DailyOTRequestRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceManagement\Attendance;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\Holiday;
use App\Models\LeaveManagement\LeaveType;
use App\Models\LeaveManagement\LeaveForm;
use App\Models\OTManagement\DailyOtRequest;
use Carbon\Carbon;
use DB;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTCancelMail;

class DailyOTRequestController extends Controller
{
    public function __construct(DailyOTRequestRepository $request_gestion){
        $this->request_gestion=$request_gestion;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('d/m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='daily_ot_requests.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        //return isset($request->monthly_request)?$request->monthly_request:0;
        $dailyrequests=$this->request_gestion->indexOrder($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:null,     
            isset($request->to_date)?$request->to_date:null,     
            $request->employee=='all'?null:$request->employee,     
            $request->department=='all'?null:$request->department,
            isset($request->status)?$request->status:'0',
            isset($request->monthly_request)?$request->monthly_request:0,$n);
        $employees = User::select('id','name','employee_name')->get();
        $departments = Department::all();
        //return $dailyrequests;
        return view('otmanagement.dailyotrequest.index',compact('dailyrequests','employees','departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $leave = LeaveForm::where("user_id","=",Auth::user()->id)->whereRaw("? between from_date and to_date", [format_dbdate($request->apply_date)])->first();
        if(isset($leave->user_id)){
            return redirect()->back()->withInput($request->input())->with("error","You cannot request OT for this date because you requested Leave Form for this date!!!");
        }

        $ot_type = $request->ot_type;
        if($ot_type=="Weekday" and !isQcStaff(Auth::user()->id)){
            $holiday = Holiday::where("date","=",format_dbdate($request->apply_date))->first();
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($holiday or $request_date->dayOfWeek == Carbon::SATURDAY or $request_date->dayOfWeek == Carbon::SUNDAY){
                return redirect()->back()->withInput($request->input())->with("error","Mismatch OT Type and Apply Date!!!");
            }

            if(strtotime(format_dbtime($request->start_from_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->start_from_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            if(strtotime(format_dbtime($request->start_to_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->start_to_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");

            $working_time = getTimeDiff(Auth::user()->working_start_time,Auth::user()->working_end_time);
            $ot_time = getTimeDiff(format_dbtime($request->start_from_time),format_dbtime($request->start_to_time));
            if($ot_time>$working_time){
                return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            }
        }
        else if($ot_type=="Weekday" and isQcStaff(Auth::user()->id)){
            $holiday = Holiday::where("date","=",format_dbdate($request->apply_date))->first();
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($holiday or $request_date->dayOfWeek == Carbon::SUNDAY){
                return redirect()->back()->withInput($request->input())->with("error","Mismatch OT Type and Apply Date!!!");
            }
            else if($request_date->dayOfWeek == Carbon::SATURDAY){
                if(strtotime(format_dbtime($request->start_from_time))>strtotime(Auth::user()->working_start_time) )
                    return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and 12:00 for Saturday!!!");
                if(strtotime(format_dbtime($request->start_to_time))>strtotime(Auth::user()->working_start_time) )
                    return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and 12:00 for Saturday!!!");
                
            }

            if(strtotime(format_dbtime($request->start_from_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->start_from_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            if(strtotime(format_dbtime($request->start_to_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->start_to_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");

            $working_time = getTimeDiff(Auth::user()->working_start_time,Auth::user()->working_end_time);
            $ot_time = getTimeDiff(format_dbtime($request->start_from_time),format_dbtime($request->start_to_time));
            if($ot_time>$working_time){
                return redirect()->back()->withInput($request->input())->with("error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            }
        }
        else if($ot_type=="Saturday" and !isQcStaff(Auth::user()->id)){
            
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($request_date->dayOfWeek != Carbon::SATURDAY){
                return redirect()->back()->withInput($request->input())->with("error","Mismatch OT Type and Apply Date!!!");
            }
        }
        else if($ot_type=="Saturday" and isQcStaff(Auth::user()->id)){
            
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($request_date->dayOfWeek != Carbon::SATURDAY){
                return redirect()->back()->withInput($request->input())->with("error","Mismatch OT Type and Apply Date!!!");
            }
            if(strtotime(format_dbtime($request->start_from_time))<strtotime("12:00") )
                return redirect()->back()->withInput($request->input())->with("error","OT Time must be  greater than 12:00 for Saturday !!!");
            if(strtotime(format_dbtime($request->start_to_time))<strtotime("12:00") )
                return redirect()->back()->withInput($request->input())->with("error","OT Time must be  greater than 12:00 for Saturday !!!");
                

        }
        else if($ot_type=="Sunday"){
            
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($request_date->dayOfWeek != Carbon::SUNDAY){
                return redirect()->back()->withInput($request->input())->with("error","Mismatch OT Type and Apply Date!!!");
            }
        }
        else{
            
            $holiday = Holiday::where("date","=",format_dbdate($request->apply_date))->first();
            if(!$holiday){
                return redirect()->back()->withInput($request->input())->with("error","Mismatch OT Type and Apply Date!!!");
            }
        
        }

        $validate_array = [
            'ot_type' => 'required',
            'apply_date'=>'required',
            'start_from_time'=>'required',
            'start_to_time'=>'required',
            'start_reason'=>'required'
        ];
        $this->validate($request,$validate_array);

        $marubeni_holiday = false;
        if($marubeni_holiday){
            return response()->json(["status"=>"marubeni_holiday","apply_date"=>$request->apply_date]);
        }
        $dailyrequest = $this->request_gestion->store($request->all());
        $break_hour = $dailyrequest->start_break_hour?$dailyrequest->start_break_hour.' hr':'';
        $break_min = $dailyrequest->start_break_minute?$dailyrequest->start_break_minute.' min':'';
        $start_breaktime = $break_hour.' '.$break_min;

        $manager = "no";
        if(Auth::user()->can("change-ot-manager-status") and $dailyrequest->monthly_request==0)
            $manager = "yes";

        $position = getPositionName(getUserFieldWithId($dailyrequest->user_id,'position_id'));
        $userdepartment = getDepartmentField(getUserFieldWithId($dailyrequest->user_id,'department_id'),'name');

        $url = url("ot-management/daily-ot-request/delete",$dailyrequest->id);
        
        return redirect()->back()->with("success_create","Successfully request for pre overtime!!!");

        return response()->json(['status'=>'ok','id'=>$dailyrequest->id,'user_name'=>getUserFieldWithId($dailyrequest->user_id,'employee_name'),'branch_name'=>getBranchField(getUserFieldWithId($dailyrequest->user_id,'branch_id'),'name'),'apply_date'=>siteformat_date($dailyrequest->apply_date),'start_from_time'=>siteformat_time24($dailyrequest->start_from_time),'start_to_time'=>siteformat_time24_nextday($dailyrequest->start_to_time,$dailyrequest->start_next_day),'start_breaktime'=>$start_breaktime,'start_reason'=>$dailyrequest->start_reason,'manager'=>$manager,'ot_type'=>$dailyrequest->ot_type,'position'=>$position,'department'=>$userdepartment,'requested_date'=>siteformat_date($dailyrequest->created_at),'start_hotel'=>$dailyrequest->start_hotel,'start_next_day'=>$dailyrequest->start_next_day,"url"=>$url]);
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
        //
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
    public function updateEndTime(Request $request)
    {
        $leave = LeaveForm::where("user_id","=",Auth::user()->id)->whereRaw("? between from_date and to_date", [format_dbdate($request->apply_date)])->first();
        if(isset($leave->user_id)){
            return redirect()->back()->withInput($request->input())->with("end_error","You cannot request OT for this date because you requested Leave Form for this date!!!");
        }

        $ot_type = $request->ot_type;
        if($ot_type=="Weekday" and !isQcStaff(Auth::user()->id)){
            $holiday = Holiday::where("date","=",format_dbdate($request->apply_date))->first();
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($holiday or $request_date->dayOfWeek == Carbon::SATURDAY or $request_date->dayOfWeek == Carbon::SUNDAY){
                return redirect()->back()->withInput($request->input())->with("end_error","Mismatch OT Type and Apply Date!!!");
            }

            if(strtotime(format_dbtime($request->end_from_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->end_from_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!rr");
            if(strtotime(format_dbtime($request->end_to_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->end_to_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");

            $working_time = getTimeDiff(Auth::user()->working_start_time,Auth::user()->working_end_time);
            $ot_time = getTimeDiff(format_dbtime($request->end_from_time),format_dbtime($request->end_to_time));
            if($ot_time>$working_time){
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            }
        }
        else if($ot_type=="Weekday" and isQcStaff(Auth::user()->id)){
            $holiday = Holiday::where("date","=",format_dbdate($request->apply_date))->first();
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($holiday or $request_date->dayOfWeek == Carbon::SUNDAY){
                return redirect()->back()->withInput($request->input())->with("end_error","Mismatch OT Type and Apply Date!!!");
            }
            else if($request_date->dayOfWeek == Carbon::SATURDAY){
                if(strtotime(format_dbtime($request->end_from_time))>strtotime(Auth::user()->working_start_time) )
                    return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and 12:00 for Saturday!!!");
                if(strtotime(format_dbtime($request->end_to_time))>strtotime(Auth::user()->working_start_time) )
                    return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and 12:00 for Saturday!!!");
                
            }

            if(strtotime(format_dbtime($request->end_from_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->end_from_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            if(strtotime(format_dbtime($request->end_to_time))>strtotime(Auth::user()->working_start_time) and strtotime(format_dbtime($request->end_to_time))<strtotime(Auth::user()->working_end_time))
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");

            $working_time = getTimeDiff(Auth::user()->working_start_time,Auth::user()->working_end_time);
            $ot_time = getTimeDiff(format_dbtime($request->end_from_time),format_dbtime($request->end_to_time));
            if($ot_time>$working_time){
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must not be within your working time ".Auth::user()->working_start_time." and ".Auth::user()->working_end_time." !!!");
            }
        }
        else if($ot_type=="Saturday" and !isQcStaff(Auth::user()->id)){
            
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($request_date->dayOfWeek != Carbon::SATURDAY){
                return redirect()->back()->withInput($request->input())->with("end_error","Mismatch OT Type and Apply Date!!!");
            }
        }
        else if($ot_type=="Saturday" and isQcStaff(Auth::user()->id)){
            
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($request_date->dayOfWeek != Carbon::SATURDAY){
                return redirect()->back()->withInput($request->input())->with("end_error","Mismatch OT Type and Apply Date!!!");
            }
            if(strtotime(format_dbtime($request->end_from_time))<strtotime("12:00") )
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must be  greater than 12:00 for Saturday !!!");
            if(strtotime(format_dbtime($request->end_to_time))<strtotime("12:00") )
                return redirect()->back()->withInput($request->input())->with("end_error","OT Time must be  greater than 12:00 for Saturday !!!");
                

        }
        else if($ot_type=="Sunday"){
            
            $request_date = new Carbon(format_dbdate($request->apply_date));
            if($request_date->dayOfWeek != Carbon::SUNDAY){
                return redirect()->back()->withInput($request->input())->with("end_error","Mismatch OT Type and Apply Date!!!");
            }
        }
        else{
            
            $holiday = Holiday::where("date","=",format_dbdate($request->apply_date))->first();
            if(!$holiday){
                return redirect()->back()->withInput($request->input())->with("end_error","Mismatch OT Type and Apply Date!!!");
            }
        
        }

        $validate_array = [
            'id'=>'required',
            'end_from_time'=>'required',
            'end_to_time'=>'required',
            'end_reason'=>'required'
        ];
        $this->validate($request,$validate_array);
        $dailyrequest = $this->request_gestion->updateEndTime($request->all());

        $break_hour = $dailyrequest->end_break_hour?$dailyrequest->end_break_hour.' hr ':'';
        $break_min = $dailyrequest->end_break_minute?$dailyrequest->end_break_minute.' min':'';
        $break_time = $break_hour.$break_min;

        $manager = "no";
        if(Auth::user()->can("change-ot-manager-status") and $dailyrequest->monthly_request==0)
            $manager = "yes";

        return redirect()->back()->with("success_create","Successfully request for post overtime!!!");
        return response()->json(['end_from_time'=>siteformat_time24($dailyrequest->end_from_time),'end_to_time'=>siteformat_time24_nextday($dailyrequest->end_to_time,$dailyrequest->start_next_day),'break_time'=>$break_time,'end_reason'=>$dailyrequest->end_reason,'index'=>$request->index,'manager'=>$manager,'end_hotel'=>$dailyrequest->end_hotel,'end_next_day'=>$dailyrequest->end_next_day]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $dailyot = DailyOtRequest::findOrFail($request->id);

        //$users = User::permission('change-ot-manager-status')->where("department_id","=",getUserFieldWithId($dailyot->user_id,"department_id"))->get();
        $terms = explode(',',auth()->user()->department_id);

        $users=User::permission('change-ot-manager-status')->where(function($query) use($terms) {
                foreach($terms as $term) {
                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
                };
            })->select('users.email','users.employee_name','users.noti_type')->get();
        if($users){
            foreach($users as $key=>$value){
                if($value->noti_type=="email")
                    Mail::to($value->email)->send(new OTCancelMail($value,$dailyot,$request->reason));
            }
        }

        $dailyot->delete();

        return redirect()->back()->with("success_delete","Successfully cancel your record!!!");
    }

    public function changeStatus(Request $request)
    {
        //return $request;
        //return response()->json($request);
        if(isset($request['reject'])){
            $status_name = 'Reject';
            $status = 2;
        }
        else if(isset($request['accept'])){
            $status_name = 'Accept';
            $status = 1;
        }
        $changestatus = $this->request_gestion->changeStatus($request->all(),$status);

        return redirect()->route('daily-ot-request.list',["employee"=>$request->employee,"department"=>$request->department,"status"=>$request->status,"monthly_request"=>$request->monthly_request,"from_date"=>$request->from_date,'to_date'=>$request->to_date]);
        return response()->json(['index'=>$request->index,'status_name'=>$status_name,'change_date'=>siteformat_date($changestatus->status_change_date),'change_by'=>getUserFieldWithId($changestatus->user_id,'employee_name')]);
    }

    public function storeMonthlyRequest(Request $request)
    {
        
        $monthlyrequest = $this->request_gestion->storeMonthlyRequest($request->all());
        return redirect()->back()->with("success_create","Successfully send daily request to monthly request");
    }
}
