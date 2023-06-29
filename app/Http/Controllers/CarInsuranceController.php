<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarManagement\CarInsurance;
use App\Models\CarManagement\Car;
use App\Models\CarManagement\CarFueling;
use App\Models\MasterManagement\Department;
use App\Models\CarManagement\CarInsuranceClaimHistory;
use App\Models\User;
use App\Exports\InsuranceExport;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;
use Response;
use Session;
use Carbon\Carbon;
use Auth;

class CarInsuranceController extends Controller
{
    //
    public function index(Request $request)
    {
        //  $driver_name= $request->search_driver_name;
         $car_number = $request->car_number;
         $insurance_no = $request->insurance_no;
         
         
         if(Auth::user()->can('car-read-all'))
         

         $car_insurances = CarInsurance::select('car_insurances.*','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->join('cars', 'cars.id','=','car_insurances.car_id')
         ->join('users', 'users.id','=','car_insurances.created_by')
         ->join('users as users2', 'users2.id','=','car_insurances.updated_by')
         //->join('departments', 'departments.id','=','cars.dept_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($insurance_no){
             if($insurance_no != null){
              $query->where('car_insurances.insurance_no','=',$insurance_no);
             }
         })
         ->where(function($query)use($car_number){
                 if($car_number != null){
                  $query->where('cars.id', '=', $car_number);
                 }
         })
         ->where('cars.deleted_at','=',NULL)
         ->groupBy('car_insurances.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
          else if(Auth::user()->can('car-read-group'))
          
          
          $car_insurances = CarInsurance::select('car_insurances.*','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->join('cars', 'cars.id','=','car_insurances.car_id')
         ->join('users', 'users.id','=','car_insurances.created_by')
         ->join('users as users2', 'users2.id','=','car_insurances.updated_by')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($insurance_no){
             if($insurance_no != null){
              $query->where('car_insurances.insurance_no','=',$insurance_no);
             }
         })
         ->where(function($query)use($car_number){
                 if($car_number != null){
                  $query->where('cars.id', '=', $car_number);
                 }
         })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.dept_id','=',auth()->user()->department_id)
         ->groupBy('car_insurances.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else
         
          $car_insurances = CarInsurance::select('car_insurances.*','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->join('cars', 'cars.id','=','car_insurances.car_id')
         ->join('users', 'users.id','=','car_insurances.created_by')
         ->join('users as users2', 'users2.id','=','car_insurances.updated_by')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($insurance_no){
             if($insurance_no != null){
              $query->where('car_insurances.insurance_no','=',$insurance_no);
             }
         })
         ->where(function($query)use($car_number){
                 if($car_number != null){
                  $query->where('cars.id', '=', $car_number);
                 }
         })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.user_id','=',auth()->user()->id)
         ->orWhere('cars.user_id','=',auth()->user()->id)
         ->groupBy('car_insurances.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         

         $departments= Department::where('status','=',1)->get();
         $users= User::whereIn('employee_type_id',[2,3,4,6.7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         $cars=Car::all();
         return view('carmanagement.car-insurances',compact('car_insurances','cars','departments','users','main_users','car_number','insurance_no'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number' => 'required',
            'insurance_no' => 'required',
            'insurance_company' => 'required',
            'premium_amount' => 'required',
            'currency' => 'required',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
         $start = explode('/',  $request->start_date);
        $date_start = $start[2].'-'.$start[1].'-'.$start[0];

          $due = explode('/',  $request->due_date);
        $date_due = $due[2].'-'.$due[1].'-'.$due[0];

        $id = CarInsurance::insertGetId([
            'car_id' => $request->car_number,
            'insurance_no' => $request->insurance_no,
            'insurance_company' => $request->insurance_company,
            'premium_amount' => $request->premium_amount,
            'currency' => $request->currency,
            'start_date' => $date_start,
            'end_date' => $date_due,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
        ]);
        return response()->json(['success' => 'Car Insurance created successfully.']);

    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number' => 'required',
            'insurance_no' => 'required',
            'insurance_company' => 'required',
            'premium_amount' => 'required',
            'currency' => 'required',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
           $start = explode('/',  $request->start_date);
        $date_start = $start[2].'-'.$start[1].'-'.$start[0];

          $due = explode('/',  $request->due_date);
        $date_due = $due[2].'-'.$due[1].'-'.$due[0];

        CarInsurance::where('id',$request->id)->update([
            'car_id' => $request->car_number,
            'insurance_no' => $request->insurance_no,
            'insurance_company' => $request->insurance_company,
            'premium_amount' => $request->premium_amount,
            'currency' => $request->currency,
            'start_date' => $date_start,
            'end_date' => $date_due,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Insurance Update successfully.']);

    }
    public function delete(Request $request){
        CarInsurance::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function selectCarNumber(Request $request){
           $searches = Car::select('cars.car_type',\DB::raw("GROUP_CONCAT(departments.short_name) as name"))
                         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'"))
                         ->where('cars.id',$request->option)
                         ->first();
             return response::json($searches);
    }
    public function premiumAmountUpdate(Request $request){
        //start
       $validator = Validator::make($request->all(), [
            'insurance_no' => 'required',
            'insurance_company' => 'required',
            'premium_amount' => 'required',
            'currency' => 'required',
            'start_date' => 'required',
            'due_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        CarInsurance::where('car_id',$request->car_id)->update([
            'status' => '0',
        ]);

        $start = explode('/',  $request->start_date);
        $date_start = $start[2].'-'.$start[1].'-'.$start[0];

          $due = explode('/',  $request->due_date);
        $date_due = $due[2].'-'.$due[1].'-'.$due[0];

        $id = CarInsurance::insertGetId([
            'car_id' => $request->car_id,
            'insurance_no' => $request->insurance_no,
            'insurance_company' => $request->insurance_company,
            'premium_amount' => $request->premium_amount,
            'currency' => $request->currency,
            'start_date' => $date_start,
            'end_date' => $date_due,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'created_at' => date('Y-m-d h:i:s'),
            'updated_at' => date('Y-m-d h:i:s'),
            'status' => 1
        ]);
        return response()->json(['success' => 'Premium Amount created successfully.']);

    }

    public function yearlyInsuranceExport(Request $request){

        $sDate = $request->start_date;
        $eDate = $request->end_date;

        $cars = CarInsurance::join('cars','cars.id','=','car_insurances.car_id')
              // ->join('departments','departments.id','=','cars.dept_id')
               ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
               ->join('users as main_users','main_users.id','=','cars.main_user')
               ->join('users as drivers','drivers.id','=','cars.user_id')
               ->select('car_insurances.id as insurance_id','car_insurances.end_date','car_insurances.insurance_no','drivers.employee_type_id','cars.id','main_users.employee_name as main_user_name',
               'cars.car_type','cars.car_number','drivers.employee_name as driver_name','cars.model_year','cars.parking',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
              // ->groupBy('cars.id')
               ->groupBy('car_insurances.id')
               ->get();


        foreach($cars as $car){
             $car->insurance_claim_history= CarInsuranceClaimHistory::select('*')
                                         ->where('car_id','=',$car->id)
                                         ->where('insurance_id','=',$car->insurance_id)
                                         ->where(function($query)use($sDate,$eDate){
                                            if($sDate != null && $eDate != null){
                                                $start = explode('/', $sDate);
                                                $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                                $end = explode('/', $eDate);
                                                $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                                 $query->whereBetween('claim_date', [$start_date, $end_date]);
                                              }
                                             })
                                         ->get();
        }
         $claim_count = CarInsuranceClaimHistory::select('*')
                                         ->where(function($query)use($sDate,$eDate){
                                          if($sDate != null && $eDate != null){
                                              $start = explode('/', $sDate);
                                              $start_date = $start[2].'-'.$start[1].'-'.$start[0];

                                              $end = explode('/', $eDate);
                                              $end_date = $end[2].'-'.$end[1].'-'.$end[0];
                                               $query->whereBetween('claim_date', [$start_date, $end_date]);
                                            }
                                        })
                                         ->groupBy('car_id','insurance_id')
                                         ->orderByRaw('count(*) DESC')
                                         ->count();
                                         
                                        // dd($cars);exit();

        $filename = "insurance_".Carbon::now()->format('d-m-Y');
       return Excel::download(new InsuranceExport($cars,$claim_count),$filename.'.xlsx');

    }
    public function insuranceAmountHistory($car_id){

             $insurance_update_datas  = CarInsurance::join('cars', 'cars.id','=','car_insurances.car_id')
             ->join('users', 'users.id','=','car_insurances.created_by')
             ->join('users as users2', 'users2.id','=','car_insurances.updated_by')
             ->join('departments', 'departments.id','=','cars.dept_id')
             ->where('car_insurances.car_id','=',$car_id)
             ->where('car_insurances.status','=','0')
             ->select('car_insurances.*','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number','departments.name as department_name',
             'users.employee_name as created_user','users2.employee_name as updated_user')
             ->orderBy('created_at', 'DESC')
             ->paginate(10);

         return view('carmanagement.insurance-amount-updates',compact('insurance_update_datas'));

    }
     public function selectCarNumberwithLiter(Request $request){


        $car_number = $request->car_number;
        $date       = explode('/',$request->date);

         // dd($date[1]);exit();
            $fuel_total= CarFueling::where('car_id','=',$car_number)
                        ->whereMonth('date', '=', $date[1])
                        ->whereYear('date', '=', $date[2])
                        ->selectRaw('sum(liter) as total_liter')
                        ->first();


            return response::json($fuel_total);
    }
}
