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

class PayListRsExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
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
        $salary = 0;$kbz_opening = 0;$transfer_from = 0;$transfer_to = 0;$electricity = 0;$car = 0;$total_usd = 0;$total_mmk = 0;$other_usd = 0;$other_usd_mmk = 0;$other_mmk = 0;$transfer_to_mmk_acc = 0;$transfer_to_mmk_cash = 0;$transfer_to_usd_acc = 0;$transfer_to_usd_cash = 0;
        foreach($paylists as $key=>$value){
            $emp_name = getUserFieldWithId($value->user_id,"employee_name");

            $in_usd = $value->salary_usd + $value->kbz_opening_usd + $value->transfer_from_japan_usd + $value->transfer_to_japan_usd + $value->electricity_usd + $value->car_usd;

            $in_mmk = $value->salary_mmk + $value->kbz_opening_mmk + $value->transfer_from_japan_mmk + $value->transfer_to_japan_mmk + $value->electricity_mmk + $value->car_mmk;

            $data[]=[
                $key+1,
                $emp_name?$emp_name:getUserFieldWithId($value->user_id,"name"),
                $value->salary_usd,
                $value->kbz_opening_usd,
                $value->transfer_from_japan_usd,
                $value->transfer_to_japan_usd,
                $value->electricity_usd,
                $value->car_usd,
                $in_usd,
                $in_mmk,
                ($value->usd_allowance_usd - $value->usd_deduction_usd),
                ($value->usd_allowance_mmk - $value->usd_deduction_mmk),
                ($value->mmk_allowance_mmk - $value->mmk_deduction_mmk),
                $value->transfer_mmk_acc,
                $value->transfer_mmk_cash,
                $value->transfer_usd_acc,
                $value->transfer_usd_cash,
                
            ];
            $salary += $value->salary_usd;
            $kbz_opening += $value->kbz_opening_usd;
            $transfer_from += $value->transfer_from_japan_usd;
            $transfer_to += $value->transfer_to_japan_usd;
            $electricity += $value->electricity_usd;
            $car += $value->car_usd;
            $total_usd += $in_usd;
            $total_mmk += $in_mmk;
            $other_usd += ($value->usd_allowance_usd - $value->usd_deduction_usd);
            $other_usd_mmk += ($value->usd_allowance_mmk - $value->usd_deduction_mmk);
            $other_mmk += ($value->mmk_allowance_mmk - $value->mmk_deduction_mmk);
            $transfer_to_mmk_acc += $value->transfer_mmk_acc;
            $transfer_to_mmk_cash += $value->transfer_mmk_cash;
            $transfer_to_usd_acc += $value->transfer_usd_acc;
            $transfer_to_usd_cash += $value->transfer_usd_cash;
        }
        $data[]=[
                '',
                'Total',
                $salary,
                $kbz_opening,
                $transfer_from,
                $transfer_to,
                $electricity,
                $car,
                $total_usd,
                $total_mmk,
                $other_usd,
                $other_usd_mmk,
                $other_mmk,
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
            "Account Name",
            'Salary',
            'KBZ A/C Opening',
            'Salary Transfer from Japan',
            'Salary Transfer to Japan',
            'W/Electy charges',
            'Car Charges',
            'Total Net Salary',
            'Total Net Salary',
            'Others in USD',
            '',
            'Others in MMK',
            'Paid by MMK A/C',
            'Paid by MMK Cash',
            'Paid by USD A/C',
            'Paid by USD Cash'
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
            'MMK',
            'USD',
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
