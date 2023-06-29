<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceptionistManagerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ot;
    public $change_by;
    public $applicant;
    public $type;
    public $emp_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ot,$change_by,$applicant,$type,$emp_type="staff")
    {
        $this->user = $user;
        $this->ot = $ot;
        $this->change_by = $change_by;
        $this->applicant = $applicant;
        $this->type = $type;
        $this->emp_type = $emp_type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Monthly Attendance Request')
                    ->view('emails.receptionist_manager_mail');
    }
}
