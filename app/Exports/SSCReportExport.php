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

class SSCReportExport implements ShouldAutoSize,WithColumnFormatting, WithTitle,FromView
{
    private $record,$from_date,$to_date,$employee,$branch;
    public function __construct($record=null,$from_date=null,$to_date=null,$employee=null,$branch=null){
        $this->record = $record;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->employee = $employee;
        $this->branch = $branch;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        
        return view('taxmanagement.ssc_export', [
                'sscs' => $this->record,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
                'employee'=>$this->employee,
                'branch'=>$this->branch
        ]);
        
            
    }
    public function columnFormats(): array
    {
            return [
                'F' => NumberFormat::builtInFormatCode(3),
                'G' => NumberFormat::builtInFormatCode(3),
                'H' => NumberFormat::builtInFormatCode(3),
                'I' => NumberFormat::builtInFormatCode(3),
                'J' => NumberFormat::builtInFormatCode(3),
                'K' => NumberFormat::builtInFormatCode(3),
                'L' => NumberFormat::builtInFormatCode(3),
            ];
    }
    public function title(): string
    {
        return 'SSC';
    }
}
