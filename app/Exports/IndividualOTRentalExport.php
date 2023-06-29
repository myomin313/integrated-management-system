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
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class IndividualOTRentalExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize,WithEvents
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
        $i = 1;$monday_hour=0;$monday_minute=0;$public_hour=0;$public_minute=0;
        $sunday_less_hour=0;$sunday_less_minute=0;$sunday_between_hour=0;$sunday_between_minute=0;$net_total = 0;$overtime_net_total = 0;
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
                $next_day = $value->end_next_day;
            }
            else{
                $start_time = siteformat_time24($value->start_from_time);
                $end_time = siteformat_time24($value->start_to_time);
                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->start_reason;
                $next_day = $value->start_next_day;
            }
            $ot_hour = getOTHour($value->id,$type);

            if($value->ot_type=="Weekday" or $value->ot_type=="Saturday"){
                            
                $monday = explode(":",convertTime($ot_hour));
                $monday_hour += $monday[0];
                $monday_minute += $monday[1];
            }
            else if($value->ot_type=="Sunday"){
                if (strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and !$next_day){
                              
                    $sundayless = explode(":",convertTime($ot_hour));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
                     
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and $next_day){
                    $time = getTimeDiff($start_time,"8:00")+getTimeDiff("18:00","24:00") + getTimeDiff("00:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
            
                    $time = getTimeDiff("8:00","18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];

                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and !$next_day){
                    $time = getTimeDiff($start_time,"8:00");
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
                              
                    $time = getTimeDiff("8:00",$end_time);
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and $next_day){
                    $time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00");
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00",$end_time);
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
                                
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and !$next_day){
                    $time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff("8:00","18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and $next_day){
                    $time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00","18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          
                }
                        
                else if (strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                              
                    $sundayless = explode(":",convertTime($ot_hour));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                    $time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          

                    $time = getTimeDiff("8:00","18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
            

                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) < strtotime("8:00")){
                    $time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00",$end_time);
                              

                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          

                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("8:00") and strtotime($end_time) < strtotime("18:00")){
                    $time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00");
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff("8:00",$end_time);
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          
                }

                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and !$next_day){
                              
                    $sundaybetween = explode(":",convertTime($ot_hour));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
                  
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and $next_day){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00");
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff($start_time,"18:00") + getTimeDiff("8:00",$end_time);
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          

                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                    $time = getTimeDiff("18:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff($start_time,"18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00")+getTimeDiff("18:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
        
                    $time = getTimeDiff($start_time,"18:00")+getTimeDiff("8:00","18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <= strtotime("8:00")){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00",$end_time);
                              
                    $sundayless = explode(":",convertTime($time));
                    $sunday_less_hour += $sundayless[0];
                    $sunday_less_minute += $sundayless[1];
          
                    $time = getTimeDiff($start_time,"18:00");
                              
                    $sundaybetween = explode(":",convertTime($time));
                    $sunday_between_hour += $sundaybetween[0];
                    $sunday_between_minute += $sundaybetween[1];
          
                }
                
            }
            else{
                            
                $public = explode(":",convertTime($ot_hour));
                $public_hour += $public[0];
                $public_minute += $public[1];
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
                        
        }

        $request_date = \Carbon\Carbon::parse($date)->format("01/m/Y");
        $ot_payment = getOTPayment($user_id,$request_date);
        

        $taxi_time = getTaxiCharge($user_id,$request_date,true);

        $taxi_charge = round_up($taxi_time * 3,2);

        
        $data[]=[''];
        
        $monday_hour += floor($monday_minute / 60);
        $monday_minute = ($monday_minute -   floor($monday_minute / 60) * 60);
        $ot_time = str_pad($monday_hour, 2, '0', STR_PAD_LEFT).":".str_pad($monday_minute, 2, '0', STR_PAD_LEFT);
        $monday_payment = getOTAmount($ot_time,$ot_payment);
        $monday_payment = siteformat_number($monday_payment)?siteformat_number($monday_payment):$monday_payment;

        $otpayment = siteformat_number($ot_payment)?siteformat_number($ot_payment):$ot_payment;
        $mondaypayment = siteformat_number($monday_payment)?siteformat_number($monday_payment):$monday_payment;
        $data[]=[
            '',
            'Monday to Saturday Overtime Allowance',
            $ot_time.' HRS x $'.$otpayment,
            '$'.$mondaypayment
                    
        ];
        $public_hour += floor($public_minute / 60);
        $public_minute = ($public_minute -   floor($public_minute / 60) * 60);
        $ot_time = str_pad($public_hour, 2, '0', STR_PAD_LEFT).":".str_pad($public_minute, 2, '0', STR_PAD_LEFT);
        $public_payment = getOTAmount($ot_time,$ot_payment);

        $publicpayment = siteformat_number($public_payment)?siteformat_number($public_payment):$public_payment;
        $data[]=[
            '',
            'Public Holidays Overtime Allowance',
            $ot_time.' HRS x $'.$otpayment,
            '$'.$publicpayment
                    
        ];
        $sunday_less_hour += floor($sunday_less_minute / 60);
        $sunday_less_minute = ($sunday_less_minute -   floor($sunday_less_minute / 60) * 60);
        $ot_time = str_pad($sunday_less_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_less_minute, 2, '0', STR_PAD_LEFT);
        $sunday_less_payment = getOTAmount($ot_time,$ot_payment);

        $sundaylesspayment = siteformat_number($sunday_less_payment)?siteformat_number($sunday_less_payment):$sunday_less_payment;
        $data[]=[
            '',
            'Sunday Allowance (<8:00 am and >18:00 pm)',
            $ot_time.' HRS x $'.$otpayment,
            '$'.$sundaylesspayment
                    
        ];
        $sunday_between_hour += floor($sunday_between_minute / 60);
        $sunday_between_minute = ($sunday_between_minute -   floor($sunday_between_minute / 60) * 60);
        $ot_time = str_pad($sunday_between_hour, 2, '0', STR_PAD_LEFT).":".str_pad($sunday_between_minute, 2, '0', STR_PAD_LEFT);
        $sunday_between_payment = getOTAmount($ot_time,$ot_payment);
        $sundaybetweenpayment = siteformat_number($sunday_between_payment)?siteformat_number($sunday_between_payment):$sunday_between_payment;
        $data[]=[
            '',
            'Sunday Allowance (8:00 ~ 18:00)',
            $ot_time.' HRS x $'.$otpayment,
            '$'.$sundaybetweenpayment
                    
        ];
        $data[]=[
            '',
            'OT Rate',
            '',
            '$'.$ot_payment
                    
        ];
        $overtime_net_total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment;
        $data[]=[
            '',
            'Total Overtime Allowances',
            '',
            '$'.siteformat_number($overtime_net_total)
                    
        ];
        $data[]=[
            '',
            'Overtime Transportation Allowance (<6:30 am and >21:30 pm)',
            $taxi_time.' Times x $3',
            '$'.$taxi_charge
                    
        ];

        $net_total = $monday_payment + $public_payment + $sunday_less_payment + $sunday_between_payment + $taxi_charge;
        $data[]=[
            '',
            'All Total Allowances',
            '',
            '$'.siteformat_number($net_total)
                    
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
