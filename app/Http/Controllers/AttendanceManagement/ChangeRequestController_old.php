<?php

namespace App\Http\Controllers\AttendanceManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AttendanceManagement\ChangeRequestRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceManagement\Attendance;
use App\Models\MasterManagement\Branch;
use App\Models\LeaveManagement\LeaveType;
use App\Models\AttendanceManagement\ChangeRequest;
use Carbon\Carbon;
use DB;
use App\Exports\LateRecordExport;
use App\Helpers\LogActivity;

class ChangeRequestController extends Controller
{
    public function __construct(ChangeRequestRepository $request_gestion){
        $this->request_gestion=$request_gestion;

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
            $request->name='change_requests.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $changes=$this->request_gestion->indexOrder($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,     
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        //return $raw_attendances;
        return view('attendancemanagement.changerequest.index',compact('changes','employees','branches'));
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
            'actual_date' => 'required',
            'changing_date'=>'required',
            'changing_start_time'=>'required',
            'changing_end_time'=>'required',
            'reason_of_correction'=>'required'
        ];
        $this->validate($request,$validate_array);

        $attendance = Attendance::where([['date','=',format_dbdate($request->actual_date)],['user_id','=',Auth::user()->id]])->first();
        if(!$attendance){
            return response()->json(["status"=>"no_record","current_date"=>$request->actual_date]);
        }
        $change = $this->request_gestion->store($request->all());
        $changing_time = siteformat_time24($change->changing_start_time).'-'.siteformat_time24($change->changing_end_time);
        return response()->json(['status'=>'ok','id'=>$change->id,'user_id'=>$change->user_id,'user_name'=>getUserFieldWithId($change->user_id,'employee_name'),'branch'=>getBranchField(getUserFieldWithId($change->user_id,'branch_id'),'name'),'current_date'=>siteformat_date($change->actual_date),'changing_date'=>siteformat_date($change->changing_date),'changing_time'=>$changing_time,'reason'=>$change->reason_of_correction,'requested_date'=>siteformat_date($change->requested_date)]);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        //return $request;
        if(isset($request['reject'])){
            $status_name = 'Reject';
            $status = 2;
        }
        else if(isset($request['accept'])){
            $status_name = 'Accept';
            $status = 1;
        }
        $changestatus = $this->request_gestion->changeStatus($request->all(),$status);

        return redirect()->route('change-request.list',["employee"=>$request->employee,"from_date"=>$request->from_date,'to_date'=>$request->to_date]);
        return response()->json(['index'=>$request->index,'status_name'=>$status_name,'change_date'=>siteformat_date($changestatus->status_change_date),'change_by'=>getUserFieldWithId($changestatus->user_id,'employee_name')]);
    }


}
