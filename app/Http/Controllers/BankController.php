<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterManagement\Bank;
use Redirect;
use Session;

class BankController extends Controller
{
     public function index()
    {
         $banks = Bank::join('users', 'users.id','=','banks.created_by')
         ->join('users as users2', 'users2.id','=','banks.updated_by')
         ->select('banks.*','users.employee_name as created_user','users.employee_name as updated_user')->get();
        return view('mastermanagement.banks',compact('banks'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()->all()
            ]);
        }
        Bank::create([
            'name' => $request->name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Bank created successfully.']);
        
    }
    public function update(Request $request){

       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()->all()
            ]);
        }
        Bank::where('id',$request->id)->update([
            'name' => $request->name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Bank Update successfully.']);
        
    }
    public function delete(Request $request){
        Bank::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
        
       
        Bank::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
