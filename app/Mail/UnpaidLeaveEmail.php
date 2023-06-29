<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnpaidLeaveEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $employee_name;
    public $from_date;
    public $to_date;
    public $leave_type_name;
    public $admin_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee_name,$from_date,$to_date,$leave_type_name,$admin_name)
    {
        $this->employee_name = $employee_name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->leave_type_name = $leave_type_name;
        $this->admin_name =$admin_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$employee_name = $this->employee_name,$from_date = $this->from_date,$to_date = $this->to_date,$leave_type_name =$this->leave_type_name,$admin_name =$this->$admin_name)->subject('Leave Approve Information')
                    ->view('emails.leave-approve-by-dep');
    }
}
