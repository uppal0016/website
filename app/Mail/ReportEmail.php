<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
       $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $data = $this->data;
        $address = 'info@talentelgia.in';
        $subject = 'Weekly Report - '.$data['sender']['first_name'].' '.$data['sender']['last_name'];
        $name = 'TalentOne';

        $details = $this->data;
        $address = 'info@talentelgia.in';
        $name = 'TalentOne';
        return $this->view('mails.report_mail')
                    ->subject($subject)
                    ->from($address, $name)
                    ->replyTo($address, $name)
                    ->with([ 
                    	'sender' => $data['sender'],
                    	'receiver' => $data['receiver'],
                    	'report_details' => $data['report_details'],
                    ]);
    }
}

