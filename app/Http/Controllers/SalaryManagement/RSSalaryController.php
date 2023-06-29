<?php

namespace App\Http\Controllers\SalaryManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Repositories\SalaryManagement\RSSalaryRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterManagement\Branch;
use App\Models\SalaryManagement\RsSalary;
use Carbon\Carbon;
use DB;
use App\Helpers\LogActivity;

class RSSalaryController extends Controller
{
    public function __construct(RSSalaryRepository $rs_gestion){
        $this->rs_gestion=$rs_gestion;

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
        $rssalary=$this->rs_gestion->indexOrder($request->name,
            $request->order,
            isset($request->year)?$request->year:$today,          
            $request->employee=='all'?null:$request->employee,$n);
        $employees = User::select('id','name','employee_name')->get();
        $branches = Branch::all();
        //return $nssalary;
        return view('salarymanagement.rssalary.index',compact('rssalary','employees','branches'));
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
        $jpn_salary_types = [
        	["type"=>"jpn_salary","label"=>"Salary"],
        	["type"=>"jpn_transfer_from","label"=>"Transfer Salary From Myanmar"],
        	["type"=>"jpn_bonus","label"=>"Bonus"],
        	["type"=>"jpn_adjustment","label"=>"Adjustment"],
        	["type"=>"jpn_income_tax","label"=>"Income Tax Paid in Japan"],
        	["type"=>"jpn_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"jpn_dc","label"=>"DC Contribution"],
        ];
        $mm_salary_types = [
        	["type"=>"mm_salary","label"=>"Salary"],
        	["type"=>"mm_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"mm_dc","label"=>"DC Contribution"],
        ];
       
        $user = User::findOrFail($user_id);
        return view("salarymanagement.rssalary.edit",compact('user','year','jpn_salary_types','mm_salary_types'));
    }

    public function detailSalary($user_id,$year)
    {
        $jpn_salary_types = [
        	["type"=>"jpn_salary","label"=>"Salary"],
        	["type"=>"jpn_transfer_from","label"=>"Transfer Salary From Myanmar"],
        	["type"=>"jpn_bonus","label"=>"Bonus"],
        	["type"=>"jpn_adjustment","label"=>"Adjustment"],
        	["type"=>"jpn_income_tax","label"=>"Income Tax Paid in Japan"],
        	["type"=>"jpn_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"jpn_dc","label"=>"DC Contribution"],
        ];
        $mm_salary_types = [
        	["type"=>"mm_salary","label"=>"Salary"],
        	["type"=>"mm_oversea","label"=>"Oversea Settlement Allowance"],
        	["type"=>"mm_dc","label"=>"DC Contribution"],
        ];
       
        $user = User::findOrFail($user_id);
        return view("salarymanagement.rssalary.detail",compact('user','year','jpn_salary_types','mm_salary_types'));
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

        $salary = $this->rs_gestion->updateSalary($request->all(),$user_id,$year);

        return redirect()->route("rs-salary.list",["employee"=>"all","year"=>$year])->with("success_create","Successfully update salary information.");
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
