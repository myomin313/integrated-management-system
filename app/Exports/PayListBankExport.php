<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PayListBankExport implements ShouldAutoSize,WithColumnFormatting, WithTitle,FromView
{
    private $paylists,$from_date,$to_date,$employee;
    public function __construct($paylists=null,$from_date=null,$to_date=null,$employee=null){
        $this->paylists = $paylists;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        
        return view('salarymanagement.salary.pay_list_bank_export', [
                'paylists' => $this->paylists,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'employee'=>$this->employee
        ]);
        
            
    }
    public function columnFormats(): array
    {
            return [
                
                'D' => NumberFormat::builtInFormatCode(1),
                'E' => NumberFormat::builtInFormatCode(39),
            ];
    }
    public function title(): string
    {
        return 'Pay List (Bank)';
    }
}
