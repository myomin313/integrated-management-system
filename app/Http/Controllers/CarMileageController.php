<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarManagement\CarMileage;
use App\Models\CarManagement\Car;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Redirect;
use Response;
use Session;
use Carbon\Carbon;
use App\Exports\CarMileageExport;
use Maatwebsite\Excel\Facades\Excel;
use DateTime;
use DatePeriod;
use DateIntercal;
use Auth;


class CarMileageController extends Controller
{

       public function index(Request $request)
    {
         $car_number = $request->car_number;

         $depart = $request->department;

         $date = $request->date;
         
           if(Auth::user()->can('car-read-all'))
           
      $car_mileages = CarMileage::join('cars', 'cars.id','=','car_mileages.car_id')
         ->join('users', 'users.id','=','car_mileages.created_by')
         ->join('users as users2', 'users2.id','=','car_mileages.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($date){
             if($date != null){
                $start = explode('/', $date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0];
               $query->where('car_mileages.date','=',$date_data);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_mileages.car_id','=', $car_number);
            }
         })
         ->where('cars.deleted_at','=',null)
          ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->select('car_mileages.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_mileages.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else if(Auth::user()->can('car-read-group'))
         
          $car_mileages = CarMileage::join('cars', 'cars.id','=','car_mileages.car_id')
         ->join('users', 'users.id','=','car_mileages.created_by')
         ->join('users as users2', 'users2.id','=','car_mileages.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($date){
             if($date != null){
                $start = explode('/', $date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0];
               $query->where('car_mileages.date','=',$date_data);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_mileages.car_id','=', $car_number);
            }
         })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.dept_id','=',auth()->user()->department_id)
         ->select('car_mileages.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_mileages.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else
         
          $car_mileages = CarMileage::join('cars', 'cars.id','=','car_mileages.car_id')
         ->join('users', 'users.id','=','car_mileages.created_by')
         ->join('users as users2', 'users2.id','=','car_mileages.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('users as users4', 'users4.id','=','cars.main_user')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($date){
             if($date != null){
                $start = explode('/', $date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0];
               $query->where('car_mileages.date','=',$date_data);
             }
         })
         ->where(function($query)use($car_number){
            if($car_number != null){
            $query->where('car_mileages.car_id','=', $car_number);
            }
         })
         ->where(function($query)use($depart){
                if($depart != null)
                $query->whereRaw("find_in_set('".$depart."',cars.dept_id)");
          })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.user_id','=',auth()->user()->id)
         ->orWhere('cars.user_id','=',auth()->user()->id)
         ->select('car_mileages.*','employee_types.type as driver_type','cars.car_type','users4.employee_name as main_user_name',
         'cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_mileages.id')
         ->orderBy('created_at', 'DESC')
         ->get();

          $departments= Department::where('status','=',1)->get();
         $drivers= User::whereIn('employee_type_id',[2,3,4,6.7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         $cars=Car::all();
         return view('carmanagement.car-mileages',compact('car_mileages','cars','departments','drivers','main_users','date','depart','car_number'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'date'=>'required',
            'liter'=>'required',
            'actual_km'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

      $mile=carMileage::where('car_id','=',$request->car_number)->orderBy('date','DESC')->select('actual_km')->first();
      
      if(!empty($mile)){
         $km = $request->actual_km - $mile->actual_km;
      }else{
         $km = $request->actual_km - 0;
      }




        $start = explode('/',  $request->date);
        $start_date = $start[2].'-'.$start[1].'-'.$start[0];

        CarMileage::create([
            'car_id' => $request->car_number,
            'date' => $start_date,
            'km' => $km,
            'liter' => $request->liter,
            'current_km' => $request->current_km,
            'actual_km' => $request->actual_km,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Mileage created successfully.']);

    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'date'=>'required',
            'liter'=>'required',
            'actual_km'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }

    
     $start = explode('/',  $request->date);
        $start_date = $start[2].'-'.$start[1].'-'.$start[0];
        
        $mile=carMileage::where('car_id','=',$request->car_number)->where('id','!=',$request->id)->where('date','<',$start_date)->orderBy('date','DESC')->select('actual_km')->first();
        
        if(!empty($mile)){
           $km = $request->actual_km - $mile->actual_km;
        }else{
           $km = $request->actual_km - 0;
        }

        CarMileage::where('id',$request->id)->update([
            'car_id' => $request->car_number,
            'date' => $start_date,
            'km' => $km,
            'liter' => $request->liter,
            'current_km' => $request->current_km,
            'actual_km' => $request->actual_km,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Mileage Update successfully.']);

    }
    public function delete(Request $request){
        CarMileage::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function yearlyMileageExport(Request $request){
       

        // start

   

// Declare two dates
// $Date1 = '01-10-2010';
// $Date2 = '05-10-2010';

$start =  explode('/',$request->start_date);
$start_date = $start[2].'-'.$start[1].'-'. $start[0];
$end =  explode('/',$request->end_date);
$end_date = $end[2].'-'.$end[1].'-'.$end[0];






   $time1  = strtotime($start_date);
   $time2  = strtotime($end_date);
   $my     = date('mY', $time2);

   $months = array(date('m-Y', $time1));
   
 

   while($time1 < $time2) {
      $time1 = strtotime(date('Y-m-d', $time1).' +1 month');
      if(date('mY', $time1) != $my && ($time1 < $time2))
         $months[] = date('m-Y', $time1);
   }

   $months[] = date('m-Y', $time2);
   
   

    $cars=Car::leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
    ->join('users as main_users','main_users.id','=','cars.main_user')
    ->join('users as drivers','drivers.id','=','cars.user_id')
    ->select('main_users.employee_name as main_user_name','cars.car_type','drivers.employee_type_id',
    'cars.car_number','drivers.employee_name as driver_name','cars.model_year','cars.parking','cars.id',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
    ->groupBy('cars.id')
    ->get();
    
    
    // $cars=Car::join('departments','departments.id','=','cars.dept_id')
    // ->join('users as main_users','main_users.id','=','cars.main_user')
    // ->join('users as drivers','drivers.id','=','cars.user_id')
    // ->select('departments.short_name as department_name',
    // 'main_users.employee_name as main_user_name','cars.car_type','drivers.employee_type_id',
    // 'cars.car_number','drivers.employee_name as driver_name','cars.model_year','cars.parking','cars.id')
    // ->get();


       
    foreach($cars as $car){


        foreach($months as $month){

         $mon = explode('-',$month);
         $monthSearch = $mon[0];
         $yearSearch  = $mon[1];

        //  get monthname ;
        $dateObj   = DateTime::createFromFormat('!m', $monthSearch);
        $monthName = $dateObj->format('F');


        $miles = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        ->whereRaw('extract(month from date) = ?',$monthSearch)
        ->whereRaw('extract(year from date) = ?',$yearSearch)
        ->where('car_mileages.car_id','=',$car->id)
        ->groupBy('year','month','car_mileages.car_id')
        ->orderBy('year','asc')
        ->orderBy('month','asc')
        ->first();

         if($monthSearch  == 4){
            if(!empty($miles)){
              $car->april_total_current_km = $miles->total_current_km;
              $car->april_total_km = $miles->total_km;
              $car->april_total_liter = $miles->total_liter;
              $car->april_per_km = $miles->per_km;
           }else{
              $car->april_total_current_km =0;
              $car->april_total_km = 0;
              $car->april_total_liter = 0;
              $car->april_per_km = 0;
           }
        }

        if($monthSearch  == 5){
            if(!empty($miles)){
              $car->may_total_current_km = $miles->total_current_km;
              $car->may_total_km = $miles->total_km;
              $car->may_total_liter = $miles->total_liter;
              $car->may_per_km = $miles->per_km;
           }else{
              $car->may_total_current_km =0;
              $car->may_total_km = 0;
              $car->may_total_liter = 0;
              $car->may_per_km = 0;
           }
        }

        if($monthSearch  == 6){
            if(!empty($miles)){
              $car->june_total_current_km = $miles->total_current_km;
              $car->june_total_km = $miles->total_km;
              $car->june_total_liter = $miles->total_liter;
              $car->june_per_km = $miles->per_km;
           }else{
              $car->june_total_current_km =0;
              $car->june_total_km = 0;
              $car->june_total_liter = 0;
              $car->june_per_km = 0;
           }
        }

        if($monthSearch  == 7){
            if(!empty($miles)){
              $car->july_total_current_km = $miles->total_current_km;
              $car->july_total_km = $miles->total_km;
              $car->july_total_liter = $miles->total_liter;
              $car->july_per_km = $miles->per_km;
           }else{
              $car->july_total_current_km =0;
              $car->july_total_km = 0;
              $car->july_total_liter = 0;
              $car->july_per_km = 0;
           }
        }

        if($monthSearch  == 8){
            if(!empty($miles)){
              $car->august_total_current_km = $miles->total_current_km;
              $car->august_total_km = $miles->total_km;
              $car->august_total_liter = $miles->total_liter;
              $car->august_per_km = $miles->per_km;
           }else{
              $car->august_total_current_km =0;
              $car->august_total_km = 0;
              $car->august_total_liter = 0;
              $car->august_per_km = 0;
           }
        }

        if($monthSearch  == 9){
            if(!empty($miles)){
              $car->sep_total_current_km = $miles->total_current_km;
              $car->sep_total_km = $miles->total_km;
              $car->sep_total_liter = $miles->total_liter;
              $car->sep_per_km = $miles->per_km;
           }else{
              $car->sep_total_current_km =0;
              $car->sep_total_km = 0;
              $car->sep_total_liter = 0;
              $car->sep_per_km = 0;
           }
        }
        if($monthSearch  == 10){
            if(!empty($miles)){
              $car->oct_total_current_km = $miles->total_current_km;
              $car->oct_total_km = $miles->total_km;
              $car->oct_total_liter = $miles->total_liter;
              $car->oct_per_km = $miles->per_km;
           }else{
              $car->oct_total_current_km =0;
              $car->oct_total_km = 0;
              $car->oct_total_liter = 0;
              $car->oct_per_km = 0;
           }
        }

        if($monthSearch  == 11){
            if(!empty($miles)){
              $car->nov_total_current_km = $miles->total_current_km;
              $car->nov_total_km = $miles->total_km;
              $car->nov_total_liter = $miles->total_liter;
              $car->nov_per_km = $miles->per_km;
           }else{
              $car->nov_total_current_km =0;
              $car->nov_total_km = 0;
              $car->nov_total_liter = 0;
              $car->nov_per_km = 0;
           }
        }

        if($monthSearch  == 12){
            if(!empty($miles)){
              $car->dec_total_current_km = $miles->total_current_km;
              $car->dec_total_km = $miles->total_km;
              $car->dec_total_liter = $miles->total_liter;
              $car->dec_per_km = $miles->per_km;
           }else{
              $car->dec_total_current_km =0;
              $car->dec_total_km = 0;
              $car->dec_total_liter = 0;
              $car->dec_per_km = 0;
           }
        }

        if($monthSearch  == 1){
            if(!empty($miles)){
              $car->jan_total_current_km = $miles->total_current_km;
              $car->jan_total_km = $miles->total_km;
              $car->jan_total_liter = $miles->total_liter;
              $car->jan_per_km = $miles->per_km;
           }else{
              $car->jan_total_current_km =0;
              $car->jan_total_km = 0;
              $car->jan_total_liter = 0;
              $car->jan_per_km = 0;
           }
        }

        if($monthSearch  == 2){
            if(!empty($miles)){
              $car->feb_total_current_km = $miles->total_current_km;
              $car->feb_total_km = $miles->total_km;
              $car->feb_total_liter = $miles->total_liter;
              $car->feb_per_km = $miles->per_km;
           }else{
              $car->feb_total_current_km =0;
              $car->feb_total_km = 0;
              $car->feb_total_liter = 0;
              $car->feb_per_km = 0;
           }
        }

        if($monthSearch  == 3){
            if(!empty($miles)){
              $car->march_total_current_km = $miles->total_current_km;
              $car->march_total_km = $miles->total_km;
              $car->march_total_liter = $miles->total_liter;
              $car->march_per_km = $miles->per_km;
           }else{
              $car->march_total_current_km =0;
              $car->march_total_km = 0;
              $car->march_total_liter = 0;
              $car->march_per_km = 0;
           }
        }




     }

}



        // end

        //start
        // $transdate = date('Y-m-d', time());
        // $month = date('m', strtotime($transdate));

        // if(in_array($month,[01,02,03])){
        //     $end_year = date("Y");
        //     $start_year = $end_year -1;
        // }else{
        //     $start_year = date("Y");
        //     $end_year = $start_year +1;
        // }


        //end

        //  $cars=Car::join('departments','departments.id','=','cars.dept_id')
        //        ->join('users as main_users','main_users.id','=','cars.main_user')
        //        ->join('users as drivers','drivers.id','=','cars.user_id')
        //        ->select('departments.short_name as department_name',
        //        'main_users.employee_name as main_user_name','cars.car_type','drivers.employee_type_id',
        //        'cars.car_number','drivers.employee_name as driver_name','cars.model_year','cars.parking','cars.id')
        //        ->get();
        // foreach($cars as $car){
        //     $aprilmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',4)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();



        //     if(!empty($aprilmileages)){
        //     $car->april_total_current_km = $aprilmileages->total_current_km;
        //     $car->april_total_km = $aprilmileages->total_km;
        //     $car->april_total_liter = $aprilmileages->total_liter;
        //     $car->april_per_km = $aprilmileages->per_km;
        //     }else{
        //        $car->april_total_current_km =0;
        //        $car->april_total_km = 0;
        //        $car->april_total_liter = 0;
        //        $car->april_per_km = 0;
        //     }


        //      $maymileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',5)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();


        //     if(!empty($maymileages)){
        //      $car->may_total_current_km = $maymileages->total_current_km;
        //     $car->may_total_km = $maymileages->total_km;
        //     $car->may_total_liter = $maymileages->total_liter;
        //     $car->may_per_km = $maymileages->per_km;;
        //     }else{
        //        $car->may_total_current_km =0;
        //        $car->may_total_km = 0;
        //        $car->may_total_liter = 0;
        //        $car->may_per_km = 0;
        //     }


        //     $junemileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',6)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($junemileages)){
        //        $car->june_total_current_km = $junemileages->total_current_km;
        //        $car->june_total_km = $junemileages->total_km;
        //        $car->june_total_liter = $junemileages->total_liter;
        //        $car->june_per_km = $junemileages->per_km;;
        //     }else{
        //        $car->june_total_current_km =0;
        //        $car->june_total_km = 0;
        //        $car->june_total_liter = 0;
        //        $car->june_per_km = 0;
        //     }


        //     $julymileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',7)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($julymileages)){
        //        $car->july_total_current_km = $julymileages->total_current_km;
        //        $car->july_total_km = $julymileages->total_km;
        //        $car->july_total_liter = $julymileages->total_liter;
        //        $car->july_per_km = $julymileages->per_km;;
        //     }else{
        //        $car->july_total_current_km =0;
        //        $car->july_total_km = 0;
        //        $car->july_total_liter = 0;
        //        $car->july_per_km = 0;
        //     }



        //     $augustmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',8)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($augustmileages)){
        //        $car->august_total_current_km = $augustmileages->total_current_km;
        //        $car->august_total_km = $augustmileages->total_km;
        //        $car->august_total_liter = $augustmileages->total_liter;
        //        $car->august_per_km = $augustmileages->per_km;;
        //     }else{
        //        $car->august_total_current_km =0;
        //        $car->august_total_km = 0;
        //        $car->august_total_liter = 0;
        //        $car->august_per_km = 0;
        //     }


        //     $sepmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',9)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($sepmileages)){
        //        $car->sep_total_current_km = $sepmileages->total_current_km;
        //        $car->sep_total_km = $sepmileages->total_km;
        //        $car->sep_total_liter = $sepmileages->total_liter;
        //        $car->sep_per_km = $sepmileages->per_km;;
        //     }else{
        //        $car->sep_total_current_km =0;
        //        $car->sep_total_km = 0;
        //        $car->sep_total_liter = 0;
        //        $car->sep_per_km = 0;
        //     }



        //     $octmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',10)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($octmileages)){
        //        $car->oct_total_current_km = $octmileages->total_current_km;
        //        $car->oct_total_km = $octmileages->total_km;
        //        $car->oct_total_liter = $octmileages->total_liter;
        //        $car->oct_per_km = $octmileages->per_km;;
        //     }else{
        //        $car->oct_total_current_km =0;
        //        $car->oct_total_km = 0;
        //        $car->oct_total_liter = 0;
        //        $car->oct_per_km = 0;
        //     }


        //     $novmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',11)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($novmileages)){
        //        $car->nov_total_current_km = $novmileages->total_current_km;
        //        $car->nov_total_km = $novmileages->total_km;
        //        $car->nov_total_liter = $novmileages->total_liter;
        //        $car->nov_per_km = $novmileages->per_km;;
        //     }else{
        //        $car->nov_total_current_km =0;
        //        $car->nov_total_km = 0;
        //        $car->nov_total_liter = 0;
        //        $car->nov_per_km = 0;
        //     }


        //     $decmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',12)
        //            ->whereRaw('extract(year from date) = ?',$start_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     // $car->dec_total_current_km = $decmileages->total_current_km;
        //     // $car->dec_total_km = $novmidecmileagesleages->total_km;
        //     // $car->dec_total_liter = $decmileages->total_liter;
        //     // $car->dec_per_km = $decmileages->per_km;

        //     if(!empty($decmileages)){
        //        $car->dec_total_current_km = $novmileages->total_current_km;
        //        $car->dec_total_km = $decmileages->total_km;
        //        $car->dec_total_liter = $decmileages->total_liter;
        //        $car->dec_per_km = $decmileages->per_km;;
        //     }else{
        //        $car->dec_total_current_km =0;
        //        $car->dec_total_km = 0;
        //        $car->dec_total_liter = 0;
        //        $car->dec_per_km = 0;
        //     }

        //     $janmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',01)
        //            ->whereRaw('extract(year from date) = ?',$end_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($janmileages)){
        //        $car->jan_total_current_km = $janmileages->total_current_km;
        //        $car->jan_total_km = $janmileages->total_km;
        //        $car->jan_total_liter = $janmileages->total_liter;
        //        $car->jan_per_km = $janmileages->per_km;;
        //     }else{
        //        $car->jan_total_current_km =0;
        //        $car->jan_total_km = 0;
        //        $car->jan_total_liter = 0;
        //        $car->jan_per_km = 0;
        //     }
        //     $febmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',02)
        //            ->whereRaw('extract(year from date) = ?',$end_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($febmileages)){
        //        $car->feb_total_current_km = $febmileages->total_current_km;
        //        $car->feb_total_km = $febmileages->total_km;
        //        $car->feb_total_liter = $febmileages->total_liter;
        //        $car->feb_per_km = $febmileages->per_km;;
        //     }else{
        //        $car->feb_total_current_km =0;
        //        $car->feb_total_km = 0;
        //        $car->feb_total_liter = 0;
        //        $car->feb_per_km = 0;
        //     }

        //     $marchmileages = CarMileage::selectRaw('year(date) year, monthname(date) month, sum(current_km) total_current_km,
        //            sum(km) total_km,sum(liter) total_liter,sum(km) / sum(liter) per_km')
        //            ->whereRaw('extract(month from date) = ?',03)
        //            ->whereRaw('extract(year from date) = ?',$end_year)
        //            ->where('car_mileages.car_id','=',$car->id)
        //            ->groupBy('year','month','car_mileages.car_id')
        //            ->orderBy('year','asc')
        //            ->orderBy('month','asc')
        //            ->first();

        //     if(!empty($marchmileages)){
        //        $car->march_total_current_km = $marchmileages->total_current_km;
        //        $car->march_total_km = $marchmileages->total_km;
        //        $car->march_total_liter = $marchmileages->total_liter;
        //        $car->march_per_km = $marchmileages->per_km;;
        //     }else{
        //        $car->march_total_current_km =0;
        //        $car->march_total_km = 0;
        //        $car->march_total_liter = 0;
        //        $car->march_per_km = 0;
        //     }

        // }

        $filename = "kilo_per_liter".Carbon::now()->format('d-m-Y');
        return Excel::download(new CarMileageExport($cars),$filename.'.xlsx');
    }
}
