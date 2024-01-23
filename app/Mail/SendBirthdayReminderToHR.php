<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendBirthdayReminderToHR extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $details = $this->details;
        $address = 'info@talentelgia.in';
        $name = 'TalentOne';
        return $this->view($details['view'])
            ->subject($details['subject'])
            ->from($address, $name)
            ->replyTo($address, $name)
            ->with("details", $details);
    }
}
