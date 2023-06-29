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

class MonthlyPayeExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
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
        $total_income_salary = 0;$toal_claim_salary = 0;$total_tax = 0;
        foreach($record as $key=>$value){
            $emp_name = getUserFieldWithId($value->user_id,"employee_name");

            $total_salary = \App\Helpers\TaxHelper::getNsTotalSalary($value->id);

            $claim_amount = $value->basic_max_allowance + $value->parent_allowance + $value->spouse_allowance + $value->children_allowance + $value->life_assured;

            $actual_tax = getNSActualTax($value->user_id,\Carbon\Carbon::parse($value->date)->endOfMonth()->format("Y-m-d"));
            if($actual_tax){
                $mmk_tax = $actual_tax->tax_amount_mmk;
            }
            else{
                $mmk_tax = 0;
            }
            $total_income_salary += $total_salary;
            $toal_claim_salary += $claim_amount;
            $total_tax += $mmk_tax;

            $data[]=[
	                $key+1,
	                $emp_name?$emp_name:getUserFieldWithId($value->user_id,"employee_name"),
	                getNSFieldWithId($value->user_id,"nrc_no"),
	                getNSFieldWithId($value->user_id,"current_address"),
	                getUserFieldWithId($value->user_id,"passport_no"),
	                getUserFieldWithId($value->user_id,"position_id"),
	                Carbon::parse($value->date)->format("M"),
	                Carbon::parse($value->date)->format("Y"),
	                Carbon::parse($value->date)->endOfMonth()->format("d-m-Y"),
	                siteformat_number($total_salary),
	                siteformat_number($claim_amount),
	                siteformat_number($mmk_tax),
	                '',
	                
	        ];

            
        }

        $data[]=[
	                '',
	                '',
	                '',
	                "",
	                "",
	                "",
	                "",
	                "",
	                "Total",
	                siteformat_number($total_income_salary),
	                siteformat_number($toal_claim_salary),
	                siteformat_number($total_tax),
	                '',
	                
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
            'Employee Name',
            'NRC No (if)',
            'Employee Address',
            'Passport No (if)',
            'Position',
            'Tax Period',
            'Income Year',
            'End Date of Period covered by Payment (DD-MM-YYYY)',
            'Salary for Period (Kyat)',
            'Allowances claimed by Employee',
            'Amount Withheld (Kyat)',
            'Remark'
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
