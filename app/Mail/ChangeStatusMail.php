<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangeStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $change_request;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$change_request)
    {
        $this->user = $user;
        $this->change_request = $change_request;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$mailaddress = env('MAIL_FROM_ADDRESS');
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Attendance Change Status')
                    ->view('emails.change_status');
    }
}
