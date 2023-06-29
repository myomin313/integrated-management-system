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

class NSActualTaxPaymentExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
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
        $i = 1;$total_salary = 0;$total_tax_usd = 0;$total_tax_mmk = 0;
        foreach($record as $key=>$value){

            $salary = getNsSalary($value->user_id,$value->tax_period);
            $data[]=[
	                $i,
	                getUserFieldWithId($value->user_id,"employee_name"),
	                getNSFieldWithId($value->user_id,"nrc_no"),
	                getUserFieldWithId($value->user_id,"dob"),
	                siteformat_number($salary),
	                siteformat_number($value->tax_amount_usd),
	                siteformat_number($value->exchange_rate),
	                siteformat_number($value->tax_amount_mmk),
	                Carbon::parse($value->date)->format("F, Y").' National Staff'
	                
	        ];

	        $total_salary += $salary;
            $total_tax_usd += $value->tax_amount_usd;
            $total_tax_mmk += $value->tax_amount_mmk;
            $i += 1;
	            
            
        }

        $data[]=[
	        '',
	        '',
	        '',
	        'Total',
	        siteformat_number($total_salary),
	        siteformat_number($total_tax_usd),
	        '',
	        siteformat_number($total_tax_mmk),
	        ''        
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
            'Name',
            'N.R.C No.',
            'Date of Birth',
            'Salary (USD)',
            'Est .Income Tax (USD)',
            'Central Bank Ex Rate',
            'Income Tax Total (Kyats)',
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
