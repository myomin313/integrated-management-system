<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarLicenseExpire extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $car_number;
    public $expire_date;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name,$car_number,$expire_date)
    {
        $this->name = $name;
        $this->car_number = $car_number;
        $this->expire_date = $expire_date;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($address = 'hrsystem@marubeniyangon.com.mm',$name=$this->name,$car_number=$this->car_number,$expire_date= $this->expire_date,$admin_name = 'Marubeni Admin Team')->subject('Car License Information')
                    ->view('emails.car_license_expire');
    }
}
