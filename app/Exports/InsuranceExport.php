<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InsuranceExport implements ShouldAutoSize,FromView
{
      private $cars,$insurance_count;
    public function __construct($cars=null,$insurance_count=null){
        $this->cars = $cars;
        $this->insurance_count = $insurance_count;
    }

    public function view(): view
    {
        return view('carmanagement.report.insurance-report', [
            'cars' => $this->cars,
            'insurance_count' => $this->insurance_count,
        ]);
    }
}
