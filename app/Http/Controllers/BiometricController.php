<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\BiometricData;
use App\Department;
use App\User;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BiometricController extends Controller
{
    public function biometric_data(Request $request)
    {
        
        if (!is_dir(public_path('logs'))) {
            mkdir(public_path('logs'), 0777, true); // true for recursive create
        }
        $yesterdayDate = date("Y-m-d", strtotime("yesterday"));
        if(file_exists(public_path('logs')."/__bioMetric_api_logs_".$yesterdayDate.".txt")) {
            unlink(public_path('logs')."/__bioMetric_api_logs_".$yesterdayDate.".txt");
        }
        $filename = '__bioMetric_api_logs_'.date('Y-m-d').'.txt';
        $logfile = fopen(public_path('logs')."/".$filename, "a") or die("Unable to open file!");
        $data =  "=========================== \n";
        $data .= "| Bio Metric API starting | \n";
        $data .= "=========================== \n";
        $data .= "Scheduler date and Time - ".Carbon::parse(date('Y-m-d H:i:s'))->addHours(5)->addMinutes(30)." \n";
        $data .= " Request Data \n";
        fwrite($logfile, $data);
        fwrite($logfile, print_r($request->all(),1));
        fclose($logfile);
        try {
            $biometric_token = $request->header('Authorization');
            $biometric_token = str_replace('Bearer ', '', $biometric_token);

            $employee_data = User::whereNotNull('employee_code')
            ->select('id', DB::raw("SUBSTRING_INDEX(employee_code, '-', -1) as trimmed_employee_code"))
            ->whereRaw("SUBSTRING_INDEX(employee_code, '-', -1) = ?", [$request['EmployeeId']])
            ->where('is_deleted',  0)
            ->where('status', 1)
            ->first();

            if ($request['CheckInTime'] !== null) {
                $checkInTime = Carbon::parse($request['CheckInTime'])->subHours(5)->subMinutes(30);
                $currentDate = $checkInTime->toDateString();
            } else {
                $checkInTime = null;
            }

            if ($request['CheckOutTime'] !== null) {
                $checkOutTime = Carbon::parse($request['CheckOutTime'])->subHours(5)->subMinutes(30);
                $currentDate = $checkOutTime->toDateString();
            } else {
                $checkOutTime = null;
            }

            // $attendance = Attendance::latest();

            // $attendance->where('user_id', $employee_data->id);
            $attendance = Attendance::where('user_id', $employee_data->id)->latest();
            if ($biometric_token == env('BIO_METRIC_KEY')) {
                $request = $request->all();
                $biometric_data = new BiometricData;
                $biometric_data->check_in_time = $request['CheckInTime'];
                $biometric_data->check_out_time = $request['CheckOutTime'];
                $biometric_data->serial_no = $request['Serial_No'];
                $biometric_data->employee_code_id = $request['EmployeeId'];
                $biometric_data->biometric_created_on = $request['CreatedOn'];

                $existing_time_in_data = BiometricData::where('check_in_time', $biometric_data->check_in_time)->where('employee_code_id', $biometric_data->employee_code_id)->first();
                $existing_time_out_data = BiometricData::where('check_out_time', $biometric_data->check_out_time)->where('employee_code_id', $biometric_data->employee_code_id)->first();
                
                if(!$existing_time_in_data){
                    $biometric_data->save();
                } else if (!$existing_time_out_data){
                    $biometric_data->save();
                }

                if ($employee_data && $checkInTime != null) {
                    $existing_attendance = $attendance->whereDate('created_at', $currentDate)->where('user_id', $employee_data->id)->exists();
                
                    if (!$existing_attendance) {
                        $attendance->create([
                            'user_id' => $employee_data->id,
                            'time_in' => $checkInTime,
                            'time_out' => null,
                            'total_working_hour' => null,
                            'late_reason' => null,
                            'created_at' => $checkInTime,
                            'updated_at' => $checkInTime,
                        ]);
                    }
                }
                

                if ($employee_data && $checkOutTime != null) {
                    $existing_attendance = $attendance->whereDate('created_at', $currentDate)->where('user_id', $employee_data->id)->get();
                    if ($existing_attendance->isNotEmpty()) {
                        $time_in = Carbon::parse($existing_attendance[0]['time_in']);
                    }
                    $biometric_time_out_data = BiometricData::where('employee_code_id', $biometric_data->employee_code_id)->whereNotNull('check_out_time')->get();
                    $check_out_times = $biometric_time_out_data->pluck('check_out_time');
                    $biometriclastValue = $check_out_times->last();

                    $data_saving_timing = Carbon::createFromFormat('H:i', '18:30');
                    $now = Carbon::now();
                    $indianTime = $now->tz('Asia/Kolkata');

                    if ($indianTime->format('H:i') > $data_saving_timing->format('H:i')) {
                        $total_working_hours = Carbon::parse($biometriclastValue)->diff($time_in)->format('%H:%I:%S');
                        if ($existing_attendance->isNotEmpty()) {
                            $attendanceRecord = $existing_attendance->first();
                            $timeOut = Carbon::parse($biometriclastValue)->subHours(5)->subMinutes(30)->toDateTimeString();
                            if ($biometriclastValue > $timeOut) {
                                $attendanceRecord->update([
                                    'time_out' => $timeOut,
                                    'total_working_hour' => $total_working_hours,
                                    'updated_at' => $timeOut,
                                ]);
                            }
                        }
                    }
                }
                $logfile = fopen(public_path('logs')."/".$filename, "a") or die("Unable to open file!");
                $APIdata =  "=========================== \n";
                $APIdata .= "| Bio Metric API completed | \n";
                $APIdata .= "=========================== \n";
                fwrite($logfile, $APIdata);
                fclose($logfile);
                return response()->json(['message' => 'Api hitting'], 200);
            } else {
                $logfile = fopen(public_path('logs')."/".$filename, "a") or die("Unable to open file!");
                $result =  "============================= \n";
                $result .= "| Not Authorized hit to API | \n";
                $result .= "============================= \n";
                fwrite($logfile, $result);
                fclose($logfile);
                return response()->json(['message' => 'Not Authorized'], 401);
            }
        } catch (\Exception $e) {
            $logfile = fopen(public_path('logs')."/".$filename, "a") or die("Unable to open file!");
            $result =  "=================== \n";
            $result .= "| Exception Found | \n";
            $result .= "=================== \n";
            fwrite($logfile, $result);
            fwrite($logfile, $e->getMessage());
            fwrite($logfile, "\n");
            fclose($logfile);
            // throw $th;
        }
    }

    public function send_attendance_mail(Request $request)
    {
        try {
            $date_range = explode(' - ', $request->date_range);
            $start_date = DateTime::createFromFormat('d/m/Y', $date_range[0])->format('Y-m-d');
            $end_date = DateTime::createFromFormat('d/m/Y', $date_range[1])->modify('+1 day')->format('Y-m-d');
            
            $user_detail = User::where('email', $request->to_email_address)->first();
            
            // getting total working hours
            $attendance = Attendance::where('user_id', $user_detail->id)->whereBetween('created_at', [$start_date, $end_date])->get();
            
            //getting total time that spend in office
            $employee_code = str_replace('TLGT-', '', trim($user_detail->employee_code));
            foreach ($attendance as $details) {
                $bioMetricTimeIn = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_out_time', '=', null)->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->get()->toArray();
                $bioMetricTimeOut = DB::table('biometric_data')->where('employee_code_id', '=', $employee_code)->where('check_in_time', '=', null)->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->get()->toArray();
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
                    }
                }
                $dateWiseRecords = array_map(function($dateTime) {
                    return $dateTime->format('H:i:s');
                }, $dateWiseRecords);
                
                $timeInDate = date('Y-m-d', strtotime($details['time_in']));
                $dateWiseRecordsKeys = array_keys($dateWiseRecords);
                if (in_array($timeInDate, $dateWiseRecordsKeys)) {
                    $details['biometric_total_times_by_date'] = $dateWiseRecords[$timeInDate];
                } else {
                    $details['biometric_total_times_by_date'] = null;
                }
            }
            Mail::send('mails.attendance_detail', [
                'attendance' =>$attendance,
                'user_detail' => $user_detail,
                'date_range' => $date_range,
            ], function($message) use($user_detail, $request){
                $message->from($request->from_email_address);
                $message->to($user_detail->email);
                $message->cc('mgmt@talentelgia.in');
                $message->subject('Attendance Details of ' . $user_detail->first_name ." " . $user_detail->last_name . " " . "(" . $user_detail->employee_code .")");
            });
            return response()->json(['message' => 'Mail send successfully.'], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['message' => 'Mail not send.'], 500);
        }
    }
}
