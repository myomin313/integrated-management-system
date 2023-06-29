<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $monthlyot;
    public $change_by;
    public $type;
    public $emp_type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$monthlyot,$change_by,$type,$emp_type="staff")
    {
        $this->user = $user;
        $this->monthlyot = $monthlyot;
        $this->change_by = $change_by;
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
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Monthly OT Request')
                    ->view('emails.account_admin_mail');
    }
}
