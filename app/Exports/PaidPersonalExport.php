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

class PaidPersonalExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
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
        $taxes = $this->record;
        $from_date = $this->from_date;
        $first_year = $this->first_year;
        $last_year = $this->last_year;
        $data = array();

        $data[] = [
            "No",
            "Name",
            "Salary",
            "Band",
            "Total I-Tax for ".$first_year." - ".$last_year,
            "Total Tax Payable",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
            "Jan",
            "Feb",
            "Mar",
            "Total Deducted Estd Tax ".$first_year." - ".$last_year,
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "Tax Paid ".$first_year." - ".$last_year,
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "Total Tax  Paid ".$first_year." - ".$last_year,
            "",
            " Tax Balance for ".$first_year." - ".$last_year,
            " Tax Refund for ".$first_year." - ".$last_year,
            " Paid by Bank Exchange Rate"
        ];
        $data[] = [
            "",
            "",
            "USD",
            "",
            "MMK",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "MMK",
            "USD",
            "USD",
            "MMK"
        ];
        $i = 1;
        $total_year_income_tax = 0;
        $total_tax_payable = 0;
        $estimated_apr = 0;
        $estimated_may = 0;
        $estimated_jun = 0;
        $estimated_jul = 0;
        $estimated_aug = 0;
        $estimated_sep = 0;
        $estimated_oct = 0;
        $estimated_nov = 0;
        $estimated_dec = 0;
        $estimated_jan = 0;
        $estimated_feb = 0;
        $estimated_mar = 0;
        $actual_apr_usd = 0;
        $actual_may_usd = 0;
        $actual_jun_usd = 0;
        $actual_jul_usd = 0;
        $actual_aug_usd = 0;
        $actual_sep_usd = 0;
        $actual_oct_usd = 0;
        $actual_nov_usd = 0;
        $actual_dec_usd = 0;
        $actual_jan_usd = 0;
        $actual_feb_usd = 0;
        $actual_mar_usd = 0;
        $actual_apr_mmk = 0;
        $actual_may_mmk = 0;
        $actual_jun_mmk = 0;
        $actual_jul_mmk = 0;
        $actual_aug_mmk = 0;
        $actual_sep_mmk = 0;
        $actual_oct_mmk = 0;
        $actual_nov_mmk = 0;
        $actual_dec_mmk = 0;
        $actual_jan_mmk = 0;
        $actual_feb_mmk = 0;
        $actual_mar_mmk = 0;

        $total_deducted_tax = 0;
        $total_tax_paid_usd = 0;
        $total_tax_paid_mmk = 0;
        $total_tax_refund = 0;
        $total_paid_mmk = 0;
        foreach($taxes as $user_id=>$tax){
            $salary = \App\Helpers\TaxHelper::getTotalSalaryForNs($user_id,$from_date);
            $total_estimated_tax = $tax["estimated"]["usd"][0] + $tax["estimated"]["usd"][1] + $tax["estimated"]["usd"][2] + $tax["estimated"]["usd"][3] + $tax["estimated"]["usd"][4] + $tax["estimated"]["usd"][5] + $tax["estimated"]["usd"][6] + $tax["estimated"]["usd"][7] + $tax["estimated"]["usd"][8] + $tax["estimated"]["usd"][9] + $tax["estimated"]["usd"][10] + $tax["estimated"]["usd"][11];
            $total_actual_tax_usd = $tax["actual"]["usd"][0] + $tax["actual"]["usd"][1] + $tax["actual"]["usd"][2] + $tax["actual"]["usd"][3] + $tax["actual"]["usd"][4] + $tax["actual"]["usd"][5] + $tax["actual"]["usd"][6] + $tax["actual"]["usd"][7] + $tax["actual"]["usd"][8] + $tax["actual"]["usd"][9] + $tax["actual"]["usd"][10] + $tax["actual"]["usd"][11];
            $total_actual_tax_mmk = $tax["actual"]["mmk"][0] + $tax["actual"]["mmk"][1] + $tax["actual"]["mmk"][2] + $tax["actual"]["mmk"][3] + $tax["actual"]["mmk"][4] + $tax["actual"]["mmk"][5] + $tax["actual"]["mmk"][6] + $tax["actual"]["mmk"][7] + $tax["actual"]["mmk"][8] + $tax["actual"]["mmk"][9] + $tax["actual"]["mmk"][10] + $tax["actual"]["mmk"][11];

            $tax_balance = $total_estimated_tax - $total_actual_tax_usd;

            $total_tax_paid_usd += $total_actual_tax_usd;
            $total_tax_paid_mmk += $total_actual_tax_mmk;

            $total_tax_refund += $tax_balance;

            $data[] = [
                $i,
                getUserFieldWithId($user_id,"employee_name"),
                $salary,
                "",
                "",
                "",
                $tax["estimated"]["usd"][0],
                $tax["estimated"]["usd"][1],
                $tax["estimated"]["usd"][2],
                $tax["estimated"]["usd"][3],
                $tax["estimated"]["usd"][4],
                $tax["estimated"]["usd"][5],
                $tax["estimated"]["usd"][6],
                $tax["estimated"]["usd"][7],
                $tax["estimated"]["usd"][8],
                $tax["estimated"]["usd"][9],
                $tax["estimated"]["usd"][10],
                $tax["estimated"]["usd"][11],
                $total_estimated_tax,
                $tax["actual"]["usd"][0],
                $tax["actual"]["mmk"][0],
                $tax["actual"]["usd"][1],
                $tax["actual"]["mmk"][1],
                $tax["actual"]["usd"][2],
                $tax["actual"]["mmk"][2],
                $tax["actual"]["usd"][3],
                $tax["actual"]["mmk"][3],
                $tax["actual"]["usd"][4],
                $tax["actual"]["mmk"][4],
                $tax["actual"]["usd"][5],
                $tax["actual"]["mmk"][5],
                $tax["actual"]["usd"][6],
                $tax["actual"]["mmk"][6],
                $tax["actual"]["usd"][7],
                $tax["actual"]["mmk"][7],
                $tax["actual"]["usd"][8],
                $tax["actual"]["mmk"][8],
                $tax["actual"]["usd"][9],
                $tax["actual"]["mmk"][9],
                $tax["actual"]["usd"][10],
                $tax["actual"]["mmk"][10],
                $tax["actual"]["usd"][11],
                $tax["actual"]["mmk"][11],
                $total_actual_tax_usd,
                $total_actual_tax_mmk,
                $tax_balance,
                $tax_balance,
                ""
            ];
            $estimated_apr += $tax["estimated"]["usd"][0];
            $estimated_may += $tax["estimated"]["usd"][1];
            $estimated_jun += $tax["estimated"]["usd"][2];
            $estimated_jul += $tax["estimated"]["usd"][3];
            $estimated_aug += $tax["estimated"]["usd"][4];
            $estimated_sep += $tax["estimated"]["usd"][5];
            $estimated_oct += $tax["estimated"]["usd"][6];
            $estimated_nov += $tax["estimated"]["usd"][7];
            $estimated_dec += $tax["estimated"]["usd"][8];
            $estimated_jan += $tax["estimated"]["usd"][9];
            $estimated_feb += $tax["estimated"]["usd"][10];
            $estimated_mar += $tax["estimated"]["usd"][11];

            $actual_apr_usd += $tax["actual"]["usd"][0];
            $actual_apr_mmk += $tax["actual"]["mmk"][0];
            $actual_may_usd += $tax["actual"]["usd"][1];
            $actual_may_mmk += $tax["actual"]["mmk"][1];
            $actual_jun_usd += $tax["actual"]["usd"][2];
            $actual_jun_mmk += $tax["actual"]["mmk"][2];
            $actual_jul_usd += $tax["actual"]["usd"][3];
            $actual_jul_mmk += $tax["actual"]["mmk"][3];
            $actual_aug_usd += $tax["actual"]["usd"][4];
            $actual_aug_mmk += $tax["actual"]["mmk"][4];
            $actual_sep_usd += $tax["actual"]["usd"][5];
            $actual_sep_mmk += $tax["actual"]["mmk"][5];
            $actual_oct_usd += $tax["actual"]["usd"][6];
            $actual_oct_mmk += $tax["actual"]["mmk"][6];
            $actual_nov_usd += $tax["actual"]["usd"][7];
            $actual_nov_mmk += $tax["actual"]["mmk"][7];
            $actual_dec_usd += $tax["actual"]["usd"][8];
            $actual_dec_mmk += $tax["actual"]["mmk"][8];
            $actual_jan_usd += $tax["actual"]["usd"][9];
            $actual_jan_mmk += $tax["actual"]["mmk"][9];
            $actual_feb_usd += $tax["actual"]["usd"][10];
            $actual_feb_mmk += $tax["actual"]["mmk"][10];
            $actual_mar_usd += $tax["actual"]["usd"][11];
            $actual_mar_mmk += $tax["actual"]["mmk"][11];

            $total_deducted_tax += $total_estimated_tax;

            $i += 1;
        }

        $data[] = [
            "",
            "",
            "",
            "Total",
            $total_year_income_tax,
            $total_tax_payable,
            $estimated_apr,
            $estimated_may,
            $estimated_jun,
            $estimated_jul,
            $estimated_aug,
            $estimated_sep,
            $estimated_oct,
            $estimated_nov,
            $estimated_dec,
            $estimated_jan,
            $estimated_feb,
            $estimated_mar,
            $total_deducted_tax,
            $actual_apr_usd,
            $actual_apr_mmk,
            $actual_may_usd,
            $actual_may_mmk,
            $actual_jun_usd,
            $actual_jun_mmk,
            $actual_jul_usd,
            $actual_jul_mmk,
            $actual_aug_usd,
            $actual_aug_mmk,
            $actual_sep_usd,
            $actual_sep_mmk,
            $actual_oct_usd,
            $actual_oct_mmk,
            $actual_nov_usd,
            $actual_nov_mmk,
            $actual_dec_usd,
            $actual_dec_mmk,
            $actual_jan_usd,
            $actual_jan_mmk,
            $actual_feb_usd,
            $actual_feb_mmk,
            $actual_mar_usd,
            $actual_mar_mmk,
            $total_tax_paid_usd,
            $total_tax_paid_mmk,
            $total_tax_refund,
            $total_tax_refund,
            $total_paid_mmk
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
