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

class TaxOfficeSubmissionExport implements FromCollection,WithHeadings,WithStyles,ShouldAutoSize
{
    private $record,$from_date,$employee,$last_year,$first_year;
    public function __construct($record=null,$from_date=null,$employee=null,$first_year,$last_year){
        $this->record = $record;
        $this->from_date = $from_date;
        $this->employee = $employee;
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
            "Sr. No.",
            "Name of Salary Earner ",
            "Designation / Occupation (Barnd Name)",
            "GIR NO.",
            "Annual Salary / Wages",
            "Overtime",
            "Other Disbursements (Bonus)",
            "Total [5+6+7]",
            "Sums Contributed to General Provident Fund (အိုနာစာ ရန်ပုံငွေ သို့ ထည့်ဝင်ငွေ )",
            "Life Insurance Premium Paid (အသက် အာမခံ ပရီမီယံ ပေးငွေ)",
            "Social Security Contribution Fund",
            "Other Saving recognized by the Govt",
            "Total Payment ( 9+10+11+12 )",
            "Whether the Wife or Husband earned taxable income within the year",
            "Numbers of Children",
            "Amount of Income Tax Deducted",
            "Remarks"
        ];
        $data[] = [
            "1",
            "2",
            "3",
            "4",
            "5",
            "6",
            "7",
            "8",
            "9",
            "10",
            "11",
            "12",
            "13",
            "14",
            "15",
            "16",
            "17"
        ];
        $data[] = [
            "",
            "",
            "",
            "",
            "(Kyats)",
            "(Kyats)",
            "(Kyats)",
            "(Kyats)",
            "(Kyats)",
            "(Kyats)",
            "(Kyats)",
            "",
            "(Kyats)",
            "(Kyats)",
            "",
            "(Kyats)",
            ""
        ];
        $i = 1;
        foreach($taxes as $user_id=>$tax){
            $j=0;$total_salary = 0;$total_ot = 0;$total_bonus = 0;$total_salaray_bonus = 0;$total_ssc = 0;$total_tax = 0;

            foreach($tax as $key=>$value){
                $salaray_bonus = $value["salary"] + $value["ot"] + $value["bonus"];
                if($j==0){
                    $data[] = [
                        $i,
                        getUserFieldWithId($user_id,"employee_name"),
                        getPositionName(getUserFieldWithId($user_id,"position_id")),
                        "",
                        siteformat_number($value["salary"]),
                        siteformat_number($value["ot"]),
                        siteformat_number($value["bonus"]),
                        siteformat_number($salaray_bonus),
                        0,
                        0,
                        siteformat_number($value["ssc"]),
                        0,
                        siteformat_number($value["ssc"]),
                        0,
                        getNumberOfChildren($user_id),
                        siteformat_number($value["tax"]),
                        $value["pay_for"]

                    ];
                }
                else{
                    $data[] = [
                        "",
                        "",
                        "",
                        "",
                        siteformat_number($value["salary"]),
                        siteformat_number($value["ot"]),
                        siteformat_number($value["bonus"]),
                        siteformat_number($salaray_bonus),
                        0,
                        0,
                        siteformat_number($value["ssc"]),
                        0,
                        siteformat_number($value["ssc"]),
                        0,
                        getNumberOfChildren($user_id),
                        siteformat_number($value["tax"]),
                        $value["pay_for"]

                    ];
                }

                $j += 1;
                $total_salary += $value["salary"];
                $total_ot += $value["ot"];
                $total_bonus += $value["bonus"];
                $total_salaray_bonus += $salaray_bonus;
                $total_ssc += $value["ssc"];
                $total_tax += $value["tax"];
            }

            $data[] = [
                "",
                "",
                "",
                "Total",
                siteformat_number($total_salary),
                siteformat_number($total_ot),
                siteformat_number($total_bonus),
                siteformat_number($total_salaray_bonus),
                0,
                0,
                siteformat_number($total_ssc),
                0,
                siteformat_number($total_ssc),
                0,
                getNumberOfChildren($user_id),
                siteformat_number($total_tax),
                "FY (".$first_year." - ".$last_year.")"

            ];

            $i += 1;
        }
        


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
           2    => ['font' => ['bold' => true]],
           3    => ['font' => ['bold' => true]],
        ];
    }
}
