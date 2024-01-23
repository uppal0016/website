<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\sendAppliedLeave;
use Mail;


class AppliedleaveStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $leavedata;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($leavedata)
    {
     
        $this->leavedata = $leavedata;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
     Mail::to( $this->leavedata['email'])->send(new sendAppliedLeave($this->leavedata));
    }
}
