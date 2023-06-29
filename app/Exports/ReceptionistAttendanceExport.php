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

class ReceptionistAttendanceExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize, WithEvents
{
    private $attendances,$from_date,$to_date,$employee,$branch,$holiday_row,$leave_row,$user_row,$staff_type;

    public function __construct($attendances=null,$from_date=null,$to_date=null,$employee=null,$branch=null,$staff_type=null){
        $this->attendances = $attendances;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
        $this->branch = $branch;
        $this->staff_type = $staff_type;
        $this->holiday_row = [];
        $this->leave_row = [];
        $this->user_row = [];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $attendances = $this->attendances;

        $data = array();

        $color_row = 3;
        foreach($attendances as $user=>$attendance){
            if(count($attendance)){
                $data[]=[
                    $user,
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
                $total_working_hour = 0;
                $color_row +=1;
                $this->user_row[] = $color_row;
                $user_id = 0;
                foreach($attendance as $key=>$value){
                    $color_row +=1;

                    $types = getAttendanceType();
                    
                    if($value['id']){
                        $att_date = new \Carbon\Carbon($value['date']);
                        if($value->device!="Leave"){
                            if(date('N', strtotime($value['date'])) == 6){
                                if(!isDriver($value->user_id) or !isQcStaff($value->user_id))
                                    $this->holiday_row[] = $color_row;
                            }
                            else if(date('N', strtotime($value['date'])) == 7 or getPublicHoliday($value['date']))
                                $this->holiday_row[] = $color_row;
                            else if($value->device=="Leave")
                                $this->leave_row[] = $color_row;

                            $working_hour = getWorkingHour($value->user_id,$value->date,$value->date,$value->id);

                            $total_working_hour += $working_hour;
                            $data[]=[
                                siteformat_date($value->date),
                                $value->device,
                                $types[$value->type.'_'.$value->type_id],
                                siteformat_time24($value->start_time),
                                $value->end_time?siteformat_time24($value->end_time):'',
                                convertTime($working_hour),
                                siteformat_time24($value->corrected_start_time).' - '.siteformat_time24($value->corrected_end_time),
                                $value->remark,
                                $value->change_request_date?siteformat_datetime($value->change_request_date):'',
                                $value->change_approve_date?siteformat_datetime($value->change_approve_date):'',
                                $value->normal_ot_hr?convertTime($value->normal_ot_hr):'',
                                $value->sat_ot_hr?convertTime($value->sat_ot_hr):'',
                                $value->sunday_ot_hr?convertTime($value->sunday_ot_hr):'',
                                $value->public_holiday_ot_hr?convertTime($value->public_holiday_ot_hr):'',
                                $value->ot_request_date?siteformat_datetime($value->ot_request_date):'',
                                $value->ot_approve_date?siteformat_datetime($value->ot_approve_date):''
                                
                            ];
                        }
                        else{
                            $this->leave_row[] = $color_row;
                            $working_hour = getWorkingHour($value->user_id,$value->date,$value->date,$value->id);
                              
                            $total_working_hour += $working_hour;

                            $data[]=[
                                siteformat_date($value['date']),
                                $types[$value->type.'_'.$value->type_id],
                                "",
                                "",
                                "",
                                convertTime($working_hour)
                                
                            ];
                        }
                            
                    }
                    else{
                        
                        $att_date = new \Carbon\Carbon($value['date']);
                        if(date('N', strtotime($value['date'])) == 6){
                            $label = "SATURDAY";
                            $this->holiday_row[] = $color_row;
                        }
                        else if(date('N', strtotime($value['date'])) == 7){
                            $label = "SUNDAY";
                            $this->holiday_row[] = $color_row;
                        }
                        else if(getPublicHoliday($value['date'])){
                            $label = getPublicHoliday($value['date']);
                            $this->holiday_row[] = $color_row;
                        }
                        else{
                            $label = "";
                            $this->leave_row[] = $color_row;
                        }
                        $data[]=[
                            siteformat_date($value['date']),
                            $label
                            
                        ];
                    }
                    
                    $i += 1;
                    if(!$user_id and isset($value->user_id))
                        $user_id=$value->user_id;
                }
                $data[]=[
                    '',
                    '',
                    '',
                    '',
                    'Total Working Hour',
                    convertTime($total_working_hour)
                ];
                $color_row += 1;

                $hourly_rate = getNSFieldWithId($user_id,"hourly_rate");
                $salary = $total_working_hour * $hourly_rate;
                $data[]=[
                    '',
                    '',
                    '',
                    '',
                    'Hourly Rate ($)',
                    $hourly_rate
                ];
                $color_row += 1;
                $data[]=[
                    '',
                    '',
                    '',
                    '',
                    'Total Payment ($)',
                    round_up_nodecimal($salary)
                ];
                $color_row += 1;
                $data[]=[''];
                $color_row += 1;
            }            
                
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
            $this->employee,
            'Branch :',
            $this->branch,
            'Staff Type :',
            $this->staff_type
        ],
        [],
        [
            'Date',
            'Device',
            'Type',
            'Arrival Time',
            'Leave Time',
            'Working Hour',
            'Corrected Working Hour',
            'Reason of Correction',
            'Apply',
            'Approval',
            'Normal OT Hr',
            'Sat OT Hr',
            'Sunday OT Hr',
            'Public Holiday OT Hr',
            'OT Apply',
            'OT Approval'
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
            AfterSheet::class=> function(AfterSheet $event) {
                foreach($this->holiday_row as $key=>$value){
                    $event->sheet->getDelegate()->getStyle('A'.$value.':O'.$value)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('BADEC8');
      
                }
                foreach($this->leave_row as $key=>$value){
                    $event->sheet->getDelegate()->getStyle('A'.$value.':O'.$value)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('F2DAAC');
      
                }
                foreach($this->user_row as $key=>$value){
                    $event->sheet->getDelegate()->getStyle('A'.$value.':O'.$value)
                            ->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB('686B69');
      
                }
                    
            },
        ];
    }
}
