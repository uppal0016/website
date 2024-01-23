<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class sendAppliedLeave extends Mailable
{
    use Queueable, SerializesModels;
    
    protected $leavedata;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($leavedata)
    {
        $this->leavedata = $leavedata;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->leavedata;
       
       $address = 'info@talentelgia.in';
        $name = 'TalentOne';
        $subjectdata = 'Applied Leave Status';
        return $this->view('mails.applied-leave-status')
                    ->subject($subjectdata)
                    ->from($address, $name)
                    ->replyTo($address, $name)
                    ->with("data",$data);
    }

       
}
