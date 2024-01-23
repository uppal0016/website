<?php

namespace App\Console\Commands;

use App\Attendance;
use App\Exports\UserAttendanceExport;
use App\Jobs\TimeInByMobileEmailJob;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\SendMobileTimeInEmail;
use DB;
use Mail;


class TimeInByMobilePhone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:time-in-by-mobile-phone';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends those users details whose time in by mobile phone to the admin and mgmt';

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
        $today_attendance = Attendance::where('attendance.created_at','like',Carbon::now()->format('Y-m-d').'%')->join('users', 'users.id', '=', 'attendance.user_id')->where('users.status', 1)->where('users.work_mode', 'WFO')->whereIn('users.role_id', ['4','5'])->get();
        
        $users_time_in_by_mobile = [];
        $i = 0;
        if(isset($today_attendance)) {
            // echo '<pre>';
            // print_r($today_attendance);
            foreach($today_attendance as $key => $val) {
                // print_r($val->employee_code);die;
                $emp_code = explode('-', $val->employee_code);
                $bioMetricTimeIn = DB::table('biometric_data')->where('employee_code_id', '=', $emp_code[1])->where('check_out_time', '=', null)->where('created_at', 'like', Carbon::now()->format('Y-m-d').'%')->first();
                $attendance_time_in = Carbon::parse($val->time_in)->addHours(5)->addMinutes(30);
                //Carbon::parse($val->time_in)->subHours(5)->subMinutes(30)->toDateTimeString();
                
                $attendance_time_in = $val->time_in;
                if(isset($bioMetricTimeIn)){
                    if(strtotime($bioMetricTimeIn->check_in_time) > strtotime($attendance_time_in)) {
                        $users_time_in_by_mobile[$i]['user_id'] = $val->user_id;
                        $users_time_in_by_mobile[$i]['emp_code'] = $val->employee_code;
                        $users_time_in_by_mobile[$i]['name'] = $val->first_name.' '.$val->last_name;
                        $users_time_in_by_mobile[$i]['bio_time_in'] = Carbon::parse($bioMetricTimeIn->check_in_time)->format('H:i:s');
                        $users_time_in_by_mobile[$i]['time_in'] = Carbon::parse($attendance_time_in)->format('H:i:s');
                        $users_time_in_by_mobile[$i]['email'] = $val->email;
                        $i++;
                    } 
                }
               
            }
        }

        // $file_name = 'timeinMobile-' . Carbon::now()->format('d-m-Y') . '.xlsx';
        // $saved_file = Excel::store(new MobileTimeinExport($users_time_in_by_mobile), $file_name, 'public_uploads');
        $downloadLink = '';
       
        if(count($users_time_in_by_mobile)>0){
            for($i=0; $i<count($users_time_in_by_mobile); $i++){
                $email = new SendMobileTimeInEmail($users_time_in_by_mobile, $downloadLink);
            }
        } else {
            $email = new SendMobileTimeInEmail(null, $downloadLink);
        }
        
        Mail::to('pradeep.joshi@talentelgia.in')->cc('manish.chopra@talentelgia.in')->send($email);
    }
}
