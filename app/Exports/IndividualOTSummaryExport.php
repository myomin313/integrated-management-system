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

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class IndividualOTSummaryExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
{
    private $otdetails,$user_id,$date,$user;
    public function __construct($otdetails,$user_id,$date,$user){
        $this->otdetails = $otdetails;
        $this->user_id = $user_id;
        $this->date = $date;
        $this->user = $user;
        $this->user_name = $user->employee_name;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $otdetails = $this->otdetails;
        $user = $this->user;
        $date = $this->date;
        $user_id = $this->user_id;

        $data = array();
        $i = 1;$weekday_hour=0;$weekday_minute=0;$holiday_hour=0;$holiday_minute=0;$net_total = 0;$total_hr=0;$total_hour = 0;$total_minute = 0;$overtime_net_total = 0;
        foreach($otdetails as $key=>$value){
            if(isset($value->attendance_id))
                $type = "driver";
            else
                $type = "staff";
            if($value->end_from_time){
                $start_time = siteformat_time24($value->end_from_time);
                $end_time = siteformat_time24($value->end_to_time);
                $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->end_reason;
            }
            else{
                $start_time = siteformat_time24($value->start_from_time);
                $end_time = siteformat_time24($value->start_to_time);
                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->start_reason;
            }
            $ot_hour = getOTHour($value->id,$type);

            if($value->ot_type=="Weekday"){
                                
                $weekday = explode(":",convertTime($ot_hour));
                $weekday_hour += $weekday[0];
                $weekday_minute += $weekday[1];

                $total_hour += $weekday[0];
                $total_minute += $weekday[1];
            }
            else{
                                
                $holiday = explode(":",convertTime($ot_hour));
                $holiday_hour += $holiday[0];
                $holiday_minute += $holiday[1];

                $total_hour += $holiday[0];
                $total_minute += $holiday[1];
            }

            $start_hotel = $value->start_hotel?' (hotel)':'';
            $end_hotel = $value->end_hotel?'Yes':'';

            if($value->morning_taxi_time or $value->evening_taxi_time){
                $normal_taxi_time = '';
                $special_taxi_time = getTaxiTime($value->user_id,$value);
            }
            else{
                $normal_taxi_time = getTaxiTime($value->user_id,$value);
                $special_taxi_time = '';
            }

            $data[]=[
	            siteformat_date($value->apply_date),
	            $value->ot_type,
	            siteformat_time24($value->start_from_time).' - '.siteformat_time24_nextday($value->start_to_time,$value->start_next_day).$start_hotel,
	            siteformat_time24($value->end_from_time).' - '.siteformat_time24_nextday($value->end_to_time,$value->end_next_day),
	            $break_time,
	            convertTime($ot_hour),
                $end_hotel,
                $normal_taxi_time,
                $special_taxi_time,
	            $reason,
	            '',
	            'Yes',
	            'Yes',
	            'Yes'
	             
	        ];
	        $total_hr+=$ot_hour;
                        
        }

        $request_date = \Carbon\Carbon::parse($date)->format("01/m/Y");
        $ot_payment = getOTPayment($user_id,$request_date);

        $taxi_time = getTaxiCharge($user_id,$request_date,true);

        $taxi_charge = round_up($taxi_time * 3,2);

        
        $data[]=[''];
        $weekday_hour += floor($weekday_minute / 60);
        $weekday_minute = ($weekday_minute -   floor($weekday_minute / 60) * 60);
        $weekday_time = str_pad($weekday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($weekday_minute, 2, '0', STR_PAD_LEFT);
        $weekday_payment = getOTAmount($weekday_time,$ot_payment);

        $weekdaypayment = siteformat_number($weekday_payment)?siteformat_number($weekday_payment):$weekday_payment;

        $otpayment = siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment;
        $data[]=[
            '',
	        'Weekdays Overtime Allowance',
	        
            $weekday_time.' HRS x $'.$otpayment,
            
	        '$'.$weekdaypayment
	                
	    ];
        $holiday_hour += floor($holiday_minute / 60);
        $holiday_minute = ($holiday_minute -   floor($holiday_minute / 60) * 60);
        $holiday_time = str_pad($holiday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($holiday_minute, 2, '0', STR_PAD_LEFT);
        $holiday_payment = getOTAmount($holiday_time,$ot_payment);
        $net_total = $weekday_payment + $holiday_payment + $taxi_charge;

        $holidaypayment = siteformat_number($holiday_payment)?siteformat_number($holiday_payment):$holiday_payment;
        $data[]=[
            '',
            'Holidays Overtime Allowance',
            $holiday_time.' HRS x $'.$otpayment,
            
            '$'.$holidaypayment
                    
        ];

        $total_hour += floor($total_minute / 60);
        $total_minute = ($total_minute -   floor($total_minute / 60) * 60);
        $total_time = str_pad($total_hour, 2, '0', STR_PAD_LEFT).":".str_pad($total_minute, 2, '0', STR_PAD_LEFT);
        $data[]=[
            '',
            'Total Overtime Allowance',
            '',
            $total_time." HRS"
                    
        ];
        // $data[]=[
        //     '',
        //     '',
        //     '',
        //     'Total Overtime Allowance (Numerical Display)',
        //     '=',
        //     '',
        //     '',
        //     $total_hr
                    
        // ];
        
        $data[]=[
            '',
            'OT Rate',
            '',
            '$'.$otpayment
                    
        ];
        $overtime_net_total = $weekday_payment + $holiday_payment;
        $data[]=[
            '',
            'Total Overtime Allowances',
            '',
            '$'.number_format(round_up_nodecimal($overtime_net_total))
                    
        ];
        $data[]=[
            '',
            'Taxi Charge ('.getTaxiCharge($user_id,Carbon::parse($date)->format("1/m/Y"),true).' times)',
            '',
            '$'.siteformat_number(getTaxiCharge($user_id,Carbon::parse($date)->format("1/m/Y")))
                    
        ];
        $net_total = number_format(round_up_nodecimal($net_total));
        $data[]=[
            '',
            'All Total Allowances',
            '',
            '$'.$net_total
                    
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
        
        [
            
            $this->user_name,
            '',
            \Carbon\Carbon::parse($this->date)->format('F Y')
        ],
        [],
        [
            'Date',
            'OT Type',
            'Estimate OT',
            'Actual OT',
            'Break Time',
            'OT Hour',
            'Hotel',
            'Normal Taxi Times',
            'Special Taxi Times',
            'Reason',
            'Applicant Sign',
            'Dept Approval',
            'Account Approval',
            'Admin GM Approval'
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
   
                $event->sheet->getDelegate()->getStyle('D')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
   
            },
        ];
    }
}
