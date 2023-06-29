<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LeaveExport implements ShouldAutoSize,FromView
{
    // private $departments;
    // public function __construct($departments=null){
    //     $this->departments = $departments;
    // }

    // public function view(): view
    // {
    //     return view('leavemanagement.report', [
    //         'departments' => $this->departments,
    //     ]);
    // }
    private $users,$casualleave,$earnedleave,$medicalleave,$maternityleave,
    $paternityleave,$longtermsickleave,$congratulatyleave,$condolenceleave,
    $departments,$employee_type;
    
    public function __construct($users=null,$casualleave=null,$earnedleave=null,$medicalleave=null,$maternityleave=null,
    $paternityleave=null,$longtermsickleave=null,$congratulatyleave=null,$condolenceleave=null,$departments=null,$employee_type=null){
        $this->users = $users;
        $this->casualleave = $casualleave;
        $this->earnedleave = $earnedleave;
        $this->medicalleave = $medicalleave;
        $this->maternityleave = $maternityleave;
        $this->paternityleave = $paternityleave;
        $this->longtermsickleave = $longtermsickleave;
        $this->congratulatyleave = $congratulatyleave;
        $this->condolenceleave = $condolenceleave;
        $this->departments = $departments;
        $this->employee_type = $employee_type;
    }

    public function view(): view
    {
        return view('leavemanagement.remain-report', [
            'users' => $this->users,
            'casualleave' => $this->casualleave,
            'earnedleave' => $this->earnedleave,
            'medicalleave' => $this->medicalleave,
            'maternityleave' => $this->maternityleave,
            'paternityleave' => $this->paternityleave,
            'longtermsickleave' => $this->longtermsickleave,
            'congratulatyleave' => $this->congratulatyleave,
            'condolenceleave' => $this->condolenceleave,
            'departments' => $this->departments,
            'employee_type' => $this->employee_type,
        ]);
    }
}
