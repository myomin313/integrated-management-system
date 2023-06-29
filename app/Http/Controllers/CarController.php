<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\CarManagement\Car;
use App\Models\CarManagement\CarDriver;
use App\Models\CarManagement\CarMainUser;
use App\Models\MasterManagement\Department;
use App\Models\CarManagement\CarLicense;
use App\Mail\CarLicenseExpire;
use App\Models\User;
use Carbon\Carbon;
use Redirect;
use Session;
use Auth;
use DB;


class CarController extends Controller
{
      public function index(Request $request)
    {
         $driver_name=$request->search_driver_name;
         $depart = $request->search_department;
         $car_number = $request->search_car_number;
         
         
         if(Auth::user()->can('car-read-all'))
         
          $cars = Car::select('cars.*','employee_types.type as employee_type','users4.id as driver_id','users4.employee_name as driver_name','users3.employee_name as main_user_name',
         'users.employee_name as created_user','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->join('users', 'users.id','=','cars.created_by')
         ->join('users as users2', 'users2.id','=','cars.updated_by')
         ->join('users as users3', 'users3.id','=','cars.main_user')
         ->join('users as users4', 'users4.id','=','cars.user_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
        // ->join('departments', 'departments.id','=','cars.dept_id')
         ->join('employee_types', 'employee_types.id','=','users4.employee_type_id')
        //  start for search
         ->where(function ($query) use ($car_number) {
                   $query->where('cars.car_number','like','%'.$car_number.'%');
         })
         ->where(function($query)use($driver_name){
                 if($driver_name != null)
                   $query->where('cars.user_id','=',$driver_name);
         })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->groupBy('cars.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         
         else if(Auth::user()->can('car-read-group'))
         
           $cars = Car::select('cars.*','employee_types.type as employee_type','users4.id as driver_id','users4.employee_name as driver_name','users3.employee_name as main_user_name',
         'users.employee_name as created_user','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->join('users', 'users.id','=','cars.created_by')
         ->join('users as users2', 'users2.id','=','cars.updated_by')
         ->join('users as users3', 'users3.id','=','cars.main_user')
         ->join('users as users4', 'users4.id','=','cars.user_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
        // ->join('departments', 'departments.id','=','cars.dept_id')
         ->join('employee_types', 'employee_types.id','=','users4.employee_type_id')
        //  start for search
         ->where(function ($query) use ($car_number) {
                   $query->where('cars.car_number','like','%'.$car_number.'%');
         })
         ->where(function($query)use($driver_name){
                 if($driver_name != null)
                   $query->where('cars.user_id','=',$driver_name);
         })
        //  ->where(function($query)use($depart){
        //          if($depart != null)
        //           $query->where('cars.dept_id', '=', $depart);
        //  })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
           })
         ->where('cars.dept_id','=',auth()->user()->department_id)
         ->groupBy('cars.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else
         
         $cars = Car::select('cars.*','employee_types.type as employee_type','users4.id as driver_id','users4.employee_name as driver_name','users3.employee_name as main_user_name',
         'users.employee_name as created_user','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->join('users', 'users.id','=','cars.created_by')
         ->join('users as users2', 'users2.id','=','cars.updated_by')
         ->join('users as users3', 'users3.id','=','cars.main_user')
         ->join('users as users4', 'users4.id','=','cars.user_id')
         // ->join('departments', 'departments.id','=','cars.dept_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->join('employee_types', 'employee_types.id','=','users4.employee_type_id')
        //  start for search
         ->where(function ($query) use ($car_number) {
                   $query->where('cars.car_number','like','%'.$car_number.'%');
         })
         ->where(function($query)use($driver_name){
                 if($driver_name != null)
                   $query->where('cars.user_id','=',$driver_name);
         })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
           })
         ->where('cars.user_id','=',auth()->user()->id)
         ->orWhere('cars.user_id','=',auth()->user()->id)
         ->groupBy('cars.id')
         ->orderBy('created_at', 'DESC')
         ->get();

        
       
         
         $departments= Department::where('status','=',1)->get();
         $users= User::whereIn('employee_type_id',[2,3,4,6,7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         return view('carmanagement.cars',compact('cars','departments','users','main_users','depart','car_number','driver_name'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'car_type' => 'required',
            'car_number' => 'required',
            'driver' => 'required',
            'model_year' => 'required',
            'chasis_no' => 'required',
            'parking' => 'required',
            'main_user' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        
         $department_id = implode(',',$request->department);
         
        Car::create([
            'dept_id' => $department_id,
            'car_type' => $request->car_type,
            'car_number' => $request->car_number,
            'user_id' => $request->driver,
            'model_year' => $request->model_year,
            'chasis_no' => $request->chasis_no,
            'parking' => $request->parking,
            'main_user' => $request->main_user,
            'tire_size' => $request->tire_size,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car created successfully.']);
        
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'department' => 'required',
            'car_type' => 'required',
            'car_number' => 'required',
            'driver' => 'required',
            'model_year' => 'required',
            'chasis_no' => 'required',
            'parking' => 'required',
            'main_user' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        Car::where('id',$request->id)->update([
            'dept_id' => $request->department,
            'car_type' => $request->car_type,
            'car_number' => $request->car_number,
            'user_id' => $request->driver,
            'model_year' => $request->model_year,
            'tire_size' => $request->tire_size,
            'chasis_no' => $request->chasis_no,
            'parking' => $request->parking,
            'main_user' => $request->main_user,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Update successfully.']);
        
    }
    public function delete(Request $request){
        Car::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function updateDriver(Request $request){ 
         
          Car::where('id',$request->id_update_driver)->update([
            'user_id' => $request->driver_name_update,
            'updated_by' => auth()->user()->id,
          ]);
        CarDriver::create([
            'car_id' => $request->id_update_driver,
            'driver_id' => $request->driver_name_update,
            'updated_by' => auth()->user()->id,
            'created_by' => auth()->user()->id,
          ]);
           
           return Redirect::back()->with('msg','Car Driver Update successfully.');
            
    } 
    public function updateMainUser(Request $request){ 
         
          Car::where('id',$request->id_update_main_user)->update([
            'main_user' => $request->main_user_name_update,
            'updated_by' => auth()->user()->id,
          ]);
        CarMainUser::create([
            'car_id' => $request->id_update_main_user,
            'main_user_id' => $request->main_user_name_update,
            'updated_by' => auth()->user()->id,
            'created_by' => auth()->user()->id,
          ]);
           return Redirect::back()->with('msg','Car Main User Update successfully.');
            
    } 
    public function licenseRemind(Request $request){

     // $ddate = date('Y-m-d');
     
      $ddate = Carbon::now()->format('Y-m-d');
      
      $expire_date = date('Y-m-d', strtotime($ddate. ' + 1 months'));
       // dd($newDate);exit(); 
       $users  = CarLicense::select('users.name','cars.car_number','car_licenses.due_date','users.email')
              ->join('cars','cars.id','=','car_licenses.car_id')
              ->join('users','users.id','=','cars.main_user')
              ->where('car_licenses.due_date','=',$expire_date)
              ->groupBy('car_licenses.car_id')
              ->orderBy('car_licenses.due_date','DESC')
              ->get();
        if($users){
            foreach($users as $user){
               $email = $user->email;
               $name = $user->name;
               $car_number = $user->car_number;
               $expire_date = $expire_date;

                Mail::to($email)->send(new CarLicenseExpire($name,$car_number,$expire_date));
            }
        }
       
    }
    
}
