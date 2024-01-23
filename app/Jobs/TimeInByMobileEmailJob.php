<?php

namespace App\Jobs;

use App\Mail\SendMobileTimeInEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class TimeInByMobileEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;
    protected $downloadLink;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details, $downloadLink)
    {
        $this->details = $details;
        $this->downloadLink = $downloadLink;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $email = 'no records found';
        if(count($this->details)>0){
            for($i=0; $i<count($this->details); $i++){
                $email = new SendMobileTimeInEmail($this->details, $this->downloadLink);
            }
        } else {
            $email = new SendMobileTimeInEmail(null, $this->downloadLink);
        }
        
        Mail::to('amanpreet.kaur@talentelgia.in')->cc('amanpreet.kaur@talentelgia.in')->send($email);
        // Mail::to('pradeep.joshi@talentelgia.in')->cc('manish.chopra@talentelgia.in')->send($email);
    }
}
