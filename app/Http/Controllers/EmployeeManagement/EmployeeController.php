<?php
namespace App\Http\Controllers\EmployeeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LeaveManagement\LeaveForm;
use App\Models\LeaveManagement\LeaveType;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Bank;
use App\Models\EmployeeManagement\RsEmployee;
use App\Models\EmployeeManagement\ContactInfo;
use App\Models\Role;
use App\Models\AttendanceManagement\Attendance;
use App\Models\EmployeeManagement\Family;
use App\Models\EmployeeManagement\Education;
use App\Models\EmployeeManagement\Qualification;
use App\Models\EmployeeManagement\Language;
use App\Models\EmployeeManagement\EnglishSkill;
use App\Models\EmployeeManagement\EmploymentRecord;
use App\Models\EmployeeManagement\OverseaRecord;
use App\Models\EmployeeManagement\Warning;
use App\Models\EmployeeManagement\Evaluation;
use App\Models\EmployeeManagement\Other;
use App\Models\EmployeeManagement\Retirement;
use App\Models\EmployeeManagement\NsEmployee;
use App\Models\EmployeeManagement\LifeAssurance;
use App\Models\EmployeeManagement\PcSkill;
use App\Models\EmployeeManagement\DriverLicense;
use App\Models\EmployeeManagement\RsLeaveData;
use App\Models\EmployeeManagement\RsRefreshLeave;
use App\Models\EmployeeManagement\UserBankAccount;
use App\Models\MasterManagement\Holiday;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NsExport;
use App\Exports\RsExport;
use App\Models\User;
use Redirect;
use Response;
use Session;
use DB;
use Auth;
use Carbon\Carbon;
use DateTime;

class EmployeeController extends Controller
{
    
     public function getNSList(Request $request){

         $employee_name=$request->search_employee_name;
         $depart = $request->search_department;
         
         if(Auth::user()->can('employee-read-all')) 

              $users = \DB::table("users")->select("users.*","branches.name as branch_name",\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                           ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
                           ->leftjoin('branches','branches.id','=','users.branch_id')
                           ->where('users.check_ns_rs','=',1)
                           ->where(function($query)use($employee_name){
                                                if($employee_name != null)
                                           $query->where('users.id','=',$employee_name);
                              })
                           ->where(function($query)use($depart){
                               if($depart != null)
                                //$query->where('users.department_id', '=', $depart);
                                $query->whereRaw("find_in_set('".$depart."',users.department_id)");
                           })
                          ->where('users.deleted_at','=',null)
                          ->groupBy("users.id")
                          ->get();
                          
            else if(Auth::user()->can('employee-read-group'))
            
            $users =  \DB::table("users")->select("users.*","branches.name as branch_name",\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                           ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
                          ->leftjoin('branches','branches.id','=','users.branch_id')
                           ->where('users.check_ns_rs','=',1)
                           ->where(function($query)use($employee_name){
                                                if($employee_name != null)
                                           $query->where('users.id','=',$employee_name);
                              })
                           ->where(function($query)use($depart){
                               if($depart != null)
                               $query->whereRaw("find_in_set('".$depart."',users.department_id)");
                                // $query->where('users.department_id', '=', $depart);
                           })
                          ->where('users.department_id','=',auth()->user()->department_id)
                          ->where('users.deleted_at','=',null)
                          ->groupBy("users.id")                       
                          ->get();
                          
            else
            
              $users =\DB::table("users")->select("users.*","branches.name as branch_name",\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                           ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
                          ->leftjoin('branches','branches.id','=','users.branch_id')
                           ->where('users.check_ns_rs','=',1)
                           ->where(function($query)use($employee_name){
                                                if($employee_name != null)
                                           $query->where('users.id','=',$employee_name);
                              })
                           ->where(function($query)use($depart){
                               if($depart != null)
                                // $query->where('users.department_id', '=', $depart);
                                $query->whereRaw("find_in_set('".$depart."',users.department_id)");
                           })
                          ->where('users.id','=',auth()->user()->id)
                          ->where('users.deleted_at','=',null)
                          ->groupBy("users.id")                        
                          ->get();
                          
              

           
         $all_users = User::where('users.check_ns_rs','=',1)->select()->get();
         $all_departments = Department::where('status','=',1)->select()->get();        
        return view('employeemanagement.ns-list',compact('users','all_users','all_departments'));

    }

    // public function getNSList(Request $request){

    //      $employee_name=$request->search_employee_name;
    //      $depart = $request->search_department;
        
       
                      
    //     if(Auth::user()->can('employee-read-all')) {
        
    //      $departments = Department::where('status','=',1)
    //                   ->where(function($query)use($depart){
    //                         if($depart != null)
    //                         $query->where('departments.id', '=', $depart);
    //                   })
    //                   ->select()
    //                   ->get();
                      
                       
    //     foreach($departments as $department){
    //           $department->user = User::leftjoin('branches','branches.id','=','users.branch_id')
    //                                   ->where('users.department_id','=',$department->id)
    //                                     //  start for search
    //                                     ->where(function($query)use($employee_name){
    //                                             if($employee_name != null)
    //                                       $query->where('users.id','=',$employee_name);
    //                                      })
    //                                   ->where('users.check_ns_rs','=',1)
    //                                   ->select('users.*','branches.name as branch_name')
    //                                   ->get();
    //     }
        
    //     }else if(Auth::user()->can('employee-read-group')){
        
    //      $departments = Department::where('status','=',1)
    //                   ->where(function($query)use($depart){
    //                         if($depart != null)
    //                         $query->where('departments.id', '=', $depart);
    //                   })
    //                   ->where('departments.id','=',auth()->user()->department_id)
    //                   ->select()
    //                   ->get();
        
    //      foreach($departments as $department){
    //           $department->user = User::leftjoin('branches','branches.id','=','users.branch_id')
    //                                   ->where('users.department_id','=',$department->id)
    //                                     //  start for search
    //                                     ->where(function($query)use($employee_name){
    //                                             if($employee_name != null)
    //                                       $query->where('users.id','=',$employee_name);
    //                                      })
    //                                   ->where('users.check_ns_rs','=',1)
    //                                   ->where('users.department_id','=',auth()->user()->department_id)
    //                                   ->select('users.*','branches.name as branch_name')
    //                                   ->get();
    //     }
        
    //     } else {
            
    //          $departments = Department::where('status','=',1)
    //                   ->where(function($query)use($depart){
    //                         if($depart != null)
    //                         $query->where('departments.id', '=', $depart);
    //                   })
    //                   ->where('departments.id','=',auth()->user()->department_id)
    //                   ->select()
    //                   ->get();
        
        
    //     foreach($departments as $department){
    //           $department->user = User::leftjoin('branches','branches.id','=','users.branch_id')
    //                                   ->where('users.department_id','=',$department->id)
    //                                     //  start for search
    //                                     ->where(function($query)use($employee_name){
    //                                             if($employee_name != null)
    //                                       $query->where('users.id','=',$employee_name);
    //                                      })
    //                                   ->where('users.check_ns_rs','=',1)
    //                                   ->where('users.id','=',auth()->user()->id)
    //                                   ->select('users.*','branches.name as branch_name')
    //                                   ->get();
    //     }
        
    //     }

    //      $all_users = User::all();
    //      $all_departments = Department::where('status','=',1)->select()->get();
        
    //     return view('employeemanagement.ns-list',compact('departments','all_users','all_departments'));
    // }
    public function getRSList(Request $request){

         $employee_name=$request->search_employee_name;
         $depart = $request->search_department;
         
         if(Auth::user()->can('employee-read-all')) 

               $users = \DB::table("users")->select("users.*","branches.name as branch_name",\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                           ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
                           ->leftjoin('branches','branches.id','=','users.branch_id')
                           ->where('users.check_ns_rs','=',0)
                           ->where(function($query)use($employee_name){
                                                if($employee_name != null)
                                           $query->where('users.id','=',$employee_name);
                              })
                           ->where(function($query)use($depart){
                               if($depart != null)
                                //$query->where('users.department_id', '=', $depart);
                                $query->whereRaw("find_in_set('".$depart."',users.department_id)");
                           })
                          ->where('users.deleted_at','=',null)
                          ->groupBy("users.id")
                          ->get();
                          
               else if(Auth::user()->can('employee-read-group'))


                $users =  \DB::table("users")->select("users.*","branches.name as branch_name",\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                           ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
                          ->leftjoin('branches','branches.id','=','users.branch_id')
                           ->where('users.check_ns_rs','=',0)
                           ->where(function($query)use($employee_name){
                                                if($employee_name != null)
                                           $query->where('users.id','=',$employee_name);
                              })
                           ->where(function($query)use($depart){
                               if($depart != null)
                               $query->whereRaw("find_in_set('".$depart."',users.department_id)");
                                // $query->where('users.department_id', '=', $depart);
                           })
                           ->where('users.deleted_at','=',null)
                          ->whereRaw("find_in_set('".$auth()->user()->department_id."',users.department_id)")
                          //->where('users.department_id','=',auth()->user()->department_id)
                          ->groupBy("users.id")                       
                          ->get();
                          
            else
            

                          $users =\DB::table("users")->select("users.*","branches.name as branch_name",\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                           ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
                          ->leftjoin('branches','branches.id','=','users.branch_id')
                           ->where('users.check_ns_rs','=',0)
                           ->where(function($query)use($employee_name){
                                                if($employee_name != null)
                                           $query->where('users.id','=',$employee_name);
                              })
                           ->where(function($query)use($depart){
                               if($depart != null)
                                // $query->where('users.department_id', '=', $depart);
                                $query->whereRaw("find_in_set('".$depart."',users.department_id)");
                           })
                           ->where('users.deleted_at','=',null)
                          ->where('users.id','=',auth()->user()->id)
                          ->groupBy("users.id")                        
                          ->get();

                          

         $all_users = User::where('users.check_ns_rs','=',0)->select()->get();;
         $all_departments = Department::where('status','=',1)->select()->get();

        return view('employeemanagement.rs-list',compact('users','all_users','all_departments'));
    }
    
    
    public function editRSList($id){
       
            $user = User::leftjoin('rs_employees','rs_employees.user_id','=','users.id')
                   ->leftjoin('contact_infos','contact_infos.user_id','=','users.id')
                   ->where('users.id','=',$id)
                   ->select('users.*','rs_employees.second_bank_name_mmk',
                    'rs_employees.second_bank_account_mmk','rs_employees.final_education',
                    'rs_employees.residant_place','rs_employees.form_c','rs_employees.frc_no',
                    'rs_employees.mjsrv','rs_employees.mjsrv_expire_date','rs_employees.stay_permit',
                    'rs_employees.stay_permit_expire_date','rs_employees.aboard_date',
                    'rs_employees.japan_hot_line','rs_employees.japan_address',
                    'rs_employees.japan_phone','rs_employees.myanmar_address','contact_infos.first_person_name',
                    'contact_infos.first_person_email','contact_infos.first_person_phone','contact_infos.first_person_hotline'
                    ,'contact_infos.first_person_relationship','contact_infos.first_person_address','contact_infos.second_person_name',
                    'contact_infos.second_person_email','contact_infos.second_person_phone','contact_infos.second_person_hotline',
                    'contact_infos.second_person_relationship','contact_infos.second_person_address')
                   ->first();
                   
                   
                   
             
           $employee_types=EmployeeType::where('status','=',1)->select()->get();
           $roles=Role::where('status','=',1)->select()->get();
           $branches=Branch::where('status','=',1)->select()->get();
           $departments=Department::where('status','=',1)->select()->get();
           $banks=Bank::where('status','=',1)->select()->get();
           //family
           $families=Family::where('user_id','=',$id)->select()->get();
          
           //rs leave data
           $rs_leave_datas =RsLeaveData::where('user_id','=',$id)->select()->get();

           $rs_refresh_leaves =RsRefreshLeave::where('user_id','=',$id)->select()->get(); 

         return view('employeemanagement.rs-edit',compact('user','employee_types','roles','branches','departments','banks','families','rs_leave_datas','rs_refresh_leaves')); 
          
    }
    public function updateBasicInfo(Request $request){

        $validator = Validator::make($request->all(), [
            'employee_name' => 'required',
            'dob' => 'required',
            'entranced_date' => 'required',
            'employee_type' => 'required',
            'position' => 'required',
            'branch_id' => 'required',
            'department_id' => 'required',
            'office_email' => 'required',
            'personal_email' => 'required',
            'gender' => 'required',
            'marital_status' => 'required',
            'bank_name_usd' => 'required',
            'bank_account_usd' => 'required',            
            'bank_name_mmk' => 'required',
            'bank_account_mmk' => 'required',
            'blood_type' => 'required',
            'final_education' => 'required',
            'passport_number' => 'required',
            'date_of_issue' => 'required',
            'date_of_expire' => 'required',
            'residant_place' => 'required',
            'check_ns_rs' => 'required',
            'working_end_time' => 'required',
            'working_start_time' => 'required',
            'working_day_per_week' => 'required',
            'working_day_type' => 'required',
            
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

         
        //  if(!empty($request->file('photo'))){
        // $file = $request->file('photo');
        // $filename =rand().'.'.$file->getClientOriginalExtension();;
        // $file->storeAs('public/employee/',$filename);
        // }else{
        //   $filename = $request->old_photo_name; 
        // }
        
        // if(!empty($request->file('photo'))){
        //   $signfile = $request->file('sign_photo');
        //   $signfilename =rand().'.'.$signfile->getClientOriginalExtension();;
        //   $signfile->storeAs('public/employee/',$signfilename);
        // }else{
        //   $signfilename = $request->old_sign_photo_name;
        // }
        // start upload image
        
           
         if(!empty($request->file('photo'))){
        $file = $request->file('photo');
        $filename =rand().'.'.$file->getClientOriginalExtension();
        $publicpath = public_path();
        $arraypath = explode("/",$publicpath);
        $count =count($arraypath);
        
        unset($arraypath[$count-1]);
        unset($arraypath[$count-2]);
       
       // $file->storeAs('public/employee/',$filename);
       
       $publicpath =  implode('/',$arraypath);
       $file->move($publicpath.'/public/employee',$filename);
       
     //  $request->image_name  = $filename;
      // $request->image_path =  $path;
       
      //   $file->move(public_path().'/employee/',$filename);
        }else{
          $filename = $request->old_photo_name; 
        }
        
        if(!empty($request->file('sign_photo'))){
          $signfile = $request->file('sign_photo');
          $signfilename =rand().'.'.$signfile->getClientOriginalExtension();
         //$signfile->storeAs('public/employee/',$signfilename);
          $signpublicpath = public_path();
        $signarraypath = explode("/",$signpublicpath);
        $signcount =count($signarraypath);
        
        unset($signarraypath[$signcount-1]);
        unset($signarraypath[$signcount-2]);
       
       // $file->storeAs('public/employee/',$filename);
       
       $signpublicpath =  implode('/',$signarraypath);
         
      $signfile->move($signpublicpath.'/public/employee',$signfilename);
           // $signfile->move(public_path().'/employee/',$signfilename);
            
        }else{
          $signfilename = $request->old_sign_photo_name;
        }
        
        
        // end upload image


        $dob_data = explode('/',  $request->dob);
        $dob_date = $dob_data[2].'-'.$dob_data[1].'-'.$dob_data[0];
        $entranced_data = explode('/',  $request->entranced_date);
        $entranced_date = $entranced_data[2].'-'.$entranced_data[1].'-'.$entranced_data[0];

        $date_of_issue_data = explode('/',  $request->date_of_issue);
        $date_of_issue = $date_of_issue_data[2].'-'.$date_of_issue_data[1].'-'.$date_of_issue_data[0];

        $date_of_expire_data = explode('/',  $request->date_of_expire);
        $date_of_expire = $date_of_expire_data[2].'-'.$date_of_expire_data[1].'-'.$date_of_expire_data[0];
        
        if(!empty($request->$request->mjsrv_expire_date)){
             $mjsrv_expire_date_data = explode('/',  $request->mjsrv_expire_date);
             $mjsrv_expire_date = $mjsrv_expire_date_data[2].'-'.$mjsrv_expire_date_data[1].'-'.$mjsrv_expire_date_data[0];
        }else{
             $mjsrv_expire_date = null;
        }
     
        if(!empty($request->stay_permit_expire_date)){
          $stay_permit_expire_date_data = explode('/',  $request->stay_permit_expire_date);
          $stay_permit_expire_date = $stay_permit_expire_date_data[2].'-'.$stay_permit_expire_date_data[1].'-'.$stay_permit_expire_date_data[0];
        }else{
           $stay_permit_expire_date = null;  
        }
        
        if(!empty($request->aboard_date)){
           $aboard_date_data = explode('/',$request->aboard_date);
           $aboard_date = $aboard_date_data[2].'-'.$aboard_date_data[1].'-'.$aboard_date_data[0];
        }else{
           $aboard_date = null;     
        }

         if(isset($request->active)) {
              $active = 1;
         }else{
              $active = 0;
         }

           $department_id = implode(',',$request->department_id);
           
        User::where('id',$request->id)->update([
            'employee_id' => $request->employee_id,
            'employee_name' => $request->employee_name,
            'dob' => $dob_date,
            'photo_name' => $filename,
            'sign_photo_name' => $signfilename,
            'personal_email' => $request->personal_email,
            'entranced_date' => $entranced_date,
            'email' => $request->office_email,
            'employee_type_id' => $request->employee_type,
            'position' => $request->position,
            'branch_id' => $request->branch_id,
            'department_id' => $department_id,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'bank_name_usd' => $request->bank_name_usd,
            'bank_account_usd' => $request->bank_account_usd,
            'bank_name_mmk' => $request->bank_name_mmk,
            'bank_account_mmk' => $request->bank_account_mmk,
            'bank_account_mmk' => $request->bank_account_mmk,
            'ssc_no'=> $request->ssc_no,
            'blood_type'=> $request->blood_type,
            'passport_number'=> $request->passport_number,
            'date_of_issue'=> $date_of_issue,
            'date_of_expire'=> $date_of_expire,
            'active'=> $active,
            'phone'=> $request->phone,
            'other_changing_condition'=> $request->other_changing_condition,
            'updated_by'=>auth()->user()->id,
            'working_start_time'=>$request->working_start_time,
            'working_end_time'=>$request->working_end_time,
            'check_ns_rs'=>$request->check_ns_rs,
            'working_day_per_week'=>$request->working_day_per_week,
            'working_day_type'=>$request->working_day_type,
            
        ]);

        $rsemp= RsEmployee::where('user_id','=',$request->id)->select()->first();

        if(!empty($rsemp)){
           RsEmployee::where('user_id',$request->id)->update([
            'second_bank_name_mmk' => $request->second_bank_name_mmk,
            'second_bank_account_mmk' => $request->second_bank_account_mmk,
            'final_education' => $request->final_education,
            'residant_place' => $request->residant_place,            
            'form_c'=> $request->form_c,            
            'frc_no'=> $request->frc_no,
            'graduation_name_of_university'=> $request->graduation_name_of_university,
            'major'=> $request->major,
            'mjsrv'=> $request->mjsrv,
            'mjsrv_expire_date'=> $mjsrv_expire_date,
            'stay_permit'=> $request->stay_permit,
            'stay_permit_expire_date'=> $stay_permit_expire_date,
            'aboard_date'=> $aboard_date,
            'japan_address'=> $request->japan_address,
            'japan_phone'=> $request->japan_phone,
            'japan_hot_line'=> $request->japan_hot_line,
            'myanmar_address'=> $request->myanmar_address
         ]);
       }else{
           RsEmployee::create([
            'user_id' => $request->id,
            'second_bank_name_mmk' => $request->second_bank_name_mmk,
            'second_bank_account_mmk' => $request->second_bank_account_mmk,
            'final_education' => $request->final_education,
            'residant_place' => $request->residant_place,            
            'form_c'=> $request->form_c,            
            'frc_no'=> $request->frc_no,
            'graduation_name_of_university'=> $request->graduation_name_of_university,
            'major'=> $request->major,
            'mjsrv'=> $request->mjsrv,
            'mjsrv_expire_date'=> $mjsrv_expire_date,
            'stay_permit'=> $request->stay_permit,
            'stay_permit_expire_date'=> $stay_permit_expire_date,
            'aboard_date'=> $aboard_date,
            'japan_address'=> $request->japan_address,
            'japan_phone'=> $request->japan_phone,
            'japan_hot_line'=> $request->japan_hot_line,
            'myanmar_address'=> $request->myanmar_address
           ]);
       }
    }
    //updateBasicInfoForNS
    public function updateBasicInfoForNS(Request $request){

        $validator = Validator::make($request->all(), [
            'employee_name' => 'required',
            'dob' => 'required',
            'entranced_date' => 'required',
            'employee_type' => 'required',
            // 'position_id' => 'required',
            'branch_id' => 'required',
            'department_id' => 'required',
            'gender' => 'required',
            'marital_status' => 'required',            
            'bank_name_mmk' => 'required',
            'bank_account_mmk' => 'required',
            'blood_type' => 'required',
            'current_address' => 'required', 
            'nrc_no' => 'required',
            'check_ns_rs' => 'required',
            'working_end_time' => 'required',
            'working_start_time' => 'required',
            'working_day_per_week' => 'required',
            'working_day_type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

         
         if(!empty($request->file('photo'))){
        $file = $request->file('photo');
        $filename =rand().'.'.$file->getClientOriginalExtension();
        $publicpath = public_path();
        $arraypath = explode("/",$publicpath);
        $count =count($arraypath);
        
        unset($arraypath[$count-1]);
        unset($arraypath[$count-2]);
       
       // $file->storeAs('public/employee/',$filename);
       
       $publicpath =  implode('/',$arraypath);
       $file->move($publicpath.'/public/employee',$filename);
       
     //  $request->image_name  = $filename;
      // $request->image_path =  $path;
       
      //   $file->move(public_path().'/employee/',$filename);
        }else{
          $filename = $request->old_photo_name; 
        }
        
        if(!empty($request->file('sign_photo'))){
          $signfile = $request->file('sign_photo');
          $signfilename =rand().'.'.$signfile->getClientOriginalExtension();
         //$signfile->storeAs('public/employee/',$signfilename);
          $signpublicpath = public_path();
        $signarraypath = explode("/",$signpublicpath);
        $signcount =count($signarraypath);
        
        unset($signarraypath[$signcount-1]);
        unset($signarraypath[$signcount-2]);
       
       // $file->storeAs('public/employee/',$filename);
       
       $signpublicpath =  implode('/',$signarraypath);
         
      $signfile->move($signpublicpath.'/public/employee',$signfilename);
           // $signfile->move(public_path().'/employee/',$signfilename);
            
        }else{
          $signfilename = $request->old_sign_photo_name;
        }


        $dob_data = explode('/',  $request->dob);
        $dob_date = $dob_data[2].'-'.$dob_data[1].'-'.$dob_data[0];

        $entranced_data = explode('/',  $request->entranced_date);
        $entranced_date = $entranced_data[2].'-'.$entranced_data[1].'-'.$entranced_data[0];

         if(!empty($request->date_of_issue)){
        $date_of_issue_data = explode('/',  $request->date_of_issue);
        $date_of_issue = $date_of_issue_data[2].'-'.$date_of_issue_data[1].'-'.$date_of_issue_data[0];
         }else{
              $date_of_issue = null ; 
         }


            if(!empty($request->date_of_expire)){
             $date_of_expire_data = explode('/',  $request->date_of_expire);
              $date_of_expire = $date_of_expire_data[2].'-'.$date_of_expire_data[1].'-'.$date_of_expire_data[0];
            }else{
              $date_of_expire  =null;   
            }

        

         if(isset($request->active)) {
              $active = 1;
         }else{
              $active = 0;
         }
        
            $department_id = implode(',',$request->department_id);
          
        User::where('id',$request->id)->update([
            'employee_id' => $request->employee_id,
            'employee_name' => $request->employee_name,
            'branch_id'=>$request->branch_id,
            'department_id'=>$department_id,
            'dob' => $dob_date,            
            'personal_email' => $request->personal_email,            
            'email' => $request->office_email,
            'entranced_date' => $entranced_date,
            'photo_name' => $filename,
            'sign_photo_name' => $signfilename,            
            'employee_type_id' => $request->employee_type, 
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'bank_name_usd' => $request->bank_name_usd,
            'bank_account_usd' => $request->bank_account_usd,
            'bank_name_mmk' => $request->bank_name_mmk,
            'bank_account_mmk' => $request->bank_account_mmk,
            'bank_account_mmk' => $request->bank_account_mmk,
            'ssc_no'=> $request->ssc_no,
            'blood_type'=> $request->blood_type,
            'passport_number'=> $request->passport_number,
            'date_of_issue'=> $date_of_issue,
            'date_of_expire'=> $date_of_expire,
            'active'=> $active,
            'phone'=> $request->phone,
            'other_changing_condition'=> $request->other_changing_condition,
            'updated_by'=>auth()->user()->id,
            'working_start_time'=>$request->working_start_time,
            'working_end_time'=>$request->working_end_time,
            'check_ns_rs'=>$request->check_ns_rs,
            'working_day_per_week'=>$request->working_day_per_week,
            'working_day_type'=>$request->working_day_type,
        ]);

        $rsemp= NsEmployee::where('user_id','=',$request->id)->select()->first();

        if(!empty($rsemp)){
           NsEmployee::where('user_id',$request->id)->update([
            'nrc_no'=> $request->nrc_no,
            'religion'=> $request->religion,
            'race'=> $request->race,
            'current_address'=> $request->current_address,
            'new_address'=> $request->new_address,
            'new_phone'=> $request->new_phone,
            'others_address'=> $request->others_address,
            'others_phone'=> $request->others_phone,
            'employment_contract_no'=> $request->employment_contract_no,
            'hourly_rate'=> $request->hourly_rate,
            'ot_rate'=> $request->ot_rate
         ]);
       }else{
           NsEmployee::create([
            'user_id' => $request->id,
            'nrc_no'=> $request->nrc_no,
            'religion'=> $request->religion,
            'race'=> $request->race,
            'current_address'=> $request->current_address,
            'new_address'=> $request->new_address,
            'new_phone'=> $request->new_phone,
            'others_address'=> $request->others_address,
            'others_phone'=> $request->others_phone,
            'employment_contract_no'=> $request->employment_contract_no,
            'hourly_rate'=> $request->hourly_rate,
            'ot_rate'=> $request->ot_rate
           ]);
       }
    }

    public function updateContactInfo(Request $request){

      $validator = Validator::make($request->all(), [
            'first_person_name' => 'required',
            'first_person_phone' => 'required',
            'first_person_relationship' => 'required',
            'first_person_address' => 'required',
      ]);

      if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
      }

       
       $rsemp= ContactInfo::where('user_id','=',$request->id)->select()->first();
        if(!empty($rsemp)){
           ContactInfo::where('user_id',$request->id)->update([
            'first_person_name' => $request->first_person_name,
            'first_person_email' => $request->first_person_email,
            'first_person_phone' => $request->first_person_phone,
            'first_person_hotline' => $request->first_person_hotline,            
            'first_person_relationship'=> $request->first_person_relationship,            
            'first_person_address'=> $request->first_person_address,            
            'second_person_name'=> $request->second_person_name,
            'second_person_email'=> $request->second_person_email,
            'second_person_phone'=> $request->second_person_phone,
            'second_person_hotline'=> $request->second_person_hotline,
            'second_person_relationship'=> $request->second_person_relationship,
            'second_person_address'=> $request->second_person_address,
            'updated_by'=>auth()->user()->id
         ]);
       }else{
           ContactInfo::create([
            'user_id' => $request->id,
            'first_person_name' => $request->first_person_name,
            'first_person_email' => $request->first_person_email,
            'first_person_phone' => $request->first_person_phone,
            'first_person_hotline' => $request->first_person_hotline,            
            'first_person_relationship'=> $request->first_person_relationship,            
            'first_person_address'=> $request->first_person_address,            
            'second_person_name'=> $request->second_person_name,
            'second_person_email'=> $request->second_person_email,
            'second_person_phone'=> $request->second_person_phone,
            'second_person_hotline'=> $request->second_person_hotline,
            'second_person_relationship'=> $request->second_person_relationship,
            'second_person_address'=> $request->second_person_address,
            'created_by'=>auth()->user()->id,
            'updated_by'=>auth()->user()->id
           ]);
       }

    }
    public function updateFamilyInfo(Request $request){     
     
     $family_relationship= $request->family_relationship;

  
     $family_name= $request->family_name;
    
     $family_dob= $request->family_dob;
     $family_work= $request->family_work;
     $allowance= $request->allowance;
     $allowance_fee= $request->allowance_fee;
     
       Family::where('user_id','=',$request->id)->delete(); 
       
      if(!empty($family_relationship)){
     for($count = 0; $count < count($family_relationship); $count++)
     {
      
      $data = array(
       'user_id' =>$request->id, 
       'relationship' => $family_relationship[$count],
       'name'  => $family_name[$count],
       'family_dob'  => $family_dob[$count],
       'work'  => $family_work[$count],
       'allowance'=>$allowance[$count],
       'allowance_fee'=>$allowance_fee[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data; 
     }

     Family::insert($insert_data);

     }

      return back();
       
    }   
    
    public function updateBankInfo(Request $request){

      //BankInfo

     $currency     = $request->currency;  
     $bank_id      = $request->bank_id;    
     $bank_account = $request->bank_account;
     $id           = $request->id;

     //dd($bank_account);exit();
     
     //  BankInfo::where('user_id','=',$request->id)->delete(); 
       
      if(!empty($bank_account)){
     for($count = 0; $count < count($bank_account); $count++)
     {
      
      $data = array(
       'user_id' =>$request->user_id, 
       'currency' => $currency[$count],
       'bank_id'  => $bank_id[$count],
       'bank_account'  => $bank_account[$count] 
      );

      if(!empty($id[$count])){
        UserBankAccount::where('id','=',$id[$count])->update($data);
      }else{
        UserBankAccount::insert($data);
      }

     // $insert_data[] = $data; 
     }  

     }

      return back();
       

    }
    public function deleteBankInfo(Request $request){
        $id = $request->id;
        UserBankAccount::where('id','=',$id)->delete();

        return back();
    }

     public function updateEducationInfo(Request $request){      
     
     $education_type= $request->education_type;
     $school_name= $request->school_name;
     $date_of_graduation= $request->date_of_graduation;
     $major= $request->major;
       
     Education::where('user_id','=',$request->id)->delete();
     
      if(!empty($education_type)){

     for($count = 0; $count < count($education_type); $count++)
     {
      $data = array(
       'user_id' =>$request->id, 
       'education_type' => $education_type[$count],
       'school_name'  => $school_name[$count],
       'date_of_graduation'  => $date_of_graduation[$count],
       'major'  => $major[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data; 
     }

     Education::insert($insert_data);
    }

      return back();
       
    }
      public function updateQualificationInfo(Request $request){      
     
     $date_of_acquition= $request->date_of_acquition;
     $certificate= $request->certificate;
       
     Qualification::where('user_id','=',$request->id)->delete();
     
      if(!empty($date_of_acquition)){
     for($count = 0; $count < count($date_of_acquition); $count++)
     {
      $data = array(
       'user_id' =>$request->id, 
       'date_of_acquition' => $date_of_acquition[$count],
       'certificate'  => $certificate[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data; 
     }
     Qualification::insert($insert_data);
    }

      return back();
       
    } 
    
    // assurance
    public function updateAssurance(Request $request){      
     
     $premium_amount= $request->premium_amount;
     $year= $request->year;
       
     LifeAssurance::where('user_id','=',$request->id)->delete();
     
      if(!empty($premium_amount)){
     for($count = 0; $count < count($premium_amount); $count++)
     {
      $data = array(
       'user_id' =>$request->id, 
       'premium_amount' => $premium_amount[$count],
       'year'  => $year[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data; 
     }
     LifeAssurance::insert($insert_data);
    }

      return back();
       
    } 


    public function updateLanguageSkill(Request $request){      
     
     $language_skill= $request->language_skill;
     $level= $request->level;

     Language::where('user_id','=',$request->id)->delete();

    if(!empty($language_skill)){       
        for($count = 0; $count < count($language_skill); $count++)
        {
         $data = array(
         'user_id' =>$request->id, 
         'language_skill' => $language_skill[$count],
         'level'  => $level[$count],
         'created_by' =>auth()->user()->id,
         'updated_by' =>auth()->user()->id 
         );
         $insert_data[] = $data; 
        }    
       Language::insert($insert_data);
     }

      return back();
       
    }    
    public function updateEnglishSkill(Request $request){      
     
     $test_type= $request->test_type;
     $mark= $request->mark;
     $level= $request->level;
     $date_of_acquition= $request->date_of_acquition;

     EnglishSkill::where('user_id','=',$request->id)->delete();
     if(!empty($test_type)){    
     for($count = 0; $count < count($test_type); $count++)
     {
      $data = array(
       'user_id' =>$request->id, 
       'test_type' => $test_type[$count],
       'mark'  => $mark[$count],
       'level'  => $level[$count],
       'date_of_acquition'  => $date_of_acquition[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data; 
     }
     EnglishSkill::insert($insert_data);
     
    }

      return back();
       
    }
    public function updateEmploymentRecords(Request $request){

     $company_name= $request->company_name;
     $start_date= $request->start_date;
     $end_date= $request->end_date;
     $position= $request->position;
     $department= $request->department;

     EmploymentRecord::where('user_id','=',$request->id)->delete();

    
     if(!empty($company_name)){    
     for($count = 0; $count < count($company_name); $count++)
     {
      $data = array(
       'user_id' =>$request->id, 
       'company_name' => $company_name[$count],
       'start_date'  => $start_date[$count],
       'end_date'  => $end_date[$count],
       'position'  => $position[$count],
       'department'  => $department[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data; 
     }

     EmploymentRecord::insert($insert_data);
    }

      return back();
       
    }
    public function updateOverseaRecords(Request $request){

      $start_date= $request->start_date;
      $purpose= $request->purpose;
      $end_date= $request->end_date;
      $country_name= $request->country_name;

     OverseaRecord::where('user_id','=',$request->id)->delete();
    
    if(!empty($country_name)){ 
     for($count = 0; $count < count($country_name); $count++)
     {
      $data = array(
       'user_id' =>$request->id, 
       'start_date' => $start_date[$count],
       'end_date'  => $end_date[$count],
       'purpose'  => $purpose[$count],
       'country_name'  => $country_name[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );

      $insert_data[] = $data;       
    }
    OverseaRecord::insert($insert_data);
  }

      return back();
  }
  public function updateWarning(Request $request){

    $date= $request->date;
    $reason= $request->reason;

     Warning::where('user_id','=',$request->id)->delete();
     
      if(!empty($date)){ 
      for($count = 0; $count < count($date); $count++)
      {
        $data = array(
        'user_id' =>$request->id, 
        'date' => $date[$count],
        'reason'  => $reason[$count],
        'created_by' =>auth()->user()->id,
        'updated_by' =>auth()->user()->id 
        );

        $insert_data[] = $data;       
      }
      Warning::insert($insert_data);
    }
      return back();

  }
  public function updatePCSkill(Request $request){

       $skill_title= $request->skill_title;
      $skill_level= $request->skill_level;

     
        
      if(!empty($request->input('microsoft_word'))){
         $word = implode(',', $request->input('microsoft_word'));
      }else{
         $word = null;
      }

    NSEmployee::where('user_id','=',$request->id) ->update([
            'microsoft_word' => $word,
            'microsoft_excel' => $request->microsoft_excel,
            'microsoft_powerpoint' => $request->microsoft_powerpoint
    ]);

     PcSkill::where('user_id','=',$request->id)->delete();
      
     
      if(!empty($skill_title)){ 
      for($count = 0; $count < count($skill_title); $count++)
      {
        $data = array(
        'user_id' =>$request->id, 
        'title' => $skill_title[$count],
        'level'  => $skill_level[$count],
        'created_by' =>auth()->user()->id,
        'updated_by' =>auth()->user()->id 
        );

        $insert_data[] = $data;       
      }
      PcSkill::insert($insert_data);
    }
      return back();

  }
    public function updateEvalucationRecords(Request $request){

       
       $year= $request->year;
       $grade= $request->grade;
       $title = $request->title;
       $position  = $request->position;
       $competency  = $request->competency;
       $performance  = $request->performance;
       $net_pay  = $request->net_pay;
       $basic_salary  = $request->basic_salary;
       $allowance  = $request->allowance;
       $ot_rate  = $request->ot_rate;
       $water_festival_bonus  = $request->water_festival_bonus;
       $thadingyut_bonus  = $request->thadingyut_bonus;

     Evaluation::where('user_id','=',$request->id)->delete();
     
      if(!empty($grade)){ 

     for($count = 0; $count < count($grade); $count++)
     {

      
      $data = array(
       'user_id' =>$request->id, 
       'year' =>$year[$count], 
       'grade' => $grade[$count],
       'title'  => $title[$count],
       'position'  => $position[$count],
       'competency'  => $competency[$count],
       'performance'  => $performance[$count],
       'net_pay'  => $net_pay[$count],
       'basic_salary'  => $basic_salary[$count],
       'allowance'  => $allowance[$count],
       'ot_rate'  => $ot_rate[$count],
       'water_festival_bonus'  => $water_festival_bonus[$count],
       'thadingyut_bonus'  => $thadingyut_bonus[$count],
       'created_by' =>auth()->user()->id,
       'updated_by' =>auth()->user()->id 
      );
      
        //  User::where('id',$request->id)->update([
        //      'position_id' =>$position_id[$count],
        //      'branch_id' => $branch_id[$count],
        //      'department_id' =>  implode(',',$department_id[$count])
        //  ]);

      $insert_data[] = $data;
         
      }
       Evaluation::insert($insert_data);
     } 
      $evaluation_data= Evaluation::where('user_id','=',$request->id)->orderBy('year','DESC')->select()->first();
      
       NsEmployee::where('user_id','=',$request->id)->update([
            'grade' => $evaluation_data->grade
       ]);
      
      return back();

    }
    public function updateOther(Request $request){      
       $other= Other::where('user_id','=',$request->id)->select()->get();
       if(count($other) > 0){
         Other::where('user_id',$request->id)->update([
            'interest' => $request->interest,
            'strong_point' => $request->strong_point,
            'weak_point' => $request->weak_point,
          ]);
        }else{
          Other::insert([
            'user_id'=>$request->id,
            'interest' => $request->interest,
            'strong_point' => $request->strong_point,
            'weak_point' => $request->weak_point,
          ]);
      }
        return back();
    }
    public function updateLicense(Request $request){


        DriverLicense::where('user_id',$request->id)->update([
            'status' => 'expired'
          ]);

       DriverLicense::insert([
            'user_id'=>$request->id,
            'license_number' => $request->license_number,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'status' => 'active',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
          ]);

           return back();

    }
    public function updateRetirements(Request $request){
      
       $retirements= Retirement::where('user_id','=',$request->id)->select()->get();

       if(count($retirements) > 0){
         Retirement::where('user_id',$request->id)->update([
            'date' => $request->date,
            'reason' => $request->reason,
            'updated_by' => auth()->user()->id,
          ]);
        }else{
          Retirement::insert([
            'user_id'=>$request->id,
            'date' => $request->date,
            'reason' => $request->reason,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
          ]);
       }
        return back();
    }
    public function editNSList($id){

         $user = User::leftjoin('ns_employees','ns_employees.user_id','=','users.id')
                   ->leftjoin('contact_infos','contact_infos.user_id','=','users.id')
                   ->where('users.id','=',$id)
                   ->select('users.*','ns_employees.ot_rate','ns_employees.hourly_rate','ns_employees.grade','ns_employees.nrc_no','ns_employees.religion','ns_employees.current_address',
                    'ns_employees.race','ns_employees.new_address','ns_employees.new_phone','ns_employees.others_address',
                    'ns_employees.others_phone','ns_employees.employment_contract_no','ns_employees.current_address','contact_infos.first_person_name',
                    'contact_infos.first_person_email','contact_infos.first_person_phone','contact_infos.first_person_hotline'
                    ,'contact_infos.first_person_relationship','contact_infos.first_person_address','contact_infos.second_person_name',
                    'contact_infos.second_person_email','contact_infos.second_person_phone','contact_infos.second_person_hotline',
                    'contact_infos.second_person_relationship','contact_infos.second_person_address',
                    'ns_employees.microsoft_word','ns_employees.microsoft_excel','ns_employees.microsoft_powerpoint')
                   ->first();

                //   dd($user);exit();
                   
             
           $employee_types=EmployeeType::where('status','=',1)->select()->get();
           $roles=Role::where('status','=',1)->select()->get();
           $branches=Branch::where('status','=',1)->select()->get();
           $departments=Department::where('status','=',1)->select()->get();
           $banks=Bank::where('status','=',1)->select()->get();
           //family
           $families=Family::where('user_id','=',$id)->select()->get();
           //education
           $educations=Education::where('user_id','=',$id)->select()->get();
           //qualification
           $qualifications = Qualification::where('user_id','=',$id)->select()->get();
           //languages
           $languages = Language::where('user_id','=',$id)->select()->get();
           //languages
           $englishes = EnglishSkill::where('user_id','=',$id)->select()->get();
           //languages
           $oversearecords = OverseaRecord::where('user_id','=',$id)->select()->get();
           //warnings
           $warnings = Warning::where('user_id','=',$id)->select()->get();
           //evaluation
           $evaluations = Evaluation::where('user_id','=',$id)->select()->get();
           //other
           $other = Other::where('user_id','=',$id)->select()->first();
           //retirements
           $retirement = Retirement::where('user_id','=',$id)->select()->first();
           // employeement records
           $employmentrecords = EmploymentRecord::where('user_id','=',$id)->select()->get();
           // pc skill
           $pcskills = PcSkill::where('user_id','=',$id)->select()->get();
           //driver license
           $driver_licenses =DriverLicense::where('user_id','=',$id)->select()->get();

           $life_assurances = LifeAssurance::where('user_id','=',$id)->select()->get();
            
            $userbanks=UserBankAccount::where('user_id','=',$id)->select()->get();
           
         return view('employeemanagement.ns-edit',compact('user','employee_types','roles','branches',
         'userbanks','departments','banks','families','educations','qualifications','languages','englishes','oversearecords',
          'warnings','evaluations','other','retirement','employmentrecords','pcskills','driver_licenses','life_assurances')); 

    }

     public function delete(Request $request)
    {        
        
        //DB::table('users')->where('id','=',$request->id)->delete();

        User::find($request->id)->delete();
        
         return redirect()->back();
    }
    public function updateRSLeave(Request $request){

           $retirements= RsLeaveData::where('user_id','=',$request->id)->where('year','=',$request->year)->select()->get();

       if(count($retirements) > 0){
         RsLeaveData::where('user_id',$request->id)->where('year','=',$request->year)->update([
            'earned_leaves' => $request->earned_leaves,
            'refresh_leaves' => $request->refresh_leaves
          ]);
        }else{
          RsLeaveData::insert([
            'user_id'=>$request->id,
            'year' => $request->year,
            'earned_leaves' => $request->earned_leaves,
            'refresh_leaves' => $request->refresh_leaves,
          ]);
       }
        return back();
         
    }

    // rs refresh leave
    public function updateRefresh(Request $request){
      // start checking for leave
      // start for total
        $rangArray = [];
        $startDate = strtotime($request->start_date);
        $endDate = strtotime($request->end_date);
        for ($currentDate = $startDate; $currentDate <= $endDate;
                            $currentDate += (86400)) {
          $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        } 
        $datecount = count($rangArray);
        $countotal =  $request->refresh_leaves +  $request->earned_leaves + $request->other;

        if( floatval($datecount) !==  floatval($countotal)){
              return back()->with('error','You cannot Applied Leaves!! Start Date and End Date Total is not same with Refresh Leave,Earned Leave,Others counts');
        }

      // end for total
      $refresh_leaves = $request->refresh_leaves;
      $earned_leaves = $request->earned_leaves;
           $transdate = date('Y-m-d', time());
           $month = date('m', strtotime($transdate));
           //  $user_id   = auth()->user()->id;
        if(in_array($month,[01,02,03])){
            $end_year = date("Y");
            $start_year = $end_year -1;
        }else{
            $start_year = date("Y");
            $end_year = $start_year +1;
        }
        //start
        $releave = RsLeaveData::where('user_id',$request->id)->where('year','=',$start_year)->select()->first();
        //end
        $earnleavedays = LeaveForm::selectRaw('sum(total_days) as took_total_days')
                     ->where('user_id','=',$request->id)
                     ->where('leave_type_id','=',1)
                     ->where('approve_by_GM','!=','reject')
                     ->where('approve_by_dep_manager','!=','reject')
                     ->whereBetween('from_date', [$start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                     ->whereBetween('to_date', [$start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                     ->groupBy('leave_forms.user_id','leave_forms.leave_type_id')
                     ->first();

        $totalrefreshleaves=RsRefreshLeave::selectRaw('sum(refresh_leaves) as took_total_days')
                          ->where('user_id','=',$request->id)
                          ->whereBetween('start_date', [ $start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                          ->whereBetween('end_date', [ $start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                          ->first();

        $totalearnedleaves=RsRefreshLeave::selectRaw('sum(earned_leaves) as took_earned_leaves')
                          ->where('user_id','=',$request->id)
                          ->whereBetween('start_date', [ $start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                          ->whereBetween('end_date', [ $start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                          ->first();

            if(!empty($totalearnedleaves) &&  !empty($earnleavedays))
            {
               $tokenearnedleaves = $earnleavedays->took_total_days +  $totalearnedleaves->took_earned_leaves;
            }else if(!empty($totalearnedleaves) && empty($earnleavedays)){
                   $tokenearnedleaves = $totalearnedleaves->took_earned_leaves;
            }else if(empty($totalearnedleaves) && !empty($earnleavedays)){
                 $tokenearnedleaves = $earnleavedays->took_total_days;
            }else{
                 $tokenearnedleaves = 0;
            }
            $earnremaining_days = $releave->earned_leaves - $tokenearnedleaves;  

               if($request->earned_leaves >  $earnremaining_days){
                 return back()->with('error',' You cannot Applied Leaves!! Earned Leaves Request  is greater than remaining days');
            }

            $remaining_days = $releave->refresh_leaves - $totalrefreshleaves->took_total_days; 
                  
            if($request->refresh_leaves >  $remaining_days){
                return back()->with('error','You cannot Applied Leaves!! Your Refresh Leaves Request  is greater than remaining days');
            }
            // end checking for leave   
            RsRefreshLeave::insert([
                'user_id'=>$request->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'other' => $request->other,
                'earned_leaves' => $request->earned_leaves,
                'refresh_leaves' => $request->refresh_leaves,
                'airfare' => $request->airfare,
            ]);
             $startDate = new DateTime($request->start_date);
             $endDate = new DateTime( $request->end_date);
             $sundays = array();


       $holidays = Holiday::where('status','=',1)->pluck('date')->toArray(); 


       $user = User::where('id','=',$request->id)->select()->first();
        
            
        
         if(!empty($request->other)){
    

              $a = 0;
             while ($startDate <= $endDate) {

             if($startDate->format('w') != 0 && $startDate->format('w') != 6 && (!in_array($startDate->format('Y-m-d'),$holidays)) ) {
                  
              if( $a  <  $request->refresh_leaves) {

                    // start attendances

                     $att_record = Attendance::where('user_id','=',$request->id)
                      ->where('date','=',$startDate->format('Y-m-d'))
                      ->select()
                      ->first();
                      
                      
                     
                      

                    if(empty($att_record)){
            $device = 'Leave';
            $device_serial = 'Leave';
            $device_ip   = $request->ip();
            $user_id     = $request->id;
            if(!empty($user->profile_id))
            $profile_id  = $user->profile_id;
            else
            $profile_id  =  0;
          //  $date = $rang;
            $start_time='NULL';
            $type_id = 2;
            $leave_form_id = null;
            $branch_id = $user->branch_id;
            $status = 1;
            $corrected_start_time    = $user->working_start_time;
            $corrected_end_time      = $user->working_end_time;
            $created_by              = auth()->user()->id;

            Attendance::create([
                 'device' => $device,
                 'device_serial' =>$device_serial,
                 'device_ip' =>$device_ip,
                 'user_id'=>$user_id,
                 'profile_id'=>$profile_id,
                  'date'=> $startDate->format('Y-m-d'),
                  'start_time'=> $start_time,
                  'type' =>'RS Leave Type',
                  'type_id' => 2,
                  'leave_form_id' =>$leave_form_id,
                  'branch_id' => $branch_id,
                  'status' => $status,
                  'corrected_start_time' =>$corrected_start_time,
                  'corrected_end_time' =>$corrected_end_time,
                  'created_by' => $created_by,
               ]);
            }
                    //  end attendances                    

                }else{

                    // start attendances

                       $att_record = Attendance::where('user_id','=',$request->id)
                      ->where('date','=',$startDate->format('Y-m-d'))
                      ->select()
                      ->first();
                      

                    if(empty($att_record)){
            $device = 'Leave';
            $device_serial = 'Leave';
            $device_ip   = $request->ip();
            $user_id     = $request->id;
             if(!empty($user->profile_id))
            $profile_id  = $user->profile_id;
            else
            $profile_id  =  0;
          //  $date = $rang;
            $start_time='NULL';
            $type_id = 1;
            $leave_form_id = null;
            $branch_id = $user->branch_id;
            $status = 1;
            $corrected_start_time    = $user->working_start_time;
            $corrected_end_time      = $user->working_end_time;
            $created_by              = auth()->user()->id;

            Attendance::create([
                 'device' => $device,
                 'device_serial' => $device_serial,
                 'device_ip' =>  $device_ip,
                 'user_id'=>$user_id,
                 'profile_id'=>$profile_id,
                 'date'=> $startDate->format('Y-m-d'),
                 'start_time'=> $start_time,
                 'type' =>'RS Leave Type',
                 'type_id' => 1,
                 'leave_form_id' => $leave_form_id,
                 'branch_id' => $branch_id,
                 'status' => $status,
                 'corrected_start_time' => $corrected_start_time,
                 'corrected_end_time' => $corrected_end_time,
                 'created_by' => $created_by,
              ]);
            }





                    // end attendances


                   }
               
               
               }
               $startDate->modify('+1 day'); 
               $a++;
            }
                     
      }else{

            // start
            $a = 0;
             while ($startDate <= $endDate) {

          
                  
              if( $a  <  $request->refresh_leaves) {

                    // start attendances

                     $att_record = Attendance::where('user_id','=',$request->id)
                      ->where('date','=',$startDate->format('Y-m-d'))
                      ->select()
                      ->first();
                      

                    if(empty($att_record)){
            $device = 'Leave';
            $device_serial = 'Leave';
            $device_ip   = $request->ip();
            $user_id     = $request->id;
            if(!empty($user->profile_id))
            $profile_id  = $user->profile_id;
            else
            $profile_id  =  0;
          //  $date = $rang;
            $start_time='NULL';
            $type_id = 2;
            $leave_form_id = null;
            $branch_id = $user->branch_id;
            $status = 1;
            $corrected_start_time    = $user->working_start_time;
            $corrected_end_time      = $user->working_end_time;
            $created_by              = auth()->user()->id;

            Attendance::create([
                 'device' => $device,
                 'device_serial' =>$device_serial,
                 'device_ip' =>$device_ip,
                 'user_id'=>$user_id,
                 'profile_id'=>$profile_id,
                  'date'=> $startDate->format('Y-m-d'),
                  'start_time'=> $start_time,
                  'type' =>'RS Leave Type',
                  'type_id' => 2,
                  'leave_form_id' =>$leave_form_id,
                  'branch_id' => $branch_id,
                  'status' => $status,
                  'corrected_start_time' =>$corrected_start_time,
                  'corrected_end_time' =>$corrected_end_time,
                  'created_by' => $created_by,
               ]);
            }
                    //  end attendances                    

                }else{

                    // start attendances

                       $att_record = Attendance::where('user_id','=',$request->id)
                      ->where('date','=',$startDate->format('Y-m-d'))
                      ->select()
                      ->first();
                      

                    if(empty($att_record)){
            $device = 'Leave';
            $device_serial = 'Leave';
            $device_ip   = $request->ip();
            $user_id     = $request->id;
            $profile_id  = $user->profile_id;
          //  $date = $rang;
            $start_time='NULL';
            $type_id = 1;
            $leave_form_id = null;
            $branch_id = $user->branch_id;
            $status = 1;
            $corrected_start_time    = $user->working_start_time;
            $corrected_end_time      = $user->working_end_time;
            $created_by              = auth()->user()->id;

            Attendance::create([
                 'device' => $device,
                 'device_serial' => $device_serial,
                 'device_ip' =>$device_ip,
                 'user_id'=>$user_id,
                 'profile_id'=>$profile_id,
                 'date'=> $startDate->format('Y-m-d'),
                 'start_time'=> $start_time,
                 'type' =>'RS Leave Type',
                 'type_id' => 1,
                 'leave_form_id' => $leave_form_id,
                 'branch_id' => $branch_id,
                 'status' => $status,
                 'corrected_start_time' => $corrected_start_time,
                 'corrected_end_time' => $corrected_end_time,
                 'created_by' => $created_by,
              ]);
            }





                    // end attendances


                   }
               
               $startDate->modify('+1 day'); 
               $a++;
            }
                

            //end
                  
       }
         return back()->with('success','You Have Successfully Applied Leaves');
           
       
    }
    public function deleteRefresh($id){
      //start
      $refreshleaves =RsRefreshLeave::where('rs_refresh_leaves.id','=',$id)
        ->select('*')
        ->first();
        $rangArray = [];
        $startDate = strtotime($refreshleaves->start_date);
        $endDate = strtotime($refreshleaves->end_date);
        for ($currentDate = $startDate; $currentDate <= $endDate;
                            $currentDate += (86400)) {
          $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }

           foreach($rangArray as $rang){
              Attendance::where('user_id','=',$refreshleaves->user_id)->where('date','=',$rang)->delete();
          }
           
           RsRefreshLeave::where('id','=',$id)->delete();      
            
          return back()->with('success','You Have Successfully Delete Leaves'); 
    }
      public function exportNS(Request $request){

      $users=$request->users;
      $families=$request->families; 
      $life_assurances=$request->life_assurances;
      $education=$request->education;
      $qualifications=$request->qualifications;
      $languages=$request->languages;
      $english_skills = $request->english_skills;
      $driver_licenses = $request->driver_licenses;
      $pc_skills   = $request->pc_skills;
      $employment_records=$request->employment_records;
      $oversea_records=$request->oversea_records;
      $warnings  = $request->warnings;
      $evaluations = $request->evaluations;     
      

     $employee_name=$request->employee_name;

       

    //dd($employee_name);exit();


     $datas= DB::table('users')
          ->leftjoin('contact_infos','contact_infos.user_id','=','users.id')
          ->leftjoin('employee_types','employee_types.id','=','users.employee_type_id')
          ->leftjoin('ns_employees','ns_employees.user_id','=','users.id')
          ->leftjoin('retirements','retirements.user_id','=','users.id')
          ->leftjoin('others','others.user_id','=','users.id')
          ->leftjoin('branches','branches.id','=','users.branch_id')
          ->leftjoin('banks as bank_usd','bank_usd.id','=','users.bank_name_usd')
          ->leftjoin('banks as bank_mmk','bank_mmk.id','=','users.bank_name_mmk')
          ->whereIn('users.id', $employee_name)
          ->select($users)
          ->get();

            
            foreach($datas as $data){


              if(!empty($families)){
              $data->family_information = Family::where('user_id','=',$data->id)
                                          ->select($families)
                                          ->get();
              }else{

                $data->family_information = null ;
              }


                                         
                if(!empty($life_assurances)){ 
                    $data->life_assurances = LifeAssurance::where('user_id','=',$data->id)
                                          ->select($life_assurances)
                                          ->get();
                }else{
                    $data->life_assurances = null ;
                }
               
                 if(!empty($education)){ 
                    $data->education = Education::where('user_id','=',$data->id)
                                          ->select($education)
                                          ->get();
                 }else{
                   $data->education = null ;
                 }
                   
                   if(!empty($qualifications)){
                  $data->qualifications  = Qualification::where('user_id','=',$data->id)
                                          ->select($qualifications)
                                          ->get();
                   }else{
                       $data->qualifications =null;
                   }
                  
                    if(!empty($languages)){
                   $data->languages       = Language::where('user_id','=',$data->id)
                                          ->select($languages)
                                          ->get();
                    }else{
                       $data->languages  = null;
                    }
                 
                    if(!empty($english_skills)){
                  $data->english_skills  = EnglishSkill::where('user_id','=',$data->id)
                                          ->select($english_skills)
                                          ->get();
                    }else{
                      $data->english_skills  = null;

                    }
                    if(!empty($driver_licenses)){

                     $data->driver_licenses  = DriverLicense::where('user_id','=',$data->id)
                                          ->select($driver_licenses)
                                          ->get();

                    }else{
                        $data->driver_licenses = null;
                    }

                  // $data->driver_licenses  = DriverLicense::where('user_id','=',$data->id)
                  //                         ->select($driver_licenses)
                  //                         ->get();
                   
                   if(!empty($pc_skills)){

                  $data->pc_skills  = PcSkill::where('user_id','=',$data->id)
                                          ->select($pc_skills)
                                          ->get();

                   }else{
                     $data->pc_skills  = null;

                   }
                  if(!empty($employment_records)){
                  $data->employment_records  = EmploymentRecord::where('user_id','=',$data->id)
                                          ->select($employment_records)
                                          ->get();
                  }else{
                     $data->employment_records = null;
                  }
                  
                  if(!empty($oversea_records)){
                  $data->oversea_records  = OverseaRecord::where('user_id','=',$data->id)
                                          ->select($oversea_records)
                                          ->get();
                  }else{
                    $data->oversea_records = null;
                  }
                  
                  if(!empty($warnings)){
                  $data->warnings  = Warning::where('user_id','=',$data->id)
                                          ->select($warnings)
                                          ->get();
                  }else{
                    $data->warnings  = null ;
                  }
 
                  if(!empty($evaluations)){
                  $data->evaluations  = Evaluation::where('user_id','=',$data->id)
                                          ->select($evaluations)
                                          ->get();
                  }else{
                     $data->evaluations  = null;
                  }

                  // start
                  if(!empty($data->department_id)){
                   $dept= explode(',',$data->department_id);
                    $ddepartments = DB::table("departments")
                           ->select("name")
                          ->whereIn('id',$dept)
                          ->get();
 
                          $ddd=[];
                          foreach( $ddepartments  as $depart){
                             $ddd[] = $depart->name;
                          }                       
                   $data->departments =  implode(", ", $ddd);
                  }else{
                     $data->departments = null;
                  }


            }
           
            
            $filename = "ns_employee_".Carbon::now()->format('d-m-Y');

            return Excel::download(new NsExport($datas,$users,$families,$life_assurances,$education
      ,$qualifications,$languages,$english_skills,$driver_licenses,$pc_skills,
    $employment_records,$oversea_records,$warnings,$evaluations),$filename.'.xlsx');  
        

    }
    
      public function exportRS(Request $request){

        $users=$request->users;
        $contact_infos=$request->contact_infos; 
        $families=$request->families;
        $rs_leave_data=$request->rs_leave_data;
        $rs_refresh_leaves=$request->rs_refresh_leaves;

        $employee_name=$request->employee_name;

         $datas= DB::table('users')
          ->leftjoin('contact_infos','contact_infos.user_id','=','users.id')
          ->leftjoin('employee_types','employee_types.id','=','users.employee_type_id')
          ->leftjoin('rs_employees','rs_employees.user_id','=','users.id')
          ->leftjoin('branches','branches.id','=','users.branch_id')
          ->leftjoin('banks as bank_usd','bank_usd.id','=','users.bank_name_usd')
          ->leftjoin('banks as bank_mmk','bank_mmk.id','=','users.bank_name_mmk')
          ->leftjoin('banks as bank_mmk2','bank_mmk2.id','=','rs_employees.second_bank_name_mmk')
          ->whereIn('users.id', $employee_name)
          ->select($users)
          ->get();

          foreach($datas as $data){


              if(!empty($families)){
              $data->family_information = Family::where('user_id','=',$data->id)
                                          ->select($families)
                                          ->get();
              }else{

                $data->family_information = null ;
              }


              if(!empty($rs_leave_data)){
                  $data->rs_leave_data = RsLeaveData::where('user_id','=',$data->id)
                                          ->select($rs_leave_data)
                                          ->get();
              }else{
                  $data->rs_leave_data = null ;
              }

              if(!empty($rs_refresh_leaves)){
                  $data->rs_refresh_leaves = RsRefreshLeave::where('user_id','=',$data->id)
                                          ->select($rs_refresh_leaves)
                                          ->get();
              }else{
                  $data->rs_refresh_leaves = null ;
              }

              if(!empty($data->department_id)){
                   $dept= explode(',',$data->department_id);
                    $ddepartments = DB::table("departments")
                           ->select("name")
                          ->whereIn('id',$dept)
                          ->get();
 
                          $ddd=[];
                          foreach( $ddepartments  as $depart){
                             $ddd[] = $depart->name;
                          }                       
                   $data->departments =  implode(", ", $ddd);
                  }else{
                     $data->departments = null;
                  }


        }

         $filename = "rs_employee_".Carbon::now()->format('d-m-Y');

            return Excel::download(new RsExport($datas,$users,$families,$rs_leave_data,$rs_refresh_leaves),$filename.'.xlsx'); 



    }

}