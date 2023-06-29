<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class NSPaySlipDetailExport implements ShouldAutoSize,FromView,WithColumnFormatting
{
	const FORMAT_NUMBER_CUSTOM = '#,##0.00_;#,##0.00';
    private $salary,$user;
    public function __construct($salary=null,$user=null){
        $this->salary = $salary;
        $this->user = $user;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        
        return view('salarymanagement.salary.ns_payslip_export', [
                'salary' => $this->salary,
                'user'=>$this->user
        ]);
        
            
    }
    public function columnFormats(): array
    {
            return [
                'E' => NumberFormat::builtInFormatCode(39),
                'F' => NumberFormat::builtInFormatCode(39),
                'G' => NumberFormat::builtInFormatCode(39),
            ];
    }

    
}
