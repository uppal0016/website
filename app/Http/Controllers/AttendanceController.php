<?php

namespace App\Http\Controllers;

use App\Dsr;
use App\Jobs\SendAttendanceEmailJob;
use App\UserLeave;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Attendance;
use Auth;
use DB;
use Mail;
use Illuminate\Support\Arr;
use App\Exports\AllEmployeeAttendanceExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserAttendanceExport;
use App\Exports\MobileTimeinExport;
use App\User;
use App\Notifications\MarkAttendanceNotification;
use App\Events\MarkAttendanceEvent;
use App\WeeklyReport;
use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use App\Jobs\SendLateTimeInJob;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Jobs\TimeInByMobileEmailJob;
use App\Mail\SendMobileTimeInEmail;

class AttendanceController extends Controller
{

   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->url= url(request()->route()->getPrefix());
        $this->prefix= 'attendance';
        $this->title= 'Attendance';
        $this->perPage= 10;
    }

    public function index(Request $request)
    {
       
        $search = trim($request->input('search'));
        $user_ids = null;
        if(!empty($search)){
            $users = \Illuminate\Support\Facades\DB::select("select id from `users` where CONCAT(users.first_name ,' ', users.last_name) LIKE '%$search%' and users.is_deleted = 0 ");
            $user_ids = [];
            foreach($users as $ids){
                $user_ids[] = $ids->id;
            }
        }
        if(!empty($request->input('daterange'))){
            $dateRange = explode('-', $request->input('daterange'));
            $starDate = str_replace("/","-",$dateRange[0]);
            $endDate = str_replace("/","-",$dateRange[1]);
            $start_date = Carbon::parse($starDate)->format('Y-m-d');
            $end_date = Carbon::parse($endDate)->format('Y-m-d');
            if($start_date != $end_date){
                $viewdate = $start_date.' to '.$end_date;
            } else {
                $viewdate = $start_date;
            }
        } else {
            
            $viewdate = date('Y-m-d');
        }
        // print_r($viewdate);die;

        $dates = $this->displayDates(date('Y-m-d'), date('Y-m-d'));
        if(empty($request->input('apply'))) {
            // if(!empty($request->input('daterange'))){
            //     $dateRange = explode('-', $request->input('daterange'));
            //     $starDate = str_replace("/","-",$dateRange[0]);
            //     $endDate = str_replace("/","-",$dateRange[1]);
            //     $start_date = Carbon::parse($starDate)->format('Y-m-d');
            //     $end_date = Carbon::parse($endDate)->format('Y-m-d');
            //     $dates = $this->displayDates($start_date, $end_date);
            // }
        }

        $workMode = $request->input('work_mode');
        $filtered_attendance = [];
        if (!empty($workMode)) {
            $users = User::where('work_mode', $workMode)->get();
            $userIds = $users->pluck('id')->toArray();
            $work_mode = Attendance::whereIn('user_id', $userIds)->get();

            foreach ($work_mode as $attendance) {
                $user = User::find($attendance->user_id);
                $attendance_data = [
                    'user_id' => $attendance->user_id,
                    'work_mode' => $user->work_mode,
                ];
                $filtered_attendance[] = $attendance_data;
            }
        }
        
        $full_data = array();
        foreach($dates as $date){
            $attendance = Attendance::select('attendance.*', 'users.work_mode', DB::raw('REPLACE(users.employee_code, "TLGT-", "") as employee_code'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))->with('user_profile')->addSelect(DB::raw("'$date' as date_range"))->with('biometric_data')->join('users', 'users.id', '=', 'attendance.user_id')->where('users.status', 1);
            // if somebody do time-in
            if($user_ids != null){
                $attendance->whereIn('attendance.user_id', $user_ids);
            }

            if($filtered_attendance != null){
                $user_id_from_filtered_attendance = array_column($filtered_attendance, 'user_id');
                $attendance->whereIn('attendance.user_id', $user_id_from_filtered_attendance);
            }

            $attendance = $attendance->whereDate("attendance.created_at" , '=',$date);
            // when no one do time-in
            if ($attendance->count() === 0) {
                $search_names = explode(',', $request->search);
                $attendance = User::select('id as user_id', 'first_name', 'last_name', 'work_mode','employee_code', DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))->addSelect(DB::raw("'$date' as date_range"), 'created_at')
                    ->where(function ($query) use ($search_names) {
                        foreach ($search_names as $name) {$query->orWhere('first_name', 'LIKE', "%$name%");}})
                    ->orWhere(function ($query) use ($search_names) {
                        foreach ($search_names as $name) {$query->orWhere('last_name', 'LIKE', "%$name%");}})
                    ->orWhere(function ($query) use ($search_names) {
                        foreach ($search_names as $name) {$query->orWhere(DB::raw('CONCAT(users.first_name, " ", users.last_name)'), 'LIKE', "%$name%");}})->where('is_deleted', '0')->where('status', '1');
                    }            

            $getIds = $attendance; 
            $attendance = $attendance->get()->toArray();            
            foreach($attendance as $key => $val){              
                $employee_code = User::where('id', $val['user_id'])->value('employee_code');
                $employee_code = str_replace('TLGT-', '', trim($employee_code));
                if(!empty($_GET['daterange'])){
                    $starting_date = $_GET['daterange'];
                    $date_parts = explode(' - ', $starting_date);
                    $start_date = DateTime::createFromFormat('d/m/Y', $date_parts[0])->format('Y-m-d');
                    $ending_point = DateTime::createFromFormat('d/m/Y', $date_parts[1])->modify('+1 day')->format('Y-m-d');
                    $bioMetricTimeIn = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_out_time', '=', null)->where('created_at', '>=', $start_date)->where('created_at', '<', $ending_point)->get()->toArray();
                    $bioMetricTimeOut = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_in_time', '=', null)->where('created_at', '>=', $start_date)->where('created_at', '<', $ending_point)->get()->toArray();
                } else {
                    $start_date = $val['date_range'];
                    $bioMetricTimeIn = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_out_time', '=', null)->where('created_at', 'like', $start_date.'%')->get()->toArray();
                    $bioMetricTimeOut = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_in_time', '=', null)->where('created_at', 'like', $start_date.'%')->get()->toArray();
                }
                $i = 0;
                for($i = 0; $i<count($bioMetricTimeIn); $i++) {
                    if(isset($bioMetricTimeIn[$i])) {
                        for($j = 0; $j<count($bioMetricTimeOut); $j++) {
                            if(isset($bioMetricTimeIn[$i+1]->check_in_time)) {
                                if(strtotime($bioMetricTimeOut[$j]->check_out_time) < strtotime($bioMetricTimeIn[$i+1]->check_in_time) && strtotime($bioMetricTimeOut[$j]->check_out_time) > strtotime($bioMetricTimeIn[$i]->check_in_time)){
                                    $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$j]->check_out_time;
                                } 
                            } else {
                                if(strtotime($bioMetricTimeOut[$j]->check_out_time) > strtotime($bioMetricTimeIn[$i]->check_in_time) ) {
                                    $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$j]->check_out_time;
                                }
                            }
                        }
                    }
                }
    
                $dateWiseRecords = [];
                $totalHours = new DateTime(" 00:00:00");
                foreach($bioMetricTimeIn as $index=>$value) {
                    $time_in = new \DateTime($value->check_in_time);
                    if ($value->check_out_time === null) {
                        $time_out_date = Carbon::now();
                        $time_out_date->addHours(5)->addMinutes(30);
                    } else {
                        $time_out_date = new \DateTime($value->check_out_time);
                    }
                    $interval = $time_in->diff($time_out_date);
                    list($hours, $minutes, $seconds) = explode(':', $interval->format('%H:%I:%S')); 
                    $totalHours = $totalHours->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'.$seconds.'S'));
                    $date = $time_in->format('Y-m-d');
                    if ($date >= $start_date && $date < (isset($end_date) ? $end_date : '')) {
                        if (!isset($dateWiseRecords[$date])) {
                            $dateWiseRecords[$date] = new DateTime("00:00:00");
                        }
                        $dateWiseRecords[$date] = $dateWiseRecords[$date]->add(new DateInterval('PT' . $hours . 'H' . $minutes . 'M' . $seconds . 'S'));
                    } else {
                        $dateWiseRecords[$date] = $totalHours;
                    }
                }
                $dateWiseRecords = array_map(function($dateTime) {
                    return $dateTime->format('H:i:s');
                }, $dateWiseRecords);

                $employee_attendance = Attendance::where('user_id', $val['user_id'])->where('created_at', '>=', $start_date)->first();
                if ($employee_attendance) {
                    $attendance[$key]['time_in'] = $employee_attendance['time_in'];
                    $attendance[$key]['time_out'] = $employee_attendance['time_out'];
                    $attendance[$key]['total_working_hour'] = $employee_attendance['total_working_hour'];
                    $attendance[$key]['total_hours'] = $dateWiseRecords;
                }
                array_push($full_data, $attendance[$key]);
            }

            // it shows the data of the remaining users those who do not do time-in
            if($user_ids == null){
                $getIds = $getIds->pluck('user_id')->toArray();
                $otherUsers = User::select('id as user_id','first_name','last_name', 'work_mode','employee_code', DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))->addSelect(DB::raw("'$date' as date_range"))->whereNotIn('id',$getIds)->where('is_deleted','=','0')->where('status','=','1')->get()->toArray();
                foreach($otherUsers as $key => $val){
                    array_push($full_data,$otherUsers[$key]);
                }
            }

            // do work mode filteration for the users whose don't do time-in
            if(!empty($workMode)){
                $filtered_array = [];
                    foreach($full_data as $data){
                        if($data['work_mode'] == $request->work_mode){
                        array_push($filtered_array, $data);
                        }
                    }
                $full_data = $filtered_array;
            }
        }
        $page = $request->input('page');
        $data = $this->paginate($full_data,$this->perPage,$page)->setPath(url('attendance/list'));
        $data->setPath('list');
        $search = trim($request->input('search'));
        $work_mode = trim($request->input('work_mode'));
        if ($request->ajax()) {
            if ($search != null && $dates != null) {
                $date_range_greater_than_two_days = false;
                if (count($dates) > 1) {
                    $first_date = new \DateTime($dates[0]);
                    $last_date = new \DateTime(end($dates));
                    $date_difference = $last_date->diff($first_date)->days;
            
                    if ($date_difference > 2) {
                        $date_range_greater_than_two_days = true;
                    }
                }
            
                if ($date_range_greater_than_two_days) {
                    $per_page = 31;
                    $data = $this->paginate($full_data, $per_page);
                    $data->withPath(url('attendance/list'));
                    $page_number = $request->input('page');
                    if ($page_number) {
                        return view($this->prefix . '/filtered_data_pagination', ['attendance' => $data, 'search' => $search, 'work_mode' => $work_mode, 'page' => $page_number]);
                    }
                    return view($this->prefix . '/filtered_data', ['attendance' => $data, 'search' => $search, 'work_mode' => $work_mode, 'dateRange' => $viewdate]);
                } else {
                    return view($this->prefix . '/search', ['attendance' => $data, 'search' => $search, 'work_mode' => $work_mode, 'dateRange' => $viewdate]);
                }
            } else {
                return view($this->prefix . '/search', ['attendance' => $data, 'search' => $search, 'work_mode' => $work_mode, 'dateRange' => $viewdate]);
            }
            
        }
        return view($this->prefix.'/index',['attendance'=>$data,'url'=>$this->url,'title'=>$this->title]);
    }

    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    function displayDates($date1, $date2, $format = 'Y-m-d' ) {
        $dates = array();
        $current = strtotime($date1);
        $date2 = strtotime($date2);
        $stepVal = '+1 day';
        while( $current <= $date2 ) {
           $dates[] = date($format, $current);
           $current = strtotime($stepVal, $current);
        }
        return $dates;
        // return array_reverse($dates);      
     }
   
   public function teamAttendanceList(Request $request){
      try{
      $search = trim($request->input('search'));
      $team = DB::table('teams')->where('team_lead_id','=',Auth::user()->id)->first();
      if($team){
        $data =  $team->employee_id;
        $res = str_replace( array( 
            ',' ), ' ',   $data );
              $employeeid =  explode(" ", $res);
             
       }else{
        $employeeid = [];
         $data  = null;
       } 
      
        $user_ids = null;
        if(!empty($search)){
            $users = \Illuminate\Support\Facades\DB::select("select id from `users` where CONCAT(users.first_name ,' ', users.last_name) LIKE '%$search%' and users.is_deleted = 0 ");
            $user_ids = [];
            foreach($users as $ids){
                $user_ids[] = $ids->id;
            }
        }
        $dates = $this->displayDates(date('Y-m-d'), date('Y-m-d'));
        if(!empty($request->input('daterange'))){
            $dateRange = explode('-', $request->input('daterange'));
            $starDate = str_replace("/","-",$dateRange[0]);
            $endDate = str_replace("/","-",$dateRange[1]);
            $start_date = Carbon::parse($starDate)->format('Y-m-d');
            $end_date = Carbon::parse($endDate)->format('Y-m-d');
            $dates = $this->displayDates($start_date, $end_date);
        }
        $fullData = array();

        foreach($dates as $date){

            $attendance = Attendance::select('attendance.*')->with('user_profile')->addSelect(DB::raw("'$date' as date_range"))->join('users','users.id','=','attendance.user_id');
             if($user_ids != null){
                $attendance->whereIn('attendance.user_id', $employeeid);
            }
              $attendance->whereIn('attendance.user_id',$employeeid);
        
            $attendance = $attendance->whereDate("attendance.created_at" , '=',$date);
 
            $getIds = $attendance; 
            $attendance = $attendance->get()->toArray(); 
 
            foreach($attendance as $key => $val){
                array_push($fullData,$attendance[$key]);
            }
            if($user_ids == null){
                $getIds = $getIds->pluck('user_id')->toArray();                
                $otherUsers = User::select('id as user_id','first_name','last_name')->addSelect(DB::raw("'$date' as date_range"))->whereNotIn('id',$getIds)->where('is_deleted','=','0')->whereIn('id',$employeeid)->get()->toArray();
            }else{
                $otherUsers = User::select('id as user_id','first_name','last_name')->addSelect(DB::raw("'$date' as date_range"))->where('is_deleted','=','0')->whereIn('id',$user_ids)->get()->toArray();
            }
            foreach($otherUsers as $key => $val){
                array_push($fullData,$otherUsers[$key]);
            }
        }
       
        $page = $request->input('page');
        $data = $this->paginate($fullData,$this->perPage,$page)->setPath(url('attendance/team-attendance-list'));
        
        $data->setPath('team-attendance-list');
        $search = trim($request->input('search'));
        if($request->ajax()){
            return view($this->prefix.'/search', ['attendance'=>$data, 'search' => $search]);
        }

        return view($this->prefix.'/index',['attendance'=>$data,'url'=>$this->url,'title'=>$this->title]);
      } catch (\Exception $e){

      }
   }


    public function userAttendanceList(Request $request)
    {

        $attendance = Attendance::whereUserId(Auth::id());
        if(!empty($request->input('daterange')))
        {
            $dateRange = explode('-', $request->input('daterange'));
            $start_date = Carbon::parse($dateRange[0])->format('Y-m-d');
            $end_date = Carbon::parse($dateRange[1])->format('Y-m-d');
            $attendance = $attendance->whereBetween(DB::raw(
                "DATE_FORMAT(`created_at`,'%Y-%m-%d')"), [$start_date, $end_date]
            );
        } 
        
        $attendance = $attendance->orderBy('created_at','desc')->paginate($this->perPage);
        if($request->ajax())
        {
            return view($this->prefix.'/search-user-attendance-list', ['attendance'=>$attendance]);
        }

        return view($this->prefix.'/user-attendance-list',['attendance'=>$attendance,'url'=>$this->url,'title'=>$this->title]);
    }

    public function getAttendanceList(Request $request){
        $attendance = Attendance::whereUserId(Auth::id());
        if(!empty($request->input('daterange')))
        {
            $dateRange = explode('-', $request->input('daterange'));
            $start_date = Carbon::parse($dateRange[0])->format('Y-m-d');
            $end_date = Carbon::parse($dateRange[1])->format('Y-m-d');
            $attendance = $attendance->whereBetween(DB::raw(
                "DATE_FORMAT(`created_at`,'%Y-%m-%d')"), [$start_date, $end_date]
            );
        }

        $attendance = $attendance->orderBy('created_at','desc')->paginate($this->perPage);
        $attendance->setPath('user-attendance-list');
        if($request->ajax())
        {
            return view($this->prefix.'/filter-user-attendance-list', ['attendance'=>$attendance]);
        }

        return view($this->prefix.'/user-attendance-list',['attendance'=>$attendance,'url'=>$this->url,'title'=>$this->title]);
    }



    /**
     * Add date wise attendance.
     *
     * @return \Illuminate\Http\Response
     */
    public function timeInAction(Request $request)
    {  

        if ($request->isMethod('get')) {
            return back();
        }
        try{
            if($request->TimeIn == true){
                $datetime = Carbon::now()->format('Y-m-d '.$request->time.':s');
                $given = new DateTime($datetime, new DateTimeZone("Asia/Kolkata"));
                $given->setTimezone(new DateTimeZone("UTC"));
                $time = $given->format("Y-m-d H:i:s"); 
                $attributes =   [
                'user_id' => Auth::id(),
                'time_in' => $time,
                'late_reason'=>$request->LateReason
                 ];
                 if($request->LateReason == ''){
                 return redirect()->back()->with('error','Space  and  special characters  not allowed.');
                 }       
                $reporting_manager_ids = explode(',', Auth::user()->reporting_manager_id);
                $management_ids = ['27', '103'];
                
                if(!in_array($management_ids[0], $reporting_manager_ids) && !in_array($management_ids[1], $reporting_manager_ids)){
                    array_push($reporting_manager_ids, '27', '103');
                } else if(!in_array($management_ids[0], $reporting_manager_ids)) {
                    array_push($reporting_manager_ids, '27');
                } else if(!in_array($management_ids[1], $reporting_manager_ids)){
                    array_push($reporting_manager_ids, '103');
                }

                $cc = ['mgmt@talentelgia.in'];

                foreach ($reporting_manager_ids as $reporting_manager_id) {
                    $user = User::find($reporting_manager_id);
                    if ($user) {
                        $details['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $details['email'] = $user->email;
                        $details['view'] = 'mails.send_late_timein';
                        $details['LateReason'] = $request->LateReason;
                        $details['time_in'] = $request->time;
                        $details['cc'] = $cc;
                        $details['reporting_manager_id'] = $reporting_manager_ids;
                        dispatch(new SendLateTimeInJob($details));
                    }
                }
               
             }else{
                $attributes =   [
                'user_id' => Auth::id(),
                'time_in' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
             }
          
            if(!empty($request->LateReason)){
                if (!empty($request->LateReason)) {
                    $attendance = Attendance::where('user_id', Auth::id())->latest()->first();
                    $attendanceTimeIn = Carbon::parse($attendance->time_in);
                    $attendanceTimeInFormatted = $attendanceTimeIn->format('Y-m-d');
                    $current_time = Carbon::now()->format('Y-m-d');
                    if($current_time == $attendanceTimeInFormatted){
                        if ($attendance) {
                            $attendance->update($attributes);
                        }
                    } else {
                        $attendance =  Attendance::create($attributes);
                    }
                }
             } else {
                $exists = Attendance::whereUserId(Auth::id())->whereRaw(DB::raw("DATE_FORMAT(`time_in`,'%Y-%m-%d') = '" . Carbon::now()->format('Y-m-d') . "'"))->exists();
                
                if($exists)
                {
                    return redirect()->back()->with('flash_message', "Today's attendance already exists!");
                }
                $attendance =  Attendance::create($attributes);
             }

            $leaves = UserLeave::where('users_id', Auth::id())->where('leave_type_id', 4)->get();
            
            foreach ($leaves as $leave) {
                $leaveEndDate = Carbon::parse($leave->end_date);
            
                if ($attendance->created_at->greaterThan($leaveEndDate)) {
                    User::where('id', Auth::id())->update(['work_mode' => 'WFO']);
                    break; // Break the loop if the condition is met for any leave
                }
            }

            if($attendance){
                $user = User::whereId(Auth::id())->first();
                $time_in = new \DateTime($attendance->time_in);
                $time_in->setTimeZone(new \DateTimeZone('UTC'));

                /*$details=[
                    "email"   =>  $user->email,
                    "subject" =>  'Time In - '.$user->first_name.' '.$user->last_name,
                    "name"    =>  $user->first_name.' '.$user->last_name,
                    "type"    =>  'time_in',
                    "view"    =>  'mails.attendance-email-to-employee',
                    "time_in_date" =>  Carbon::parse($attendance->time_in)->format('d-m-Y'),
                    "time_in_time" =>  Carbon::parse($attendance->time_in)->format('g:i A'),
                    "emp_code"     =>  $user->employee_code
                 ];
                dispatch(new SendAttendanceEmailJob($details));

                $management = User::whereIn('role_id',['2','3','5'])->get();
                if(!empty($management)){
                    foreach($management  as $manage){
                        Log::info('sending email to management');
                        $details['email'] = $manage->email;
                        $details['view'] = 'mails.attendance-email';
                        dispatch(new SendAttendanceEmailJob($details));
                    }
                }*/
                return redirect()->back();
            }else{
                return redirect()->back()->with('flash_message', 'There is something wrong. Please try again.');
            }
        }
        catch(\Exception $e){
            return redirect()->back()->with('flash_message', $e->getMessage());
        }
    }

    /**
     * Update time out attendance.
     *
     * @return \Illuminate\Http\Response
     */
    public function timeOutAction(Request $request)
    {

        date_default_timezone_set("UTC");

        //check user have submitted DSR or not for today
        try {
            $check_dsr = Dsr::whereUserId(Auth::id())->whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('to_ids','!=','')->first();
            if(!$check_dsr){
                throw new Exception('You have not filled your today\'s DSR. Kindly fill that and then do the time out.');
            }
            if(date('l') == 'Friday'){
                $check_weeky_report = WeeklyReport::whereUserId(Auth::id())->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();
                if(!$check_weeky_report){
                    throw new Exception('You have not filled your weekly report. Kindly fill that and then do the time out.');
                }
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        try{
            $time_in_date = $request->get('date');
            if(!empty($time_in_date)){
                $attendance = Attendance::whereUserId(Auth::id())->whereDate('time_in', $time_in_date)->first();
            }else{
                $attendance = Attendance::whereUserId(Auth::id())->orderBy('id','DESC')->first();
            }

            if($attendance->time_out != ''){
                 return redirect()->back()->with('flash_message', "Already time out today.");
            }

            $time_in = new \DateTime($attendance->time_in);
            $time_in->setTimeZone(new \DateTimeZone('UTC'));

            $time_out = Carbon::now()->format('Y-m-d H:i:s');

            $update =  $attendance->update([
                'time_out' => $time_out /*,
                'total_working_hour' => $interval->format('%H:%I:%S')*/
            ]);

            //get attendance for this user
            $attendance = Attendance::whereUserId(Auth::id())->orderBy('id','DESC')->first();
            $time_out_date = new \DateTime($attendance->time_out);
            $interval = $time_in->diff($time_out_date);
            $attendance->total_Working_hour = $interval->format('%H:%I:%S');
            $attendance->save();

            if($update){
                /*$user = User::whereId(Auth::id())->first();
                $details=[
                    "email"             =>  $user->email,
                    "subject"           =>  'Time Out - '.$user->first_name.' '.$user->last_name,
                    "name"              =>  $user->first_name.' '.$user->last_name,
                    "type"              =>  'time_out',
                    "view"              =>  'mails.attendance-email-to-employee',
                    'time_out_date'     =>  Carbon::parse($attendance->time_out)->format('d-m-Y') ,
                    'time_out_time'     =>  Carbon::parse($attendance->time_out)->format('g:i A') ,
                    'total_working_hour'=>  $interval->format('%H:%I:%S'),
                    'emp_code'          =>  $user->employee_code
                ];
                dispatch(new SendAttendanceEmailJob($details));
                $management = User::whereIn('role_id',['2','3','5'])->where('status',1)->get();

                if(!empty($management)){
                    foreach($management  as $manage){
                        Log::info('sending email to management');
                        $details['email'] = $manage->email;
                        $details['view'] = 'mails.attendance-email';
                        dispatch(new SendAttendanceEmailJob($details));
                    }
                }*/
                return redirect()->back();
                // return redirect('/dashboard')->with('flash_message', 'Attendance Updated successfully!');
            }else{
                return redirect()->back()->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return redirect()->back()->with('flash_message', 'Something went wrong');
        }

    }

    public function exportAllEmployeeAttendance(Request $request)
    {
        $search = trim($request->input('search'));
        $user_ids = null;
        if(!empty($search)){
            $users = \Illuminate\Support\Facades\DB::select("select id from `users` where CONCAT(users.first_name ,' ', users.last_name) LIKE '%$search%' and users.is_deleted = 0 ");
            $user_ids = [];
            foreach($users as $ids){
                $user_ids[] = $ids->id;
            }
        }
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $dates = $this->displayDates($start_date , $end_date);
        if(!empty($request->input('dates'))){
            $dateRange = explode('-', $request->input('dates'));
            $starDate = str_replace("/","-",$dateRange[0]);
            $endDate = str_replace("/","-",$dateRange[1]);
            $start_date = Carbon::parse($starDate)->format('Y-m-d');
            $end_date = Carbon::parse($endDate)->format('Y-m-d');
            $dates = $this->displayDates($start_date, $end_date);
        }
        $file_name = 'attendance_' . $start_date.'_'. $end_date . '.xlsx';

        $workMode = $request->input('work_mode');
        $filteredAttendance = [];
        if (!empty($workMode)) {
            $users = User::where('work_mode', $workMode)->get();
            $userIds = $users->pluck('id')->toArray();
            $work_mode = Attendance::whereIn('user_id', $userIds)->get();

            foreach ($work_mode as $attendance) {
                $user = User::find($attendance->user_id);
                $attendanceData = [
                    'user_id' => $attendance->user_id,
                    'work_mode' => $user->work_mode,
                ];
                $filteredAttendance[] = $attendanceData;
            }
        }

        $fullData = array();
        foreach($dates as $date){
            $attendance = Attendance::select('attendance.*', 'users.work_mode')->with('user_profile')->addSelect(DB::raw("'$date' as date_range"))->join('users','users.id','=','attendance.user_id')->where('users.status', '=', 1);
            if($user_ids != null){
                $attendance->whereIn('attendance.user_id', $user_ids);
            }

            if($filteredAttendance != null){
                $userIdsFromFilteredAttendance = array_column($filteredAttendance, 'user_id');
                $attendance->whereIn('attendance.user_id', $userIdsFromFilteredAttendance);
            }

            $attendance = $attendance->whereDate("attendance.created_at" , '=',$date);
            $getIds = $attendance; 
            $attendance = $attendance->get()->toArray();            
            foreach($attendance as $key => $val){
                $status = 'Absent';
                if(!empty($val['time_in'])){
                    if(!empty($val['total_working_hour'])){
                        $status = 'Present';
                    }else{
                        if((Carbon::parse($val['time_in'])->format('Y-m-d')) == (Carbon::now()->format('Y-m-d'))){
                            $status = 'Working';
                        }
                    }
                }
                $attendance[$key]['status'] = $status;
                array_push($fullData,$attendance[$key]);
            }

            if($user_ids == null && $filteredAttendance == null){
                $getIds = $getIds->pluck('user_id')->toArray();
                $otherUsers = User::select('id as user_id','first_name','last_name', 'work_mode')->addSelect(DB::raw("'$date' as date_range"))->whereNotIn('id',$getIds)->where('is_deleted','=','0')->where('status','=','1')->get()->toArray();
                foreach($otherUsers as $key => $val){
                    $otherUsers[$key]['status'] = 'Absent';
                    array_push($fullData,$otherUsers[$key]);
                }
            }
        }


     

        $employees_attendance = [];
        foreach($fullData as $attendance){
            if(!empty($attendance['user_profile'])){
                $employee_name = $attendance['user_profile']['first_name'] .' ' .$attendance['user_profile']['last_name'];
            }else{
                $employee_name = $attendance['first_name'] .' ' .$attendance['last_name'];
            }
            $employees_attendance[] = [
                'Date' => !empty($attendance['date_range']) ? $attendance['date_range'] : '-',
                'Employee Name' => !empty($employee_name) ? $employee_name : '-',
                'Time In' => !empty($attendance['time_in']) ? Carbon::parse($attendance['time_in'])->format('g:i A') : '-',
                'Time Out' => !empty($attendance['time_out']) ? Carbon::parse($attendance['time_out'])->format('g:i A') : '-',
                'Total Working Hour' => !empty($attendance['total_working_hour']) ? $attendance['total_working_hour'] : '-',
                'Work Mode' => !empty($attendance['work_mode']) ? ( ($attendance['work_mode'] === 'WFH') ? 'WFH' : (($attendance['work_mode'] === 'WFO') ? 'WFO' : (($attendance['work_mode'] === 'Hybrid') ? 'Hybrid' : '-'))) : '-',
                'Status' => !empty($attendance['status']) ? $attendance['status'] : '-',
            ];
        }
        return Excel::download(new AllEmployeeAttendanceExport($employees_attendance), $file_name);
    }

    public function exportUserAttendance(Request $request)
    {
        $post = Arr::except($request->all(), ['_token']);
        $attendance = Attendance::whereUserId(Auth::id());
        $file_name = Auth::user()->first_name.'_attendance_' . Carbon::now()->format('d-m-Y') . '.xlsx';
        if(!empty($request->input('dates')))
        {
            $dateRange = explode('-', $request->input('dates'));
            $start_date = Carbon::parse($dateRange[0])->format('Y-m-d');
            $end_date = Carbon::parse($dateRange[1])->format('Y-m-d');
            $attendance = $attendance->whereBetween(DB::raw(
                "DATE_FORMAT(`created_at`,'%Y-%m-%d')"), [$start_date, $end_date]
            );
            $file_name = Auth::user()->first_name.'_attendance_' . $start_date.'_'. $end_date . '.xlsx';
        } 

       
        $attendance = $attendance->orderBy('created_at','asc')->get();

        $employees_attendance = [];
        $old_date = '';
        foreach($attendance as $atten){
            $status = 'Absent';
            if(!empty($atten->time_in)){
                if(!empty($atten->total_working_hour)){
                    $status = 'Present';
                }else{
                    if((Carbon::parse($atten->time_in)->format('Y-m-d')) == (Carbon::now()->format('Y-m-d'))){
                        $status = 'Working';
                    }
                }
            }

            $employees_attendance[] = [
                'Date' => !empty($atten->time_in) ? Carbon::parse($atten->time_in)->format('Y-m-d') : Carbon::parse($atten->created_at)->format('Y-m-d'),
                'Time In' => !empty($atten->time_in) ? Carbon::parse($atten->time_in)->format('d-m-Y g:i A') : '-',
                'Time out' => !empty($atten->time_out) ? Carbon::parse($atten->time_out)->format('d-m-Y g:i A') : '-',
                'Total Working Hour' => !empty($atten->total_working_hour) ? $atten->total_working_hour : '-',
                'Status' => $status,
            ];
        }
        return Excel::download(new UserAttendanceExport($employees_attendance), $file_name);
    }

    public function searchSuggestions(Request $request) 
    {
        $query = $request->input('query');
        $suggestions = [];

        $matchingUsers = DB::table('users')
            ->where('first_name', 'LIKE', $query . '%')
            ->orWhere('last_name', 'LIKE', $query . '%')
            ->get();

        foreach ($matchingUsers as $user) {
            $fullName = $user->first_name . ' ' . $user->last_name;
            $formattedSuggestion = (object) [
                'text' => $fullName,
            ];   
            $suggestions[] = $formattedSuggestion;
        }
        return response()->json($suggestions);
    }

    public function bioMetricDetail(Request $request) 
    {
        $employee_code = $request->id;
        if(str_contains($employee_code, "-")){
            $employee_code = explode('-', $employee_code);
            $employee_code = $employee_code[1];
            $empCode = $request->id;
        } else {
            $employee_code = $request->id;
            $empCode = 'TLGT-'.$request->id;
        }
        
        $id_name = User::select('first_name', 'last_name')->where('employee_code', $empCode)->first();
        $full_name = $id_name->first_name.' '.$id_name->last_name;
        $employee_code = explode('-', $employee_code);
        $bioMetricTimeIn = DB::table('biometric_data')
            ->where('employee_code_id', '=', $employee_code)
            ->where('check_out_time', '=', null)
            ->where('check_in_time', 'like', $request->date.'%')
            ->orderBy('check_in_time', 'asc')
            ->get()->toArray();//->unique('biometric_created_on')
        $bioMetricTimeOut = DB::table('biometric_data')
            ->where('employee_code_id', '=', $employee_code)
            ->where('check_in_time', '=', null)
            ->where('check_out_time', 'like', $request->date.'%')
            ->orderBy('check_out_time', 'asc')
            ->get()->toArray();//->unique('biometric_created_on')
            $i = 0;
            // print_r(count($bioMetricTimeOut));die;
            for($i = 0; $i<count($bioMetricTimeIn); $i++) {
            // foreach($bioMetricTimeOut as $key => $val){   
                // print_r($bioMetricTimeIn[$i]->check_out_time);die;
                if(isset($bioMetricTimeIn[$i]) ){
                   
                    for($j = 0; $j<count($bioMetricTimeOut); $j++) {
                        if(isset($bioMetricTimeIn[$i+1]->check_in_time)) {
                            if(strtotime($bioMetricTimeOut[$j]->check_out_time) < strtotime($bioMetricTimeIn[$i+1]->check_in_time) && strtotime($bioMetricTimeOut[$j]->check_out_time) > strtotime($bioMetricTimeIn[$i]->check_in_time)){
                                $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$j]->check_out_time;
                            } 
                        } else {
                           
                            if(strtotime($bioMetricTimeOut[$j]->check_out_time) > strtotime($bioMetricTimeIn[$i]->check_in_time) ) {
                                $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$j]->check_out_time;
                            }
                        }
                    
                        }
                    // $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$i]->check_out_time;
                }
                
                // array_push($full_data,$attendance[$key]);
                
            }
    
     
            // print_r($bioMetricTimeIn);die;
        return view($this->prefix . '/bio-metric-attendence-detail', ['attendance' => $bioMetricTimeIn, 'date' => $request->date, 'name' => $full_name, 'id' => $request->id]);
        
    }

    public function monthlyAttendenceDetail(Request $request){
        // print_r($request->id);die;
        $emp_id = $request->id;
        $id_name = User::select('first_name', 'last_name')->where('id', $emp_id)->first();
        $full_name = $id_name->first_name.' '.$id_name->last_name;
        // $employee_code = explode('-', $employee_code);
        $date = $request->date;
        $date = explode(' to ', $date);
        $start_date = $date[0];
        $end_date = $date[1];
        
        $dates = $this->displayDates($start_date, $end_date);
        $full_data = array();
        foreach($dates as $date){
            $attendance = Attendance::select('attendance.*', 'users.work_mode', DB::raw('REPLACE(users.employee_code, "TLGT-", "") as employee_code'), DB::raw('CONCAT(users.first_name, " ", users.last_name) as full_name'))->with('user_profile')->addSelect(DB::raw("'$date' as date_range"))->with('biometric_data')->join('users', 'users.id', '=', 'attendance.user_id')->where('users.status', 1);
            if($emp_id != null){
                $attendance->where('attendance.user_id', $emp_id);
            }


            $attendance = $attendance->whereDate("attendance.created_at" , '=',$date);
                      

            $getIds = $attendance; 
            $attendance = $attendance->get()->toArray();
            foreach($attendance as $key => $val){              
                $employee_code = User::where('id', $val['user_id'])->value('employee_code');
                $employee_code = str_replace('TLGT-', '', trim($employee_code));
                if(!empty($_GET['daterange'])){
                    $starting_date = $_GET['daterange'];
                    $date_parts = explode(' - ', $starting_date);
                    $start_date = DateTime::createFromFormat('d/m/Y', $date_parts[0])->format('Y-m-d');
                    $ending_point = DateTime::createFromFormat('d/m/Y', $date_parts[1])->modify('+1 day')->format('Y-m-d');
                    $bioMetricTimeIn = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_out_time', '=', null)->where('created_at', '>=', $start_date)->where('created_at', '<', $ending_point)->get()->toArray();
                    $bioMetricTimeOut = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_in_time', '=', null)->where('created_at', '>=', $start_date)->where('created_at', '<', $ending_point)->get()->toArray();
                } else {
                    $start_date = $val['date_range'];
                    $bioMetricTimeIn = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_out_time', '=', null)->where('created_at', 'like', $start_date.'%')->get()->toArray();
                    $bioMetricTimeOut = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_in_time', '=', null)->where('created_at', 'like', $start_date.'%')->get()->toArray();
                }
                $i = 0;
                for($i = 0; $i<count($bioMetricTimeIn); $i++) {
                    if(isset($bioMetricTimeIn[$i])) {
                        for($j = 0; $j<count($bioMetricTimeOut); $j++) {
                            if(isset($bioMetricTimeIn[$i+1]->check_in_time)) {
                                if(strtotime($bioMetricTimeOut[$j]->check_out_time) < strtotime($bioMetricTimeIn[$i+1]->check_in_time) && strtotime($bioMetricTimeOut[$j]->check_out_time) > strtotime($bioMetricTimeIn[$i]->check_in_time)){
                                    $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$j]->check_out_time;
                                } 
                            } else {
                                if(strtotime($bioMetricTimeOut[$j]->check_out_time) > strtotime($bioMetricTimeIn[$i]->check_in_time) ) {
                                    $bioMetricTimeIn[$i]->check_out_time = $bioMetricTimeOut[$j]->check_out_time;
                                }
                            }
                        }
                    }
                }
    
                $dateWiseRecords = [];
                $totalHours = new DateTime(" 00:00:00");
                foreach($bioMetricTimeIn as $index=>$value) {
                    $time_in = new \DateTime($value->check_in_time);
                    $time_out_date = new \DateTime($value->check_out_time);
                    $interval = $time_in->diff($time_out_date);
                    list($hours, $minutes, $seconds) = explode(':', $interval->format('%H:%I:%S')); 
                    $totalHours = $totalHours->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'.$seconds.'S'));
    
                    $date = $time_in->format('Y-m-d');
                    if($date >= $start_date && $date < $end_date) {
                        if(!isset($dateWiseRecords[$date])) {
                            $dateWiseRecords[$date] = new DateTime("00:00:00");
                        }
                        $dateWiseRecords[$date] = $dateWiseRecords[$date]->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'.$seconds.'S'));
                    } else {
                        $dateWiseRecords[$date] = $totalHours;
                    }
                }
                $dateWiseRecords = array_map(function($dateTime) {
                    return $dateTime->format('H:i:s');
                }, $dateWiseRecords);

                $employee_attendance = Attendance::where('user_id', $val['user_id'])->where('created_at', '>=', $start_date)->first();
                if ($employee_attendance) {
                    $attendance[$key]['time_in'] = $employee_attendance['time_in'];
                    $attendance[$key]['time_out'] = $employee_attendance['time_out'];
                    $attendance[$key]['total_working_hour'] = $employee_attendance['total_working_hour'];
                    $attendance[$key]['total_hours'] = $dateWiseRecords;
                }
                array_push($full_data,$attendance[$key]);
            }
            
            
        }
        $page = $request->input('page');
        $data = $this->paginate($full_data,31,$page)->setPath(url('attendance/monthly-attendence/'.$emp_id.'/'.$request->date));
        $data->setPath('monthly-attendence/'.$emp_id.'/'.$request->date);

        
        $query1  = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','users.first_name','users.last_name','users.employee_code','leave_types.value'])
        ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
        ->leftJoin('users','users.id','=','leaves.users_id')   
        ->where('leaves.users_id','=',$emp_id)
        ->where('leaves.end_date','>=',$start_date)
        ->where('leaves.end_date','<=',$end_date);
   
  
        //getting approved leave count
        $query = $query1->where('leaves.leave_status', '=', 'approved')
            ->where('leaves.request_type', '=', 'leave_request');

            $endProbation = Auth::user()->end_probation;
            if ($endProbation !== null) {
                $query = $query->where('leaves.start_date', '>', $endProbation);
            }

            $totalLeaveapproved = $query->orderBy('id', 'DESC')->get();
            $leavecount = [];
            foreach($totalLeaveapproved as $key => $val){ 
                $begin = strtotime($val->start_date);   
                $end   = strtotime($val->end_date);  
                    $days  = 0;
                    while ($begin <= $end) {        
                        $what_day = date("N", $begin);            
                        if (!in_array($what_day, [6,7]) ) // 6 and 7 are weekend
                            $days++;
                        $begin += 86400; // +1 day
                    }    
                    $leavecount[] = $days*$val->value;  
                   
                }
            $leaves = array_sum($leavecount);

            $total_working_hour = 0;
            $late_count = 0;
            $startDate = $start_date;
            $endDate = $end_date;
            $startDate = DateTime::createFromFormat('Y-m-d', $start_date);
            $endDate = DateTime::createFromFormat('Y-m-d', $end_date);
            $total_attendance = \App\Attendance::where('user_id', $emp_id)->get();
            if ($startDate === false || $endDate === false) {
                echo 'Invalid date format';
            } else {
            while ($startDate <= $endDate) {
                $currentDate = $startDate->format('Y-m-d');
                foreach ($total_attendance as $attendanceRecord) { 
                    $attendanceDate = \Carbon\Carbon::parse($attendanceRecord['time_in'])->format('Y-m-d');
                    if ($attendanceDate == $currentDate) {
                        $timeComponents = explode(':', $attendanceRecord['total_working_hour']);
                        if (count($timeComponents) === 3) {
                            list($hours, $minutes, $seconds) = $timeComponents;
                            $total_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;
                            $total_working_hour += $total_seconds;
                        } 
                        
                        if(\Carbon\Carbon::parse($attendanceRecord['time_in'])->format('H:i') >= '09:30' ) {
                            $late_count++;
                        }        
                        break;
                    }
                }
                $startDate->modify('+1 day'); 
            }
                $hours = floor($total_working_hour / 3600);
                $minutes = floor(($total_working_hour % 3600) / 60);
                $seconds = $total_working_hour % 60;
                $total_working_hour_formatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);   
                $total_working_hour_formatted = $total_working_hour_formatted != '00:00:00' ? $total_working_hour_formatted : '-';
            }
        return view($this->prefix . '/monthly-attendence-detail', ['attendance' => $data, 'leavecount' => $leaves, 'total_working_hours'=> $total_working_hour_formatted, 'late_count' => $late_count, 'full_name' => $full_name]);
    }

    public function testing(){
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

        return $users_time_in_by_mobile;
    }
}