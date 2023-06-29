<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CarLicenseExport implements ShouldAutoSize,FromView
{   
    private $car_licenses;
    public function __construct($car_licenses=null){
        $this->car_licenses = $car_licenses;
    }
      public function view(): view
    {
        return view('carmanagement.report.license-report', [
            'car_licenses' => $this->car_licenses,
        ]);
    }
 
}
