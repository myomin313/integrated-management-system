<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthenticationInfo extends Mailable
{
    use Queueable, SerializesModels;
    public $name,$password,$key;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$password,$key)
    {
        $this->name = $name;
        $this->password = $password;
        $this->key = $key;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni Admin Team')->subject('Authentication Information')
                    ->view('emails.authentication_info');
    }
}
