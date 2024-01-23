@extends('layouts.page')
@section('content')

<?php 
use Carbon\Carbon;
// use DateTime;
// use DateTimeZone;
?>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7 secLeft">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Bio Metric Attendance</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right formBOx formResponsive">
        <form action="{{url('attendance/export_user_attendance')}}" method="post" id="export_attendance_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="row">
                <div class="col-md-12 text-right d-flex justify-content-end">
                    <input type="text" id="attendance_dates" autocomplete="off"placeholder="Date Range" name="dates" class="form-control">
                    <button type="submit" class="btn btn-primary">   Export         </button>
                </div>
            </div>
        </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card minHeight">
        <!-- Card header -->
        <div class=" row mx-0 card-header border-0">
        <div class='col-md-3'><h3 class="mb-0">Bio Metric Attendance</h3></div>
          <div class='col-md-3'><b>Name:</b> {{$name}}</div>
          <div class='col-md-3'><b>Employee ID:</b> {{$id}}</div>
        </div>
        
        <!-- Light table -->
        <div class="table-responsive" id ="paginationData">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col" class="sort" data-sort="budget">Date</th>
                <th scope="col">Time In</th>
                <th scope="col">Time Out</th>
                <!-- <th scope="col">Total Working Hours</th>
                <th scope="col">Status</th> -->
            </thead>
            <tbody class="list">
            @if(!empty($attendance))
              @php
              $total_spent_hours = '';
              $totalHours = new DateTime($date." 00:00:00");
              @endphp
                @foreach($attendance as $index=>$value)
                    @php
                    $today = date('Y-m-d');
                    $time_in = new \DateTime($value->check_in_time);
                    $time_out_date = new \DateTime($value->check_out_time);
                    if(!empty($value->check_out_time)) {
                    $interval = $time_in->diff($time_out_date);
                    list($hours, $minutes, $seconds) = explode(':', $interval->format('%H:%I:%S')); 
                    $totalHours = $totalHours->add(new DateInterval('PT'.$hours.'H'.$minutes.'M'.$seconds.'S'));
                    }
                    @endphp
                    @if($date == $today)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ !empty($value->check_in_time) ? \Carbon\Carbon::parse($value->check_in_time)->format('g:i A') : '-' }}</td>
                        <td>{{ !empty($value->check_out_time) ? \Carbon\Carbon::parse($value->check_out_time)->format('g:i A') : '-' }}</td>
                    </tr>
                    @else 
                    @if(!empty($value->check_out_time))
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ !empty($value->check_in_time) ? \Carbon\Carbon::parse($value->check_in_time)->format('g:i A') : '-' }}</td>
                        <td>{{ !empty($value->check_out_time) ? \Carbon\Carbon::parse($value->check_out_time)->format('g:i A') : '-' }}</td>
                    </tr>
                    @endif
                    @endif
                @endforeach
            @else
                <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
            @endif
            </tbody>
          </table>
          @if(!empty($attendance))
          <div class="card-header border-0 text-right">
            <span>Total Hours Spent: {{!empty($totalHours->format('H:i:s')) ? $totalHours->format('H:i:s') : ''}}</span>
          </div>
          @endif
            <!-- <div class="card-footer py-4">
                <div class="common pagination">
                    
                </div>
            </div> -->
        </div>
        <!-- Card footer -->

      </div>
    </div>
  </div>
</div>
@section('script')
<script>
        var searchUrl = 'user-attendance-list';
    </script>
    <script src="{{ URL::asset('js/custom.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('input[name="dates"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $('#attendance_dates').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            let dates = $(this).val();
            var search = jQuery('.searchBox').val();
            var entriesperpage = jQuery('.entriesperpage :selected').val();
            searchAttendance(search, dates ,entriesperpage )
        });

        $('#attendance_dates').on('cancel.daterangepicker', function () {
            $(this).val('');
            var entriesperpage = jQuery('.entriesperpage :selected').val();
            var search = jQuery('.searchBox').val();
            searchAttendance(search, '' ,entriesperpage )
        });
    </script>
@endsection
@endsection
