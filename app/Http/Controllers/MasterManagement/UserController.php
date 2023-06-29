<?php

namespace App\Http\Controllers\MasterManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AuthenticationInfo;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $n = NUMBER_PER_PAGE;
        $search = $request->search?$request->search:null;
        $position = $request->position!="all"?$request->position:0;

        $query = User::select('id','noti_type','name','email','phone','position_id','profile_id','active','created_at');

        if($search){
            $query->where('name','like',"$search%");
        }
        if($position){
            $query->where('position_id','=',$position);
        }

        $users = $query->get();

        $positions = Role::all();
        $fig_profiles = DB::table('raw_profile')->get();
        //return $positions;
        return view('mastermanagement.user.index',compact('users','positions','fig_profiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $positions = Role::all();
        $fig_profiles = DB::table('raw_profile')->get();
        //return $positions;
        return view('mastermanagement.user.create',compact('positions','fig_profiles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $validate_array = [
            'noti_type'=>'required',
            'name'=>'required|string|max:255|unique:users',
            // 'position_id'=>'required',
            // 'profile_id'=>'required'
        ];
        if($request->noti_type=='email')
            $validate_array["email"] = "required|string|email|max:255|unique:users";
        else
            $validate_array["phone"] = "required|numeric|regex:/^(09)/i";

        $this->validate($request,$validate_array, [
            'phone.regex' => 'Phone Number must start with 09 and must be number.'
        ]);

        //return $request->all();

        $noti_type = $request->noti_type;
        $email = $request->email;
        $phone = $request->phone;
        $name = $request->name;
        //$position_id = $request->position_id;
        //$profile_id = $request->profile_id;
        $password = Str::random(12);

        $google2fa = app('pragmarx.google2fa');
        $key = $google2fa->generateSecretKey();

        $user = User::create([
                'name' => $name,
                'noti_type' => $noti_type,
                'password' => Hash::make($password),
                'google2fa_secret' => $key,
                'email' => $email,
                'phone' => $phone,
                //'position_id' => $position_id,
                //'profile_id' => $profile_id,
                'created_by'=>Auth::user()->id
        ]);

        //$user->assignRole($position_id);

        if($noti_type=='email'){
            Mail::to($email)->send(new AuthenticationInfo($name,$password,$key));
        }
        else{
            $message = "Url : https://mobile.marubeniyangon.com.mm\n";
            $message .= "Username : $name\n";
            $message .= "Password : $password\n";
            $message .= "Google Key : $key";
            sendSMS($phone,$message);
        }

        if(isset($request['save'])){
            return redirect('master-management/user/list')->with('success_create','Successfully created new user !!!');
        }
        else if(isset($request['save_new'])){
            return redirect('master-management/user/create')->with('success_create','Successfully created new user !!!');
        }

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
    public function updateInfo(Request $request)
    {
        $validate_array = [
            'noti_type'=>'required',
            'name'=>'required|string|max:255',
            'position_id'=>'required'
            //'profile_id'=>'required'
        ];
        if($request->noti_type=='email')
            $validate_array["email"] = "required|string|email|max:255";
        else
            $validate_array["phone"] = "required|numeric";
        $this->validate($request,$validate_array);

        $user=User::findOrFail($request->id);
        DB::table('model_has_roles')->where('model_id',$request->id)->delete();

        $user->update([
            'name' => $request->name,
            'noti_type' => $request->noti_type,
            'email' => $request->email,
            'phone' => $request->phone,
            'position_id' => $request->position_id,
            //'profile_id' => $request->profile_id,
            'updated_by' => Auth::user()->id,
        ]);
        $user->assignRole($request->position_id);

        return response()->json(['name'=>$user->name,'noti_type'=>$user->noti_type,'email'=>$user->email,'phone'=>$user->phone,'position'=>getPositionName($user->position_id),'position_id'=>$user->position_id,'profile_id'=>$user->profile_id,'index'=>$request->index]);

    }

    /**
     * Change the password of the user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword(){
        $user=Auth::user();
        return view('mastermanagement.user.change-password',compact('user'));
    }

    public function updatePassword(Request $request){
        $this->validate($request,[
            'password'=>'required|string|min:12|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/|confirmed'
        ], [
            'password.regex' => 'Passwords must have at least one digit (0-9), one uppercase letter (A-Z) and one none alphanumeric charactor.'
        ]);
        $user=Auth::user();
        $current_password = $user->password;

        if(Hash::check($request->current_password, $current_password))
        {           
            $user_id = $user->id;                       
            User::where('id',$user_id)->update([
                'password'=>Hash::make($request->password),
                'password_change'=>1
            ]);
            return redirect('master-management/user/profile')->with('success_change','Your password have been changed !!!');
        }
        else
        {           
            return redirect('master-management/user/change-password')->with('current_password','Your current password is incorrect !!!');   
        }

    }

    public function profile(){
        $user=Auth::user();
        $branches = Branch::all();
        $departments = Department::all();
        $employee_types = EmployeeType::all();
        $fig_profiles = DB::table('raw_profile')->orderBy("pro_UserID","asc")->get();
        $positions = Role::all();

        return view('mastermanagement.user.profile',compact('user','branches','departments','employee_types','fig_profiles','positions'));
    }

    public function updateProfile(Request $request)
    {
        $validate_array = [
            'employee_name'=>'required|string|max:255',
            'dob'=>'required',
            'entranced_date'=>'required',
            'employee_type_id'=>'required',
            'branch_id'=>'required',
            'department_id'=>'required',
            //'position_id'=>'required',
            'working_start_time'=>'required',
            'working_end_time'=>'required',
            'working_day_per_week'=>'required|numeric'
        ];

        $user=Auth::user();

        //DB::table('model_has_roles')->where('model_id',$request->id)->delete();
        
        if(!$user->profile_id and $request->profile_id!=0)
            $validate_array["profile_id"] = "required|unique:users";
        
        if($request->personal_email)
            $validate_array["personal_email"] = "required|string|email|max:255";
        
        $this->validate($request,$validate_array);

        if(isset($request->check_ns_rs))
            $check_ns = $request->check_ns_rs;
        else
            $check_ns = 2;

        $department_id = implode(',',$request->department_id);
        
        $user->update([
            'profile_id' => $request->profile_id,
            'employee_name' => $request->employee_name,
            'dob' => format_dbdate($request->dob),
            'entranced_date' => format_dbdate($request->entranced_date),
            'personal_email' => $request->personal_email,
            'employee_type_id' => $request->employee_type_id,
            'branch_id' => $request->branch_id,
            'department_id' => $department_id,
            //'position_id' => $request->position_id,
            'check_ns_rs' => $check_ns,
            'working_day_type' => $request->working_day_type,
            'working_start_time' => $request->working_start_time,
            'working_end_time' => $request->working_end_time,
            'working_day_per_week' => $request->working_day_per_week,
            'profile_add' => 1

        ]);
        // if($request->position_id)
        //     $user->assignRole($request->position_id);

        return redirect(route('dashboard'));
    }

    public function delete(Request $request)
    {
        
        $user=User::findOrFail($request->id);
        

        $user->update([
            'name'=>$user->name."_old",
            'active' => 0,
            'profile_id' => 0,
            'email' => NULL,
            'deleted_by' => Auth::user()->id
        ]);
        
        $user->delete();
        // return response()->json(['name'=>$user->name,'noti_type'=>$user->noti_type,'email'=>$user->email,'phone'=>$user->phone,'position'=>getPositionName($user->position_id),'position_id'=>$user->position_id,'index'=>$request->index]);

        return redirect('master-management/user/list')->with('success_delete','Successfully update existing user name !!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function addPermission($id){
        $user=User::findOrFail($id);
        
        $selectedPermission=$user->permissions->pluck('name')->toArray();
        
        $permissions = Permission::orderBy('type')->get()->groupBy(function($item) {
            return $item->type;
        });
        //return $permissions;
        return view('mastermanagement.user.add-permission',compact('user','permissions','selectedPermission'));
        
    }

    public function updatePermission(Request $request,$id){
        //return $request;
        $user=User::findOrFail($id);          
        
        //delete pre record 
        DB::table("model_has_permissions")->where("model_has_permissions.model_id",$id)
            ->delete();
        if($request->permission){
            foreach ($request->permission as $per) {
                //$user->perms()->attach($per);
                $user->givePermissionTo($per);
    
            }
        }
            

        if(isset($request->attendance_permission))
            $user->givePermissionTo($request->attendance_permission);
        if(isset($request->ot_data_permission))
            $user->givePermissionTo($request->ot_data_permission);
        if(isset($request->salary_data_permission))
            $user->givePermissionTo($request->salary_data_permission);
        if(isset($request->tax_data_permission))
            $user->givePermissionTo($request->tax_data_permission);
        if(isset($request->ot_status_permission))
            $user->givePermissionTo($request->ot_status_permission);
        if(isset($request->leave_data_permission))
            $user->givePermissionTo($request->leave_data_permission);
        if(isset($request->car_data_permission))
            $user->givePermissionTo($request->car_data_permission);
        if(isset($request->employee_data_permission))
            $user->givePermissionTo($request->employee_data_permission);
            
               
        return redirect('master-management/user/list')->with('success_create','Successfully permissions added !!!');
    }
}
