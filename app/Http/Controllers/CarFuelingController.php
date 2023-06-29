<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarManagement\CarFueling;
use Illuminate\Support\Facades\Mail;
use App\Models\CarManagement\Car;
use App\Models\MasterManagement\Department;
use App\Models\User;
use App\Exports\CarFuelingExport;
use App\Mail\CarFuelingInfo;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Response;
use Session;
use Carbon\Carbon;
use DateTime;
use Auth;

class CarFuelingController extends Controller
{

       public function index(Request $request)
    {
         $car_number = $request->car_number;

         $depart = $request->department;

         $date = $request->date;

            //echo $driver;exit();
            
      if(Auth::user()->can('car-read-all'))
            
      $car_fuelings = CarFueling::join('cars', 'cars.id','=','car_fuelings.car_id')
         ->join('users', 'users.id','=','car_fuelings.created_by')
         ->join('users as users2', 'users2.id','=','car_fuelings.updated_by')
         ->join('users as users3', 'users3.id','=','car_fuelings.driver')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($date){
             if($date != null){
             $start = explode('/', $date);
             $date_data = $start[2].'-'.$start[1].'-'.$start[0];
             $query->where('car_fuelings.date','=',$date_data);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_fuelings.car_id','=', $car_number);
            }
         })
          ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->select('car_fuelings.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',
         'cars.main_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_fuelings.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
          else if(Auth::user()->can('car-read-group'))
          
            $car_fuelings = CarFueling::join('cars', 'cars.id','=','car_fuelings.car_id')
         ->join('users', 'users.id','=','car_fuelings.created_by')
         ->join('users as users2', 'users2.id','=','car_fuelings.updated_by')
         ->join('users as users3', 'users3.id','=','car_fuelings.driver')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($date){
             if($date != null){
             $start = explode('/', $date);
             $date_data = $start[2].'-'.$start[1].'-'.$start[0];
             $query->where('car_fuelings.date','=',$date_data);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_fuelings.car_id','=', $car_number);
            }
         })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.dept_id','=',auth()->user()->department_id)
         ->select('car_fuelings.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',
         'cars.main_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_fuelings.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else
         
          $car_fuelings = CarFueling::join('cars', 'cars.id','=','car_fuelings.car_id')
         ->join('users', 'users.id','=','car_fuelings.created_by')
         ->join('users as users2', 'users2.id','=','car_fuelings.updated_by')
         ->join('users as users3', 'users3.id','=','car_fuelings.driver')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
          ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($date){
             if($date != null){
             $start = explode('/', $date);
             $date_data = $start[2].'-'.$start[1].'-'.$start[0];
             $query->where('car_fuelings.date','=',$date_data);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_fuelings.car_id','=', $car_number);
            }
         })
          ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.user_id','=',auth()->user()->id)
         //->orWhere('cars.user_id','=',auth()->user()->id)
         ->select('car_fuelings.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',
         'cars.main_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_fuelings.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         

          $departments= Department::where('status','=',1)->get();
         $drivers= User::whereIn('employee_type_id',[2,3,4,6.7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         $cars=Car::all();
         return view('carmanagement.car-fueling',compact('car_fuelings','cars','departments','drivers','main_users','car_number','depart','date'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'liter'=>'required',
            'date'=>'required',
            'rate'=>'required',
            'current_meter'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

          $lastkl=CarFueling::where('car_id','=',$request->car_number)
                            ->orderBy('date','DESC')
                            ->select('current_meter')
                            ->first();

            if(!empty($lastkl)){
                $mileage_difference =  $request->current_meter -  $lastkl->current_meter;
            }else{
                $mileage_difference =   $request->current_meter;
             }



        $totalRate =  $request->liter * $request->rate;
        $start = explode('/',  $request->date);
        $date_data = $start[2].'-'.$start[1].'-'.$start[0];
        CarFueling::create([
            'car_id' => $request->car_number,
            'liter' => $request->liter,
            'date' =>  $date_data,
            'rate'=>$request->rate,
            'totalRate'=>$totalRate,
            'driver'=> auth()->user()->id,
            'current_meter' => $request->current_meter,
            'reason' => $request->reason,
            'mileage_difference' => $mileage_difference,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);


         $result=Car::join('users','users.id','=','cars.main_user')
                ->where('cars.id','=',$request->car_number)
                ->select('users.email','users.employee_name')
                ->first();

            $email = $result->email;
            $name  = $result->employee_name;

        Mail::to($email)->send(new CarFuelingInfo($name));


        return response()->json(['success' => 'Car Fueling created successfully.']);

    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'liter'=>'required',
            'date'=>'required',
            'rate'=>'required',
            'current_meter'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

         $start = explode('/',  $request->date);
        $date_data = $start[2].'-'.$start[1].'-'.$start[0];
        $totalRate =  $request->liter * $request->rate;

        $lastkl=CarFueling::where('car_id','=',$request->car_number)
        ->orderBy('date','DESC')
        ->select('current_meter')
        ->first();

        if(!empty($lastkl)){
           $mileage_difference =  $request->current_meter -  $lastkl->current_meter;
        }else{
           $mileage_difference =   $request->current_meter;
         }


        CarFueling::where('id',$request->id)->update([
            'car_id' => $request->car_number,
            'date' => $date_data,
            'liter' => $request->liter,
            'rate'=>$request->rate,
            'totalRate'=>$totalRate,
            'current_meter' => $request->current_meter,
            'mileage_difference' => $mileage_difference,
            'reason' => $request->reason,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Fueling Update successfully.']);

    }
    public function delete(Request $request){
        CarFueling::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function fuelingExport(Request $request){
        $dateElements = explode('/', $request->date_search);
        // to search by month and year
        $month = $dateElements[0];
        $year = $dateElements[1];
        //to get month name
        $dateObj   = DateTime::createFromFormat('!m', $month);
        $monthName = $dateObj->format('F');

      $car_number = $request->car_number_search;
      $date       =  $request->date_search;
      $car = Car::join('users','users.id','=','cars.user_id')
            ->where('cars.id','=',$car_number)
            ->select('cars.car_number','users.employee_name')
            ->first();

      $cars = CarFueling::join('cars','cars.id','=','car_fuelings.car_id')
               ->join('departments','departments.id','=','cars.dept_id')
               ->join('users as main_users','main_users.id','=','cars.main_user')
               ->join('users as drivers','drivers.id','=','cars.user_id')
               ->join('users as gasoline_drivers','gasoline_drivers.id','=','car_fuelings.driver')
               ->where('car_fuelings.car_id','=',$car_number)
               ->whereRaw('extract(month from car_fuelings.date) = ?',$month)
               ->whereRaw('extract(year from car_fuelings.date) = ?',$year)
               ->select('car_fuelings.*','drivers.employee_type_id','cars.id','departments.short_name as department_name','main_users.employee_name as main_user_name',
               'cars.car_type','cars.car_number','drivers.employee_name as driver_name','cars.model_year','cars.parking','gasoline_drivers.employee_name as gasoline_driver_name')
               ->get();

          $number_of_car=  $car->car_number;
          $driver_name  = $car->employee_name;

      $filename = "fuelings_".Carbon::now()->format('d-m-Y');
      return Excel::download(new CarFuelingExport($cars,$number_of_car,$driver_name,$date,$monthName,$year),$filename.'.xlsx');


    }
    public function changeStatus(Request $request){

           $id = $request->id;
           $status = $request->status;
           CarFueling::where('id',$id)->update([
             'status' => $status
           ]);
          return response()->json(['success' => 'Fueling Status change successfully.']);

    }
}
