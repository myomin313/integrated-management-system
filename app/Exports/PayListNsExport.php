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

class PayListNsExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $paylists,$from_date,$to_date,$employee;
    public function __construct($paylists=null,$from_date=null,$to_date=null,$employee=null){
        $this->paylists = $paylists;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $paylists = $this->paylists;

        $data = array();
        $salary = 0;$bonus = 0;$leave = 0;$kbz_opening = 0;$income_tax = 0;$ssc = 0;$overtime = 0;$other = 0;$total_usd = 0;$total_income_usd = 0;$first_salary_to_mmk = 0;$second_salary_to_mmk = 0;$other_in_mmk = 0;$transfer_to_mmk_acc = 0;$transfer_to_mmk_cash = 0;$transfer_to_usd_acc = 0;$transfer_to_usd_cash = 0;
        foreach($paylists as $key=>$value){
            $emp_name = getUserFieldWithId($value->user_id,"employee_name");

            $other_usd = $value->usd_allowance_usd - $value->usd_deduction_usd;

            $in_usd = $value->salary_usd + $value->bonus_usd - $value->leave_usd - $value->kbz_opening_usd - $value->estimated_tax_usd - $value->ssc_usd + $value->total_ot_payment_usd + $other_usd;

            $income_usd = $value->salary_usd + $value->bonus_usd - $value->kbz_opening_usd - $value->estimated_tax_usd - $value->ssc_usd + $value->total_ot_payment_usd;

            if($income_usd>400){
                $first_mmk = 400 * 2000;
                $second_mmk = ($income_usd - 400) * 1850;
            }
            else{
                $first_mmk = $in_usd * 2000;
                $second_mmk = 0;
            }

            $other_mmk = $value->mmk_allowance_mmk - $value->mmk_deduction_mmk;
    
            $data[]=[
                $key+1,
                $emp_name?$emp_name:getUserFieldWithId($value->user_id,"name"),
                $value->salary_usd,
                $value->bonus_usd,
                $value->leave_usd,
                $value->kbz_opening_usd,
                $value->estimated_tax_usd,
                $value->ssc_usd,
                $value->total_ot_payment_usd,
                $other_usd,
                $in_usd,
                $income_usd,
                $first_mmk,
                $second_mmk,
                $other_mmk,
                $value->transfer_mmk_acc,
                $value->transfer_mmk_cash,
                $value->transfer_usd_acc,
                $value->transfer_usd_cash,
                
            ];
            $salary += $value->salary_usd;
            $bonus += $value->bonus_usd;
            $leave += $value->leave_usd;
            $kbz_opening += $value->kbz_opening_usd;
            $income_tax += $value->estimated_tax_usd;
            $ssc += $value->ssc_usd;
            $overtime += $value->total_ot_payment_usd;
            $other += $other_usd;
            $total_usd += $in_usd;
            $total_income_usd += $in_usd;
            $first_salary_to_mmk += $first_mmk;
            $second_salary_to_mmk += $second_mmk;
            $other_in_mmk += $other_mmk;
            $transfer_to_mmk_acc += $value->transfer_mmk_acc;
            $transfer_to_mmk_cash += $value->transfer_mmk_cash;
            $transfer_to_usd_acc += $value->transfer_usd_acc;
            $transfer_to_usd_cash += $value->transfer_usd_cash;
        }
        $data[]=[
                '',
                'Total',
                $salary,
                $bonus,
                $leave,
                $kbz_opening,
                $income_tax,
                $ssc,
                $overtime,
                $other,
                $total_usd,
                $total_income_usd,
                $first_salary_to_mmk,
                $second_salary_to_mmk,
                $other_in_mmk,
                $transfer_to_mmk_acc,
                $transfer_to_mmk_cash,
                $transfer_to_usd_acc,
                $transfer_to_usd_cash,
                
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
            "Staff's Name",
            'Salary',
            'Bonus',
            'W/O paid Leave',
            'KBZ A/C Opening',
            'Income Tax',
            'Overtime',
            'Other',
            '(in USD)',
            '(in USD) not include others expense',
            'Salary to MMK A/C (@  --)',
            'Salary to MMK A/C (@  --)',
            'Other in MMK',
            'Total Transfer to MMK A/C',
            'Total Transfer to MMK Cash',
            'Total Transfer to USD A/C',
            'Total Transfer to USD Cash'
        ],
        [
            '',
            "",
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            'USD',
            'MMK',
            'MMK',
            'MMK',
            'MMK',
            'MMK',
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
        ];
    }
}
