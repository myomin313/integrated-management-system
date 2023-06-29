<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DateTime;
use DB;
use Carbon\Carbon;

class ApprovedListExport implements ShouldAutoSize,FromView
{
    private $user,$monthlyot,$monthlyotdetail,$staff_type;
    public function __construct($user=null,$monthlyot=null,$monthlyotdetail=null,$staff_type=null){
        $this->user = $user;
        $this->monthlyot = $monthlyot;
        $this->monthlyotdetail = $monthlyotdetail;
        $this->staff_type = $staff_type;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        if($this->staff_type=="staff"){
            return view('otmanagement.monthlyotrequest.export-ot', [
                'user' => $this->user,
                'monthlyot'=>$this->monthlyot,
                'monthlyotdetail'=>$this->monthlyotdetail
            ]);
        }
        else if($this->staff_type=="receptionist"){
            return view('otmanagement.monthlyreceptionist.export-ot', [
                'user' => $this->user,
                'monthlyot'=>$this->monthlyot,
                'monthlyotdetail'=>$this->monthlyotdetail
            ]);
        }
        else{
            return view('otmanagement.monthlyotdriver.export-ot', [
                'user' => $this->user,
                'monthlyot'=>$this->monthlyot,
                'monthlyotdetail'=>$this->monthlyotdetail
            ]);
        }
            
    }

}
