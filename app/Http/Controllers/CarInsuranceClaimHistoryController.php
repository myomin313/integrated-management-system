<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CarManagement\CarInsuranceClaimHistory;
use App\Models\CarManagement\Car;
use App\Models\CarManagement\CarInsurance;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Redirect;
use Response;
use Session;
use Auth;



class CarInsuranceClaimHistoryController extends Controller
{
    //
       public function index(Request $request)
    {
         $car_number = $request->car_number;
         $insurance_no = $request->insurance_no;
         $claim_date = $request->claim_date;
         
         
        if(Auth::user()->can('car-read-all'))

      $car_insurance_claim_histories = CarInsuranceClaimHistory::join('car_insurances', 'car_insurances.id','=','car_insurance_claim_histories.insurance_id')
         ->join('cars', 'cars.id','=','car_insurances.car_id')
         ->join('users', 'users.id','=','car_insurance_claim_histories.created_by')
         ->join('users as users2', 'users2.id','=','car_insurance_claim_histories.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         //->join('departments', 'departments.id','=','cars.dept_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($insurance_no){
             if($insurance_no != null){
              $query->where('car_insurances.id','=',$insurance_no);
              }
         })
         ->where(function($query)use($car_number){
                if($car_number != null){
                $query->where('cars.id', '=', $car_number);
                }
         })
          ->where(function($query)use($claim_date){
                if($claim_date != null){
                   $start = explode('/', $claim_date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0]; 
                $query->where('car_insurance_claim_histories.claim_date', '=', $date_data);
                }
         })
         ->where('cars.deleted_at','=',NULL)
         ->select('car_insurance_claim_histories.*','employee_types.type as driver_type','car_insurances.insurance_no','car_insurances.insurance_company','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_insurance_claim_histories.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
          else if(Auth::user()->can('car-read-group'))
          
          
          $car_insurance_claim_histories = CarInsuranceClaimHistory::join('car_insurances', 'car_insurances.id','=','car_insurance_claim_histories.insurance_id')
         ->join('cars', 'cars.id','=','car_insurances.car_id')
         ->join('users', 'users.id','=','car_insurance_claim_histories.created_by')
         ->join('users as users2', 'users2.id','=','car_insurance_claim_histories.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($insurance_no){
             if($insurance_no != null){
              $query->where('car_insurances.id','=',$insurance_no);
              }
         })
         ->where(function($query)use($car_number){
                if($car_number != null){
                $query->where('cars.id', '=', $car_number);
                }
         })
          ->where(function($query)use($claim_date){
                if($claim_date != null){
                   $start = explode('/', $claim_date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0]; 
                $query->where('car_insurance_claim_histories.claim_date', '=', $date_data);
                }
         })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.dept_id','=',auth()->user()->department_id)
         ->select('car_insurance_claim_histories.*','employee_types.type as driver_type','car_insurances.insurance_no','car_insurances.insurance_company','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_insurance_claim_histories.id')
         ->orderBy('created_at', 'DESC')
         ->get();
         
         else
         
         $car_insurance_claim_histories = CarInsuranceClaimHistory::join('car_insurances', 'car_insurances.id','=','car_insurance_claim_histories.insurance_id')
         ->join('cars', 'cars.id','=','car_insurances.car_id')
         ->join('users', 'users.id','=','car_insurance_claim_histories.created_by')
         ->join('users as users2', 'users2.id','=','car_insurance_claim_histories.updated_by')
         ->join('users as users3', 'users3.id','=','cars.user_id')
         ->join('employee_types', 'employee_types.id','=','users3.employee_type_id')
         ->leftjoin("departments",\DB::raw("FIND_IN_SET(departments.id,cars.dept_id)"),">",\DB::raw("'0'")) 
         ->where(function($query)use($insurance_no){
             if($insurance_no != null){
              $query->where('car_insurances.id','=',$insurance_no);
              }
         })
         ->where(function($query)use($car_number){
                if($car_number != null){
                $query->where('cars.id', '=', $car_number);
                }
         })
          ->where(function($query)use($claim_date){
                if($claim_date != null){
                   $start = explode('/', $claim_date);
                $date_data = $start[2].'-'.$start[1].'-'.$start[0]; 
                $query->where('car_insurance_claim_histories.claim_date', '=', $date_data);
                }
         })
         ->where('cars.deleted_at','=',NULL)
         ->where('cars.user_id','=',auth()->user()->id)
         ->orWhere('cars.user_id','=',auth()->user()->id)
         ->select('car_insurance_claim_histories.*','employee_types.type as driver_type','car_insurances.insurance_no','car_insurances.insurance_company','cars.car_type','cars.chasis_no','cars.model_year','cars.car_number',
         'users.employee_name as created_user','users3.employee_name as driver_name','users.employee_name as updated_user',\DB::raw("GROUP_CONCAT(departments.short_name) as docname"))
         ->groupBy('car_insurance_claim_histories.id')
         ->orderBy('created_at', 'DESC')
         ->get();
          
          
          $departments= Department::where('status','=',1)->get();
         $users= User::whereIn('employee_type_id',[2,3,4,6.7])->get();
         $main_users= User::whereIn('employee_type_id',[1,5])->get();
         $cars=Car::all();
         $car_insurances=CarInsurance::all();
         return view('carmanagement.car-insurance-claim-history',compact('car_insurance_claim_histories','car_insurances','cars','departments','users','main_users','insurance_no','car_number','claim_date'));
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'insurance_no'=>'required',
            'claim_date'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
         $start = explode('/',  $request->claim_date);
        $date_data = $start[2].'-'.$start[1].'-'.$start[0]; 
        CarInsuranceClaimHistory::create([
            'car_id' => $request->car_number,
            'insurance_id' => $request->insurance_no,
            'claim_date' => $date_data,
            'claim_detail' => $request->claim_detail,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Insurance Claim History created successfully.']);
        
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'car_number'=>'required',
            'insurance_no'=>'required',
            'claim_date'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
               'error' => $validator->errors()
            ]);
        }
        $start = explode('/',  $request->claim_date);
        $date_data = $start[2].'-'.$start[1].'-'.$start[0];

        CarInsuranceClaimHistory::where('id',$request->id)->update([
            'car_id' => $request->car_number,
            'insurance_id' => $request->insurance_no,
            'claim_date' =>  $date_data,
            'claim_detail' => $request->claim_detail,
            'updated_by' => auth()->user()->id,
        ]);
        return response()->json(['success' => 'Car Insurance Claim History Update successfully.']);
        
    }
    public function delete(Request $request){
        CarInsuranceClaimHistory::where('id',$request->id)->delete();
        return Redirect::back()->with('msg','successfully delete');
    }
    public function selectInsuranceNo(Request $request){
           $searches = CarInsurance::where('id',$request->option)
                         ->select('insurance_company')
                         ->first();
             return response::json($searches);
    }
}
