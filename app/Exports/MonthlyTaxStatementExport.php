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

class MonthlyTaxStatementExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $record,$from_date,$to_date,$employee;
    public function __construct($record=null,$from_date=null,$to_date=null,$employee=null){
        $this->record = $record;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $record = $this->record;

        $data = array();
        $i = 1;$total_salary = 0;$total_ot = 0;$total_bonus = 0;$total_leave = 0;$total_adjustment = 0; $total_income = 0; $total_tax = 0; $total_tax_round = 0;
        foreach($record as $key=>$value){
            
            $data[]=[
	                $i,
	                \Carbon\Carbon::parse($value->date)->format("F, Y"),
	                getUserFieldWithId($value->user_id,"employee_name"),
	                siteformat_number($value->salary_usd),
	                siteformat_number($value->ot_usd),
	                siteformat_number($value->bonus_usd),
	                siteformat_number($value->leave_usd),
	                siteformat_number($value->adjustment_usd),
	                siteformat_number($value->total_income_usd),
	                $value->estimated_percent?$value->estimated_percent:'',
	                $value->estimated_usd?siteformat_number($value->estimated_usd):'',
	                siteformat_number($value->estimated_income_tax),
	                siteformat_number($value->estimated_income_tax_round),
	                
	        ];

	        $total_salary += $value->salary_usd;
            $total_ot += $value->ot_usd;
            $total_bonus += $value->bonus_usd;
            $total_leave += $value->leave_usd;
            $total_adjustment += $value->adjustment_usd;
            $total_income += $value->total_income_usd;
            $total_tax += $value->estimated_income_tax;
            $total_tax_round += $value->estimated_income_tax_round;
            $i += 1;
	            
            
        }

        $data[]=[
	        '',
	        '',
	        'Total',
	        siteformat_number($total_salary),
	        siteformat_number($total_ot),
	        siteformat_number($total_bonus),
	        siteformat_number($total_leave),
	        siteformat_number($total_adjustment),
	        siteformat_number($total_income),
	        '',
	        '',
	        siteformat_number($total_tax),
	        siteformat_number($total_tax_round),
	                
	    ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
        [
            'From Date :',
            $this->from_date,
            'To Date :',
            $this->to_date,
            'Employee :',
            $this->employee
        ],
        [],
        [
            'No',
            'Month',
            'Name',
            'Salary',
            'OT',
            'Bonus',
            'W/O Pay',
            'Adjustment',
            'Total Income',
            'Tax (%)',
            'Tax',
            'Estd. Income Tax',
            'Estd. Income Tax'
        ],
        [
            '',
            '',
            '',
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            '',
            'USD',
            'USD',
            'USD'
        ]];
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
