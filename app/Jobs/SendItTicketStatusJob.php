<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Illuminate\Support\Facades\Auth;
class SendItTicketStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $ticket_id;
    protected $emails;
    protected $status;
    protected $user_name;
    public function __construct($emails,$status,$ticket_id,$user_name)
    {
        $this->emails = $emails;
        $this->status = $status;
        $this->ticket_id = $ticket_id;
        $this->user_name = $user_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ticket_id = $this->ticket_id;
        foreach ($this->emails as $key => $email) {
            try {
                Mail::send('mails.it_ticket_status_mail', [
                    'ticketStatus' =>  $this->status,
                    'userName' =>  $this->user_name,
                    'ticketNumber' =>  $ticket_id,
                ], function ($message) use ($email,  $ticket_id) {
                    $message->to($email);
                    $message->cc('mgmt@talentelgia.in');
                    $message->subject( $ticket_id. ' : ' .$this->status);
                });
            } catch (\Exception $e) {
            }
        }
    }
}
