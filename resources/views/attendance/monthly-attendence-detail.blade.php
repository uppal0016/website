<?php 
use Carbon\Carbon;
?>
<div class='row mx-0'>
<div class='col-md-6 col-lg-3'><h5><b>Employee Name: </b>{{$full_name}}</h5></div>
<div class='col-md-6 col-lg-3'><h5><b>Total Leaves:</b> {{$leavecount}}</h5></div>
<div class='col-md-6 col-lg-3'><h5><b>Total Working Hours: </b>{{$total_working_hours}}</h5></div>
<div class='col-md-6 col-lg-3'><h5><b>Total Late Count: </b>{{$late_count}}</h5></div>
</div>
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
            <tr>
                          <th scope="col" class="sort" data-sort="budget">Date</th>
                          <th scope="col" class="sort" data-sort="status">Employee Name</th>
                          <th scope="col" style="text-align: center; ">Time In</th>
                          <th scope="col" style="text-align: center; ">Time Out</th>
                          <th scope="col" style="text-align: center; ">Total Working Hours</th>
                          <th scope="col" style="text-align: center; ">Total Time Spend in Office</th>
                          <th scope="col" style="text-align: center; ">Work Mode</th>
                          <th scope="col" style="text-align: center; ">Status</th>
                          <th scope="col" style="text-align: center; padding-right: 23px !important;">Send Email</th>
                        </tr>
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
                        <!-- <tr>
                          <td colspan="7" style="text-align: center;font-weight: bold;font-size: 18px;background: #555555;color: white">{{ $old_date }}</td>
                        </tr> -->
                        
                      @endif
                  @endif
             
            </thead>
            <tbody class="list">
              <tr>
                <td>
                @if(!empty($value['user_profile']))
                <a href="{{url('attendance/bio-metric-detail/'.$value['user_profile']['employee_code'].'/'.Carbon::parse($value['date_range'])->format('Y-m-d'))}}">
                {{Carbon::parse($value['date_range'])->format('d-m-Y')}}</a> 
                @else 
                <a href="{{url('attendance/bio-metric-detail/'.$value['employee_code'].'/'.Carbon::parse($value['date_range'])->format('Y-m-d'))}}">
                {{Carbon::parse($value['date_range'])->format('d-m-Y')}}</a> 
                @endif
               </td>
                <td>
                  @if(!empty($value['user_profile']))
                  {{ $value['user_profile']['first_name'] .' ' .$value['user_profile']['last_name'] }}
                  @else
                  {{ $value['first_name'] .' ' .$value['last_name']}}
                  @endif
                </td>
               
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
                              id="{{ $value['id'] }}" currentTimeIn="{{ $currentTimeIn }}"
                              lateReason="{{ $value['late_reason'] }}">
                          </div>
                      @endif
                  </td>
                  <td style="text-align: center; ">{{ !empty($value['time_out']) ? \Carbon\Carbon::parse($value['time_out'])->format('g:i A') : '-'}}</td>
                  <td style="text-align: center; {{ !empty($value['total_working_hour']) && $value['total_working_hour'] < '09:30:00' ? 'color: red;' : 'color: green' }}">
                    {{ !empty($value['total_working_hour']) ? $value['total_working_hour'] : '-' }}
                </td>        
                <td style="text-align: center; {{ !empty($value['total_hours']) && $value['total_hours'] < '08:30:00' ? 'color: red;' : 'color: green' }}">
                  {{ !empty($value['total_hours']) ? implode(', ', $value['total_hours']) : '-'}}</td>
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
                  <td style="text-align: center; ">
                  <span class="badge badge-dot mr-4">
                    <i class="{{$color}}"></i>
                    <span class="status">{{$status}}</span>
                  </span>
                </td>
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
            {{ $attendance->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
          </div>
        </div>
        <!-- Card footer -->
        <!-- <div class="card-footer py-4">
            
        </div> -->

        <script type="text/javascript"> $('.fa-clock').click(function(){ 
 $("#Time").html($(this).attr("currentTimeIn"));
  $("#reason").html($(this).attr("lateReason"));
  });
  $('.monthlyAttendence').on('click', function() {alert('test');
  // let dates = $(this).attr('date');
  // let emp_id = $(this).attr('empid');
    var search = jQuery('#attendance-search').val();
    var work_mode = jQuery('#attendance-search').val();
   var url = $(this).attr('url');
    var entriesperpage = jQuery('.entriesperpage :selected').val();
    searchMonthlyAttendance(url);
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
</script>
      