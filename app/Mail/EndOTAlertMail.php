<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EndOTAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ot;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ot)
    {
        $this->user = $user;
        $this->ot = $ot;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Alert for End OT')
                    ->view('emails.end_ot');
    }
}
