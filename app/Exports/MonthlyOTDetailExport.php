<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonthlyOTDetailExport implements FromCollection,WithHeadings,ShouldAutoSize,WithColumnFormatting, WithTitle
{
    private $otsummaries,$date,$employee_type,$employee;
    public function __construct($otsummaries=null,$date=null,$employee=null,$employee_type=null){
        $this->otsummaries = $otsummaries;
        $this->date = $date;
        $this->employee_type = $employee_type;
        $this->employee = $employee;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $otsummaries = $this->otsummaries;

        $data = array();
        $date = $this->date;
        $i = 1;$net_total = 0;$overtime_net_total = 0;$total_taxi=0;$mmk_net_total=0;
        foreach($otsummaries as $user=>$otsummary){
          
            
            $ot_payment = $otsummary['ot_payment'];
            //monday to sat
            $monday_hour = isset($otsummary['monday_hour'])?$otsummary['monday_hour']:0;
            $monday_minute = isset($otsummary['monday_minute'])?$otsummary['monday_minute']:0;

            $monday_hour += floor($monday_minute / 60);
            $monday_minute = ($monday_minute -   floor($monday_minute / 60) * 60);
            $monday_time = str_pad($monday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($monday_minute, 2, '0', STR_PAD_LEFT);
            $monday_payment = getOTAmount($monday_time,$ot_payment);

            //public holiday
            $public_hour = isset($otsummary['public_hour'])?$otsummary['public_hour']:0;
            $public_minute = isset($otsummary['public_minute'])?$otsummary['public_minute']:0;

            $public_hour += floor($public_minute / 60);
            $public_minute = ($public_minute -   floor($public_minute / 60) * 60);
            $public_time = str_pad($public_hour, 2, '0', STR_PAD_LEFT).":".str_pad($public_minute, 2, '0', STR_PAD_LEFT);
            $public_payment = getOTAmount($public_time,$ot_payment);

            //sunday less
            $sunday_less_hour = isset($otsummary['sunday_less_hour'])?$otsummary['sunday_less_hour']:0;
            $sunday_less_minute = isset($otsummary['sunday_less_minute'])?$otsummary['sunday_less_minute']:0;

            $sunday_less_hour += floor($sunday_less_minute / 60);
            $sunday_less_minute = ($sunday_less_minute -   floor($sunday_less_minute / 60) * 60);
            $sunday_less_time = str_pad($sunday_less_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_less_minute, 2, '0', STR_PAD_LEFT);
            $sunday_less_payment = getOTAmount($sunday_less_time,$ot_payment);

            //sunday between
            $sunday_between_hour = isset($otsummary['sunday_between_hour'])?$otsummary['sunday_between_hour']:0;
            $sunday_between_minute = isset($otsummary['sunday_between_minute'])?$otsummary['sunday_between_minute']:0;
            $sunday_between_hour += floor($sunday_between_minute / 60);
            $sunday_between_minute = ($sunday_between_minute -   floor($sunday_between_minute / 60) * 60);
            $sunday_between_time = str_pad($sunday_between_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_between_minute, 2, '0', STR_PAD_LEFT);
            $sunday_between_payment = getOTAmount($sunday_between_time,$ot_payment);

            //total overtime allowance
            $overtime_total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment;
            $overtime_net_total += round_up_nodecimal($overtime_total);
            //all overtime allowance                
            $total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment + $otsummary['taxi_charge'];
            $net_total += round_up_nodecimal($total);

            $exchange_rate = getOTExchangeRate($user,$date);
            $mmk_amount = round_up_nodecimal($total) * $exchange_rate;

            $total_taxi += $otsummary['taxi_charge'];
            $mmk_net_total += $mmk_amount;
                        
            $data[]=[
	                $i,
	                $otsummary['name'],
	                $monday_time,
	                $public_time,
	                $sunday_less_time,
	                $sunday_between_time,
	                $otsummary['ot_payment'],
	                $monday_payment,
	                $public_payment,
	                $sunday_less_payment,
	                $sunday_between_payment,
                    round_up_nodecimal($overtime_total),
	                $otsummary['taxi_charge'],
	                round_up_nodecimal($total),
	                $exchange_rate?$exchange_rate:'',
                    $mmk_amount?$mmk_amount:'',
	                $otsummary['remark']
	             
	        ];
            
            $i += 1;
            
        }
        
        $data[]=[
	                '',
	                '',
	                '',
	                '',
	                '',
	                '',
	                '',
	                '',
	                '',
	                '',
	                'Total',
	                $overtime_net_total,
                    $total_taxi,
                    $net_total,
	                '',
                    $mmk_net_total,
	                ''
	                
	            ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
        [
            'Date :',
            $this->date,
            'Employee :',
            $this->employee,
            'Type :',
            $this->employee_type
        ],
        [],
        [
            'No',
            'Name',
            'Mon ~ Sat',
            'P/Holiday',
            'Sunday (<8:00 & >18:00)',
            'Sunday (8:00 ~ 18:00)',
            'OT Rate ($)',
            'Mon ~ Sat Amt ($)',
            'P/Holiday Amt ($)',
            'Sunday (<8:00 & >18:00) Amt ($)',
            'Sunday (8:00 ~ 18:00) Amt ($)',
            'Total Overtime Allowances ($)',
            'Taxi Charge ($)',
            'All Total Allowances ($)',
            'Exchange Rate (1USD=MMK)',
            'Transfer to MMK A/C (MMK)',
            'Remark'
        ]];
    }

    public function columnFormats(): array
    {
            return [
                'G' => NumberFormat::builtInFormatCode(39),
                'H' => NumberFormat::builtInFormatCode(39),
                'I' => NumberFormat::builtInFormatCode(39),
                'J' => NumberFormat::builtInFormatCode(39),
                'K' => NumberFormat::builtInFormatCode(39),
                'L' => NumberFormat::builtInFormatCode(3),
                'M' => NumberFormat::builtInFormatCode(3),
                'N' => NumberFormat::builtInFormatCode(3),
                'O' => NumberFormat::builtInFormatCode(3),
                'P' => NumberFormat::builtInFormatCode(3),
            ];
    }
    public function title(): string
    {
        return 'Monthly OT Summary';
    }
}
