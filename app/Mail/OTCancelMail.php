<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTCancelMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ot;
    public $reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ot,$reason)
    {
        $this->user = $user;
        $this->ot = $ot;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('OT Cancel')
                    ->view('emails.ot_cancel');
    }
}
