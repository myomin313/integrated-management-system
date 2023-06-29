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

class AnnualOTSummaryExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $otsummaries,$from_date,$to_date,$employee;
    public function __construct($otsummaries=null,$from_date=null,$to_date=null,$employee=null){
        $this->otsummaries = $otsummaries;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $otsummaries = $this->otsummaries;

        $data = array();
        foreach($otsummaries as $branch=>$otsummary){
                        
            $data[]=[
                getBranchField($branch,'name'),
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
                '',
                '',
                '',
                '',
                ''
                
            ];
            $i = 1;
            $april_total = 0;$may_total = 0;$june_total = 0;$july_total = 0;
            $auguest_total = 0;$september_total = 0;$october_total = 0;
            $november_total = 0;$december_total = 0;$january_total = 0;
            $february_total = 0;$march_total = 0;$all_total = 0; 
            foreach($otsummary as $key=>$value){
                $otpayment = getOTPayment($value->user_id,"01/04/".$value->year,true);
                $hour_min = explode(":",convertTime($value->april));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $april = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/05/".$value->year,true);
                $hour_min = explode(":",convertTime($value->may));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $may = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/06/".$value->year,true);
                $hour_min = explode(":",convertTime($value->june));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $june = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/07/".$value->year,true);
                $hour_min = explode(":",convertTime($value->july));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $july = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/08/".$value->year,true);
                $hour_min = explode(":",convertTime($value->august));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $august = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/09/".$value->year,true);
                $hour_min = explode(":",convertTime($value->september));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $september = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/10/".$value->year,true);
                $hour_min = explode(":",convertTime($value->october));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $october = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/11/".$value->year,true);
                $hour_min = explode(":",convertTime($value->november));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $november = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/12/".$value->year,true);
                $hour_min = explode(":",convertTime($value->december));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $december = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/01/".$value->year,true);
                $hour_min = explode(":",convertTime($value->january));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $january = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/02/".$value->year,true);
                $hour_min = explode(":",convertTime($value->february));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $february = round_up_nodecimal(getOTAmount($time,$otpayment));

                $otpayment = getOTPayment($value->user_id,"01/03/".$value->year,true);
                $hour_min = explode(":",convertTime($value->march));
                $hour = $hour_min[0];
                $minute = $hour_min[1];

                $hour += floor($minute / 60);
                $minute = ($minute -   floor($minute / 60) * 60);
                $time = $hour.":".str_pad($minute, 2, '0', STR_PAD_LEFT);
                $march = round_up_nodecimal(getOTAmount($time,$otpayment));

            	$total = $april + $may + $june + $july + $august + $september + $october + $november + $december + $january + $february + $march;

                $april_total += $april;
                $may_total += $may;
                $june_total += $june;
                $july_total += $july;
                $auguest_total += $august;
                $september_total += $september;
                $october_total += $october;
                $november_total += $november;
                $december_total += $december;
                $january_total += $january;
                $february_total += $february;
                $march_total += $march;
                $all_total += $total;

            	$data[]=[
	                $i,
	                getUserFieldWithId($value->user_id,'employee_name'),
	                getDepartmentField(getUserFieldWithId($value->user_id,'department_id'),'name'),
	                $april,
	                $may,
	                $june,
	                $july,
	                $august,
	                $september,
	                $october,
	                $november,
	                $december,
	                $january,
	                $february,
	                $march,
	                number_format($total)
	                
	            ];
	            $i += 1;
            }
            $data[]=[
	                '',
	                '',
	                'Total',
	                '$'.number_format($april_total),
	                '$'.number_format($may_total),
	                '$'.number_format($june_total),
	                '$'.number_format($july_total),
	                '$'.number_format($auguest_total),
	                '$'.number_format($september_total),
	                '$'.number_format($october_total),
	                '$'.number_format($november_total),
	                '$'.number_format($december_total),
	                '$'.number_format($january_total),
	                '$'.number_format($february_total),
	                '$'.number_format($march_total),
	                '$'.number_format($all_total)
	                
	            ];
        }

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
            'Department',
            'Apr ($)',
            'May ($)',
            'Jun ($)',
            'Jul ($)',
            'Aug ($)',
            'Sep ($)',
            'Oct ($)',
            'Nov ($)',
            'Dec ($)',
            'Jan ($)',
            'Feb ($)',
            'Mar ($)',
            'Total ($)'
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
