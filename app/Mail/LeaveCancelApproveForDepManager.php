<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveCancelApproveForDepManager extends Mailable
{
    use Queueable, SerializesModels;
    public $check_ns_rs;
    public $dep_person_name;
    public $name;
    public $from_date;
    public $to_date;
    public $leave_cancel_reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($check_ns_rs,$dep_person_name,$name,$from_date,$to_date,$leave_cancel_reason)
    {
        $this->check_ns_rs  = $check_ns_rs;
        $this->dep_person_name = $dep_person_name;
        $this->name = $name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->leave_cancel_reason = $leave_cancel_reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$check_ns_rs=$this->check_ns_rs,$name = $this->name,$from_date = $this->from_date,$to_date =$this->to_date,$dep_person_name =$this->dep_person_name,$leave_cancel_reason=$this->leave_cancel_reason)->subject('Leave Approve Information')
                    ->view('emails.leave-cancel-approve-for-dep');
    }
}
