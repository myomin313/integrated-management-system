<?php

namespace App\Http\Controllers\AttendanceManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AttendanceManagement\RawAttendanceRepository;
use App\Models\User;
use App\Models\MasterManagement\Branch;
use App\Models\LeaveManagement\LeaveType;
use Carbon\Carbon;
use DB;
use Excel;
use App\Exports\LateRecordExport;
use App\Exports\DetailAttendanceExport;
use App\Exports\ReceptionistAttendanceExport;
use App\Helpers\LogActivity;

class RawAttendanceController extends Controller
{
    public function __construct(RawAttendanceRepository $attendance_gestion){
        $this->attendance_gestion=$attendance_gestion;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('d/m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='raw_att.att_UserID';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $raw_attendances=$this->attendance_gestion->indexOrder($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,   
            $request->profile=='all'?null:$request->profile,$n);
        
        $profiles = DB::table("raw_profile")->select("pro_id","pro_UserName")->get();
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        
        return view('attendancemanagement.rawattendance.index',compact('raw_attendances','profiles','employees','branches'));
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
        //return $request->ip();
        $validate_array = [
            'user_id' => 'required',
            'date'=>'required',
            'time'=>'required',
            'branch'=>'required',
            'reason'=>'required'
        ];
        $this->validate($request,$validate_array);

        $attendance = $this->attendance_gestion->store($request->all(),$request->ip());
        
        return redirect()->back()->with("success_create","Successfully add new attendance!!!");

        return response()->json(['att_id'=>$attendance->att_id,'user_id'=>$attendance->user_id,'profile_name'=>getProfileNameWithId($attendance->att_UserID),'user_name'=>getUserFieldWithId($attendance->user_id,'employee_name'),'branch_id'=>$attendance->branch,'branch_name'=>getBranchField($attendance->branch,'name'),'date'=>siteformat_date($attendance->att_Date),'time'=>siteformat_time($attendance->att_Date),'time24'=>siteformat_time24($attendance->att_Date),'reason'=>$attendance->reason,'index'=>$request->index]);
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

    public function updateAtt(Request $request)
    {
        $validate_array = [
            'user_id' => 'required',
            'date'=>'required',
            'time'=>'required',
            'branch'=>'required',
            'reason'=>'required'
        ];
        $this->validate($request,$validate_array);

        $attendance = $this->attendance_gestion->update($request->all());

        return redirect()->back()->with("success_create","Successfully update attendance!!!");

        return response()->json(['user_id'=>$attendance->user_id,'profile_name'=>getProfileNameWithId($attendance->att_UserID),'user_name'=>getUserFieldWithId($attendance->user_id,'employee_name'),'branch_id'=>$attendance->branch,'branch_name'=>getBranchField($attendance->branch,'name'),'date'=>siteformat_date($attendance->att_Date),'time'=>siteformat_time($attendance->att_Date),'time24'=>siteformat_time24($attendance->att_Date),'reason'=>$attendance->reason,'index'=>$request->index]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        
        $rawatt=DB::table('raw_att')->where('att_id','=',$request->id)->first();
        $rawatt->method = 'DELETE';
        //add to log
        LogActivity::addToLog('raw-att', $rawatt);
        
        DB::table('raw_att')->where('att_id','=',$request->id)->delete();
        return redirect()->back()->with("success_delete","Successfully delete attendance!!!");
        return response()->json(['index'=>$request->index]);
    }

    public function detail(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $start_date=Carbon::now()->format('1/m/Y');
        $today=Carbon::now()->format('d/m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='attendances.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $staff_type = isset($request->staff_type)?$request->staff_type:"all";
        $attendances=$this->attendance_gestion->indexOrderDetail($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$start_date,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,     
            $request->branch=='all'?null:$request->branch,
            $request->staff_type=='all'?null:$request->staff_type,$n);

        //return $attendances;
        //$profiles = $profiles = User::select('pro_id','pro_UserID','pro_UserName')->leftJoin('raw_profile','users.profile_id','=','raw_profile.pro_id')->get();;
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        $types = [];
        $types["Working Day_0"] = "Working Day";
        $types["OT Type_0"] = "Normal";
        $types["OT Type_1"] = "Saturday";
        $types["OT Type_2"] = "Sunday";
        $types["OT Type_3"] = "Public Holiday";

        $types["RS Leave Type_1"] = "Earned Leave";
        $types["RS Leave Type_2"] = "Refresh Leave";

        $leavetypes = LeaveType::select("id","leave_type_name")->get();
        foreach($leavetypes as $key=>$value){
            $types["NS Leave Type_".$value->id] = $value->leave_type_name;
        }
        //return $attendances;
        if($staff_type=="receptionist"){
            return view('attendancemanagement.rawattendance.receptionist',compact('attendances','employees','branches','types'));
        }
        else{
            return view('attendancemanagement.rawattendance.detail',compact('attendances','employees','branches','types'));
        }
        
    }

    public function printDetail(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('d/m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='attendances.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $staff_type = isset($request->staff_type)?$request->staff_type:"all";
        $attendances=$this->attendance_gestion->indexOrderDetail($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,     
            $request->branch=='all'?null:$request->branch,     
            $request->staff_type=='all'?null:$request->staff_type,$n);

        //return $attendances;
        $profiles = $profiles = User::select('pro_id','pro_UserID','pro_UserName')->leftJoin('raw_profile','users.profile_id','=','raw_profile.pro_id')->get();;
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        $types = [];
        $types["Working Day_0"] = "Working Day";
        $types["OT Type_0"] = "Normal";
        $types["OT Type_1"] = "Sunday";
        $types["OT Type_2"] = "Public Holiday";

        $types["RS Leave Type_1"] = "Earned Leave";
        $types["RS Leave Type_2"] = "Refresh Leave";

        $leavetypes = LeaveType::select("id","leave_type_name")->get();
        foreach($leavetypes as $key=>$value){
            $types["NS Leave Type_".$value->id] = $value->leave_type_name;
        }
        //return $raw_attendances;
        if($staff_type=="receptionist"){
            return view('attendancemanagement.rawattendance.print-receptionist',compact('attendances','profiles','employees','branches','types'));
        }
        else{
            return view('attendancemanagement.rawattendance.print-detail',compact('attendances','profiles','employees','branches','types'));
        }
        
    }

    public function detailDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('d/m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='attendances.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $staff_type = isset($request->staff_type)?$request->staff_type:"all";
        $attendances=$this->attendance_gestion->indexOrderDetail($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,     
            $request->branch=='all'?null:$request->branch,     
            $request->staff_type=='all'?null:$request->staff_type,$n);

        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');
        $branch = $request->branch=='all'?'All':getBranchField($request->branch,'name');
        $staff_type = $request->staff_type=='all'?'All':$request->staff_type;

        $filename="Daily_Detail_Attendance".Carbon::now()->format('d-m-Y');
        if($staff_type=="receptionist"){
            return Excel::download(new ReceptionistAttendanceExport($attendances,$from_date,$to_date,$employee,$branch,$staff_type), $filename.'.xlsx');
        }
        else{
            return Excel::download(new DetailAttendanceExport($attendances,$from_date,$to_date,$employee,$branch,$staff_type), $filename.'.xlsx');
        }
            
    }

    public function updateDetail(Request $request)
    {
        //return $request->all();
        $validate_array = [
            'type.*' => 'required',
            'start_time.*'=>'required',
            //'end_time.*'=>'required',
            'corrected_start_time.*'=>'required',
            'corrected_end_time.*'=>'required'
        ];
        $this->validate($request,$validate_array);
        if(isset($request["update"])){
            if(!isset($request->all_attendance))
                return redirect()->back()->with("success_update","Please select at least one record to update");
            $attendance = $this->attendance_gestion->updateDetail($request->all());

            return redirect()->back()->with("success_update","Successfully update the records");
        }
        else if(isset($request["ot"])){
            // if(!isset($request->all_morning_ot) and !isset($request->all_evening_ot))
            //     return redirect()->back()->with("success_update","Please select at least one record to update the ot request");
            $attendance = $this->attendance_gestion->updateOTRequest($request->all());

            return redirect()->back()->with("success_update","Successfully update the ot request");
        }
            
    }

    public function lateRecord(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('Y');

        $month = Carbon::now()->format('F');
        $month = strtolower($month);

        if($month=='january' || $month=="february" ||$month=="march")
            $today = $today-1;

        if(!isset($request->name) || $request->name==null){
            $request->name='lates.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $lates=$this->attendance_gestion->lateRecord($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,$n);
        
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        //return $raw_attendances;
        return view('attendancemanagement.rawattendance.late-record',compact('lates','employees','branches'));
    }

    public function lateRecordDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('Y');

        $month = Carbon::now()->format('F');
        $month = strtolower($month);

        if($month=='january' || $month=="february" ||$month=="march")
            $today = $today-1;
        
        if(!isset($request->name) || $request->name==null){
            $request->name='lates.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $lates=$this->attendance_gestion->lateRecord($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Late_Record".Carbon::now()->format('d-m-Y');
        return Excel::download(new LateRecordExport($lates,$from_date,$to_date,$employee), $filename.'.xlsx');
    }
}
