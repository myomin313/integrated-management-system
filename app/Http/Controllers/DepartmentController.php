<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterManagement\Department;
use Redirect;
use Session;

class DepartmentController extends Controller
{
        //
      public function index()
    {
         $departments = Department::join('users', 'users.id','=','departments.created_by')
         ->join('users as users2', 'users2.id','=','departments.updated_by')
         ->select('departments.*','users.employee_name as created_user','users.employee_name as updated_user')->get();
        return view('mastermanagement.departments',compact('departments'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'order_no' => 'required',
            'name' => 'required',
            'short_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Department::create([
            'order_no' => $request->order_no,
            'name' => $request->name,
            'short_name' => $request->short_name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Department created successfully.']);
        
    }
    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'order_no' => 'required',
            'name' => 'required',
            'short_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Department::where('id',$request->id)->update([
            'order_no' => $request->order_no,
            'name' => $request->name,
            'short_name' => $request->short_name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Department Update successfully.']);
        
    }
    public function delete(Request $request){
        Department::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
        
       
        Department::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
