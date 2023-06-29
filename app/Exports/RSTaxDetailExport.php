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

class RSTaxDetailExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $rs_tax,$user,$tax_ranges;
    public function __construct($rs_tax=null,$user=null,$tax_ranges=null){
        $this->rs_tax = $rs_tax;
        $this->user = $user;
        $this->tax_ranges = $tax_ranges;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $rs_tax = $this->rs_tax;
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
        	"Salary received in Japan"
        ];
        $data [] = [
        	"Month",
        	"Salary (JPY)",
        	"Transfer Salary From Myanmar (JPY)",
        	"Adjustments (JPY)",
        	"Income Tax Paid In Japan (JPY)",
        	"Bonus (JPY)",
        	"Basic Salary & Bonus (JPY)",
        	"Oversea Settlement Allowances (JPY)",
        	"DC Contribution (JPY)",
        	"Total Salary (JPY)",
        	"Total Salary (Kyats)",
        	"Exchange Rate (100Y =MMK)"
        ];
        $net_salary_mmk = 0; $net_salary_usd = 0;$net_salary_yen = 0;$net_salary_usd_mmk = 0;$net_salary_yen_mmk = 0; $total_salary = 0; $total_transfer_from = 0;$total_adjustment = 0;$total_tax_paid = 0;$total_bonus = 0;$total_salary_bonus = 0; $total_oversea = 0; $total_dc = 0;
        foreach($rs_tax->rs_jpy_detail as $key=>$value){
        	$salary_bonus = $value->salary_yen + $value->transfer_from_mm_yen + $value->adjustment_yen - $value->income_tax_jpy_yen + $value->bonus_yen;

        	$data [] = [
	        	$value->month."' ".$value->year,
	        	siteformat_number($value->salary_yen),
	        	siteformat_number($value->transfer_from_mm_yen),
	        	siteformat_number($value->adjustment_yen),
	        	'('.siteformat_number($value->income_tax_jpy_yen).')',
	        	siteformat_number($value->bonus_yen),
	        	siteformat_number($salary_bonus),
	        	siteformat_number($value->oversea_yen),
	        	siteformat_number($value->dc_yen),
	        	siteformat_number($value->total_salary_yen),
	        	siteformat_number($value->total_salary_mmk),
	        	siteformat_number($value->exchange_rate)
	        ];
	        $net_salary_yen += $value->total_salary_yen;
            $net_salary_yen_mmk += $value->total_salary_mmk;
            $total_salary += $value->salary_yen; $total_transfer_from += $value->transfer_from_mm_yen;
            $total_adjustment += $value->adjustment_yen;
            $total_tax_paid += $value->income_tax_jpy_yen;
            $total_bonus += $value->bonus_yen;
            $total_salary_bonus += $salary_bonus;
            $total_oversea += $value->oversea_yen;
            $total_dc += $value->dc_yen;
        }
        $data [] = [
        	"Total",
        	siteformat_number($total_salary),
        	siteformat_number($total_transfer_from),
        	siteformat_number($total_adjustment),
        	'('.siteformat_number($total_tax_paid).')',
        	siteformat_number($total_bonus),
        	siteformat_number($total_salary_bonus),
        	siteformat_number($total_oversea),
        	siteformat_number($total_dc),
        	siteformat_number($net_salary_yen),
        	siteformat_number($net_salary_yen_mmk),
        	""
        ];
        $data[] = [""];
        $data[] = ["Salary received in Myanmar"];
        $data[] = [
        	"Month",
        	"Salary (USD)",
        	"Transfer Salary to Japan (USD)",
        	"Bonus (USD)",
        	"Salary & Bonus (USD)",
        	"Oversea Settlement Allowances (USD)",
        	"DC Contribution (USD)",
        	"Total Salary (USD)",
        	"Total Salary (Kyats)",
        	"Exchange Rate (1USD =MMK)"
        ];
        $net_salary_usd = 0;$net_salary_usd_mmk = 0; $total_salary = 0; $total_transfer_to = 0;$total_bonus = 0;$total_salary_bonus = 0; $total_oversea = 0; $total_dc = 0;
        foreach($rs_tax->rs_mm_detail as $key=>$value){

        	$salary_bonus = $value->salary_usd + $value->transfer_to_jp_usd + $value->bonus_usd;

        	$data[] = [
	        	$value->month."' ".$value->year,
	        	siteformat_number($value->salary_usd),
	        	'('.siteformat_number($value->transfer_to_jp_usd).')',
	        	siteformat_number($value->bonus_usd),
	        	siteformat_number($salary_bonus),
	        	siteformat_number($value->oversea_usd),
	        	siteformat_number($value->dc_usd),
	        	siteformat_number($value->total_salary_usd),
	        	siteformat_number($value->total_salary_mmk),
	        	siteformat_number($value->exchange_rate)
	        ];

	        $net_salary_usd += $value->total_salary_usd;
            $net_salary_usd_mmk += $value->total_salary_mmk;
            $total_salary += $value->salary_usd; $total_transfer_to += $value->transfer_to_jp_usd;
            $total_bonus += $value->bonus_usd;
            $total_salary_bonus += $salary_bonus;
            $total_oversea += $value->oversea_usd;
            $total_dc += $value->dc_usd;
        }

        $data[] = [
        	"Total",
        	siteformat_number($total_salary),
        	'('.siteformat_number($total_transfer_to).')',
        	siteformat_number($total_bonus),
        	siteformat_number($total_salary_bonus),
        	siteformat_number($total_oversea),
        	siteformat_number($total_dc),
        	siteformat_number($net_salary_usd),
        	siteformat_number($net_salary_usd_mmk),
        	""
        ];
        $data[] = [""];
        $data[] = [
        	"",
        	"",
        	"Total (Kyats)"
        ];
        $data[] = [
        	"Total Salary (in Kyats) in Japan",
        	"=",
        	siteformat_number($net_salary_yen_mmk)
        ];
        $data[] = [
        	"Total Salary (in Kyats) in Myanmar",
        	"=",
        	siteformat_number($net_salary_usd_mmk)
        ];
        $ssc = 6000 * 12;
        $net_salary_mmk = $net_salary_yen_mmk + $net_salary_usd_mmk + $ssc;
        $data[] = [
        	"Total Social Security Contribution Fund",
        	"=",
        	siteformat_number($ssc)
        ];
        $data[] = [
        	"Total Assessable salary income",
        	"=",
        	siteformat_number($net_salary_mmk)
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
        	"(".siteformat_number($rs_tax->max_allowance).")"
        ];
        $data[] = [
        	"but maximum",
        	"",
        	"(Ks. 10,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($rs_tax->max_allowance).")"
        ];
        $data[] = [
        	"Parent (Jobless) Allowance",
        	"",
        	"(Ks. 1,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($rs_tax->parent_allowance).")"
        ];
        $data[] = [
        	"Spouse Allowance",
        	"",
        	"(Ks. 1,000,000)",
        	"",
        	"",
        	"",
        	"(".siteformat_number($rs_tax->spouse_allowance).")"
        ];
        $data[] = [
        	"Children Allowance",
        	"",
        	"(Ks. 500,000) per children",
        	"",
        	"",
        	$rs_tax->children_allowance/500000,
        	"(".siteformat_number($rs_tax->children_allowance).")"
        ];
        $tax_income = $net_salary_mmk - $rs_tax->max_allowance - $rs_tax->parent_allownce - $rs_tax->spouse_allownce - $rs_tax->children_allownce;

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
        	"salary head mentioned in Return of Income Form [IRD (I. T) - 1]",
        	"",
        	"",
        	"",
        	"",
        	"",
        	"(Kyats)"
        ];
        $year_tax = $one_year_tax / $rs_tax->tax_calculation_percent;
        $data[] = [
        	"Tax on tax Calculation",
        	"",
        	"",
        	siteformat_number($one_year_tax),
        	"/",
        	$rs_tax->tax_calculation_percent,
        	siteformat_number(round($year_tax,2))
        ];
        $data[] = [
        	"Estimated Total Tax for 1 Month",
        	"",
        	"",
        	"",
        	"",
        	"",
        	siteformat_number(round($year_tax/12))
        ];
        $data[] = [""];
        $data[] = [""];

        $tax_year = Carbon::parse($rs_tax->date)->format('Y');
        $first_year = $tax_year;
        $last_year = $tax_year;
        $tax_month = \Carbon\Carbon::parse($rs_tax->date)->format('F');
        if(strtolower($tax_month)=="january" or strtolower($tax_month)=="february" or strtolower($tax_month)=="march")
            $first_year = $tax_year - 1;
        else
            $last_year = $tax_year +1;

        $data[] = [
        	"Total Individual Income tax payable for April' ".$first_year."up to March' ".$last_year." (Kyats)",
        	"",
        	"=",
        	number_format($one_year_tax / $rs_tax->tax_calculation_percent,2),
        	"(USD)"
        ];
        $data[] = [
        	"Total Individual Income tax payable for April' ".$first_year."up to March' ".$last_year." (Kyats)",
        	"",
        	"=",
        	number_format($one_year_tax / $rs_tax->tax_calculation_percent,2),
        	"(USD)"
        ];
        $month_arr = ["04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December","01"=>"January","02"=>"February","03"=>"March"];
        $total_actual_tax = 0;

        foreach($month_arr as $key=>$value){
        	if($key=="01" or $key=="02" or $key=="03")
                $show_year = $last_year;
            else
                $show_year = $first_year;
            $actual_tax = getRSActualTax($rs_tax->user_id,\Carbon\Carbon::parse("$show_year-$key")->endOfMonth()->format("Y-m-d"));
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
	        	"Salary-tax already paid for ".$value."' ".$show_year." (Kyats)",
	        	"",
	        	"=",
	        	siteformat_number($mmk_tax),
	        	siteformat_number($usd_tax)
	        ];
        }
        $data[] = [];
        $remaining_tax = $year_tax - $total_actual_tax;
        $data[] = [
        	"Remaining Individual Income tax payable for April' ".$first_year."up to March' ".$last_year." (Kyats)",
        	"",
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
