<?php

namespace App\Console\Commands;

use App\BirthdayCard;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendBirthdayReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_birthday_reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Birthday Reminder for HR before one day of birthday';

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
        $current_time = new \DateTime(now());
        $today_date = $current_time->format('Y-m-d');
        $next_date = Carbon::parse($today_date)->addDay(1)->format('Y-m-d');
        $birthdays = BirthdayCard::where(['birthday_date' => $next_date, 'status' => true])->with('user')->get();
        $all_hr = User::where(['role_id' => User::ROLE_HR, 'status' => 1])->get();
        if($birthdays->count()){
            foreach($birthdays as $birthday)
            {
                foreach($all_hr as $hr){
                    $details = [
                          'subject' => 'Attendance Reminder',
                          'email' => $hr->email,
                          'employee_name' => $birthday->user->first_name .' '. $birthday->user->last_name,
                          'hr_name' => $hr->first_name .' '.$hr->last_name,
                          'view'    =>  'mails.birthday_reminder_to_hr'
                    ];
                    dispatch(new \App\Jobs\SendBirthdayReminder($details));
                }
            }
        }
        $this->info('Reminder mail has been sent to HR');
        $birthdays = BirthdayCard::where(['birthday_date' => $today_date, 'status' => true])->with('user')->get();
        $all_employees = User::where('status',true)->select('id','first_name','email')->get();
        if($birthdays->count()){
            foreach($birthdays as $birthday)
            {
                foreach($all_employees as $employee){
                    if($employee->id == $birthday->user_id){
                        $details = [
                            'subject' => 'Attendance Reminder',
                            'email' => $employee->email,
                            'employee_name' => $birthday->user->first_name .' '. $birthday->user->last_name,
                            'hr_name' => $employee->first_name .' '.$employee->last_name,
                            'card' => $birthday->birthday_card,
                            'view'    =>  'mails.birthday_email_in_person'
                        ];
                    }else{
                        $details = [
                            'subject' => 'Attendance Reminder',
                            'email' => $employee->email,
                            'employee_name' => $birthday->user->first_name .' '. $birthday->user->last_name,
                            'hr_name' => $employee->first_name .' '.$employee->last_name,
                            'card' => $birthday->birthday_card,
                            'view'    =>  'mails.birthday_email_to_all'
                        ];
                    }
                    dispatch(new \App\Jobs\SendBirthdayReminder($details));

                }
            }
        }



    }
}
