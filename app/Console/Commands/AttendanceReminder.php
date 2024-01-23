<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Notifications\EmployeeAttendanceReminder;
use DB;
use Illuminate\Support\Facades\Log;

class AttendanceReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:attendance-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "If Employee not mark the attendance then reminder send on the employee's register email";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        date_default_timezone_set("UTC");
        //%H:%i H:i
        $current_time = new \DateTime(now());
        $today_date = $current_time->format('Y-m-d');
        $users = User::where('status' , 1)
                    ->where('is_deleted' , 0)
                    ->whereNotExists( function ($query) use($today_date){
                        $query->from('attendance')
                        ->whereRaw('attendance.user_id = users.id')
                        ->where(DB::raw('DATE_FORMAT(time_in, "%Y-%m-%d")') , '=', $today_date);
                    })
                    ->get();
        Log::info("attendance reminder users");
        Log::info(json_encode($users));
        if($users->count()){
            foreach($users as $user)
            {
                //worked on the email part
                $user->notify(new EmployeeAttendanceReminder());
            }
        }
        Log::info('Reminder mail has been sent to users');
    }
    
}
