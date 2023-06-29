<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StayPermitExpireMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employee_name;
    public $stay_permit_expire_date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee_name,$stay_permit_expire_date)
    {
        $this->employee_name = $employee_name;
        $this->stay_permit_expire_date = $stay_permit_expire_date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$employee_name=$this->employee_name,$stay_permit_expire_date= $this->stay_permit_expire_date,$admin_name = 'Marubeni Admin Team')->subject('Stay Permit Information')
                    ->view('emails.stay_permit_expire');
    }
}
