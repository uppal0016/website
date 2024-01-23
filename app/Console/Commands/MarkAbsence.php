<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Attendance;
use DB;

class MarkAbsence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:mark-absence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
                        ->where('is_deleted' , 1)
                        ->whereNotExists( function ($query) use($today_date){
                            $query->from('attendance')
                            ->whereRaw('attendance.user_id = users.id')
                            ->where(DB::raw('DATE_FORMAT(time_in, "%Y-%m-%d")') , '=', $today_date);
                        })
                        ->get();
        if($users->count()){
            foreach($users as $user)
            {
                Attendance::create([
                    'user_id' => $user->id
                ]);
                
            }
        }
    }
}
