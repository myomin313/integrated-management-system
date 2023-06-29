<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CarFuelingExport implements ShouldAutoSize,FromView
{
    private $cars,$number_of_car,$driver_name,$date,$monthName,$year;
    public function __construct($cars=null,$number_of_car=null,$driver_name=null,$date=null,$monthName=null,$year=null){
        $this->cars = $cars;
        $this->number_of_car = $number_of_car;
        $this->driver_name = $driver_name;
        $this->date = $date;
        $this->monthName = $monthName;
        $this->year = $year;
        
    }

    public function view(): view
    {
        return view('carmanagement.report.fueling-report', [
            'cars' => $this->cars,
            'number_of_car' => $this->number_of_car,
            'driver_name' => $this->driver_name,
            'date' => $this->date,
            'monthName' => $this->monthName,
            'year' => $this->year,
        ]);
    }
}
