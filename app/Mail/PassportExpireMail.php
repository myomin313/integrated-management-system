<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassportExpireMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employee_name;
    public $passport_date_of_expire;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee_name,$passport_date_of_expire)
    {
        $this->employee_name = $employee_name;
        $this->passport_date_of_expire = $passport_date_of_expire;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$employee_name=$this->employee_name,$passport_date_of_expire= $this->passport_date_of_expire,$admin_name = 'Marubeni Admin Team')->subject('Passport Information')
                    ->view('emails.passport_expire');
    }
}
