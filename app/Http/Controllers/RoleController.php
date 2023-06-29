<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use Redirect;
use Session;

class RoleController extends Controller
{
    //
       public function index()
    {
         $roles = Role::join('users', 'users.id','=','roles.created_by')
         ->join('users as users2', 'users2.id','=','roles.updated_by')
         ->select('roles.*','users.employee_name as created_user','users.employee_name as updated_user')->get();
        return view('mastermanagement.roles',compact('roles'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'short_name' => 'required',
            'guard_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Role::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'guard_name' => $request->guard_name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Role created successfully.']);
        
    }
    public function update(Request $request){
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'short_name' => 'required',
            'guard_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Role::where('id',$request->id)->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'guard_name' => $request->guard_name,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Role Update successfully.']);
        
    }
    public function delete(Request $request){
        Role::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function status(Request $request){
        
       
        Role::where('id',$request->id)->update([
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
         return Redirect::back()->with('msg','successfully change status');
        
    }
}
