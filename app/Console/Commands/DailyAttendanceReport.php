<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Exports\UserAttendanceExport;
use App\Jobs\SendAttendanceEmailJob;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class DailyAttendanceReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:daily-attendance-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the previous day attendance report on every day morning to the admin and mgmt';

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
        $last_day_attendance = Attendance::where('created_at','=',Carbon::now()->subDay(1))->get();
        $employees_attendance = [];
        foreach($last_day_attendance as $atten){
            $status = 'Absent';
            if(!empty($atten->total_working_hour)){
                $status = 'Present';
            }else{
                if((Carbon::parse($atten->time_in)->format('Y-m-d')) == (Carbon::now()->format('Y-m-d'))){
                    $status = 'Working';
                }
            }
            $new_date = Carbon::parse($atten->created_at)->format('Y-m-d');
            // if($new_date != $old_date){
            //     $employees_attendance[] = [
            //         'Date' => '',
            //         'Time In' => '',
            //         'Time out' => '',
            //         'Total Working Hour' => '',
            //         'Status' => '',
            //     ];
            //     $old_date = $new_date;
            // }
            $employees_attendance[] = [
                'Date' => !empty($atten->time_in) ? Carbon::parse($atten->time_in)->format('Y-m-d') : '',
                'Time In' => !empty($atten->time_in) ? Carbon::parse($atten->time_in)->format('d-m-Y g:i A') : '',
                'Time out' => !empty($atten->time_out) ? Carbon::parse($atten->time_out)->format('d-m-Y g:i A') : '',
                'Total Working Hour' => $atten->total_working_hour,
                'Status' => $status,
            ];
        }
        $file_name = 'attendance-' . Carbon::now()->format('d-m-Y') . '.xlsx';
        $saved_file = Excel::store(new UserAttendanceExport($employees_attendance), $file_name, 'daily_attendance');
        //send email to admin, mgmt and HR(
        $users = User::whereIn('role_id',[User::ROLE_ADMIN, User::ROLE_MGMT, User::ROLE_HR])
                        ->where('status' , 1)
                        ->where('is_deleted' , 1)
                        ->select('email')->get();
        foreach($users as $user){
            $details = [
                "email"   =>  $user->email,
                "subject" =>  Carbon::now()->subDay(1)->format('d-m-Y').'_attendance_report',
                "view"    =>  'mails.send-attendance-report'
            ];
            dispatch(new SendAttendanceEmailJob($details));
        }
    }
}
