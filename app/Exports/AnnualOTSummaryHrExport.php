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

class AnnualOTSummaryHrExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
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
            $april_total = array();
            $may_total = array();
            $june_total = array();
            $july_total = array();
            $auguest_total = array();
            $september_total = array();
            $october_total = array();
            $november_total = array();
            $december_total = array();
            $january_total = array();
            $february_total = array();
            $march_total = array();
            $all_total = array(); 
            $total = array(); 
            foreach($otsummary as $key=>$value){
                
            	$april = getMonthlyOTHour($value->user_id,$value->year."-04",true);
                $april_total[] = $april;

                $may = getMonthlyOTHour($value->user_id,$value->year."-05",true);
                $may_total[] = $may;

                $june = getMonthlyOTHour($value->user_id,$value->year."-06",true);
                $june_total[] = $june;

                $july = getMonthlyOTHour($value->user_id,$value->year."-07",true);
                $july_total[] = $july;

                $august = getMonthlyOTHour($value->user_id,$value->year."-08",true);
                $august_total[] = $august;

                $september = getMonthlyOTHour($value->user_id,$value->year."-09",true);
                $september_total[] = $september;

                $october = getMonthlyOTHour($value->user_id,$value->year."-10",true);
                $october_total[] = $october;

                $november = getMonthlyOTHour($value->user_id,$value->year."-11",true);
                $november_total[] = $november;

                $december = getMonthlyOTHour($value->user_id,$value->year."-12",true);
                $december_total[] = $december;

                $january = getMonthlyOTHour($value->user_id,($value->year+1)."-01",true);
                $january_total[] = $january;

                $february = getMonthlyOTHour($value->user_id,($value->year+1)."-02",true);
                $february_total[] = $february;

                $march = getMonthlyOTHour($value->user_id,($value->year+1)."-03",true);
                $march_total[] = $march;

                $total = sumTime([$april, $may , $june , $july , $august , $september, $october , $november , $december , $january , $february , $march]);

                $all_total[] = $total;

            	$data[]=[
	                $i,
	                getUserFieldWithId($value->user_id,'employee_name'),
	                getDepartmentField(getUserFieldWithId($value->user_id,'department_id'),'name'),
	                $april!="00:00"?$april:'',
	                $may!="00:00"?$may:'',
	                $june!="00:00"?$june:'',
	                $july!="00:00"?$july:'',
	                $august!="00:00"?$august:'',
	                $september!="00:00"?$september:'',
	                $october!="00:00"?$october:'',
	                $november!="00:00"?$november:'',
	                $december!="00:00"?$december:'',
	                $january!="00:00"?$january:'',
	                $february!="00:00"?$february:'',
	                $march!="00:00"?$march:'',
	                $total!="00:00"?$total:''
	                
	            ];
	            $i += 1;
            }
            $total_april = sumTime($april_total);
            $total_may = sumTime($may_total);
            $total_june = sumTime($june_total);
            $total_july = sumTime($july_total);
            $total_august = sumTime($august_total);
            $total_september = sumTime($september_total);
            $total_october = sumTime($october_total);
            $total_november = sumTime($november_total);
            $total_december = sumTime($december_total);
            $total_january = sumTime($january_total);
            $total_february = sumTime($february_total);
            $total_march = sumTime($march_total);

            $main_total = sumTime($all_total);

            $data[]=[
	                '',
	                '',
	                'Total',
	                $total_april!="00:00"?$total_april:'',
	                $total_may!="00:00"?$total_may:'',
	                $total_june!="00:00"?$total_june:'',
	                $total_july!="00:00"?$total_july:'',
	                $total_august!="00:00"?$total_august:'',
	                $total_september!="00:00"?$total_september:'',
	                $total_october!="00:00"?$total_october:'',
	                $total_november!="00:00"?$total_november:'',
	                $total_december!="00:00"?$total_december:'',
	                $total_january!="00:00"?$total_january:'',
	                $total_february!="00:00"?$total_february:'',
	                $total_march!="00:00"?$total_march:'',
	                $main_total!="00:00"?$main_total:''
	                
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
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec',
            'Jan',
            'Feb',
            'Mar',
            'Total'
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
