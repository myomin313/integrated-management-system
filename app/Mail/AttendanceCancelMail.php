<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceCancelMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $change_request;
    public $reason;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$change_request,$reason)
    {
        $this->user = $user;
        $this->change_request = $change_request;
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
        return $this->from($address = $mailaddress, $name = 'Marubeni HR System')->subject('Attendance Change Cancel')
                    ->view('emails.attendance_cancel');
    }
}
