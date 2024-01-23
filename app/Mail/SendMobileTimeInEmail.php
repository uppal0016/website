<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMobileTimeInEmail extends Mailable
{
    use Queueable, SerializesModels;
     protected $details;
     protected $downloadLink;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $downloadLink)
    {
        $this->details = $details;
        $this->downloadLink = $downloadLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $details = $this->details;
        // print_r($details);die;
        $downloadLink = $this->downloadLink;
        $address = 'info@talentelgia.in';
        $name = 'TalentOne';
        $subjectdata = 'Time in from mobile ('.date('Y-m-d').')';
        return $this->view('mails.time-in-from-mobile')
                    ->subject($subjectdata)
                    ->from($address, $name)
                    ->replyTo($address, $name)
                    ->with(["details"=> $details, "downloadLink" => $downloadLink]);
          
    }
}
