<?php
use Carbon\Carbon;
?>
<div class="table-responsive" id="paginationData" style="text-align: center;">
    <table class="table align-items-center table-flush">
        <tbody>
            @if (!$attendance->isEmpty())
                <div class="heading" style="position: absolute; top: 15px; left: 108px">
                    <div class="employee_heading">
                        @if ($attendance->count() > 0)
                            @if ($attendance->currentPage() == 1)
                                <h3 class="mb-3" style="margin-left: 1rem;"> - {{ $attendance[0]['full_name'] }}</h3>
                            @else
                                @php
                                    $firstEmployeeOnCurrentPage = $attendance->first()['full_name'];
                                @endphp
                                <h3 class="mb-3" style="margin-left: 1rem;"> - {{ $firstEmployeeOnCurrentPage }}</h3>
                            @endif
                        @else
                            <h3 class="mb-3" style="margin-left: 1rem;">No employee data available.</h3>
                        @endif
                    </div>
                </div>

                @php
                    $currentMonth = null;

                    function isHoliday($date) {
                        // $holidays = \App\Holiday::where('status', 1)->pluck('date')->toArray();
                        // return in_array($date, $holidays);
                        $holiday = \App\Holiday::where('status', 1)->where('date', $date)->first();
                        return $holiday ? $holiday->title : null;
                    }

                    function onLeave($date, $user_id) {
                        $leaveDates = \App\UserLeave::where('users_id', $user_id)->select('start_date', 'end_date', 'leave_status', 'leave_type_id')->get();

                        foreach ($leaveDates as $leave) {
                            $startDate = $leave->start_date;
                            $endDate = $leave->end_date;
                            $leaveStatus = $leave->leave_status;
                            $leaveTypeId = $leave->leave_type_id;
                            if ($date >= $startDate && $date <= $endDate && $leaveStatus === 'approved' && $leaveTypeId != 4) {
                                return true;
                            }
                        }                        
                        return false;
                    }

                    function isWeekend($date) {
                        $weekDay = date('w', strtotime($date));
                        return ($weekDay == 0 || $weekDay == 6);
                    }

                    function getDayName($date) {
                        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $weekDay = date('w', strtotime($date));
                        return $dayNames[$weekDay];
                    }
                @endphp
                @foreach ($attendance as $index => $value)
                    @php
                        $lateTimeInColor = '';
                        $currentTimeIn = '';
                        $status = 'Absent';
                        $color = 'bg-danger';

                        $isHoliday = isHoliday(Carbon::parse($value['date_range'])->format('Y-m-d'));
                        $status = $isHoliday ? 'Holiday - ' . $isHoliday : $status;
                        $color = $isHoliday ? 'bg-primary' : $color;

                        $onLeave = onLeave(Carbon::parse($value['date_range'])->format('Y-m-d'), $value['user_id']);
                        $status = $onLeave ? 'On Leave' : $status;
                        $color = $onLeave ? 'bg-primary' : $color;

                        $isWeekend = isWeekend(Carbon::parse($value['date_range'])->format('Y-m-d'));
                        if ($isWeekend) {
                            $status = getDayName(Carbon::parse($value['date_range'])->format('Y-m-d'));
                            $color = 'bg-info';
                        }

                        if (!empty($value['total_working_hour'])) {
                            $status = 'Present';
                            $color = 'bg-success';
                        } else {
                            if (!empty($value['time_in'])) {
                                if (Carbon::parse($value['time_in'])->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                                    $status = 'Working';
                                    $color = 'bg-warning';
                                }
                                if (!empty($value['late_reason'])) {
                                    $lateTimeInColor = 'red';
                                    $date = new DateTime($value['created_at'], new DateTimeZone('UTC'));
                                    $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
                                    $currentTimeIn = $date->format('Y-m-d H:i:s ');
                                }
                            }
                        }
                        
                        $new_date = Carbon::parse($value['date_range']);
                        $newMonth = $new_date->format('F - Y');
                        
                        if ($currentMonth !== $newMonth) {
                            echo '<tr>';
                            echo '<td colspan="6" style="background-color: #555555; font-size: 1.5em; font-weight: 700; color: white;">';
                            echo strtoupper($newMonth);
                            echo '</td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<th scope="col" class="sort" data-sort="budget">Date</th>';
                            echo '<th scope="col">Time In</th>';
                            echo '<th scope="col">Time Out</th>';
                            echo '<th scope="col">Total Working Hours</th>';
                            echo '<th scope="col" style="text-align: center;">Work Mode</th>';
                            echo '<th scope="col">Status</th>';
                            echo '</tr>';
                            $currentMonth = $newMonth;
                        }
                    @endphp
                    <tr>
                        <!-- <td>{{ Carbon::parse($value['date_range'])->format('d-m-Y') }} </td> -->
                        <td>
                @if(!empty($value['user_profile']))
                <a date='{{ $date}}' url="{{url('attendance/monthly-attendence/'.$value['user_profile']['user_id'].'/'.$date)}}"  class="monthlyAttendence">{{ $date}} </a>
                <!-- Carbon::parse($value['date_range'])->format('d-m-Y')  -->
                @else 
                <a date='{{ $date}}' url="{{url('attendance/monthly-attendence/'.$value['user_id'].'/'.$date)}}" class="monthlyAttendence">{{ $date }} </a>
                @endif
               </td>
                        {{-- <td><span
                                class="{{ $lateTimeInColor }}">{{ !empty($value['time_in']) ? \Carbon\Carbon::parse($value['time_in'])->format('g:i A') : '-' }}
                            </span>
                            @if ($lateTimeInColor == 'red')
                                <div class="far fa-clock" data-toggle="modal" data-target="#TimeInModal"
                                    id="{{ $value['id'] }}" currentTimeIn="{{ $currentTimeIn }}"
                                    lateReason="{{ $value['late_reason'] }}">
                            @endif
                        </td> --}}
                        <td>
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
                        <td>{{ !empty($value['time_out']) ? \Carbon\Carbon::parse($value['time_out'])->format('g:i A') : '-' }}
                        </td>
                        <td>{{ !empty($value['total_working_hour']) ? $value['total_working_hour'] : '-' }}</td>
                        <td style="text-align: center;">
                            @if (!empty($value['work_mode']))
                                @if ($value['work_mode'] === 'WFH')
                                    WFH
                                @elseif($value['work_mode'] === 'WFO')
                                    @if (\App\UserLeave::where('users_id', $value['user_id'])
                                        ->where('leave_type_id', 4)
                                        ->where('leave_status', 'approved')
                                        ->where(function ($query) use ($value) {
                                            $currentDate = Carbon::parse($value['created_at'])->format('Y-m-d');
                                            $query->where('start_date', '<=', $currentDate)
                                                ->where('end_date', '>=', $currentDate);
                                        })->exists())
                                        WFH
                                    @else
                                        WFO
                                    @endif
                                @elseif($value['work_mode'] === 'Hybrid')
                                    Hybrid
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td style="width: 19rem;">
                            <span class="badge badge-dot mr-4">
                                <i class="{{ $color }}"></i>
                                <span class="status">{{ $status }}</span>
                            </span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center"><b>No record found</b></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="common pagination">
        {{ $attendance->appends(['search' => $search, 'work_mode' => $work_mode, 'daterange' => Request::get('daterange'), 'page' => Request::get('page'), '_token' => csrf_token()])->render() }}
    </div>
</div>
<script type="text/javascript">
    $('.fa-clock').click(function() {
        $("#Time").html($(this).attr("currentTimeIn"));
        $("#reason").html($(this).attr("lateReason"));
    });
</script>
