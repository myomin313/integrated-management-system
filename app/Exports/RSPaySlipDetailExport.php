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

class RSPaySlipDetailExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $salary,$user;
    public function __construct($salary=null,$user=null){
        $this->salary = $salary;
        $this->user = $user;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $salary = $this->salary;
        $user = $this->user;

        $data = array();
        $data[] = [
        	getUserNameWithPrefix($user->id),
        	'',
        	'',
        	'',
        	'',
        	'on '.$salary->pay_for
        ];
        $data[] = [
        	'',
        	'',
        	'',
        	'',
        	'USD',
        	'MMK'
        ];
        $data[] = [
        	'Salary for '.$salary->pay_for,
        	'',
        	'',
        	'+',
        	siteformat_number($salary->salary_usd),
        	number_format($salary->salary_mmk)
        ];
        if($salary->transfer_from_japan_usd>0){
        	$data[] = [
	        	'Transfer Salary From Japan',
	        	'',
	        	'',
	        	'+',
	        	siteformat_number($salary->transfer_from_japan_usd),
	        	number_format($salary->transfer_from_japan_mmk)
	        ];
        }
        if($salary->transfer_to_japan_usd>0){
        	$data[] = [
	        	'Transfer Salary To Japan',
	        	'',
	        	'',
	        	'-',
	        	siteformat_number($salary->transfer_to_japan_usd),
	        	number_format($salary->transfer_to_japan_mmk)
	        ];
        }
        if($salary->electricity_usd>0){
        	$data[] = [
	        	'Public utility fees',
	        	'',
	        	'',
	        	'-',
	        	siteformat_number($salary->electricity_usd),
	        	number_format($salary->electricity_mmk)
	        ];
        }
        if($salary->car_usd>0){
        	$data[] = [
	        	'Car Charge',
	        	'',
	        	'',
	        	'-',
	        	siteformat_number($salary->car_usd),
	        	number_format($salary->car_mmk)
	        ];
        }
        if($salary->kbz_opening_usd>0){
        	$data[] = [
	        	'KBZ Opening A/C',
	        	'',
	        	'',
	        	'-',
	        	siteformat_number($salary->kbz_opening_usd),
	        	number_format($salary->kbz_opening_mmk)
	        ];
        }
        if($salary->usd_allowance_usd>0 or $salary->mmk_allowance_mmk>0){
        	$data[] = [
	        	'Other Allowance',
	        	'',
	        	'',
	        	'',
	        	'',
	        	''
	        ];
	        foreach($salary->other_allowance as $key=>$value){
	        	$data[] = [
		        	'',
		        	$value->name,
		        	'',
		        	strtoupper($value->currency),
		        	$value->currency=="usd"?siteformat_number($value->amount):'',
		        	$value->currency=="mmk"?number_format($value->amount):number_format($value->amount*$salary->payment_exchange_rate),
		        	''
		        ];
	        }
	        $data[] = [
	        	'Total Allowance',
	        	'',
	        	'',
	        	'+',
	        	siteformat_number($salary->usd_allowance_usd+$salary->mmk_allowance_usd),
	        	number_format($salary->usd_allowance_mmk+$salary->mmk_allowance_mmk)
	        ];
        }

        if($salary->usd_deduction_usd>0 or $salary->mmk_deduction_mmk>0){
        	$data[] = [
	        	'Other Deduction',
	        	'',
	        	'',
	        	'',
	        	'',
	        	''
	        ];
	        foreach($salary->other_deduction as $key=>$value){
	        	$data[] = [
		        	'',
		        	$value->name,
		        	'',
		        	strtoupper($value->currency),
		        	$value->currency=="usd"?siteformat_number($value->amount):'',
		        	$value->currency=="mmk"?number_format($value->amount):number_format($value->amount*$salary->payment_exchange_rate),
		        	''
		        ];
	        }
	        $data[] = [
	        	'Total Deduction',
	        	'',
	        	'',
	        	'-',
	        	siteformat_number($salary->usd_deduction_usd+$salary->mmk_deduction_usd),
	        	number_format($salary->usd_deduction_mmk+$salary->mmk_deduction_mmk)
	        ];
        }
        $data[] = [''];
        $data[] = [
        	'Salary and Other Payments (Total)',
        	'',
        	'',
        	'',
        	siteformat_number($salary->net_salary_usd),
        	number_format($salary->net_salary_mmk)
        ];
        $data[] = [
        	'Salary Transfer To USD A/C',
        	'',
        	'',
        	'',
        	'',
        	siteformat_number($salary->transfer_usd_acc)
        ];
        $data[] = [
        	'Salary Transfer To MMK A/C',
        	'',
        	'',
        	'',
        	'',
        	number_format($salary->transfer_mmk_acc)
        ];
        $data[] = [
        	'Salary Transfer To USD Cash',
        	'',
        	'',
        	'',
        	'',
        	siteformat_number($salary->transfer_usd_cash)
        ];
        $data[] = [
        	'Salary Transfer To MMK Cash',
        	'',
        	'',
        	'',
        	'',
        	number_format($salary->transfer_mmk_cash)
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
        
        []];
    }

    public function styles(Worksheet $sheet)
    {
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true]],
           3    => ['font' => ['bold' => true]],
        ];
    }
}
