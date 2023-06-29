<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveApprove extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $from_date;
    public $to_date;
    public $status;
    public $approve_reason_by_GM;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$from_date,$to_date,$status,$approve_reason_by_GM)
    {
        $this->name = $name;
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->status = $status;
        $this->approve_reason_by_GM = $approve_reason_by_GM;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$status = $this->status,$name = $this->name,$from_date = $this->from_date,$to_date =$this->to_date,$approve_reason_by_GM=$this->approve_reason_by_GM)->subject('Leave Approve Information')
                    ->view('emails.leave_approve');
    }
}
