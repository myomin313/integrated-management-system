<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveCancelApprove extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $from_date;
    public $to_date;
    public $status;
    public $cancel_leave_approve_reason_by_admi_manager;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$from_date,$to_date,$status,$cancel_leave_approve_reason_by_admi_manager)
    {
        $this->name = $name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->status = $status;
        $this->cancel_leave_approve_reason_by_admi_manager = $cancel_leave_approve_reason_by_admi_manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$status = $this->status,$name = $this->name,$from_date = $this->from_date,$to_date =$this->to_date,$cancel_leave_approve_reason_by_admi_manager=$this->cancel_leave_approve_reason_by_admi_manager)->subject('Leave Approve Information')
                    ->view('emails.leave_cancel_approve');
    }
}
