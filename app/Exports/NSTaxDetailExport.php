<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Carbon\Carbon;

class NSTaxDetailExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $ns_tax,$user,$tax_ranges;
    public function __construct($ns_tax=null,$user=null,$tax_ranges=null){
        $this->ns_tax = $ns_tax;
        $this->user = $user;
        $this->tax_ranges = $tax_ranges;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ns_tax = $this->ns_tax;
        $user = $this->user;
        $tax_ranges = $this->tax_ranges;

        $data = array();
        $i = 1;$total_salary = 0;$total_tax_usd = 0;$total_tax_mmk = 0;
        
        $data [] = [
        	"Name : ",
        	$user->employee_name?$user->employee_name:$user->name
        ];
        $data [] = [
        	"Position : ",
        	getPositionName($user->position_id)
        ];
        $data [] = [
        	"Department : ",
        	getDepartmentField($user->department_id,'name')
        ];
        $data [] = [
        	""
        ];
        
        $data[] = [
        	"Month",
        	"Monthly Salary (USD)",
        	"Overtime Allowances (USD)",
        	"Social Security Contributioin (USD)",
        	"Bonus (USD)",
        	"Basic Salary & Bonus (USD)",
        	"Total Basic Salary (Kyats)",
        	"Exchange Rate (1USD=MMK)"
        ];
        $net_salary_mmk = 0; $net_salary_usd = 0; $total_salary = 0; $total_ot = 0;$total_ssc = 0;$total_bonus = 0;
        foreach($ns_tax->ns_detail as $key=>$value){

        	$data[] = [
                $value->month."' ".$value->year,
                siteformat_number($value->salary_usd),
                siteformat_number($value->ot_usd),
                "(".siteformat_number($value->ssc_usd).")",
                siteformat_number($value->bonus_usd),
                siteformat_number($value->total_salary_usd),
                siteformat_number($value->total_salary_mmk),
                siteformat_number($value->exchange_rate)
            ];

	        $total_salary += $value->salary_usd;
            $total_ot += $value->ot_usd;
            $total_ssc += $value->ssc_usd;
            $total_bonus += $value->bonus_usd;
            $net_salary_usd += $value->total_salary_usd;
            $net_salary_mmk += $value->total_salary_mmk;
        }

        $data[] = [
                "Total",
                siteformat_number($total_salary),
                siteformat_number($total_ot),
                "(".siteformat_number($total_ssc).")",
                siteformat_number($total_bonus),
                siteformat_number($net_salary_usd),
                siteformat_number($net_salary_mmk),
                ""
        ];
        $data[] = [""];
       
        $data[] = [""];
        $data[] = [
        	"Tax Calculation",
        	"",
        	"",
        	"",
        	"",
        	"",
        	"(Kyats)"
        ];
        $data[] = [
        	"Total Assessable salary income",
        	"",
        	"",
        	"",
        	"",
        	"",
        	siteformat_number($net_salary_mmk)
        ];
        $data[] = [
        	"Less"
        ];
        $basic_allowance = $net_salary_mmk * 20 / 100;
        $data[] = [
        	"Basic allowance - 20%  ",
        	"",
        	"",
        	"",
        	siteformat_number($basic_allowance),
        	"",
        	""
        ];
        $data[] = [
        	"but maximum",
        	"",
        	"(Ks. 10,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($ns_tax->max_allowance).")"
        ];
        $data[] = [
        	"but maximum",
        	"",
        	"(Ks. 10,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($ns_tax->max_allowance).")"
        ];
        $data[] = [
        	"Parent (Jobless) Allowance",
        	"",
        	"(Ks. 1,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($ns_tax->parent_allowance).")"
        ];
        $data[] = [
        	"Spouse Allowance",
        	"",
        	"(Ks. 1,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($ns_tax->spouse_allowance).")"
        ];
        $data[] = [
        	"Children Allowance",
        	"",
        	"(Ks. 500,000) per children",
        	"",
        	"",
        	$ns_tax->children_allowance/500000,
        	"(".siteformat_number($ns_tax->children_allowance).")"
        ];
        $data[] = [
            "Life Assured Annualised Premium",
            "",
            "",
            "",
            "",
            "",
            "(".siteformat_number($ns_tax->life_assured).")"
        ];
        $tax_income = $net_salary_mmk - $ns_tax->basic_max_allowance - $ns_tax->parent_allownce - $ns_tax->spouse_allownce - $ns_tax->children_allownce - $ns_tax->life_assured;

        $data[] = [
        	"Net taxable salary income ",
        	"",
        	"",
        	"",
        	"",
        	"",
        	siteformat_number($tax_income)
        ];
        $data[] = [""];

        $data[] = [
        	"From Kyat ",
        	"",
        	"To Kyat",
        	"(Difference)",
        	"(%)",
        	"Tax Amount (Kyats)",
        	""
        ];
        $one_year_tax = 0;$last_diff = $tax_income;$difference_show = 1;
        foreach($tax_ranges as $key=>$value){

        	if($tax_income >= $value->to_kyat){
                $different = ($value->to_kyat - $value->from_kyat + 1);
                $tax = ( ($value->to_kyat - $value->from_kyat + 1) * $value->percent ) / 100;
                $one_year_tax += $tax;
                $last_diff -= $different;
            }
            else if($tax_income >= $value->from_kyat){
                $different = ($tax_income - $value->from_kyat + 1);
                $tax = ( ($tax_income - $value->from_kyat + 1) * $value->percent ) / 100;
                $one_year_tax += $tax;
                $last_diff = 0;

            }
            else{
                $tax = 0;
                $last_diff = 0;
                $difference_show = 0;
            }

        	$data[] = [
	        	siteformat_number($value->from_kyat),
	        	"",
	        	$value->from_kyat==70000001?"Above":siteformat_number($value->to_kyat),
	        	$difference_show == 1?siteformat_number($different):'',
	        	$value->percent,
	        	siteformat_number($tax),
	        	$last_diff?siteformat_number($last_diff):''
	        ];
        }
        $data[] = [
        	"",
        	"",
        	"",
        	"",
        	"",
        	"",
        	siteformat_number($one_year_tax)
        ];
        $data[] = [""];
        $data[] = [
        	"Total Tax For 1 Year",
        	"(Ks.)",
        	"",
        	siteformat_number($one_year_tax),
        	"",
        	"",
        	""
        ];
        $data[] = [
            "Total Tax For 1 Month",
            "(Ks.)",
            "",
            number_format($one_year_tax/12,2),
            "",
            "",
            ""
        ];
        $data[] = [
            "Deducted Monthly Estimated Tax Rate",
            "",
            "",
            round(($one_year_tax/$tax_income) * 100)."%",
            "",
            "",
            ""
        ];
        
       
        $data[] = [""];
        $data[] = [""];

        $tax_year = Carbon::parse($ns_tax->date)->format('Y');
        $first_year = $tax_year;
        $last_year = $tax_year;
        $tax_month = \Carbon\Carbon::parse($ns_tax->date)->format('F');
        if(strtolower($tax_month)=="january" or strtolower($tax_month)=="february" or strtolower($tax_month)=="march")
            $first_year = $tax_year - 1;
        else
            $last_year = $tax_year +1;

        
        
        $month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];
        $total_actual_tax = 0;
        $total_actual_tax_usd = 0;

        foreach($month_arr as $key=>$value){
        	if($key=="01" or $key=="02" or $key=="03")
                $show_year = $last_year;
            else
                $show_year = $first_year;
            $actual_tax = getNSActualTax($ns_tax->user_id,\Carbon\Carbon::parse("$show_year-$key")->endOfMonth()->format("Y-m-d"));
            if($actual_tax){
                $mmk_tax = $actual_tax->tax_amount_mmk;
                $usd_tax = $actual_tax->tax_amount_usd;
            }
            else{
                $mmk_tax = 0;
                $usd_tax = 0;
            }
            $total_actual_tax += $mmk_tax;
        	$data[] = [
	        	"Tax Amount for ".$value."' ".$show_year." (Kyats)",
	        	"=",
	        	siteformat_number($mmk_tax),
	        	siteformat_number($usd_tax)
	        ];
        }
        $data[] = [""];
        $data[] = [
            "Total Tax Amount For FY April' ".$first_year."up to March' ".$last_year." (Kyats)",
            "=",
            siteformat_number($total_actual_tax),
            siteformat_number($total_actual_tax_usd)
        ];
        $remaining_tax = $one_year_tax - $total_actual_tax;
        $data[] = [
        	"Remaining Tax Amount For FY April' ".$first_year."up to March' ".$last_year." (Kyats)",
        	"=",
        	siteformat_number($remaining_tax),
        	""
        ];


        return collect($data);
    }

    public function headings(): array
    {
        return [
	        [],
	        []
    	];
    }

    public function styles(Worksheet $sheet)
    {
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true]],
           3    => ['font' => ['bold' => true]],
           4    => ['font' => ['bold' => true]],
        ];
    }
}
