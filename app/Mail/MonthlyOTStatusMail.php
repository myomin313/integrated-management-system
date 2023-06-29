<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MonthlyOTStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ot;
    public $approved_by;
    public $change_by;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ot,$approved_by,$change_by)
    {
        $this->user = $user;
        $this->ot = $ot;
        $this->approved_by = $approved_by;
        $this->change_by = $change_by;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Monthly OT Status')
                    ->view('emails.monthly_ot_status');
    }
}
