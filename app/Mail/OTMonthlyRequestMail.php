<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTMonthlyRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $apply_date;
    public $applicant;
    public $emp_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$apply_date,$applicant,$emp_type="staff")
    {
        $this->user = $user;
        $this->apply_date = $apply_date;
        $this->applicant = $applicant;
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
        if($this->emp_type=="receptionist")
            return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Monthly Receptionist Attendance')
                    ->view('emails.monthly_ot_request');
        else
            return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Monthly OT Request')
                    ->view('emails.monthly_ot_request');
    }
}
