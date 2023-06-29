<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NsExport implements ShouldAutoSize,FromView
{
    
    private $datas,$users,$families,$life_assurances,$education,$qualifications,$languages,$english_skills,$driver_licenses,
    $pc_skills,$employment_records,$oversea_records,$warnings,$evaluations;
    
    
    public function __construct($datas=null,$users=null,$families=null,$life_assurances=null,
    $education=null,$qualifications=null,$languages=null,$english_skills=null,
    $driver_licenses=null,$pc_skills=null,$employment_records=null,$oversea_records=null,
    $warnings=null,$evaluations=null){

        $this->datas = $datas;
        $this->users = $users;
        $this->families = $families;
        $this->life_assurances = $life_assurances;
        $this->education = $education;
        $this->qualifications = $qualifications;
        $this->languages  = $languages;
        $this->english_skills = $english_skills;
        $this->driver_licenses = $driver_licenses;
        $this->pc_skills = $pc_skills;
        $this->employment_records = $employment_records;
        $this->oversea_records = $oversea_records;
        $this->warnings = $warnings;
        $this->evaluations = $evaluations;

    }

    public function view(): View
    {
        return view('employeemanagement.ns-report', [
            'datas' => $this->datas,
            'users' => $this->users,
            'families' => $this->families,
            'life_assurances' => $this->life_assurances,
            'education' => $this->education,
            'qualifications' => $this->qualifications,
            'languages' => $this->languages,
            'english_skills' => $this->english_skills,
            'driver_licenses' => $this->driver_licenses,
            'pc_skills' => $this->pc_skills,
            'employment_records' => $this->employment_records,
            'oversea_records' => $this->oversea_records,
            'warnings' =>  $this->warnings,
            'evaluations' =>  $this->evaluations,
        ]);
    }
}
