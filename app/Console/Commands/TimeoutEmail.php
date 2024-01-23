<?php

namespace App\Console\Commands;

use App\Attendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserTimeoutExport;
use App\Jobs\SendTimeoutEmailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class TimeoutEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:send_timeout_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the time out email on every day evening to the admin, hr and mgmt';

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
        $today_attendance = Attendance::whereDate('created_at', Carbon::now()->subDay(1)->format('Y-m-d'))->get();
        $employees_attendance = [];
        $totalPresent = Attendance::whereNotNull('time_in')->whereNotNull('time_out')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->count();
        $totalEmployees = User::where('status',1)->where('is_deleted',0)->count();
        $totalAbsent = $totalEmployees - $totalPresent;
        $userIds = Attendance::select('user_id')->whereDate('created_at', Carbon::now()->format('Y-m-d'))->get();
        $absentEmployees = User::whereNotIn('id', $userIds)->where('status',1)->where('is_deleted',0)->get();
        
        //absent employees list
        $absent_employee = [];
        foreach($absentEmployees as $employee){
            $absent_employee[] = [
                'Name' =>  $employee->first_name.' '.$employee->last_name,
                'Employee Code' =>  $employee->employee_code,  
            ];
        }
        
        //time in employee list
        foreach($today_attendance as $attendence){
            $employees_attendance[] = [
                'Date' => !empty($attendence->time_in) ? Carbon::parse($attendence->time_in)->format('Y-m-d') : '',
                'Name' => !empty($attendence->time_in) ? $attendence->user_profile->first_name.' '.$attendence->user_profile->last_name : '',
                'Time In' => !empty($attendence->time_in) ? Carbon::parse($attendence->time_in)->format('d-m-Y g:i A') : '',
                'Time Out' => !empty($attendence->time_out) ? Carbon::parse($attendence->time_out)->format('d-m-Y g:i A') : '',
                'Total Working Hour' => $attendence->total_working_hour,
            ];
        }

        $file_name = 'timeout-' . Carbon::now()->format('d-m-Y') . '.xlsx';
        $saved_file = Excel::store(new UserTimeoutExport($employees_attendance), $file_name, 'public_uploads');
        $downloadLink = env('APP_URL').'/timein/'.$file_name;

        //send email to admin, mgmt and HR
        $users = User::whereIn('role_id',[User::ROLE_ADMIN, User::ROLE_MGMT, User::ROLE_HR, User::ROLE_PROJECT_MANAGER])
                        ->where('status' , 1)
                        ->where('is_deleted' , 0)
                        ->select('email')->get();

        foreach($users as $user){
            $details = [
                "email"   => $user->email,
                "subject" => Carbon::now()->format('d-m-Y').' Time-out Report',
                "view"    => 'mails.send-timeout-report',
                'absent' => $absent_employee,
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'downloadLink' =>$downloadLink
            ];
            
            dispatch(new SendTimeoutEmailJob($details));
            Log::info("sent time out report to ".$user->email);
        }
    }    
}
