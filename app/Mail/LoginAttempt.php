<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginAttempt extends Mailable
{
    use Queueable, SerializesModels;

    public $ip;
    public $agent;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ip,$agent)
    {
        $this->ip = $ip;
        $this->agent = $agent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni Security Alert')->subject('Too Many Login Attempt')
                    ->view('emails.login_attempt');
    }
}
