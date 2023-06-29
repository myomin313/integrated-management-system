<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RsExport implements ShouldAutoSize,FromView
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
    private $datas;
    private $users;
    private $families;
    private $rs_leave_data;
    private $rs_refresh_leaves;
    public function __construct($datas=null,$users=null,$families=null,$rs_leave_data=null,
    $rs_refresh_leaves=null){

        $this->datas = $datas;
        $this->users = $users;
        $this->families = $families;
        $this->rs_leave_data = $rs_leave_data;
        $this->rs_refresh_leaves = $rs_refresh_leaves;

    }

    public function view(): view
    {
        return view('employeemanagement.rs-report', [
            'datas' => $this->datas,
            'users' => $this->users,
            'families' => $this->families,
            'rs_leave_data' => $this->rs_leave_data,
            'rs_refresh_leaves' => $this->rs_refresh_leaves,
        ]);
    }
}
