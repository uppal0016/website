<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLateTimeIn extends Mailable
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
        // $cc = ['mgmt@talentelgia.in'];

        // $management_ids = ['27', '103'];
        // $reporting_manager_ids = $details['reporting_manager_id'];

        // if(!in_array($management_ids[0], $reporting_manager_ids) && !in_array($management_ids[1], $reporting_manager_ids)){
        //     array_push($cc, 'rohit.gupta@talentelgia.in', 'manish.chopra@talentelgia.in');
        // } else if(!in_array($management_ids[0], $reporting_manager_ids)) {
        //     array_push($cc, 'rohit.gupta@talentelgia.in');
        // } else if(!in_array($management_ids[1], $reporting_manager_ids)){
        //     array_push($cc, 'manish.chopra@talentelgia.in');
        // }
        
        $subjectdata = 'Late Time In ('.$details['name'].')';
        return $this->view($details['view'])
                    ->subject($subjectdata)
                    ->from($address, $name)
                    ->cc($details['cc'])
                    ->replyTo($address, $name)
                    ->with("details", $details);
    }
}
