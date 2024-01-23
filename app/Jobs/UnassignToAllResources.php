<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use Carbon\Carbon;
use App\ProjectAssigned;
use Log;


class UnassignToAllResources implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $projectId;
    protected $userId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($projectId, $userId)
    {
     
        $this->projectId = $projectId;
        $this->userId = $userId;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info($this->userId);
        \Log::info($this->projectId);
        $exist = ProjectAssigned::where('user_id',$this->userId)->where('project_id',$this->projectId)->first();
        if($exist){
            $exist->delete();
            
        }
    //  Mail::to( $this->leavedata['email'])->send(new sendAppliedLeave($this->leavedata));
    }
}
