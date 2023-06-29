<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarManagement\CarRepairAndMaintanance;
use App\Models\CarManagement\Car;
use App\Models\CarManagement\CarInsurance;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Redirect;
use Response;
use Session;
use Carbon\Carbon;
use App\Exports\CarRepairAndMaintananceExport;
use App\Exports\RepairRecordByCarExport;
use App\Exports\RepairRecordExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\CarManagement\CarMileage;
use DB;
use DateTime;
use Auth;

class CarRepairAndMaintananceController extends Controller
{

     //
       public function index(Request $request)
    {
         $car_number = $request->car_number;
         $kilo_meter = $request->kilo_meter;
         $repair_date = $request->repair_date;
         
         
          if(Auth::user()->can('car-read-all')){

      $car_repair_and_maintanances = CarRepairAndMaintanance::join('cars', 'cars.id','=','car_repair_and_maintanances.car_id')
         ->join('users', 'users.id','=','car_repair_and_maintanances.created_by')
         ->join('users as users2', 'users2.id','=','car_repair_and_maintanances.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         //->join('departments', 'departments.id','=','cars.dept_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($kilo_meter){
             if($kilo_meter != null){
              $query->where('car_repair_and_maintanances.kilo_meter','=',$kilo_meter);
             }
         })
         ->where(function($query)use($car_number){
                if($car_number != null){
                $query->where('cars.id', '=', $car_number);
                }
         })
          ->where(function($query)use($repair_date){
                if($repair_date != null){
                $start = explode('/', $repair_date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0];
                $query->where('car_repair_and_maintanances.repair_date', '=', $date_data);
                }
         })
         ->where('cars.deleted_at','=',NULL)
         ->select('car_repair_and_maintanances.*','employee_types.type as driver_type','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_repair_and_maintanances.id')
         ->orderBy('car_repair_and_maintanances.repair_date', 'DESC')
         ->get();
         
         
         
         }else if(Auth::user()->can('car-read-group')){
         
         $car_repair_and_maintanances = CarRepairAndMaintanance::join('cars', 'cars.id','=','car_repair_and_maintanances.car_id')
         ->join('users', 'users.id','=','car_repair_and_maintanances.created_by')
         ->join('users as users2', 'users2.id','=','car_repair_and_maintanances.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($kilo_meter){
             if($kilo_meter != null){
              $query->where('car_repair_and_maintanances.kilo_meter','=',$kilo_meter);
             }
         })
         ->where(function($query)use($car_number){
                if($car_number != null){
                $query->where('cars.id', '=', $car_number);
                }
         })
          ->where(function($query)use($repair_date){
                if($repair_date != null){
                $start = explode('/', $repair_date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0];
                $query->where('car_repair_and_maintanances.repair_date', '=', $date_data);
                }
         })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.dept_id','=',auth()->user()->department_id)
          ->select('car_repair_and_maintanances.*','employee_types.type as driver_type','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
           'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_repair_and_maintanances.id')
         ->orderBy('car_repair_and_maintanances.repair_date', 'DESC')
         ->get();
         
      
         
         }else{
         
         $car_repair_and_maintanances = CarRepairAndMaintanance::join('cars', 'cars.id','=','car_repair_and_maintanances.car_id')
         ->join('users', 'users.id','=','car_repair_and_maintanances.created_by')
         ->join('users as users2', 'users2.id','=','car_repair_and_maintanances.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($kilo_meter){
             if($kilo_meter != null){
              $query->where('car_repair_and_maintanances.kilo_meter','=',$kilo_meter);
             }
         })
         ->where(function($query)use($car_number){
                if($car_number != null){
                $query->where('cars.id', '=', $car_number);
                }
         })
          ->where(function($query)use($repair_date){
                if($repair_date != null){
                $start = explode('/', $repair_date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0];
                $query->where('car_repair_and_maintanances.repair_date', '=', $date_data);
                }
         })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.user_id','=',auth()->user()->id)
         ->orWhere('cars.user_id','=',auth()->user()->id)
         ->select('car_repair_and_maintanances.*','employee_types.type as driver_type','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_repair_and_maintanances.id')
         ->orderBy('car_repair_and_maintanances.repair_date', 'DESC')
         ->get();
         
         
         }
         
         

          $departments= Department::where('status','=',1)->get();
         $users= User::whereIn('employee_type_id',[2,3,4,6.7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         $cars=Car::all();
         $car_insurances=CarInsurance::all();
         return view('carmanagement.car-repair-and-maintanances',compact('car_repair_and_maintanances','car_insurances','cars','departments','users','main_users','car_number','repair_date','kilo_meter'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'kilo_meter'=>'required',
            'repair_date'=>'required',
            'amount'=>'required',
            'repair_type'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
          $start = explode('/', $request->repair_date);
          $repair_date = $start[2].'-'.$start[1].'-'.$start[0];
          $repair_types= implode(",",$request->repair_type);
        CarRepairAndMaintanance::create([
            'car_id' => $request->car_number,
            'kilo_meter' => $request->kilo_meter,
            'repair_date' => $repair_date,
            'amount' => $request->amount,
            'repair_type' => $repair_types,
            'repair_detail' => $request->repair_detail,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Repair And Maintanance  created successfully.']);

    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'kilo_meter'=>'required',
            'repair_date'=>'required',
            'amount'=>'required',
            'repair_type'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

          $start = explode('/', $request->repair_date);
          $repair_date = $start[2].'-'.$start[1].'-'.$start[0];
         $repair_types= implode(",",$request->repair_type);
        CarRepairAndMaintanance::where('id',$request->id)->update([
            'car_id' => $request->car_number,
            'kilo_meter' => $request->kilo_meter,
            'repair_date' => $repair_date,
            'amount' => $request->amount,
            'repair_type' => $repair_types,
            'repair_detail' => $request->repair_detail,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Repair And Maintanance Update successfully.']);

    }
    public function delete(Request $request){

        CarRepairAndMaintanance::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function selectInsuranceNo(Request $request){
           $searches = CarInsurance::where('id',$request->option)
                         ->select('insurance_company')
                         ->first();
             return response::json($searches);
    }
    public function exportForKMMaintanance(Request $request){

           // $date_format =  strtotime($request->month);
          //$month = date("Y-m", strtotime($date_format));
           // $month = date ("Y-m", $date_format);
        //     $newDate = date("Y-m", strtotime($request->month));
        //    echo $newDate;exit();
          $month = $request->month;
          $km_for_maintenances=CarRepairAndMaintanance::join('cars','cars.id','=','car_repair_and_maintanances.car_id')
               ->join('car_mileages','car_mileages.car_id','=','cars.id')
              // ->join('departments','departments.id','=','cars.dept_id')
               ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
               ->join('users as main_users','main_users.id','=','cars.main_user')
               ->join('users as drivers','drivers.id','=','cars.user_id')
               ->whereRaw("find_in_set('maintenance',repair_type)")
               ->where(function($query)use($month){
                   if($month != null)
                  // $query->where(DB::raw("(DATE_FORMAT(car_repair_and_maintanances.repair_date,'%m/%Y'))"),$month);
                   $query->where(DB::raw("(DATE_FORMAT(car_mileages.date,'%m/%Y'))"),$month);
               })
               ->selectRaw('MAX(car_mileages.current_km) as current_km,drivers.employee_type_id,main_users.employee_name as main_user_name,
               cars.car_type,cars.car_number,drivers.employee_name as driver_name,cars.model_year,cars.parking,
               MAX(car_repair_and_maintanances.kilo_meter) as kilo_meter,cars.id,GROUP_CONCAT(departments.short_name) as docname')
               ->groupBy('cars.id')
               ->orderBy('cars.id','desc')
               ->orderBy('car_mileages.date','desc')
               ->orderBy('car_repair_and_maintanances.repair_date','desc')
               ->get();

        $filename = "km_for_Maintenance".Carbon::now()->format('d-m-Y');
        return Excel::download(new CarRepairAndMaintananceExport($km_for_maintenances,$month),$filename.'.xlsx');

    }
     public function repairRecordByCar(Request $request){

        $car_id = $request->car_id;

        $car =  Car::where('id',$car_id)->first();
        $car_number = $car->car_number;

                $sDate= $request->start_date;
                $eDate= $request->end_date;

               $car_mileage = CarMileage::select(DB::raw('MAX(current_km) as max_current_km'))
                            ->where('car_id',$car_id)
                            ->first();

              $max_current_km = $car_mileage->max_current_km;

          $repair_by_cars=CarRepairAndMaintanance::select('car_repair_and_maintanances.*')
                         ->where('car_repair_and_maintanances.car_id','=',$car_id)
                         ->where(function($query)use($sDate,$eDate){
                            if($sDate != null && $eDate != null){
                                $start = explode('/', $sDate);
                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                $end = explode('/', $eDate);
                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                            $query->whereBetween('car_repair_and_maintanances.repair_date', [$start_date, $end_date]);
                            }
                        })
                         ->orderBy('car_repair_and_maintanances.repair_date','desc')
                         ->get();


        $filename = "repair_record_of_".$car_number."_".Carbon::now()->format('d-m-Y');
        return Excel::download(new RepairRecordByCarExport($repair_by_cars,$car_number,$max_current_km,$request->start_date,$request->end_date),$filename.'.xlsx');

     }
     public function repairRecordExport(Request $request){

         $sDate = $request->repair_start_date;
         $eDate = $request->repair_end_date;

         $cars = Car::leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
               ->join('users as main_users','main_users.id','=','cars.main_user')
               ->join('users as drivers','drivers.id','=','cars.user_id')
               ->select('drivers.employee_type_id','cars.id','main_users.employee_name as main_user_name',
               'cars.car_type','cars.car_number','drivers.employee_name as driver_name','cars.model_year','cars.parking',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
               ->groupBy('cars.id')
               ->get();
               
              

               foreach($cars as $car){
                     $car->repair_maintenance_record = CarRepairAndMaintanance::select('*')
                                         ->whereRaw("find_in_set('maintenance',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->where('car_id','=',$car->id)
                                         ->get();

                     $car->repair_tyre_record = CarRepairAndMaintanance::select('*')
                                         ->whereRaw("find_in_set('tyre',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->where('car_id','=',$car->id)
                                         ->get();

                     $car->repair_battery_record = CarRepairAndMaintanance::select('*')
                                         ->whereRaw("find_in_set('battery',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->where('car_id','=',$car->id)
                                         ->get();

                    $car->repair_other_record = CarRepairAndMaintanance::select('*')
                                         ->whereRaw("find_in_set('other',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->where('car_id','=',$car->id)
                                         ->get();

               }
               
                //dd($cars);exit();

                $maintenance_count = CarRepairAndMaintanance::select('repair_date')
                                         ->whereRaw("find_in_set('maintenance',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->groupBy('car_id')
                                         ->orderByRaw('count(*) DESC')
                                         ->count();

                $tyre_count = CarRepairAndMaintanance::select('repair_date')
                                         ->whereRaw("find_in_set('tyre',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->groupBy('car_id')
                                         ->orderByRaw('count(*) DESC')
                                         ->count();

                $battery_count = CarRepairAndMaintanance::select('repair_date')
                                         ->whereRaw("find_in_set('battery',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->groupBy('car_id')
                                         ->orderByRaw('count(*) DESC')
                                         ->count();

                $other_count = CarRepairAndMaintanance::select('repair_date')
                                         ->whereRaw("find_in_set('other',repair_type)")
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                            $query->whereBetween('repair_date', [$start_date, $end_date]);
                                            }
                                         })
                                         ->groupBy('car_id')
                                         ->orderByRaw('count(*) DESC')
                                         ->count();
               

        $filename = "repair_record".Carbon::now()->format('d-m-Y');
       return Excel::download(new repairRecordExport($cars,$maintenance_count,$tyre_count,$battery_count,$other_count),$filename.'.xlsx');



     }
}
