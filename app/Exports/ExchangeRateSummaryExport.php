<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Carbon\Carbon;

class ExchangeRateSummaryExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $record,$from_date,$last_year,$first_year;
    public function __construct($record=null,$from_date=null,$first_year,$last_year){
        $this->record = $record;
        $this->from_date = $from_date;
        $this->first_year = $first_year;
        $this->last_year = $last_year;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $exchange_rates = $this->record;
        $from_date = $this->from_date;
        $first_year = $this->first_year;
        $last_year = $this->last_year;
        $data = array();
        $exchange_date = Carbon::parse($from_date)->format("F' Y");
        $data[] = [
            "",
            "Exchange Rate - USD (FY ".$first_year." - ".$last_year,
            "",
            "",
            "",
            "Exchange Rate - YEN (FY ".$first_year." - ".$last_year,
            ""
        ];
        $data[] = [
            "No",
            "Month",
            "Average Exchange Rate (MMK per USD)",
            "",
            "No",
            "Month",
            "Average Exchange Rate (1JPY=100MMK)",
        ];
        $no_of_day = 0;
        $total_exchange_usd = 0;
        $total_exchange_yen = 0;
        $i = 1;
        foreach($exchange_rates as $key=>$value){
            

            $data[] = [
                $i,
                $key,
                siteformat_number($value['usd']),
                "",
                $i,
                $key,
                siteformat_number($value['yen']),
            ];

            $i += 1;
            if($value['usd']>0)
                $no_of_day += 1;
            $total_exchange_usd += $value['usd'];
            $total_exchange_yen += $value['yen'];

        }
        if($no_of_day>0){
            $average_usd = round($total_exchange_usd / $no_of_day);
            $average_yen = round($total_exchange_yen / $no_of_day);
        }
        else{
            $average_usd = 0;
            $average_yen = 0;
        }
            
        $data[] = [
            "Average",
            "",
            siteformat_number($average_usd),
            "",
            "Average",
            "",
            siteformat_number($average_yen)
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
        
        [""]];
    }

    public function styles(Worksheet $sheet)
    {
        
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true]],
           3    => ['font' => ['bold' => true]],
           4    => ['font' => ['bold' => true]],
        ];
    }
}
