<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApproveForDepManager extends Mailable
{
    use Queueable, SerializesModels;
    public $dep_person_name;
    public $name;
    public $from_date;
    public $to_date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dep_person_name,$name,$from_date,$to_date)
    {
        $this->dep_person_name = $dep_person_name;
        $this->name = $name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$name = $this->name,$from_date = $this->from_date,$to_date =$this->to_date,$dep_person_name =$this->dep_person_name)->subject('Leave Approve Information')
                    ->view('emails.leave-approve-for-dep');
    }
}
