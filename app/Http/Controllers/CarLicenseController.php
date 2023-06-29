<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarManagement\CarLicense;
use App\Models\CarManagement\Car;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Redirect;
use Response;
use Session;
use Carbon\Carbon;
use App\Exports\CarLicenseExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;

class CarLicenseController extends Controller
{
       public function index(Request $request)
    {
         $car_number = $request->car_number;

         $depart = $request->department;

         $drive = $request->driver;
            //echo $driver;exit();
            
            if(Auth::user()->can('car-read-all'))
            
      $car_licenses = CarLicense::join('cars', 'cars.id','=','car_licenses.car_id')
         ->join('users', 'users.id','=','car_licenses.created_by')
         ->join('users as users2', 'users2.id','=','car_licenses.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($drive){
             if($drive != null){
            $query->where('users3.id','=',$drive);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_licenses.car_id','=', $car_number);
            }
         })
          ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->select('car_licenses.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_licenses.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
        else if(Auth::user()->can('car-read-group'))
        
         $car_licenses = CarLicense::join('cars', 'cars.id','=','car_licenses.car_id')
         ->join('users', 'users.id','=','car_licenses.created_by')
         ->join('users as users2', 'users2.id','=','car_licenses.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($drive){
             if($drive != null){
            $query->where('users3.id','=',$drive);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_licenses.car_id','=', $car_number);
            }
         })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.dept_id','=',auth()->user()->department_id)
         ->select('car_licenses.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_licenses.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else
         
         $car_licenses = CarLicense::join('cars', 'cars.id','=','car_licenses.car_id')
         ->join('users', 'users.id','=','car_licenses.created_by')
         ->join('users as users2', 'users2.id','=','car_licenses.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($drive){
             if($drive != null){
            $query->where('users3.id','=',$drive);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_licenses.car_id','=', $car_number);
            }
         })
          ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.user_id','=',auth()->user()->id)
         ->orWhere('cars.user_id','=',auth()->user()->id)
         ->select('car_licenses.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_licenses.id')
         ->orderBy('created_at', 'DESC')
         ->get();

          $departments= Department::where('status','=',1)->get();
         $drivers= User::whereIn('employee_type_id',[2,3,4,6,7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         $cars=Car::all();
         return view('carmanagement.car-licenses',compact('car_licenses','cars','departments','drivers','main_users','car_number','depart','drive'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $start = explode('/',  $request->start_date);
        $start_date = $start[2].'-'.$start[1].'-'.$start[0];

          $end = explode('/',  $request->end_date);
        $end_date = $end[2].'-'.$end[1].'-'.$end[0];

        CarLicense::create([
            'car_id' => $request->car_number,
            'start_date' => $start_date,
            'due_date' => $end_date,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car License created successfully.']);

    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $start = explode('/',  $request->start_date);
        $start_date = $start[2].'-'.$start[1].'-'.$start[0];

          $end = explode('/',  $request->end_date);
        $end_date = $end[2].'-'.$end[1].'-'.$end[0];

        CarLicense::where('id',$request->id)->update([
            'car_id' => $request->car_number,
            'start_date' => $start_date,
            'due_date' => $end_date ,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car License Update successfully.']);

    }
    public function delete(Request $request){
        CarLicense::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function exportlicenses(Request $request){


          
         $car_licenses=CarLicense::join('cars','cars.id','=','car_licenses.car_id')
               //->join('departments','departments.id','=','cars.dept_id')
               ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
               ->join('users as main_users','main_users.id','=','cars.main_user')
               ->join('users as drivers','drivers.id','=','cars.user_id')
               ->leftjoin('driver_licenses','driver_licenses.user_id','=','cars.user_id')
               ->selectRaw('max(driver_licenses.due_date) as driver_license_expire_date,departments.short_name as department_name,
               main_users.employee_name as main_user_name,cars.car_type,
               cars.car_number,drivers.employee_type_id,drivers.employee_name as driver_name,cars.model_year,cars.parking,
               cars.id,max(car_licenses.due_date) as car_license_expire_date,GROUP_CONCAT(departments.short_name) as docname')
               ->groupBy('cars.id')
               ->get();
               
                //dd($car_licenses);exit();

        $filename = "license".Carbon::now()->format('d-m-Y');
        return Excel::download(new CarLicenseExport($car_licenses),$filename.'.xlsx');
     }
}
