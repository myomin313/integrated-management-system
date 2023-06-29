<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $ot;
    public $type;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$ot,$type)
    {
        $this->user = $user;
        $this->ot = $ot;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('OT Request')
                    ->view('emails.ot_request');
    }
}
