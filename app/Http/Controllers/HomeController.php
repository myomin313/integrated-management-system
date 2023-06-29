<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceManagement\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Repositories\HomeRepository;
use App\Models\LeaveManagement\LeaveType;
use App\Models\CarManagement\Car;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeRepository $home_gestion)
    {
        $this->middleware('auth');
        $this->home_gestion=$home_gestion;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $user_id = Auth::user()->id;
        $attendance = Attendance::where([['date','=',$today],['user_id','=',$user_id]])->first();
        
        $leave_types = LeaveType::where('status','=',1)->select()->get();
        $cars=Car::all();
        return view('dashboard',compact('attendance','leave_types','cars'));
    }

    public function checkIn(Request $request)
    {
        if(isset($request["ot_btn"]))
            $ot = 1;
        else
            $ot = 0;
        $checkin = $this->home_gestion->store($request->ip(),$ot);

        return redirect()->back()->with('success_create','Successfully check in.');
    }
    public function checkOut(Request $request)
    {
        if(isset($request["ot_btn"]))
            $ot = 1;
        else
            $ot = 0;
        $checkout = $this->home_gestion->store($request->ip(),$ot);
        return redirect()->back()->with('success_create','Successfully check out.');
    }
    
    public function otRequest(Request $request)
    {
        $att = Attendance::where([['date','=',Carbon::now()->format("Y-m-d")],['user_id','=',Auth::user()->id]])->first();
        if(!$att)
            return redirect()->back()->with('success_update','Please Time in first.');

        $otrequest = $this->home_gestion->otRequest($request->all());
        return redirect()->back()->with('success_create','Successfully request OT.');
    }

    public function storeMonthlyOT(Request $request){
        $this->home_gestion->storeMonthlyOT();
        return redirect()->back()->with('success_create','Successfully request for monthly overtime of assistant and driver.');
    }
    
    public function storeChangeRequest(Request $request)
    {
        $validate_array = [
            'changing_date'=>'required',
            'reason_of_correction'=>'required'
        ];
        $attendance = Attendance::where([['date','=',format_dbdate($request->changing_date)],['user_id','=',Auth::user()->id]])->first();
        if(!$attendance){
            $validate_array['changing_start_time'] = "required";
        }
        else{
            if($request->changing_start_time){
                $validate_array['changing_start_time'] = "required";
            }
            else{
                $validate_array['changing_end_time'] = "required";
            }
        }

        $this->validate($request,$validate_array);

        
        $change = $this->home_gestion->storeChangeRequest($request->all());

        return redirect()->back()->with("success_create","Successfully request for attendance change!!!");

        
    }
    
    public function storeHotelUsage(Request $request)
    {
        $validate_array = [
            'usage_date'=>'required'
        ];
        $attendance = Attendance::where([['date','=',format_dbdate($request->usage_date)],['user_id','=',Auth::user()->id]])->first();
        if(!$attendance){
            return redirect()->back()->withInput($request->input())->with("hotel_error","The selected date doesn't have in Attendance !!!");
        }

        $this->validate($request,$validate_array);

        
        $change = $this->home_gestion->storeHotelUsage($request->all());

        return redirect()->back()->with("success_create","Successfully request for hotel usage!!!");

        
    }
}
