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

class LateRecordExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $lates,$from_date,$to_date,$employee;
    public function __construct($lates=null,$from_date=null,$to_date=null,$employee=null){
        $this->lates = $lates;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $lates = $this->lates;

        $data = array();
        $user_id = [];
        foreach($lates as $department=>$late){
            if(count($late)){
                $data[]=[
                    $department,
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
                foreach($late as $key=>$value){
                    $total = $value->april + $value->may + $value->june + $value->july + $value->august + $value->september + $value->october + $value->november + $value->december + $value->january + $value->february + $value->march;
                    if(!in_array($value->user_id,$user_id) and $total>0){
                        $data[]=[
                            $i,
                            getUserFieldWithId($value->user_id,'employee_name'),
                            $value->april,
                            $value->may,
                            $value->june,
                            $value->july,
                            $value->august,
                            $value->september,
                            $value->october,
                            $value->november,
                            $value->december,
                            $value->january,
                            $value->february,
                            $value->march,
                            $total
                            
                        ];
                        $i += 1;
                    }

                    $user_id[] = $value->user_id;
                        
                }
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
            $this->employee
        ],
        [],
        [
            'No',
            'Name',
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
