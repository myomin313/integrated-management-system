<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Carbon\Carbon;

class ExchangeRateDetailExport implements ShouldAutoSize,FromView
{
    private $record,$from_date;
    public function __construct($record=null,$from_date=null){
        $this->record = $record;
        $this->from_date = $from_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        
        return view('taxmanagement.export_exchange_rate', [
                'exchange_rates' => $this->record,
                'from_date'=>$this->from_date
        ]);
        
            
    }
}
