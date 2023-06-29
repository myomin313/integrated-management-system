<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterManagement\HolidayType;
use Redirect;
use Session;

class HolidayTypeController extends Controller
{
    //
     //
    public function index()
    {
         $holidaytypes = HolidayType::join('users', 'users.id','=','holiday_types.created_by')
         ->join('users as users2', 'users2.id','=','holiday_types.updated_by')
         ->select('holiday_types.*','users.employee_name as created_user','users.employee_name as updated_user')->get();;
        return view('mastermanagement.holidaytypes',compact('holidaytypes'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        HolidayType::create([
            'name' => $request->name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'holiday type created successfully.']);
        
    }
    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        HolidayType::where('id',$request->id)->update([
            'name' => $request->name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'holiday type Update successfully.']);
        
    }
    public function delete(Request $request){
        HolidayType::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
       
        HolidayType::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
