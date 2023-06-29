<?php

namespace App\Http\Controllers\TaxManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterManagement\Branch;
use App\Models\MasterManagement\Department;
use App\Models\MasterManagement\EmployeeType;
use App\Models\TaxManagement\RsActualTax;
use App\Models\TaxManagement\RsIncomeTax;
use DB;
use Carbon\Carbon;
use App\Repositories\TaxManagement\RsTaxRepository;

class RsTaxController extends Controller
{
    public function __construct(RsTaxRepository $tax_gestion){
        $this->tax_gestion=$tax_gestion;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('d/m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='rs_actual_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $taxes=$this->tax_gestion->indexOrder($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $employees = User::select('id','name','employee_name')->where("check_ns_rs","=",0)->get();
               
        return view('taxmanagement.rstax.index',compact('taxes','employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = User::where("check_ns_rs",0)->get();
        return view('taxmanagement.rstax.create',compact('employees'));
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
            'tax_for.*'=>'required',
            'user_id.*'=>'required',
            'tax_amount_mmk.*'=>'required|numeric',
            'exchange_rate.*'=>'required|numeric',
            'tax_amount_usd.*'=>'required|numeric',
            'pay_date.*'=>'required',
        ];

        $this->validate($request,$validate_array);
        //return $request->all();
        $rs_tax = $this->tax_gestion->store($request->all());


        if(isset($request['save'])){
            return redirect('tax-management/actual-tax/rs-income-tax-list')->with('success_create','Successfully created new user !!!');
        }
        else if(isset($request['save_new'])){
            return redirect('tax-management/actual-tax/rs-income-tax-create')->with('success_create','Successfully created new user !!!');
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
    public function updateTax(Request $request)
    {
        $validate_array = [
            'tax_for.*'=>'required',
            'user_id.*'=>'required',
            'tax_amount_mmk.*'=>'required|numeric',
            'exchange_rate.*'=>'required|numeric',
            'tax_amount_usd.*'=>'required|numeric',
            'pay_date.*'=>'required',
        ];
        $this->validate($request,$validate_array);

        $rstax=$this->tax_gestion->update($request->all());
        $emp_name = getUserFieldWithId($rstax->user_id,"employee_name");
        $user_name = $emp_name?$emp_name:getUserFieldWithId($rstax->user_id,"name");
        return response()->json(['user_id'=>$rstax->user_id,'user_name'=>$user_name,'tax_for'=>Carbon::parse($rstax->tax_for)->format("F Y"),'tax_period'=>$rstax->tax_for,'tax_mmk'=>$rstax->tax_amount_mmk,'exchange_rate'=>$rstax->exchange_rate,'tax_usd'=>$rstax->tax_amount_usd,'pay_date'=>siteformat_date($rstax->pay_date),'index'=>$request->index]);

    }

    

    public function deleteTax(Request $request)
    {
        
        $rstax=RsActualTax::findOrFail($request->id);

        $end_date = Carbon::parse($rstax->tax_for)->endOfMonth()->format("Y-m-d");
        $rs_income_tax = RsIncomeTax::where([["user_id","=",$rstax->user_id],["date","=",$end_date]])->first();

        if($rs_income_tax){
                $rs_income_tax->actual_tax_mmk = 0;
                $rs_income_tax->actual_exchange_rate = 0;
                $rs_income_tax->actual_tax_usd = 0;
                $rs_income_tax->save();
        }
        
        $rstax->delete();

        return redirect('tax-management/actual-tax/rs-income-tax-list')->with('success_delete','Successfully update existing the record !!!');
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

}
