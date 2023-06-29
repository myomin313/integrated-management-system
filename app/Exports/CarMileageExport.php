<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;

class CarMileageExport implements ShouldAutoSize,FromView 
{
    private $cars;
    public function __construct($cars=null){
        $this->cars = $cars;
    }
     public function view(): view
    {
        return view('carmanagement.report.kilometer_per_liter_export', [
            'cars' => $this->cars,
        ]);
    }
    

}


