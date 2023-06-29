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

class MonthlyOTSummaryExport implements FromCollection,WithHeadings,ShouldAutoSize,WithColumnFormatting, WithTitle
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
        $date = $this->date;
        $data = array();
        $i = 1;$net_total = 0;$overtime_net_total = 0;$total_taxi=0;$mmk_net_total=0;
        foreach($otsummaries as $user=>$otsummary){
            $ot_payment = $otsummary['ot_payment'];

            //weekday to sat
            $weekday_hour = isset($otsummary['weekday_hour'])?$otsummary['weekday_hour']:0;
            $weekday_minute = isset($otsummary['weekday_minute'])?$otsummary['weekday_minute']:0;

            $weekday_hour += floor($weekday_minute / 60);
            $weekday_minute = ($weekday_minute -   floor($weekday_minute / 60) * 60);
            $weekday_time = str_pad($weekday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($weekday_minute, 2, '0', STR_PAD_LEFT);
            $weekday_payment = getOTAmount($weekday_time,$ot_payment);

            //holiday to sat
            $holiday_hour = isset($otsummary['holiday_hour'])?$otsummary['holiday_hour']:0;
            $holiday_minute = isset($otsummary['holiday_minute'])?$otsummary['holiday_minute']:0;

            $holiday_hour += floor($holiday_minute / 60);
            $holiday_minute = ($holiday_minute -   floor($holiday_minute / 60) * 60);
            $holiday_time = str_pad($holiday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($holiday_minute, 2, '0', STR_PAD_LEFT);
            $holiday_payment = getOTAmount($holiday_time,$ot_payment);

            //all total allowance
            $overtime_total = $weekday_payment + $holiday_payment;
            $overtime_net_total += round_up_nodecimal($overtime_total);
            //all overtime allowance
            $total = $weekday_payment + $holiday_payment + $otsummary['taxi_charge'];

            $net_total += round_up_nodecimal($total);
            $exchange_rate = getOTExchangeRate($user,$date);
            $mmk_amount = round_up_nodecimal($total) * $exchange_rate;

            $total_taxi += $otsummary['taxi_charge'];
            $mmk_net_total += $mmk_amount;

            	$data[]=[
	                $i,
	                $otsummary['name'],
	                $weekday_time,
	                $holiday_time,
	                $otsummary['ot_payment'],
	                $weekday_payment,
	                $holiday_payment,
                    round_up_nodecimal($overtime_total),
	                $otsummary['taxi_charge'],
	                round_up_nodecimal($total),
                    $exchange_rate?$exchange_rate:'',
	                $mmk_amount?$mmk_amount:0,
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
            'Weekday',
            'Holiday',
            'OT Rate ($)',
            'Weekday Amt ($)',
            'Holiday Amt ($)',
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
                'E' => NumberFormat::builtInFormatCode(39),
                'F' => NumberFormat::builtInFormatCode(39),
                'G' => NumberFormat::builtInFormatCode(39),
                'H' => NumberFormat::builtInFormatCode(3),
                'I' => NumberFormat::builtInFormatCode(3),
                'J' => NumberFormat::builtInFormatCode(3),
                'K' => NumberFormat::builtInFormatCode(3),
                'L' => NumberFormat::builtInFormatCode(3),
            ];
    }
    public function title(): string
    {
        return 'Monthly OT Summary';
    }
}
