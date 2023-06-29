<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterManagement\Holiday;
use App\Models\MasterManagement\HolidayType;
use Redirect;
use Session;
use DateTime;

class HolidayController extends Controller
{
    
     //
    public function index()
    {
         $holidays = Holiday::join('holiday_types', 'holiday_types.id','=','holidays.holiday_type_id')
         ->join('users', 'users.id','=','holidays.created_by')
         ->join('users as users2', 'users2.id','=','holidays.updated_by')         
         ->select('holidays.*','holiday_types.name as holiday_type_name','users.employee_name as created_user','users.employee_name as updated_user')->get();
         $holidaytypes=HolidayType::where('status','=',1)->get();
        return view('mastermanagement.holidays',compact('holidays','holidaytypes'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'title' => 'required',
            'holiday_type_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $date = DateTime::createFromFormat('d/m/Y', $request->date);
        $newDate=$date->format('Y-m-d');
        Holiday::create([
            'date' =>  $newDate,
            'title' => $request->title,
            'holiday_type_id'=> $request->holiday_type_id,
            'driver' => $request->driver,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'holiday  created successfully.']);
        
    }
    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'title' => 'required',
            'holiday_type_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $date = DateTime::createFromFormat('d/m/Y', $request->date);
        $newDate=$date->format('Y-m-d');
        
        Holiday::where('id',$request->id)->update([
            'date' => $newDate,
            'title' => $request->title,
            'holiday_type_id' => $request->holiday_type_id,
            'driver' => $request->driver,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'holiday  Update successfully.']);
        
    }
    public function delete(Request $request){
        Holiday::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
       
        Holiday::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
