<?php

namespace App\Http\Controllers\SalaryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SalaryManagement\NSSalaryRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterManagement\Branch;
use App\Models\SalaryManagement\NsSalary;
use Carbon\Carbon;
use DB;
use App\Helpers\LogActivity;

class NSSalaryController extends Controller
{
    public function __construct(NSSalaryRepository $ns_gestion){
        $this->ns_gestion=$ns_gestion;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        $today=Carbon::now()->format('Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='users.id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $nssalary=$this->ns_gestion->indexOrder($request->name,
            $request->order,
            isset($request->year)?$request->year:$today,          
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        //return $nssalary;
        return view('salarymanagement.nssalary.index',compact('nssalary','employees','branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function editSalary($user_id,$year)
    {
        $nssalary = NsSalary::where([["user_id","=",$user_id],["year","=",$year]])->first();
        if(!$nssalary)
            $nssalary = null;

        $user = User::findOrFail($user_id);
        return view("salarymanagement.nssalary.edit",compact('nssalary','user','year'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateSalary(Request $request, $user_id, $year)
    {
        //return $request->all();

        $salary = $this->ns_gestion->updateSalary($request->all(),$user_id,$year);

        return redirect()->route("ns-salary.list",["employee"=>"all","year"=>$year])->with("success_create","Successfully update salary information.");
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
