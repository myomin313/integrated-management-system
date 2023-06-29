<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;

class CarRepairAndMaintananceExport implements ShouldAutoSize,FromView
{
   
    private $km_for_maintenances,$month;
    public function __construct($km_for_maintenances=null,$month=null){
        $this->km_for_maintenances = $km_for_maintenances;
        $this->month = $month;
    }
    public function view():view {

     return view('carmanagement.report.car-repair-and-maintenance-report', [
            'km_for_maintenances' => $this->km_for_maintenances,
            'month' => $this->month,
        ]);
           
    }
    
    // public function collection()
    // {
    //      $km_for_maintenances = $this->km_for_maintenances;
    //       $data = array();
    //         $i = 1;
    //           foreach($km_for_maintenances as $km_for_maintenance){
    //         	$data[]=[
	//                 $i,
    //                 $km_for_maintenance->department_name,
    //                 $km_for_maintenance->main_user_name,
    //                 $km_for_maintenance->car_type,
    //                 $km_for_maintenance->car_number,
    //                 $km_for_maintenance->driver_name,
    //                 $km_for_maintenance->model_year,
    //                 $km_for_maintenance->parking,      
	//                 $km_for_maintenance->current_km - $km_for_maintenance->kilo_meter,
	//             ];
	//             $i += 1;
    //         }
    //      return collect($data);
    // }
    // public function headings(): array
    // {
    //     return [
     
    //     [
    //         'Detail Information',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         'Different Kilometer for periodic maintenance',
    //     ],
    //     [
    //         'No',
    //         'Dept',
    //         'Main User',
    //         'Car',
    //         'Car No',
    //         'Driver',
    //         'Model Year',
    //         'Car Parking',
    //         'Current Kilo - Maintenance Kilo',
    //     ]];
    // }
    // public function styles(Worksheet $sheet)
    // {
    //     return [
    //        1    => ['font' => ['bold' => true]],
    //        2    => ['font' => ['bold' => true]],
    //     ];
    // }
}
