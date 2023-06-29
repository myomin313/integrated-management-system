<?php

namespace App\Http\Controllers\LeaveManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\LeaveManagement\LeaveForm;
use App\Models\LeaveManagement\LeaveType;
use App\Models\MasterManagement\Department;
use App\Models\AttendanceManagement\Attendance;
use App\Models\EmployeeManagement\RsLeaveData;
use App\Models\MasterManagement\Holiday;
use App\Models\EmployeeManagement\RsRefreshLeave;
use App\Models\Permission;
use App\Models\User;
use App\Exports\LeaveExport;
use App\Exports\LeaveListExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\LeaveApprove;
use App\Mail\LeaveApproveByDepManager;
use App\Mail\LeaveApproveForDepManager;
use App\Mail\UnpaidLeaveEmail;
use App\Mail\LeaveCancelApproveForDepManager;
use App\Mail\LeaveCancelApproveByDepManager;
use App\Mail\LeaveCancelApprove;
use Redirect;
use Session;
use DB;
use Response;
use Carbon\Carbon;
use DateTime;

class LeaveController extends Controller
{
     public function index(Request $request)
    {
        $leave_type_id = $request->search_leave_type_id;
         $employee_name = $request->employee_name;
        $users        =User::all();
        //  $banks = Bank::join('users', 'users.id','=','banks.created_by')
        //  ->join('users as users2', 'users2.id','=','banks.updated_by')
        //  ->select('banks.*','users.employee_name as created_user','users.employee_name as updated_user')->get();
        $leave_types =LeaveType::where('status','=',1)->select()->get();

       
                         
                         $terms = explode(',',auth()->user()->department_id);
                         
                         if (in_array(1, $terms)) {
                         
       $leave_requests =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                         ->join('users','users.id','=','leave_forms.user_id')
                         ->where(function($query)use($leave_type_id){
                            if($leave_type_id != null){
                            $query->where('leave_forms.leave_type_id','=', $leave_type_id);
                           }
                          })
                          ->where(function($query)use($employee_name){
                            if($employee_name != null){
                            $query->where('users.id','=', $employee_name);
                           }
                          })
                       //  ->where('leave_forms.user_id','=',auth()->user()->id)
                         //->whereIn('employee.department_id',$terms)
                         ->select('users.employee_name','leave_forms.*','leave_types.leave_type_name','users.check_ns_rs')
                         ->get();
                         $permission = 1;
                         }else{
                             
                    $leave_requests =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                         ->join('users','users.id','=','leave_forms.user_id')
                         ->where(function($query)use($leave_type_id){
                            if($leave_type_id != null){
                            $query->where('leave_forms.leave_type_id','=', $leave_type_id);
                           }
                          })
                         ->where('leave_forms.user_id','=',auth()->user()->id)
                         ->select('users.employee_name','leave_forms.*','leave_types.leave_type_name','users.check_ns_rs')
                         ->get();
                         $permission = 0;
                         }


        return view('leavemanagement.leave-request-history',compact('leave_types','leave_requests','permission','users'));
    }
    public function approveListForDepManager(Request $request)
    {
        $employee_name = $request->employee_name;
        $leave_types =LeaveType::where('status','=',1)->select()->get();

        $department = User::where('id','=',auth()->user()->id)->select('department_id')->first();
        
         $terms = explode(',',$department->department_id);
          
       // dd($terms);exit();
              
       // $ns_leave_request = LeaveForm::join('rs_leave_data','rs_leave_datas.user_id');

      
        $leave_requests =LeaveForm::select('leave_forms.*','leave_types.leave_type_name','employee.employee_name'
                          ,'roles.name as role_name','employee.photo_name','employee.check_ns_rs')
                         ->join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                         ->leftjoin('users as employee','employee.id','=','leave_forms.user_id')
                         ->leftjoin('roles','roles.id','=','employee.position_id')
                         ->where(function ($query) use ($employee_name) {
                            if($employee_name != null)
                             $query->where('employee.id','=',$employee_name);
                         })
                        // ->where('employee.id','=',auth()->user()->id)
                        //  ->where(function($query) use($terms) {
                        //   foreach($terms as $term) {
                        //   //  $query->whereRaw("find_in_set('".$term."',employee.department_id)");
                        //       $query->whereIn('employee.department_id','=',$term);
                        //   }
                        //  })
                         ->whereIn('employee.department_id',$terms)
                         ->orWhere('employee.department_id','like', '%' . $department->department_id . '%')
                      // ->whereRaw("find_in_set('".$department->department_id."',employee.department_id)")
                        
                         // ->groupBy('leave_forms.id')
                         ->get();
                         
                         
                         
                          //terms = explode(',',auth()->user()->department_id);
                          
              //auth()->user()->id
              //->whereRaw("find_in_set('".$department."',employee.department_id)")
            $all_users=  User::whereIn('users.department_id',$terms)->select('employee_name','id')->get();
           
          
            
            // $all_users=  User::whereRaw("find_in_set('".$department->department_id."',users.department_id)")->groupBy('id')->select('employee_name','id')->get();
            
               
      
        

        return view('leavemanagement.leave-request-history-for-approve',compact('leave_types','leave_requests','all_users'));
    }
        public function approveListForADMI(Request $request){

        $employee_name = $request->employee_name;
        $leave_types =LeaveType::where('status','=',1)->select()->get();
        $departments =Department::where('status','=',1)->select()->get();
        
        $department = $request->search_department;
        $status = $request->search_status;
        if(!empty($status)){

           $leave_requests =\DB::table("leave_forms")->select('leave_forms.*','leave_types.leave_type_name','employee.employee_name'
                          ,'roles.name as role_name','departments.short_name as department_short_name','employee.photo_name',
                          'employee.check_ns_rs',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                          ->join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                         ->join('users as employee','employee.id','=','leave_forms.user_id')
                         ->leftjoin('roles','roles.id','=','employee.position_id')
                         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,employee.department_id)"),">",\DB::raw("'0'"))
                        //  ->leftjoin('departments','departments.id','=','employee.department_id')
                         ->where(function ($query) use ($employee_name) {
                            if($employee_name != null)
                            $query->where('employee.id','=',$employee_name);
                         })
                        //  ->where(function($query)use($department){
                        //     if($department != null)
                        //      $query->where('employee.department_id', '=', $department);
                        //  })
                         ->where(function($query)use($department){
                               if($department != null)
                                //$query->where('users.department_id', '=', $depart);
                                $query->whereRaw("find_in_set('".$department."',employee.department_id)");
                           })
                         ->where(function($query)use($status){
                            if($status != null)
                             $query->where('leave_forms.approve_by_GM', '=', $status);
                         })
                         ->where('leave_forms.approve_by_dep_manager','=','accept')
                         ->groupBy('leave_forms.id')
                         ->get();

        }else{

            $leave_requests =\DB::table("leave_forms")->select('leave_forms.*','leave_types.leave_type_name','employee.employee_name'
                          ,'roles.name as role_name','departments.short_name as department_short_name','employee.photo_name',
                          'employee.check_ns_rs',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
                          ->join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                         ->join('users as employee','employee.id','=','leave_forms.user_id')
                         ->leftjoin('roles','roles.id','=','employee.position_id')
                         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,employee.department_id)"),">",\DB::raw("'0'"))
                        //  ->leftjoin('departments','departments.id','=','employee.department_id')
                         ->where(function ($query) use ($employee_name) {
                            if($employee_name != null)
                            $query->where('employee.id','=',$employee_name);
                         })
                         ->where(function($query)use($department){
                               if($department != null)
                                //$query->where('users.department_id', '=', $depart);
                                $query->whereRaw("find_in_set('".$department."',employee.department_id)");
                           })
                        //  ->where(function($query)use($department){
                        //     if($department != null)
                        //      $query->where('employee.department_id', '=', $department);
                        //  })
                         ->where('leave_forms.approve_by_dep_manager','=','accept')
                         ->where('leave_forms.approve_by_GM','=','pending')
                         ->groupBy('leave_forms.id')
                        //  ->select('leave_forms.*','leave_types.leave_type_name','employee.employee_name'
                        //   ,'roles.name as role_name','departments.short_name as department_short_name','employee.photo_name',
                        //   'employee.check_ns_rs')
                         ->get();

         }
        

           $all_users=  User::select('employee_name','id')->get();
        

        return view('leavemanagement.leave-request-history-approve-by-admin',compact('leave_types','status','leave_requests','departments','all_users'));

    }
    // public function approveListForADMI(Request $request){

    //     $employee_name = $request->employee_name;
    //     $leave_types =LeaveType::where('status','=',1)->select()->get();
    //     $departments =Department::where('status','=',1)->select()->get();
        
    //     $department = $request->search_department;
    //     $status = $request->search_status;
    //     if(!empty($status)){

    //       $leave_requests =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
    //                      ->join('users as employee','employee.id','=','leave_forms.user_id')
    //                      ->leftjoin('roles','roles.id','=','employee.position_id')
    //                      ->leftjoin('departments','departments.id','=','employee.department_id')
    //                      ->where(function ($query) use ($employee_name) {
    //                         if($employee_name != null)
    //                         $query->where('employee.id','=',$employee_name);
    //                      })
    //                      ->where(function($query)use($department){
    //                         if($department != null)
    //                          $query->where('employee.department_id', '=', $department);
    //                      })
    //                      ->where(function($query)use($status){
    //                         if($status != null)
    //                          $query->where('leave_forms.approve_by_GM', '=', $status);
    //                      })
    //                      ->where('leave_forms.approve_by_dep_manager','=','accept')
    //                      ->select('leave_forms.*','leave_types.leave_type_name','employee.employee_name'
    //                       ,'roles.name as role_name','departments.short_name as department_short_name','employee.photo_name',
    //                       'employee.check_ns_rs')
    //                      ->get();

    //     }else{

    //         $leave_requests =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
    //                      ->join('users as employee','employee.id','=','leave_forms.user_id')
    //                      ->leftjoin('roles','roles.id','=','employee.position_id')
    //                      ->leftjoin('departments','departments.id','=','employee.department_id')
    //                      ->where(function ($query) use ($employee_name) {
    //                         if($employee_name != null)
    //                         $query->where('employee.id','=',$employee_name);
    //                      })
    //                      ->where(function($query)use($department){
    //                         if($department != null)
    //                          $query->where('employee.department_id', '=', $department);
    //                      })
    //                      ->where('leave_forms.approve_by_dep_manager','=','accept')
    //                      ->where('leave_forms.approve_by_GM','=','pending')
    //                      ->select('leave_forms.*','leave_types.leave_type_name','employee.employee_name'
    //                       ,'roles.name as role_name','departments.short_name as department_short_name','employee.photo_name',
    //                       'employee.check_ns_rs')
    //                      ->get();

    //      }

    //       $all_users=  User::select('employee_name','id')->get();
        

    //     return view('leavemanagement.leave-request-history-approve-by-admin',compact('leave_types','status','leave_requests','departments','all_users'));

    // }
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'leave_type_id'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'reason'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
       // $totalRate =  $request->liter * $request->rate;
        $start = explode('/',  $request->start_date);
        $from_date = $start[2].'-'.$start[1].'-'.$start[0];

        $end = explode('/',  $request->end_date);
        $to_date = $end[2].'-'.$end[1].'-'.$end[0];
        
        
        $aaDate = Date('Y-m-d', strtotime('+3 days'));
        
        if($request->leave_type_id == 1){
            if($from_date > $aaDate){
                return response()->json(['status'=> 0,'message' => 'You can not take casual leave 3  days in advance']);
            }
        }

  
        $emp_type = User::where('id','=',auth()->user()->id)->select('employee_type_id')->first();
       $driver_holidays = Holiday::where('status','=',1)->where('driver','=',1)->pluck('date')->toArray();
       $holidays = Holiday::where('status','=',1)->pluck('date')->toArray();
      //  dd($holidays);exit();
      // start
         
           //for total days
           
        $startDate = new DateTime($from_date);
        $endDate = new DateTime($to_date);
        $sundays = array();
        while ($startDate <= $endDate) {
           if(!empty($emp_type)){
           if($emp_type->employee_type_id == 2  || $emp_type->employee_type_id == 6 ){
             if($startDate->format('w') != 0 && (!in_array($startDate->format('Y-m-d'),$holidays)) ) {
                 $sundays[] = $startDate->format('Y-m-d');
               }    
              $startDate->modify('+1 day');
            //   }
            }else if($emp_type->employee_type_id == 3  || $emp_type->employee_type_id == 7){

              if($startDate->format('w') != 0 && (!in_array($startDate->format('Y-m-d'),$driver_holidays))) {
                 $sundays[] = $startDate->format('Y-m-d');
               }    
              $startDate->modify('+1 day');

            }else if($emp_type->employee_type_id == 8 ){
               if($startDate->format('w') != 0 && $startDate->format('w') != 6 && (!in_array($startDate->format('Y-m-d'),$holidays)) ) {
                   $sundays[] = $startDate->format('Y-m-d');
               }    
               $startDate->modify('+1 day');               
            }else{
              if($startDate->format('w') != 0 && $startDate->format('w') != 6 && (!in_array($startDate->format('Y-m-d'),$holidays)) ) {
                 $sundays[] = $startDate->format('Y-m-d');
               }    
               $startDate->modify('+1 day');
             }
           }
        }
    
        //for total days
      // end
        
        //for total days
        $to =date_create($to_date);
        $from =date_create($from_date);
        $interval = date_diff($from, $to);
        $total=$interval->format('%a') + 1;

         if( $request->leave_type_id == 4 ||  $request->leave_type_id == 6 ){
                 $total_days = $total;
         }else{
                $total_days = count($sundays);
         }
        //for total days

         if($request->leave_type_id == 1 || $request->leave_type_id == 2){
                  $date=date_create($from_date);
                   date_sub($date,date_interval_create_from_date_string("1 days"));
                  $isEqualDate= date_format($date,"Y-m-d");
              $leavedays=LeaveForm::where('leave_type_id','=',$request->leave_type_id)
                              ->where('user_id','=',auth()->user()->id)
                              ->where('to_date','=',$isEqualDate)
                              ->where('approve_by_GM','!=','reject')
                              ->select()
                              ->first();

            if(!empty($leavedays)){
                return response()->json(['status'=> 0,'message' => 'You can not take casual leave and earned leave continuously']); 
            }
         }
          
         
         //  $remaining_days = (int)$request->remaining_days;

             
            if($request->holiday_type_id == 1){
                $left_remaining_days = $request->remaining_days;
               $left_total_days =  $total_days;
            }else{
               $left_total_days =  $total_days - 0.5;
               $left_remaining_days=  $request->remaining_days - 0.5;

            }

         if($left_total_days > $left_remaining_days){
            return response()->json(['status'=> 0,'message' => 'Your  Request is Greater Than  Remaining Days.You need to check end date']);
        }else{
          
         if($request->holiday_type_id == 1){
            LeaveForm::create([
                'leave_type_id' => $request->leave_type_id,
                'remaining_days' => $left_remaining_days,
                'from_date' =>  $from_date,
                'to_date'=>$to_date,
                'total_days'=>$left_total_days,
                'user_id'=> auth()->user()->id,
                'reason'=> $request->reason,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
         }else{
             LeaveForm::create([
                'leave_type_id' => $request->leave_type_id,
                'remaining_days' => $left_remaining_days,
                'from_date' => $from_date,
                'to_date'=>$to_date,
                'day_type'=>$request->day_type,
                'total_days'=>$left_total_days,
                'user_id'=> auth()->user()->id,
                'reason'=> $request->reason,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
         }

        //  $users=User::permission('leave-approve-by-dep-manager')->where('users.department_id','=',auth()->user()->department_id)->select('users.email','users.employee_name')->get();
       // $sid=auth()->user()->department_id;
        
        $terms = explode(',',auth()->user()->department_id);
       
      //  $terms = explode(',',$department->department_id);
// ->whereIn('employee.department_id',$terms)
        
         $users=User::permission('leave-approve-by-dep-manager')
                      ->where(function($query) use($terms) {
                        foreach($terms as $term) {
                             $query->whereRaw("find_in_set('".$term."',users.department_id)");
                        };
                    })
                    // ->whereIn('users.department_id',$terms)
                    ->select('users.email','users.employee_name')->get();
                  
         
          $employee_name =auth()->user()->employee_name;

        foreach($users as $use){
             $email=$use->email;
             $dep_person_name = $use->employee_name;
             Mail::to($email)->send(new LeaveApproveForDepManager($dep_person_name,$employee_name,$from_date,$to_date));
        }
      

            return response()->json(['status'=> 1 ,'message' => 'Leave Request submitted successfully.']);
     }


    }
   //  rs leave store
     public function RsLeavestore(Request $request){  
        //start 
        
        $validator = Validator::make($request->all(), [
            'leave_type_id'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'reason'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
          $start = explode('/',  $request->start_date);
        $from_date = $start[2].'-'.$start[1].'-'.$start[0];

        $end = explode('/',  $request->end_date);
        $to_date = $end[2].'-'.$end[1].'-'.$end[0];
       
          $startDate = new DateTime($from_date);
          $endDate = new DateTime($to_date);
          $sundays = array();
       
           $holidays = Holiday::where('status','=',1)->pluck('date')->toArray();
       
         while ($startDate <= $endDate) {
           if($startDate->format('w') != 0 && $startDate->format('w') != 6 && (!in_array($startDate->format('Y-m-d'),$holidays)) ) {
               $sundays[] = $startDate->format('Y-m-d');
               }    
              $startDate->modify('+1 day'); 
          }
              
              
         $total_days =  count($sundays);
         
        
            if($request->holiday_type_id == 1){
                $left_remaining_days = $request->remaining_days;
                 $left_total_days =  $total_days;
            }else{
               $left_total_days =  $total_days - 0.5;
               $left_remaining_days=  $request->remaining_days - 0.5;

            }
          
         
       
       if($left_total_days > $left_remaining_days){
            return response()->json(['status'=> 0,'message' => 'Your  Request is Greater Than  Remaining Days.You need to check end date']);
        }else{
        //ss

          if($request->holiday_type_id == 1){
            LeaveForm::create([
                'leave_type_id' => $request->leave_type_id,
                'remaining_days' => $left_remaining_days,
                'from_date' =>  $from_date,
                'to_date'=>$to_date,
                'total_days'=>$left_total_days,
                'user_id'=> auth()->user()->id,
                'reason'=> $request->reason,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
         }else{          
             LeaveForm::create([
                'leave_type_id' => $request->leave_type_id,
                'remaining_days' => $left_remaining_days,
                'from_date' => $from_date,
                'to_date'=>$to_date,
                'day_type'=>$request->day_type,
                'total_days'=>$left_total_days,
                'user_id'=> auth()->user()->id,
                'reason'=> $request->reason,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);
         }
      }
      
      //start for mail
      
    //   $user= User::where('id','=',auth()->user()->id)->select()->first();
       
        // $department= User::join('departments','departments.id','=','users.department_id')->where('users.id','=',auth()->user()->id)->select()->first();
        
        // $department_name = $department->name;
        $employee_name =auth()->user()->employee_name;
        // $from_date =$leave_form->from_date;
        // $to_date =$leave_form->to_date;
        
       // $users=User::permission('leave-approve-by-dep-manager')->where('users.department_id','=',auth()->user()->department_id)->select('users.email','users.employee_name')->get();
       
        $terms = explode(',',auth()->user()->department_id);
        
         $users=User::permission('leave-approve-by-dep-manager')
                      ->where(function($query) use($terms) {
                        foreach($terms as $term) {
                            //$query->orWhere('users.department_id', 'like', "%$term%");
                            $query->whereRaw("find_in_set('".$term."',users.department_id)");
                        };
                    })
                    ->select('users.email','users.employee_name')->get();
                    
          
        foreach($users as $use){
             $email=$use->email;
             $dep_person_name = $use->employee_name;
             Mail::to($email)->send(new LeaveApproveForDepManager($dep_person_name,$employee_name,$from_date,$to_date));
        }
      
      
      // end for mail
         

     }
    public function approveByDepManager(Request $request){

        $id = $request->id;
        $status = $request->status;
        $approve_reason_by_dep_manager=$request->approve_reason_by_dep_manager;
        
        $leave_form=LeaveForm::where('id',$id)->update([
          'approve_by_dep_manager' => $status,
          'approve_reason_by_dep_manager'=>$approve_reason_by_dep_manager
        ]);
        
         $leave_form =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
        ->where('leave_forms.id','=',$id)
        ->select('leave_forms.*','leave_types.leave_type_name')
        ->first();
        
        $user= User::where('id','=',$leave_form->user_id)->select()->first();
        $department= User::join("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))->where('users.id','=',auth()->user()->id)->groupBy('users.id')->select()->first();
        $department_name = $department->name;
        $employee_name =$user->employee_name;
        $from_date =$leave_form->from_date;
        $to_date =$leave_form->to_date;
        if($status == 'accept'){
        $users=User::permission('leave-approve-by-admi-gm')->select('users.email','users.employee_name')->get();
          foreach($users as $use){
             $email=$use->email;
             $admin_name = $use->employee_name;
             $approve_reason_by_dep_manager =$leave_form->approve_reason_by_dep_manager;
             Mail::to($email)->send(new LeaveApproveByDepManager($admin_name,$employee_name,$from_date,$to_date,$status,$department_name,$approve_reason_by_dep_manager));
          }
        }else{
            
             //start for reject
                if( $user->employee_type_id = 2 ||  $user->employee_type_id = 3  || $user->employee_type_id = 4  || $user->employee_type_id = 6  || $user->employee_type_id = 7){
         //send sms
               $phone = $user->phone;
            $message  = "Url : https://mobile.marubeniyangon.com.mm\n";
            $message .= "Your Request For  Leave";
            $message .= "Employee Name : $employee_name\n";
            $message .= "From Date : $from_date\n";
            $message .= "To Date : $to_date\n";
            $message .= "is : $status\n";
            $message .= "Reason\n";
            if(!empty($approve_reason_by_dep_manager)){
               $message .= "$approve_reason_by_dep_manager";
            }
            sendSMS($phone,$message);
       }else{
           
            Mail::to($email)->send(new LeaveApproveDepReject($department_name,$employee_name,$from_date,$to_date,$status,$approve_reason_by_dep_manager));  
           
       }
            
        }
        
       return response()->json(['success' => 'Department Manager Approve Status successfully.']);
    }

    public  function  requestLeaveCancelapproveByDepManager(Request $request){

        $id = $request->id;
        $status = $request->status;
        $cancel_leave_approve_reason_by_dep_manager=$request->cancel_leave_approve_reason_by_dep_manager;
        //start
               $leaveforms=LeaveForm::where('id',$id)->select()->first(); 
           if($leaveforms->approve_by_GM  == 'pending' || $leaveforms->approve_by_GM  == 'reject'){
                         
                          $leaveforms=LeaveForm::where('id',$id)->delete();
                         
                     }else{
        //end
        if($status == 'accept'){
        $leave_form=LeaveForm::where('id',$id)->update([
          'cancel_leave_approve_by_dep_manager' => $status,
          'cancel_leave_approve_by_admi_manager' => 'pending',
          'cancel_leave_approve_reason_by_dep_manager'=> $cancel_leave_approve_reason_by_dep_manager,
        ]);
        }else{
           $leave_form=LeaveForm::where('id',$id)->update([
            'cancel_leave_approve_by_dep_manager' => $status,
            'cancel_leave_approve_by_admi_manager' => Null,
            'cancel_leave_approve_reason_by_dep_manager'=> $cancel_leave_approve_reason_by_dep_manager,
          ]);
        }

     $leave_form =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                  ->where('leave_forms.id','=',$id)
                  ->select('leave_forms.*','leave_types.leave_type_name')
                  ->first();
        
        $user= User::where('id','=',$leave_form->user_id)->select()->first();
        $department= User::join("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))->where('users.id','=',auth()->user()->id)->groupBy('users.id')->select()->first();
        $department_name = $department->name;
        $employee_name =$user->employee_name;
        $from_date =$leave_form->from_date;
        $to_date =$leave_form->to_date;
        if($status == 'accept'){
        $users=User::permission('leave-approve-by-admi-gm')->select('users.email','users.employee_name')->get();
          
        foreach($users as $use){
             $email=$use->email;
             $admin_name = $use->employee_name;
             Mail::to($email)->send(new LeaveCancelApproveByDepManager($admin_name,$employee_name,$from_date,$to_date,$status,$department_name,$cancel_leave_approve_reason_by_dep_manager));
        }
      }
                     }

        return response()->json(['success' => 'Department Manager Approve Cancel Request For Leave successfully.']);

    }
   public function requestLeaveCancelapproveByAdminManager(Request $request){

        $id = $request->id;
        $status = $request->status;
        $cancel_leave_approve_reason_by_admi_manager =$request->cancel_leave_approve_reason_by_admi_manager;
        
        if($status == 'accept'){
          
      $leave_form =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                  ->where('leave_forms.id','=',$id)
                  ->select('leave_forms.*','leave_types.leave_type_name')
                  ->first();

         $rangArray = [];
        $startDate = strtotime($leave_form->from_date);
        $endDate = strtotime($leave_form->to_date);
        for ($currentDate = $startDate; $currentDate <= $endDate;
                            $currentDate += (86400)) {
          $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
       $user= User::where('id','=',$leave_form->user_id)->select()->first();

           foreach($rangArray as $rang){
              Attendance::where('user_id','=',$leave_form->user_id)->where('date','=',$rang)->delete();
          }

          $leave_form =LeaveForm::where('leave_forms.id','=',$id)->delete();                  

        }else{

          $leave_form=LeaveForm::where('id',$id)->update([
             'cancel_leave_approve_by_admi_manager' => $status,
             'cancel_leave_approve_reason_by_admi_manager'=> $cancel_leave_approve_reason_by_admi_manager
          ]);

         $leave_form =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                  ->where('leave_forms.id','=',$id)
                  ->select('leave_forms.*','leave_types.leave_type_name')
                  ->first();
        
        $user= User::where('id','=',$leave_form->user_id)->select()->first();       
        $employee_name =$user->employee_name;
        $from_date =$leave_form->from_date;
        $to_date =$leave_form->to_date;
        $email =  $user->email;


           Mail::to($email)->send(new LeaveCancelApprove($employee_name,$from_date,$to_date,$status)); 
        } 

        return response()->json(['success' => 'Department Manager Approve Cancel Request For Leave successfully.']);
        
   }

    // public function getPermission(){
        
    //   $users=User::permission('leave-approve-by-dep-manager')->select('users.email')->get()->toArray();
    //     Mail::to($email)->send(new LeaveApprove($employee_name,$from_date,$to_date,$status));
    // }
    public function approveByAdmin(Request $request){

        $id = $request->id;
        $status = $request->status;
        $approve_reason_by_GM = $request->approve_reason_by_GM;
        LeaveForm::where('id',$id)->update([
          'approve_by_GM' => $status,
          'approve_reason_by_GM'=>$approve_reason_by_GM
        ]);

        if($status == 'accept'){

        $leave_form =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
                    ->where('leave_forms.id','=',$id)
                    ->select('leave_forms.*','leave_types.leave_type_name')
                    ->first();
                    
                    
                    
     

                    //start

                     // dd($leave_form->from_date);exit();
      //   $startDate = new DateTime($leave_form->from_date);
      //   $endDate = new DateTime($leave_form->to_date);
      //   $rangArray =[];
      //   while ($startDate <= $endDate) {
      //        if ($startDate->format('w') == 1 || $startDate->format('w') == 2 || $startDate->format('w') == 3 || $startDate->format('w') == 4 || $startDate->format('w') == 5) {
      //              $rangArray[] = $startDate->format('Y-m-d');
      //         }    
      //      $startDate->modify('+1 day');
      //   }

       $user= User::where('id','=',$leave_form->user_id)->select()->first();

        
         $driver_holidays = Holiday::where('status','=',1)->where('driver','=',1)->pluck('date')->toArray();
       $holidays = Holiday::where('status','=',1)->pluck('date')->toArray();


         if($user->check_ns_rs ==  1 ) {

        //to start ns or rs

       if( $leave_form->leave_type_id == 4 ||  $leave_form->leave_type_id == 6 ){

           $rangArray = [];
        $startDate = strtotime($leave_form->from_date);
        $endDate = strtotime($leave_form->to_date);
        for ($currentDate = $startDate; $currentDate <= $endDate;
                                        $currentDate += (86400)) {
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
          }

       }else{
         $startDate = new DateTime($leave_form->from_date);
        $endDate = new DateTime($leave_form->to_date);
        $rangArray = array();
        while ($startDate <= $endDate) {
             if(!empty($user)){
               if($user->employee_type_id == 2 ||  $user->employee_type_id == 6 ){
                  if($startDate->format('w') != 0 && (!in_array($startDate->format('Y-m-d'),$holidays)) ) {
                     $rangArray[] = $startDate->format('Y-m-d');
                  } 
                 $startDate->modify('+1 day');
            }else if($user->employee_type_id == 3 || $user->employee_type_id == 4 || $user->employee_type_id == 7){
               if($startDate->format('w') != 0 && (!in_array($startDate->format('Y-m-d'),$driver_holidays))) {
                  $rangArray[] = $startDate->format('Y-m-d');
               }    
               $startDate->modify('+1 day');               
            }else if($user->employee_type_id == 8){
               if($startDate->format('w') != 0 && (!in_array($startDate->format('Y-m-d'),$holidays))) {
                  $rangArray[] = $startDate->format('Y-m-d');
               }    
               $startDate->modify('+1 day');               
            }else{
                if($startDate->format('w') != 0 && $startDate->format('w') != 6 && (!in_array($startDate->format('Y-m-d'),$holidays))) {
                  $rangArray[] = $startDate->format('Y-m-d');
                }
                 $startDate->modify('+1 day');
            }
           }
         }
       }
         //end
         // to start ns or rs end
      }else{
         $startDate = new DateTime($leave_form->from_date);
         $endDate = new DateTime($leave_form->to_date);
         $rangArray = array();

           while ($startDate <= $endDate) {
            if($startDate->format('w') != 0 && $startDate->format('w') != 6 && (!in_array($startDate->format('Y-m-d'),$holidays))) {
                    $rangArray[] = $startDate->format('Y-m-d');
                }
                 $startDate->modify('+1 day');

            }     
            
                
          
      }

 

       

        

        foreach($rangArray as $rang){

           $att_record = Attendance::where('user_id','=',$leave_form->user_id)
                      ->where('date','=',$rang)
                      ->select()
                      ->first();

           if(empty($att_record)){
            $device = 'Leave';
            $device_serial = 'Leave';
            $device_ip   = $request->ip();
            $user_id     = $leave_form->user_id;
            $profile_id  = $user->profile_id;
            if($user->check_ns_rs == 1){
               $type ="NS Leave Type";
            }else{
               $type ="RS Leave Type";
            }
            $date = $rang;
            $start_time='NULL';
            $type_id = $leave_form->leave_type_id;
            $leave_form_id = $leave_form->id;
            $branch_id = $user->branch_id;
            $status = 1;
            $corrected_start_time    = $user->working_start_time;
            $corrected_end_time      = $user->working_end_time;
            $created_by              = $user->id;

            Attendance::create([
                'device' => $device,
                'device_serial' => $device_serial,
                'device_ip' =>  $device_ip,
                'user_id'=> $user_id,
                'profile_id'=> $profile_id,
                'date'=> $date,
                'start_time'=> $start_time,
                'type' => $type,
                'type_id' => $type_id,
                'leave_form_id' => $leave_form_id,
                'branch_id' => $branch_id,
                'status' => $status,
                'corrected_start_time' => $corrected_start_time,
                'corrected_end_time' => $corrected_end_time,
                'created_by' => $created_by,
            ]);
            }
         }
      }else if($status == 'reject'){
        $leave_form =LeaveForm::join('leave_types','leave_types.id','=','leave_forms.leave_type_id')
        ->where('leave_forms.id','=',$id)
        ->select('leave_forms.*','leave_types.leave_type_name')
        ->first();



        $rangArray = [];
        $startDate = strtotime($leave_form->from_date);
        $endDate = strtotime($leave_form->to_date);
        for ($currentDate = $startDate; $currentDate <= $endDate;
                            $currentDate += (86400)) {
          $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
       $user= User::where('id','=',$leave_form->user_id)->select()->first();

           foreach($rangArray as $rang){
              Attendance::where('user_id','=',$leave_form->user_id)->where('date','=',$rang)->delete();
          }

      }
              $employee_name =$user->employee_name;
              $from_date =$leave_form->from_date;
              $to_date =$leave_form->to_date;
              $approve_reason_by_GM=$leave_form->approve_reason_by_GM;
               $email=$user->email;
               
               if($status == 'accept'){
                   $approve_status = 'accept';
                    //unpaid leave
                    
                    if($leave_form->leave_type_id == 9) {
                        
                      $unpaidusers=User::permission('alert-email-for-unpaid-leave')->select('users.email','users.employee_name')->get();
                        foreach($unpaidusers as $unpaiduser){
                            $email=$unpaiduser->email;
                            $admin_name = $unpaiduser->employee_name;
                            $leave_type_name= $leave_form->leave_type_name;
                            Mail::to($email)->send(new UnpaidLeaveEmail($employee_name,$from_date,$to_date,$leave_type_name,$admin_name));
                        }
                        
                    }
          
               }else{
                   $approve_status = 'reject';  
               }
               
               
        
           
       
         
       if( $user->employee_type_id = 2 ||  $user->employee_type_id = 3  || $user->employee_type_id = 4  || $user->employee_type_id = 6  || $user->employee_type_id = 7){
         //send sms
               $phone = $user->phone;
            $message  = "Url : https://mobile.marubeniyangon.com.mm\n";
            $message .= "Your Request For  Leave";
            $message .= "Employee Name : $employee_name\n";
            $message .= "From Date : $from_date\n";
            $message .= "To Date : $to_date\n";
            $message .= "Reason\n";
            if(!empty($approve_reason_by_GM)){
               $message .= "$approve_reason_by_GM";
            }
            $message .= "is : $approve_status";
            sendSMS($phone,$message);
       }else{
           
            Mail::to($email)->send(new LeaveApprove($employee_name,$from_date,$to_date,$status,$approve_reason_by_GM));  
           
       }
         
         
         
       return response()->json(['success' => 'Admin Approve Status successfully.']);
    }

    public function selectLeaveType(Request $request){
           $leave_type_id = $request->leave_type_id;
           $user_id     = auth()->user()->id;
           $transdate = date('Y-m-d', time());
           $month = date('m', strtotime($transdate));

        if(in_array($month,[01,02,03])){
            $end_year = date("Y");
            $start_year = $end_year -1;
        }else{
            $start_year = date("Y");
            $end_year = $start_year +1;
        }

        // $start = $start_year.'_04';
        // $end   = $end_year.'_03';

          $leave_type = LeaveType::where('id','=',$leave_type_id)->select()->first();
          $users = User::where('id','=',$user_id)->select()->first();


          $totalleavedays = LeaveForm::selectRaw('sum(total_days) as took_total_days')
          ->where('user_id','=',$user_id)
          ->where('leave_type_id','=',$leave_type_id)
          ->where('approve_by_GM','!=','reject')
          ->where('approve_by_dep_manager','!=','reject')
          ->whereBetween('from_date', [ $start_year.'_04'.'_01',  $end_year.'_03'.'_31'])
          ->whereBetween('to_date', [ $start_year.'_04'.'_01',  $end_year.'_03'.'_31'])
          ->groupBy('leave_forms.user_id','leave_forms.leave_type_id')
          ->first();

            if($leave_type_id == 1){
                //start
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);
                if($month >  3){
                    if(!empty($totalleavedays)){
                          $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                    }else{
                         $remaining_days =  $leave_type->leave_day;
                    }
                }else{
                   if(!empty($totalleavedays)){
                    $remaining_days  =  2 - $totalleavedays->took_total_days;
                   }else{
                     $remaining_days = 2;  
                   }
                }
             } else if ($leave_type_id == 2){
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);
                if($month >  12 ){
                    if( $users->check_ns_rs == 0 ) {
                        $rtransdate = date('Y-m-d', time());
                        $rmonth = date('m', strtotime($rtransdate));
                        if(in_array($rmonth,[01,02,03])){
                            $nsend_year = date("Y");
                            $nsstart_year = $nsend_year - 2;
                        }else{
                            $nsstart_year = date("Y");
                            $nsend_year = $nsstart_year + 2;
                        }
                        $total2leavedays = LeaveForm::selectRaw('sum(total_days) as took_total_days')
                        ->where('user_id','=',$user_id)
                        ->where('leave_type_id','=',$leave_type_id)
                        ->where('approve_by_GM','!=','reject')
                        ->where('approve_by_dep_manager','!=','reject')
                        ->whereBetween('from_date', [ $nsstart_year.'_04'.'_01',  $nsend_year.'_03'.'_31'])
                        ->whereBetween('to_date', [ $nsstart_year.'_04'.'_01',  $nsend_year.'_03'.'_31'])
                        ->groupBy('leave_forms.user_id','leave_forms.leave_type_id')
                        ->first();
                        if(!empty($totalleavedays)){
                           $remaining_days  =  ( $leave_type->leave_day * 2 ) - $total2leavedays->took_total_days;
                        }else{
                            $remaining_days  =   $leave_type->leave_day * 2 ;
                        }
                    }else{
                          if(!empty($totalleavedays)){
                            $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                          }else{
                             $remaining_days =  $leave_type->leave_day;  
                          }
                    }
                }else{
                    $remaining_days  = 0;
                }

             }else if( $leave_type_id == 3 ){
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);

                
                if($month >  6){
                    if(!empty($totalleavedays)){
                      $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                    }else{
                      $remaining_days =  $leave_type->leave_day;  
                    }
                 }else{
                    $remaining_days  = 0;
                 }
             }else if( $leave_type_id == 4 ){
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);
                if($month >  6){
                   if(!empty($totalleavedays)){
                     $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                   }else{
                     $remaining_days =  $leave_type->leave_day;   
                   }
                 }else{
                    $remaining_days  = 0;
                 }
             }else if( $leave_type_id == 5 ){
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);
                if(!empty($totalleavedays)){
                     $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                   }else{
                     $remaining_days =  $leave_type->leave_day;   
                   }
             }else if( $leave_type_id == 6 ){
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);
                if(!empty($totalleavedays)){
                     $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                   }else{
                     $remaining_days =  $leave_type->leave_day;   
                   }
             }else if( $leave_type_id == 7 ){
                $diff = abs(strtotime(date('Y-m-d'))-strtotime($users->entranced_date));
                $month = $diff/(60*60*24*30);
                if(!empty($totalleavedays)){
                     $remaining_days =  $leave_type->leave_day  - $totalleavedays->took_total_days;
                   }else{
                     $remaining_days =  $leave_type->leave_day;   
                   }
             }else if($leave_type_id == 8 ){
                $remaining_days =  7;
             }else if($leave_type_id == 9 ){
                $remaining_days =  365;  
             }

                return response()->json(['remaining_days' => $remaining_days]);
    }
    public function selectRsLeaveType(Request $request){

       $leave_type_id = $request->leave_type_id;

           $transdate = date('Y-m-d', time());
           $month = date('m', strtotime($transdate));

             $user_id   = auth()->user()->id;

        if(in_array($month,[01,02,03])){
            $end_year = date("Y");
            $start_year = $end_year -1;
        }else{
            $start_year = date("Y");
            $end_year = $start_year +1;
        }

$totalleavedays = LeaveForm::selectRaw('sum(total_days) as took_total_days')
             ->where('user_id','=',$user_id)
             ->where('leave_type_id','=',$leave_type_id)
             ->where('approve_by_GM','!=','reject')
             ->where('approve_by_dep_manager','!=','reject')
             ->whereBetween('from_date', [ $start_year.'_04'.'_01',  $end_year.'_03'.'_31'])
             ->whereBetween('to_date', [ $start_year.'_04'.'_01',  $end_year.'_03'.'_31'])
             ->groupBy('leave_forms.user_id','leave_forms.leave_type_id')
             ->first();

      
          $rsearnedleave=RsRefreshLeave::where('user_id','=',$user_id)
                     ->whereBetween('start_date',[$start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                     ->whereBetween('end_date',[$start_year.'_04'.'_01',$end_year.'_03'.'_31'])
                     ->selectRaw('sum(earned_leaves ) as took_earned_leaves')
                     ->first();

         
                      if(!empty($rsearnedleave)){
                        $rsearned=$rsearnedleave->took_earned_leaves;
                      }else{
                        $rsearned=0;
                      }





          if($leave_type_id ==  1){
           $leave_type =RsLeaveData::where('year','=',$start_year)
                        ->where('user_id','=',$user_id)
                        ->select('earned_leaves')
                        ->first();
                      
                       
                        if(!empty($leave_type)){
                           if(!empty($totalleavedays)){
                             $remaining_days =  $leave_type->earned_leaves  - ( $totalleavedays->took_total_days + $rsearned );
                           }else{
                              $remaining_days =  $leave_type->earned_leaves -  $rsearned;
                          }
                        }else{
                             $remaining_days = 0;
                        }

          }else{

            $leave_type =RsLeaveData::where('year','=',$start_year)
                        ->where('user_id','=',$user_id)
                        ->select('refresh_leaves')
                        ->first();
                         if(!empty($leave_type)){
                        if(!empty($totalleavedays)){
                           $remaining_days =  $leave_type->refresh_leaves  - $totalleavedays->took_total_days;
                        }else{
                           $remaining_days =  $leave_type->refresh_leaves;
                        }
                     }else{
                          $remaining_days = 0;
                     }

          }

            return response()->json(['remaining_days' => $remaining_days]);

                   
                
    }

    public function  getRemainingDays(Request $request){

        $transdate = date('Y-m-d', time());
        $month = date('m', strtotime($transdate));

        if(in_array($month,[01,02,03])){
            $end_year = date("Y");
            $start_year = $end_year -1;
        }else{
            $start_year = date("Y");
            $end_year = $start_year +1;
        }
            //search
            $start =  $request->from_search;
            $end =  $request->to_search;
            $search_department = $request->search_department;
            $search_employee_name = $request->search_employee_name;
            
           // $n=NUMBER_PER_PAGE;
       
        $users =\DB::table("users")->select("users.*",'users.check_ns_rs',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
               ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
               
               // ->where(function($query)use($search_department){
               //     if($search_department != null){
               //       $query->where('departments.id','=', $search_department);
               //     }
               //  })

               ->where(function($query)use($search_department){
                        if($search_department != null)
                        $query->whereRaw("find_in_set('".$search_department."',departments.id)");
               })
               ->where(function($query)use($search_employee_name){
                   if($search_employee_name != null){
                     $query->where('users.id','=', $search_employee_name);
                   }
                })
              // ->where('users.check_ns_rs','=',1)
               //->select('users.id','users.employee_name','departments.short_name as department_name','users.check_ns_rs')
               ->groupBy('users.id')
               ->orderBy('users.employee_name')
               ->get();

              
       

        foreach($users as $user){

           if($user->check_ns_rs ==  1){  // for ns

          
        $casualleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',1)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($casualleavedays)){
               $user->casualleavedays =  $casualleavedays->took_total_days;
           }else{
                $user->casualleavedays = 0;
           }

        $earnedleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',2)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')       
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($earnedleavedays)){
               $user->earnedleavedays =  $earnedleavedays->took_total_days;
           }else{
                $user->earnedleavedays = 0;
           }

         $medicalleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',3)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($medicalleavedays)){
               $user->medicalleavedays =  $medicalleavedays->took_total_days;
           }else{
              $user->medicalleavedays = 0;
           }

         $maternityleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',4)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($maternityleavedays)){
               $user->maternityleavedays =  $maternityleavedays->took_total_days;
           }else{
              $user->maternityleavedays = 0;
           }
        
        $paternityleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',5)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($paternityleavedays)){
               $user->paternityleavedays =  $paternityleavedays->took_total_days;
           }else{
              $user->paternityleavedays = 0;
           }
        
        $longtermsickleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',6)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($longtermsickleavedays)){
               $user->longtermsickleavedays =  $longtermsickleavedays->took_total_days;
           }else{
              $user->longtermsickleavedays = 0;
           }
        
        $congratulatyleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',7)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($congratulatyleavedays)){
               $user->congratulatyleavedays =  $congratulatyleavedays->took_total_days;
           }else{
              $user->congratulatyleavedays = 0;
           }

        $condolenceleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',8)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($condolenceleavedays)){
               $user->condolenceleavedays =  $condolenceleavedays->took_total_days;
           }else{
              $user->condolenceleavedays = 0;
           }

        $unpaidleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',9)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($unpaidleavedays)){
               $user->unpaidleavedays =  $unpaidleavedays->took_total_days;
           }else{
              $user->unpaidleavedays = 0;
           }

              $user->refreshleavedays = 0;

            }else{   // for rs

                 $user->casualleavedays = 0;
               //   $user->earnedleavedays = 0;
                 $user->medicalleavedays = 0;
                 $user->maternityleavedays = 0;
                 $user->paternityleavedays = 0;
                 $user->longtermsickleavedays = 0;
                 $user->congratulatyleavedays = 0;
                 $user->condolenceleavedays = 0;
                 $user->unpaidleavedays = 0;
               //   rs earned leaves and  refresh leave

                    $earnedleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
                             ->where('leave_forms.user_id','=',$user->id)
                             ->where('leave_forms.leave_type_id','=',1)
                             ->where('leave_forms.approve_by_GM','=','accept')
                             ->where('leave_forms.approve_by_dep_manager','=','accept')
                             ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                        ->groupBy('leave_forms.leave_type_id')
                        ->first();

                      

                        //employment  earned
                     $empearneddays = RsRefreshLeave::selectRaw('sum(rs_refresh_leaves.earned_leaves) as took_emp_earned_days')
                           ->where('rs_refresh_leaves.user_id','=',$user->id)
                           ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('rs_refresh_leaves.start_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('rs_refresh_leaves.end_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('rs_refresh_leaves.start_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('rs_refresh_leaves.end_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                           ->groupBy('rs_refresh_leaves.user_id')
                           ->first();

                        if(!empty($empearneddays)){
                                 $empearnedtotal = $empearneddays->took_emp_earned_days;
                         }else{
                                 $empearnedtotal = 0;
                         }

                        if(!empty($earnedleavedays)){                           
                            $user->earnedleavedays  =  $earnedleavedays->took_total_days +  $empearnedtotal;
                       }else{
                            $user->earnedleavedays  =  0 + $empearnedtotal;
                             
                        }


                        //employment earned
                     

                       $earn =  RsLeaveData::where('user_id','=',$user->id)
                                    ->where('year','=',$start_year)
                                    ->select('earned_leaves')
                                    ->first();

                                    if(!empty($earn)){
                                       $user->earnedtotal = $earn->earned_leaves;
                                    }else{
                                        $user->earnedtotal = 0;
                                    }

                        $refresh =  RsLeaveData::where('user_id','=',$user->id)
                                    ->where('year','=',$start_year)
                                    ->select('refresh_leaves')
                                    ->first();

                                      if(!empty($refresh)){
                                       $user->refreshtotal = $refresh->refresh_leaves;
                                    }else{
                                        $user->refreshtotal = 0;
                                    }

            // $refreshleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
            //                  ->where('leave_forms.user_id','=',$user->id)
            //                  ->where('leave_forms.leave_type_id','=',2)
            //                  ->where('leave_forms.approve_by_GM','=','accept')
            //                  ->where('leave_forms.approve_by_dep_manager','=','accept')
            //                  ->where(function($query)use($start,$end,$start_year,$end_year){
            //                  if($start != null && $start != null ){
            //                     $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
            //                      ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);
            //                   }else{
            //                   $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
            //                         ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
            //                   }
            //                })
            //             ->groupBy('leave_forms.leave_type_id')
            //             ->first();

                        //start

            $refreshleavedays = RsRefreshLeave::selectRaw('sum(rs_refresh_leaves.refresh_leaves) as took_total_days')
                           ->where('rs_refresh_leaves.user_id','=',$user->id)
                           ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('rs_refresh_leaves.start_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('rs_refresh_leaves.end_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('rs_refresh_leaves.start_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('rs_refresh_leaves.end_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                           ->groupBy('rs_refresh_leaves.user_id')
                           ->first();


                        

                        if(!empty($refreshleavedays)){
                             $user->refreshleavedays =  $refreshleavedays->took_total_days;
                        }else{
                             $user->refreshleavedays = 0;
                        }

                        //   rs earned leaves and  refresh leave
                    


            }
        }

        $casualleave      = LeaveType::where('id','=',1)->select()->first();
        $earnedleave      = LeaveType::where('id','=',2)->select()->first();
        $medicalleave     = LeaveType::where('id','=',3)->select()->first();
        $maternityleave   = LeaveType::where('id','=',4)->select()->first();
        $paternityleave   = LeaveType::where('id','=',5)->select()->first();
        $longtermsickleave= LeaveType::where('id','=',6)->select()->first();
        $congratulatyleave= LeaveType::where('id','=',7)->select()->first();
        $condolenceleave  = LeaveType::where('id','=',8)->select()->first();
        $departments      = Department::where('status','=',1)->select()->get();

        $all_users   = User::select('employee_name','id')->get();

       

         return view('leavemanagement.remaining-days',compact('users','casualleave','earnedleave',
         'medicalleave','maternityleave','paternityleave','longtermsickleave','congratulatyleave','condolenceleave',
         'departments','all_users'));

    }

     public function getRsRemainingDays(Request $request){

        $transdate = date('Y-m-d', time());
        $month = date('m', strtotime($transdate));

        if(in_array($month,[01,02,03])){
            $end_year = date("Y");
            $start_year = $end_year -1;
        }else{
            $start_year = date("Y");
            $end_year = $start_year +1;
        }

         $start =  $request->from_search;
         $end =  $request->to_search;

      $users = User::join('departments','departments.id','=','users.department_id')
              
               ->where('users.check_ns_rs','=',0)
               ->select('users.id','users.employee_name','departments.name as department_name')
               ->orderBy('users.employee_name')
               ->get();

               // dd($start_year);exit();


         foreach($users as $user){

            $earnedleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
                             ->where('leave_forms.user_id','=',$user->id)
                             ->where('leave_forms.leave_type_id','=',1)
                             ->where('leave_forms.approve_by_GM','=','accept')
                             ->where('leave_forms.approve_by_dep_manager','=','accept')
                             ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                        ->groupBy('leave_forms.leave_type_id')
                        ->first();

                       if(!empty($earnedleavedays)){
                              $user->earnedleavedays =  $earnedleavedays->took_total_days;
                       }else{
                             $user->earnedleavedays = 0;
                             
                        }
                     

                       $earn =  RsLeaveData::where('user_id','=',$user->id)
                                    ->where('year','=',$start_year)
                                    ->select('earned_leaves')
                                    ->first();

                                    if(!empty($earn)){
                                       $user->earnedtotal = $earn->earned_leaves;
                                    }else{
                                        $user->earnedtotal = 0;
                                    }

                        $refresh =  RsLeaveData::where('user_id','=',$user->id)
                                    ->where('year','=',$start_year)
                                    ->select('refresh_leaves')
                                    ->first();

                                      if(!empty($refresh)){
                                       $user->refreshtotal = $refresh->refresh_leaves;
                                    }else{
                                        $user->refreshtotal = 0;
                                    }

            $refreshleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
                             ->where('leave_forms.user_id','=',$user->id)
                             ->where('leave_forms.leave_type_id','=',1)
                             ->where('leave_forms.approve_by_GM','=','accept')
                             ->where('leave_forms.approve_by_dep_manager','=','accept')
                             ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                        ->groupBy('leave_forms.leave_type_id')
                        ->first();

                        if(!empty($refreshleavedays)){
                             $user->refreshleavedays =  $refreshleavedays->took_total_days;
                        }else{
                             $user->refreshleavedays = 0;
                        }
                    
            }

              $departments      = Department::where('status','=',1)->select()->get();

            $all_users   = User::where('check_ns_rs','=',1)->select('employee_name','id')->get();

        return view('leavemanagement.rs-remaining-days',compact('users','all_users','departments'));

     }

    //for excel
      public function  getExcelData(Request $request){

        $transdate = date('Y-m-d', time());
        $month = date('m', strtotime($transdate));

        if(in_array($month,[01,02,03])){
            $end_year = date("Y");
            $start_year = $end_year -1;
        }else{
            $start_year = date("Y");
            $end_year = $start_year +1;
        }
            //search
            $start =  $request->from_excel;
            $end =  $request->to_excel;
            $employee_type = $request->search_employee_type;
            $search_employee_name = $request->search_employee_name;


        // $users = User::join('departments','departments.id','=','users.department_id')
        //       ->where('check_ns_rs','=',$employee_type)
        //       ->where(function($query)use($search_employee_name){
        //           if($search_employee_name != null){
        //              $query->where('users.id','=', $search_employee_name);
        //           }
        //         })
        //       ->select('users.id','users.employee_name','departments.name as department_name')
        //       ->orderBy('users.employee_name')
        //       ->get();
        
        $users =\DB::table("users")->select("users.*",'users.employee_name',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
               ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,users.department_id)"),">",\DB::raw("'0'"))
               ->where('check_ns_rs','=',$employee_type)
               ->where(function($query)use($search_employee_name){
                   if($search_employee_name != null){
                     $query->where('users.id','=', $search_employee_name);
                   }
                })
              // ->select('users.id','users.employee_name','departments.name as department_name')
               ->orderBy('users.employee_name')
               ->groupBy('users.id')
               ->get();
        
        

           if(count($users) > 0){   
      //   if(!empty($users))
         foreach($users as $user){

           if($user->check_ns_rs ==  1){  // for ns

          
        $casualleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',1)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($casualleavedays)){
               $user->casualleavedays =  $casualleavedays->took_total_days;
           }else{
                $user->casualleavedays = 0;
           }

        $earnedleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',2)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')       
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($earnedleavedays)){
               $user->earnedleavedays =  $earnedleavedays->took_total_days;
           }else{
                $user->earnedleavedays = 0;
           }

         $medicalleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',3)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($medicalleavedays)){
               $user->medicalleavedays =  $medicalleavedays->took_total_days;
           }else{
              $user->medicalleavedays = 0;
           }

         $maternityleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',4)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($maternityleavedays)){
               $user->maternityleavedays =  $maternityleavedays->took_total_days;
           }else{
              $user->maternityleavedays = 0;
           }
        
        $paternityleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',5)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($paternityleavedays)){
               $user->paternityleavedays =  $paternityleavedays->took_total_days;
           }else{
              $user->paternityleavedays = 0;
           }
        
        $longtermsickleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',6)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($longtermsickleavedays)){
               $user->longtermsickleavedays =  $longtermsickleavedays->took_total_days;
           }else{
              $user->longtermsickleavedays = 0;
           }
        
        $congratulatyleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',7)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($congratulatyleavedays)){
               $user->congratulatyleavedays =  $congratulatyleavedays->took_total_days;
           }else{
              $user->congratulatyleavedays = 0;
           }

        $condolenceleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',8)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($condolenceleavedays)){
               $user->condolenceleavedays =  $condolenceleavedays->took_total_days;
           }else{
              $user->condolenceleavedays = 0;
           }

        $unpaidleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
        ->where('leave_forms.user_id','=',$user->id)
        ->where('leave_forms.leave_type_id','=',9)
        ->where('leave_forms.approve_by_GM','=','accept')
        ->where('leave_forms.approve_by_dep_manager','=','accept')
        ->where(function($query)use($start,$end,$start_year,$end_year){
              if($start != null && $start != null ){
                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                       ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);

              }else{
                $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                     ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
              }
         })
        ->groupBy('leave_forms.leave_type_id')
        ->first();

           if(!empty($unpaidleavedays)){
               $user->unpaidleavedays =  $unpaidleavedays->took_total_days;
           }else{
              $user->unpaidleavedays = 0;
           }

              $user->refreshleavedays = 0;

            }else{   // for rs

                 $user->casualleavedays = 0;
               //   $user->earnedleavedays = 0;
                 $user->medicalleavedays = 0;
                 $user->maternityleavedays = 0;
                 $user->paternityleavedays = 0;
                 $user->longtermsickleavedays = 0;
                 $user->congratulatyleavedays = 0;
                 $user->condolenceleavedays = 0;
                 $user->unpaidleavedays = 0;
               //   rs earned leaves and  refresh leave

                    $earnedleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
                             ->where('leave_forms.user_id','=',$user->id)
                             ->where('leave_forms.leave_type_id','=',1)
                             ->where('leave_forms.approve_by_GM','=','accept')
                             ->where('leave_forms.approve_by_dep_manager','=','accept')
                             ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                        ->groupBy('leave_forms.leave_type_id')
                        ->first();

                       if(!empty($earnedleavedays)){
                              $user->earnedleavedays =  $earnedleavedays->took_total_days;
                       }else{
                             $user->earnedleavedays = 0;
                             
                        }
                     

                       $earn =  RsLeaveData::where('user_id','=',$user->id)
                                    ->where('year','=',$start_year)
                                    ->select('earned_leaves')
                                    ->first();

                                    if(!empty($earn)){
                                       $user->earnedtotal = $earn->earned_leaves;
                                    }else{
                                        $user->earnedtotal = 0;
                                    }

                        $refresh =  RsLeaveData::where('user_id','=',$user->id)
                                    ->where('year','=',$start_year)
                                    ->select('refresh_leaves')
                                    ->first();

                                      if(!empty($refresh)){
                                       $user->refreshtotal = $refresh->refresh_leaves;
                                    }else{
                                        $user->refreshtotal = 0;
                                    }

            $refreshleavedays = LeaveForm::selectRaw('sum(leave_forms.total_days) as took_total_days')
                             ->where('leave_forms.user_id','=',$user->id)
                             ->where('leave_forms.leave_type_id','=',2)
                             ->where('leave_forms.approve_by_GM','=','accept')
                             ->where('leave_forms.approve_by_dep_manager','=','accept')
                             ->where(function($query)use($start,$end,$start_year,$end_year){
                             if($start != null && $start != null ){
                                $query->whereBetween('leave_forms.from_date',  [ $start.'-04-01',  $end.'-03-31'])
                                 ->whereBetween('leave_forms.to_date',  [ $start.'-04-01',  $end.'-03-31']);
                              }else{
                              $query->whereBetween('leave_forms.from_date', [ $start_year.'-04'.'-01',  $end_year.'-03-31'])
                                    ->whereBetween('leave_forms.to_date', [$start_year.'-04'.'-01',  $end_year.'-03-31']);
                              }
                           })
                        ->groupBy('leave_forms.leave_type_id')
                        ->first();

                        if(!empty($refreshleavedays)){
                             $user->refreshleavedays =  $refreshleavedays->took_total_days;
                        }else{
                             $user->refreshleavedays = 0;
                        }

                        //   rs earned leaves and  refresh leave
                    


            }
      }
   }

        $casualleave      = LeaveType::where('id','=',1)->select()->first();
        $earnedleave      = LeaveType::where('id','=',2)->select()->first();
        $medicalleave     = LeaveType::where('id','=',3)->select()->first();
        $maternityleave   = LeaveType::where('id','=',4)->select()->first();
        $paternityleave   = LeaveType::where('id','=',5)->select()->first();
        $longtermsickleave= LeaveType::where('id','=',6)->select()->first();
        $congratulatyleave= LeaveType::where('id','=',7)->select()->first();
        $condolenceleave  = LeaveType::where('id','=',8)->select()->first();
        $departments      = Department::where('status','=',1)->select()->get();
     
        
         

           $filename = "leave_".Carbon::now()->format('d-m-Y');

        return Excel::download(new LeaveExport($users,$casualleave,$earnedleave,$medicalleave,$maternityleave,$paternityleave,
       $longtermsickleave,$congratulatyleave,$condolenceleave,$departments,$employee_type),$filename.'.csv');

    }
    public function requestLeaveCancel(Request $request){
        
                     $id=$request->id;
                     $leave_cancel_reason = $request->leave_cancel_reason; 
                     
                     $leaveforms=LeaveForm::where('id',$id)->select()->first(); 
                     
                     if($leaveforms->approve_by_dep_manager  == 'pending' || $leaveforms->approve_by_dep_manager  == 'reject'){
                         
                          $leaveforms=LeaveForm::where('id',$id)->delete();
                         
                     }else{
                      
                     $leave_form=LeaveForm::where('id',$id)->update([
                           'request_leave_cancel'=>1,
                           'leave_cancel_reason' => $leave_cancel_reason,
                           'cancel_leave_approve_by_dep_manager'=> 'pending',
                     ]); 


                $leave_form=LeaveForm::where('id','=',$id)->select()->first();
                
                
                //start
                $terms = explode(',',auth()->user()->department_id);
                
        
         $users=User::permission('leave-approve-by-dep-manager')
                      ->where(function($query) use($terms) {
                        foreach($terms as $term) {
                            $query->whereRaw("find_in_set('".$term."',users.department_id)");
                             //$query->where('users.department_id','=',$term);
                        };
                        
                    })->select('users.department_id','users.email','users.employee_name')->get();
                      
          //$users=User::permission('leave-approve-by-dep-manager')->whereRaw("find_in_set('".auth()->user()->department_id."',users.department_id)")->groupBy('users.id')->select('users.email','users.employee_name')->get();
           
          $employee_name =auth()->user()->employee_name; 

           foreach($users as $use){                  
             $email=  $use->email;
             $dep_person_name = $use->employee_name;
             $from_date=$leave_form->from_date;
             $to_date=$leave_form->to_date;
             Mail::to($email)->send(new LeaveCancelApproveForDepManager($dep_person_name,$employee_name,$from_date,$to_date,$leave_cancel_reason));
         
            }
         }
          
           return back()->with('success','You Have Successfully Applied Cancel Leaves');
       

    }

    

   

}
