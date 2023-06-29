<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class RepairRecordByCarExport implements ShouldAutoSize,FromView
{
    private $repair_by_cars,$car_number,$max_current_km,$start_date,$end_date;
    public function __construct($repair_by_cars=null,$car_number=null,$max_current_km=null,
    $start_date=null,$end_date=null){
        $this->repair_by_cars = $repair_by_cars;
        $this->car_number     = $car_number;
        $this->max_current_km = $max_current_km;
        $this->start_date     = $start_date;
        $this->end_date       = $end_date;
    }
     public function view(): view
    {
        return view('carmanagement.report.repair_record_by_car', [
            'repair_by_cars' => $this->repair_by_cars,
            'car_number' => $this->car_number,
            'max_current_km' => $this->max_current_km,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
    }
}
