<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LeaveManagement\LeaveType;
use Redirect;
use Session;

class LeaveTypeController extends Controller
{
    //
         //
    public function index()
    {
        $leavetypes =  LeaveType::join('users', 'users.id','=','leave_types.created_by')
         ->join('users as users2', 'users2.id','=','leave_types.updated_by')         
         ->select('leave_types.*','users.employee_name as created_user','users.employee_name as updated_user')->get();
        return view('mastermanagement.leavetypes',compact('leavetypes'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'leave_type_name' => 'required',
            'leave_day' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $orgDate= $request->date;
        $newDate=date("Y-m-d",strtotime($orgDate));
        LeaveType::create([
            'leave_type_name' =>  $request->leave_type_name,
            'leave_day' => $request->leave_day,
            'type' => $request->type,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Leave Type created successfully.']);
        
    }
    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'leave_type_name' => 'required',
            'leave_day' => 'required',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $orgDate= $request->date;
        $newDate=date("Y-m-d",strtotime($orgDate));
        LeaveType::where('id',$request->id)->update([
            'leave_type_name' =>  $request->leave_type_name,
            'leave_day' => $request->leave_day,
            'type' => $request->type,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Leave Type Update successfully.']);
        
    }
    public function delete(Request $request){
        LeaveType::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
       
        LeaveType::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
