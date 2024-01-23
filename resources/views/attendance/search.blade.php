<?php 
use Carbon\Carbon;
?>
<style>
  .monthlyAttendence {
    color:  #3F66FB !important;
  }
</style>
<div class="table-responsive" id ="paginationData">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">

              @php
              function onLeave($date, $user_id) {
                $leaveDates = \App\UserLeave::where('users_id', $user_id)->select('start_date', 'end_date', 'leave_status')->get();
                
                foreach ($leaveDates as $leave) {
                    $startDate = $leave->start_date;
                    $endDate = $leave->end_date;
                    $leaveStatus = $leave->leave_status;
                    if ($date >= $startDate && $date <= $endDate && $leaveStatus === 'approved') {
                        return true;
                    }
                }                        
                return false;
              }
            @endphp

            @if(!$attendance->isEmpty())
                  <?php $old_date = ''; ?>
                @foreach($attendance as $index=>$value)
                @php
                  $lateTimeInColor ='';
                  $currentTimeIn = '';
                  $status = 'Absent';
                  $color = 'bg-danger';

                  $onLeave = onLeave(Carbon::parse($value['date_range'])->format('Y-m-d'), $value['user_id']);
                  $status = $onLeave ? 'On Leave' : $status;
                  $color = $onLeave ? 'bg-primary' : $color;

                  if(!empty($value['total_working_hour']))
                  {
                      $status = 'Present';
                      $color = 'bg-success';
                  } else {
                    if(!empty($value['time_in'])){
                      if((Carbon::parse($value['time_in'])->format('Y-m-d'))== (Carbon::now()->format('Y-m-d')))
                      {
                        $status = 'Working';
                        $color = 'bg-warning';
                      } 
                       if(!empty($value['late_reason'])){
                       $lateTimeInColor = 'red';
                       $date = new DateTime($value['created_at'], new DateTimeZone('UTC'));
                       $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                       $currentTimeIn =  $date->format('Y-m-d H:i:s ');
                     }
                    }
                    
                  }
                  // $new_date = Carbon::parse($value->created_at)->format('d-m-Y');
                  $new_date = Carbon::parse($value['date_range'])->format('d-m-Y');
                  @endphp
                  <tr>
                  @if($old_date != $new_date)
                      <?php $old_date = $new_date; ?>
                      @if($old_date != '')
                        <tr>
                          <td colspan="12" style="text-align: center;font-weight: bold;font-size: 18px;background: #555555;color: white">
                            <?php
                            if(!empty( $_GET['daterange'])){
                              $date_range = $_GET['daterange'];
                              list($start, $end) = explode(" - ", $date_range);
                              if ($start === $end) {
                                  echo str_replace('/', '-', $start);
                              } else {
                                  echo str_replace('/', '-', $start) . ' to ' . str_replace('/', '-', $end);
                              }
                            } else {
                              $start = Carbon::now()->format('Y-m-d'); 
                              $end = Carbon::now()->format('Y-m-d'); 
                              echo($new_date);
                            }
                            ?>
                            </td>
                        </tr>
                        <tr>
                          <th scope="col" class="sort" data-sort="budget">Date</th>
                          <th scope="col" class="sort" data-sort="status">Employee Name</th>
                          @if($start == $end)
                            <th scope="col" style="text-align: center; ">Time In</th>
                            <th scope="col" style="text-align: center; ">Time Out</th>
                          @endif
                          <th scope="col" style="text-align: center; ">Total Working Hours</th>
                          <th scope="col" style="text-align: center; ">Total Time Spend in Office</th>
                          <th scope="col" style="text-align: center; ">Work Mode</th>
                          @if($start == $end)
                            <th scope="col" style="text-align: center; ">Status</th>
                          @endif
                          <th scope="col" style="text-align: center; padding-right: 23px !important;">Send Email</th>
                        </tr>
                      @endif
                  @endif
             
            </thead>
            <tbody class="list">
              <tr>
                <td>
                  @if(str_contains($dateRange, "to")) 
                  @if(!empty($value['user_profile']))
                  <a href="javascript:;" date='{{ $dateRange}}' url="{{url('attendance/monthly-attendence/'.$value['user_profile']['id'].'/'.$dateRange)}}"  class="monthlyAttendence">{{ $dateRange}} </a>
                  <!-- Carbon::parse($value['date_range'])->format('d-m-Y')  -->
                  @else 
                  <a href="javascript:;" date='{{ $dateRange}}' url="{{url('attendance/monthly-attendence/'.$value['user_id'].'/'.$dateRange)}}" class="monthlyAttendence">{{ $dateRange }} </a>
                  @endif
                  @else 
                  @if(!empty($value['user_profile']))
                  <a href="{{url('attendance/bio-metric-detail/'.$value['user_profile']['employee_code'].'/'.$dateRange)}}">{{ Carbon::parse($dateRange)->format('d-m-Y')}} </a>
                  <!-- Carbon::parse($value['date_range'])->format('d-m-Y')  -->
                  @else 
                  <a href="{{url('attendance/bio-metric-detail/'.$value['employee_code'].'/'.$dateRange)}}">{{ Carbon::parse($dateRange)->format('d-m-Y') }} </a>
                  @endif
                  @endif
               </td>
                <td>
                  @if(!empty($value['user_profile']))
                  {{ $value['user_profile']['first_name'] .' ' .$value['user_profile']['last_name'] }}
                  @else
                  {{ $value['first_name'] .' ' .$value['last_name']}}
                  @endif
                </td>
               
                @if($start == $end)
                  {{-- <td><span class="{{$lateTimeInColor}}">{{!empty($value['time_in']) ? \Carbon\Carbon::parse($value['time_in'])->format('g:i A') : '-'}}   </span> @if($lateTimeInColor =='red') <div class="far fa-clock" data-toggle="modal" data-target="#TimeInModal" id="{{$value['id']}}" currentTimeIn="{{$currentTimeIn}}" lateReason="{{$value['late_reason']}}">@endif</td> --}}
                    <td style="text-align: center; ">
                      @php
                          $lateTimeInColor = (!empty($value['time_in']) && \Carbon\Carbon::parse($value['time_in'])->format('H:i') >= '09:30') ? 'red' : '';
                      @endphp
                      <span class="{{ $lateTimeInColor }}">
                          {{ !empty($value['time_in']) ? \Carbon\Carbon::parse($value['time_in'])->format('g:i A') : '-' }}
                      </span>
                      @if ($lateTimeInColor == 'red')
                          <div class="far fa-clock" data-toggle="modal" data-target="#TimeInModal"
                              id="{{ $value['user_id'] }}" currentTimeIn="{{ $currentTimeIn }}"
                              lateReason="{{ !empty($value['late_reason']) ?$value['late_reason'] : '' }}">
                              <!-- {{ $value['late_reason'] }} -->
                          </div>
                      @endif
                  </td>
                  <td style="text-align: center; ">{{ !empty($value['time_out']) ? \Carbon\Carbon::parse($value['time_out'])->format('g:i A') : '-'}}</td>
                @endif
                  {{-- <td style="text-align: center; ">{{ !empty($value['total_working_hour']) ? $value['total_working_hour'] : '-'}}</td> --}}
                  <td style="text-align: center;">
                    @if ($start != $end)
                        @php
                          $total_working_hour = 0;
                          $startDate = DateTime::createFromFormat('d/m/Y', $start);
                          $endDate = DateTime::createFromFormat('d/m/Y', $end);
                          $total_attendance = \App\Attendance::where('user_id', $value['user_id'])->get();
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
                                      break;
                                  }
                              }
                              $startDate->modify('+1 day'); 
                          }
                              $hours = floor($total_working_hour / 3600);
                              $minutes = floor(($total_working_hour % 3600) / 60);
                              $seconds = $total_working_hour % 60;
                              $total_working_hour_formatted = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);   
                              echo $total_working_hour_formatted != '00:00:00' ? $total_working_hour_formatted : '-';
                          }
                        @endphp
                    @else
                        {{ !empty($value['total_working_hour']) ? $value['total_working_hour'] : '-' }}
                    @endif
                </td>
                  <td style="text-align: center; ">
                    @if ($start != $end)
                      @php
                        if (isset($value['total_hours'])) {
                            $totalSeconds = array_sum(array_map(function ($value) {
                                [$hours, $minutes, $seconds] = explode(':', $value);
                                return ($hours * 3600) + ($minutes * 60) + $seconds;
                            }, $value['total_hours']));
                    
                            $totalHours = floor($totalSeconds / 3600);
                            $totalMinutes = floor(($totalSeconds % 3600) / 60);
                            $totalSeconds = $totalSeconds % 60;
                    
                            $totalTime = sprintf("%02d:%02d:%02d", $totalHours, $totalMinutes, $totalSeconds);
                            echo $totalTime != '00:00:00' ? $totalTime : '-';
                        } else {
                            echo '-';
                        }
                    @endphp
                    @else
                      {{ isset($value['total_hours']) ? implode(', ', array_map(function ($time) {
                          return date('H:i:s', strtotime($time));
                      }, $value['total_hours'])) : '-' }}
                    @endif
                  </td>
                  <td style="text-align: center;">
                    @if(!empty($value['work_mode']))
                        @if($value['work_mode'] === 'WFH')
                            WFH
                        @elseif($value['work_mode'] === 'WFO')
                            WFO
                        @elseif($value['work_mode'] === 'Hybrid')
                            Hybrid
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                </td>
                @if($start == $end)
                  <td style="text-align: center; ">
                  <span class="badge badge-dot mr-4">
                    <i class="{{$color}}"></i>
                    <span class="status">{{$status}}</span>
                  </span>
                </td>
                @endif
                <td class="send_email" title="Send Email" style="text-align: center; width: 10px; " data-toggle="modal" data-target=".send_mail_modal" value="{{ \App\User::where('id', $value['user_id'])->value('email') }}">
                  <i class="fa-solid fa-paper-plane"></i>
                </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
          <div id="success_message" class="alert alert-success" role="alert" style="position: absolute; top: -10.5rem; display: none">
            Mail sent successfully!
          </div>
          <div class="common pagination">
            {{ $attendance->appends(['search' => $search,'work_mode' => $work_mode,'daterange' => Request::get('daterange') ,'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
          </div>
        </div>
        <!-- Card footer -->
        <!-- <div class="card-footer py-4">
            
        </div> -->

        <script type="text/javascript"> $('.fa-clock').click(function(){ 
 $("#Time").html($(this).attr("currentTimeIn"));
  $("#reason").html($(this).attr("lateReason"));
  }); </script>

<script>
  // sending attendance mail
  $(document).ready(function () {
    $(".send_email").click(function () {
      var id = $(this).attr("value");
      $(".to_email_address").val(id);
      $(".from_email_address").val("{{Auth::user()->email}}");
      $('input[name="dates"]').daterangepicker({
          startDate: moment().format('DD/MM/YYYY'),
          endDate: moment().format('DD/MM/YYYY'),
          maxDate: moment().format('DD/MM/YYYY'),
          locale: {
              format: 'DD/MM/YYYY',
              cancelLabel: 'Clear'
          }
      });
    });
  });
  $('.monthlyAttendence').on('click', function() {
  // let dates = $(this).attr('date');
  // let emp_id = $(this).attr('empid');
    var search = jQuery('#attendance-search').val();
    var work_mode = jQuery('#attendance-search').val();
   var url = $(this).attr('url');
    var entriesperpage = jQuery('.entriesperpage :selected').val();
    searchMonthlyAttendance(url);
}); 
</script>
      