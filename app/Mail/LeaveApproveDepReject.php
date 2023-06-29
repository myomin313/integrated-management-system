<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApproveDepReject extends Mailable
{
    use Queueable, SerializesModels;
    public $employee_name;
    public $from_date;
    public $to_date;
    public $status;
    public $department_name;
    public $approve_reason_by_dep_manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee_name,$from_date,$to_date,$status,$approve_reason_by_dep_manager,$department_name)
    {
        $this->employee_name = $employee_name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->status = $status;
        $this->approve_reason_by_dep_manager = $approve_reason_by_dep_manager;
        $this->department_name = $department_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$status = $this->status,$employee_name = $this->employee_name,$from_date = $this->from_date,$to_date =$this->to_date,$approve_reason_by_dep_manager=$this->approve_reason_by_dep_manager,$department_name=$this->department_name)->subject('Leave Approve Information')
                    ->view('emails.leave_dep_reject_approve');
    }
}
