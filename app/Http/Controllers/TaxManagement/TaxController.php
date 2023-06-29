<?php

namespace App\Http\Controllers\TaxManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\TaxManagement\TaxRepository;
use App\Models\User;
use App\Models\MasterManagement\Branch;
use App\Models\LeaveManagement\LeaveType;
use App\Models\TaxManagement\NsIncomeTax;
use App\Models\TaxManagement\RsIncomeTax;
use App\Models\TaxManagement\TaxRange;
use Carbon\Carbon;
use DB;
use Excel;
use App\Helpers\LogActivity;
use App\Exports\SSCReportExport;
use App\Exports\MonthlyTaxStatementExport;
use App\Exports\NSActualTaxPaymentExport;
use App\Exports\RSActualTaxPaymentExport;
use App\Exports\RSTaxDetailExport;
use App\Exports\NSTaxDetailExport;
use App\Exports\MonthlyPayeExport;
use App\Exports\ExchangeRateDetailExport;
use App\Exports\ExchangeRateSummaryExport;
use App\Exports\PaidPersonalExport;
use App\Exports\TaxOfficeSubmissionExport;

class TaxController extends Controller
{
    public function __construct(TaxRepository $tax_gestion){
        $this->tax_gestion=$tax_gestion;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sscReport(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='sscs.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $sscs=$this->tax_gestion->sscReport($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,  
            $request->branch=='all'?null:$request->branch,$n);
        
        $employees = User::select('id','name','employee_name')->where("check_ns_rs","=",1)->get();
        $branches = Branch::select("id","name")->get();
        //return $sscs;
               
        //return view('taxmanagement.ssc_export',compact('sscs','employees',"branches"));
        return view('taxmanagement.ssc_report',compact('sscs','employees',"branches"));
    }

    public function sscReportDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='sscs.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $sscs=$this->tax_gestion->sscReport($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,  
            $request->branch=='all'?null:$request->branch,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');
        $branch = $request->branch=='all'?'All':getBranchField($request->branch,'name');

        $filename="SSC_Report".Carbon::now()->format('d-m-Y');
        return Excel::download(new SSCReportExport($sscs,$from_date,$to_date,$employee,$branch), $filename.'.xlsx');
    }

    public function monthlyTaxStatement(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='ns_income_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->monthlyTaxStatement($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $employees = User::select('id','name','employee_name')->where("check_ns_rs","=",1)->get();
               
        return view('taxmanagement.monthly_tax_statement',compact('income_taxes','employees'));
    }

    public function monthlyTaxStatementDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='ns_income_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->monthlyTaxStatement($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Monthly_Withholding_Tax".Carbon::now()->format('d-m-Y');
        return Excel::download(new MonthlyTaxStatementExport($income_taxes,$from_date,$to_date,$employee), $filename.'.xlsx');
    }

    public function monthlyTaxStatementDetail($id)
    {
        $ns_tax = NsIncomeTax::findOrFail($id);
        $user = User::findOrFail($ns_tax->user_id); 
        $tax_ranges = TaxRange::orderBy("id","asc")->get();    
        return view('taxmanagement.ns_tax_detail',compact('ns_tax','user','tax_ranges'));
    }
    public function monthlyTaxStatementDetailDownload($id)
    {
        $ns_tax = NsIncomeTax::findOrFail($id);
        $user = User::findOrFail($ns_tax->user_id); 
        $tax_ranges = TaxRange::orderBy("id","asc")->get();    
        $filename="Tax Calculation (NS)".Carbon::now()->format('d-m-Y');
            return Excel::download(new NSTaxDetailExport($ns_tax,$user,$tax_ranges), $filename.'.xlsx');
    }

    public function nsActualTaxPayment(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='ns_actual_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->nsActualTaxPayment($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $employees = User::select('id','name','employee_name')->where("check_ns_rs","=",1)->get();
               
        return view('taxmanagement.ns_tax_payment',compact('income_taxes','employees'));
    }

    public function nsActualTaxPaymentDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='ns_actual_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->nsActualTaxPayment($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Actual Tax Payment (NS)".Carbon::now()->format('d-m-Y');
        return Excel::download(new NSActualTaxPaymentExport($income_taxes,$from_date,$to_date,$employee), $filename.'.xlsx');
    }

    public function rsActualTaxPayment(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='rs_actual_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->rsActualTaxPayment($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $employees = User::select('id','name','employee_name')->where("check_ns_rs","=",0)->get();
               
        return view('taxmanagement.rs_tax_payment',compact('income_taxes','employees'));
    }

    public function rsActualTaxPaymentDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='rs_actual_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->rsActualTaxPayment($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Actual Tax Payment (JPN)".Carbon::now()->format('d-m-Y');
        return Excel::download(new RSActualTaxPaymentExport($income_taxes,$from_date,$to_date,$employee), $filename.'.xlsx');
    }

    public function rsTaxDetail($user_id,$date)
    {
        //$rs_tax = RsIncomeTax::findOrFail($id);
        $rs_tax = RsIncomeTax::where([["user_id","=",$user_id],["date","=",$date]])->first();
        if($rs_tax){
            $user = User::findOrFail($rs_tax->user_id); 
            $tax_ranges = TaxRange::orderBy("id","asc")->get();    
            return view('taxmanagement.rs_tax_detail',compact('rs_tax','user','tax_ranges',"user_id","date"));
        }
        return redirect()->back()->with("message","There is no tax calculation in ".Carbon::parse($date)->format("F Y"));
            
    }
    public function rsTaxDetailDownload($user_id,$date)
    {
        //$rs_tax = RsIncomeTax::findOrFail($id);
        $rs_tax = RsIncomeTax::where([["user_id","=",$user_id],["date","=",$date]])->first();
        if($rs_tax){
            $user = User::findOrFail($rs_tax->user_id); 
            $tax_ranges = TaxRange::orderBy("id","asc")->get();    
            $filename="Tax Calculation (JPN)".Carbon::now()->format('d-m-Y');
            return Excel::download(new RSTaxDetailExport($rs_tax,$user,$tax_ranges), $filename.'.xlsx');
        }
        return redirect()->back()->with("message","There is no tax calculation in ".Carbon::parse($date)->format("F Y"));
            
    }
    public function monthlyPaye(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='ns_income_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->monthlyPaye($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $employees = User::select('id','name','employee_name')->where("check_ns_rs","=",1)->get();
               
        return view('taxmanagement.monthly_paye',compact('income_taxes','employees'));
    }
    public function monthlyPayeDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('m/Y');
        if(!isset($request->name) || $request->name==null){
            $request->name='ns_income_taxes.user_id';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $income_taxes=$this->tax_gestion->monthlyPaye($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,     
            isset($request->to_date)?$request->to_date:$today,  
            $request->employee=='all'?null:$request->employee,$n);
        
        $from_date = isset($request->from_date)?$request->from_date:$today;  
        $to_date = isset($request->to_date)?$request->to_date:$today;
        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Monthly PAYE".Carbon::now()->format('d-m-Y');
        return Excel::download(new MonthlyPayeExport($income_taxes,$from_date,$to_date,$employee), $filename.'.xlsx');
    }

    public function exchangeRateDetail(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y-m');
        if(!isset($request->name) || $request->name==null){
            $request->name='exchange_rates.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $exchange_rates=$this->tax_gestion->exchangeRateDetail($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,$n);
    
               
        return view('taxmanagement.exchange_rate_detail',compact('exchange_rates'));
    }

    public function exchangeRateDetailDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y-m');
        if(!isset($request->name) || $request->name==null){
            $request->name='exchange_rates.date';
        }
        if(!isset($request->order) || $request->name==null){
            $request->order='asc';
        }
        $exchange_rates=$this->tax_gestion->exchangeRateDetail($request->name,
            $request->order,
            isset($request->from_date)?$request->from_date:$today,$n);
    
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $filename="Exchange Rate".Carbon::now()->format('d-m-Y');
        return Excel::download(new ExchangeRateDetailExport($exchange_rates,$from_date), $filename.'.xlsx');
    }

    public function exchangeRateSummary(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y');
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $exchange_rates=$this->tax_gestion->exchangeRateSummary($from_date);
    
        $first_year = $from_date;
        $last_year = $from_date+1;
        return view('taxmanagement.exchange_rate_summary',compact('exchange_rates','from_date','first_year','last_year'));
    }

    public function exchangeRateSummaryDownload(Request $request)
    {
        
        $today=Carbon::now()->format('Y');
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $exchange_rates=$this->tax_gestion->exchangeRateSummary($from_date);
    
        $first_year = $from_date;
        $last_year = $from_date+1;
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $filename="Exchange Rate Summary".Carbon::now()->format('d-m-Y');
        return Excel::download(new ExchangeRateSummaryExport($exchange_rates,$from_date,$first_year,$last_year), $filename.'.xlsx');
    }

    public function paidPersonal(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y');
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $taxes=$this->tax_gestion->paidPersonal($from_date);
    
        $first_year = $from_date;
        $last_year = $from_date+1;
        
        return view('taxmanagement.paid_personal',compact('taxes','from_date','first_year','last_year'));
    }
    public function paidPersonalDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y');
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $taxes=$this->tax_gestion->paidPersonal($from_date);
    
        $first_year = $from_date;
        $last_year = $from_date+1;
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $filename="Deducted & Paid Personal".Carbon::now()->format('d-m-Y');
        return Excel::download(new PaidPersonalExport($taxes,$from_date,$first_year,$last_year), $filename.'.xlsx');
        
    }

    public function taxOfficeSubmission(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y');
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $employee = $request->employee=='all'?null:$request->employee;
        $taxes=$this->tax_gestion->taxOfficeSubmission($from_date,$employee);
    
        $first_year = $from_date;
        $last_year = $from_date+1;
        //return $taxes;
        // foreach($taxes as $key=>$value){
        //     foreach($value as $index=>$v){
        //         echo $v["salary"]."<br>";
        //     }

        // }
        //return "ok";
        $employees = User::select("id","name","employee_name")->where("check_ns_rs","=",1)->get();
        return view('taxmanagement.tax_office_submission',compact('taxes','from_date','first_year','last_year','employees','employee'));
    }

    public function taxOfficeSubmissionDownload(Request $request)
    {
        $n=NUMBER_PER_PAGE;
        
        $today=Carbon::now()->format('Y');
        $from_date = isset($request->from_date)?$request->from_date:$today;
        $employee = $request->employee=='all'?null:$request->employee;
        $taxes=$this->tax_gestion->taxOfficeSubmission($from_date,$employee);
    
        $first_year = $from_date;
        $last_year = $from_date+1;

        $employee = $request->employee=='all'?'All':getUserFieldWithId($request->employee,'employee_name');

        $filename="Tax Office Submission".Carbon::now()->format('d-m-Y');
        return Excel::download(new TaxOfficeSubmissionExport($taxes,$from_date,$employee,$first_year,$last_year), $filename.'.xlsx');
        
        
    }

}
