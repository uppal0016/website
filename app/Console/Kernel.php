<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //$schedule->command('command:attendance-reminder')->everyMinute();
            //->dailyAt('4:15'); //utc time 4:30 when Asia/Kolkata time is 10:00Am
              /*$schedule->command('command:send_birthday_reminder')->everyMinute();*/
        $schedule->command('command:time-in-by-mobile-phone')->dailyAt('6:00'); // utc time 5:00 when Asia/Kolkata time is 10:30Am
        $schedule->command('command:attendance-reminder')->weekly()->days([1,2,3,4,5])->at('4:15');
        
        $schedule->command('command:mark-absence')
            ->dailyAt('6:25'); //utc time 6:25 when Asia/Kolkata time is 11:55PM'
        $schedule->command('command:daily-attendance-report')
            ->cron('* * * * *');
      
        $schedule->command('command:send_timein_email')->weekdays()->at('5:30'); //utc time 5:30 when Asia/Kolkata time is 11:00Am
        $schedule->command('command:send_timeout_email')->weekly()->days([2,3,4,5,6])->at('23:30'); //utc time 11:30 when Asia/Kolkata time is 5:00Am
        
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
