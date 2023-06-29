<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApproveByDepManager extends Mailable
{
    use Queueable, SerializesModels;
    public $admin_name;
    public $name;
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
    public function __construct($admin_name,$name,$from_date,$to_date,$status,$department_name,$approve_reason_by_dep_manager)
    {
        $this->admin_name = $admin_name;
        $this->name = $name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->status = $status;
        $this->department_name =$department_name;
        $this->approve_reason_by_dep_manager=$approve_reason_by_dep_manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$status = $this->status,$name = $this->name,$from_date = $this->from_date,$to_date =$this->to_date,$admin_name =$this->admin_name,$department_name=$this->department_name,$approve_reason_by_dep_manager=$this->approve_reason_by_dep_manager)->subject('Leave Approve Information')
                    ->view('emails.leave-approve-by-dep');
    }
}
