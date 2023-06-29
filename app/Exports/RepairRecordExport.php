<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


class RepairRecordExport implements ShouldAutoSize,FromView {
    
      private $cars,$maintenance_count,$tyre_count,$battery_count,$other_count;
    public function __construct($cars=null,$maintenance_count=null,$tyre_count=null,$battery_count=null,$other_count=null){
        $this->cars = $cars;
        $this->maintenance_count = $maintenance_count;
        $this->tyre_count = $tyre_count;
        $this->battery_count = $battery_count;
        $this->other_count = $other_count;
    }

    public function view(): view
    {
        return view('carmanagement.report.repair-report', [
            'cars' => $this->cars,
            'maintenance_count' => $this->maintenance_count,
            'tyre_count' => $this->tyre_count,
            'battery_count' => $this->battery_count,
            'other_count' => $this->other_count,
        ]);
    }
    

  
}
