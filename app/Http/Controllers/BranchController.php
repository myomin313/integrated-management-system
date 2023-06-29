<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterManagement\Branch;
use Redirect;
use Session;


class BranchController extends Controller
{
    //
      public function index()
    {
         $branches = Branch::join('users', 'users.id','=','branches.created_by')
         ->join('users as users2', 'users2.id','=','branches.updated_by')
         ->select('branches.*','users.employee_name as created_user','users.employee_name as updated_user')->get();
        return view('mastermanagement.branches',compact('branches'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'short_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Branch::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Branch created successfully.']);
        
    }
    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'short_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Branch::where('id',$request->id)->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Bank Update successfully.']);
        
    }
    public function delete(Request $request){
        Branch::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
       
        Branch::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
