@extends('layouts.page')
@section('content')

<?php 
use Carbon\Carbon;
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
              <li class="breadcrumb-item active" aria-current="page">Attendance</li>
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
        <div class="card-header border-0">
          <h3 class="mb-0">Attendance</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive" id ="paginationData">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col" class="sort" data-sort="budget">Date</th>
                <th scope="col">Time In</th>
                <th scope="col">Time Out</th>
                <th scope="col">Total Working Hours</th>
                <th scope="col">Status</th>
            </thead>
            <tbody class="list">
            @if(!$attendance->isEmpty())
                @foreach($attendance as $index=>$value)
                    @php
                        $status = 'Absent';
                        $color = 'bg-danger';
                        $date = Carbon::parse($value->created_at)->format('d-m-Y');
                        if(!empty($value->total_working_hour))
                        {
                            $status = 'Present';
                            $color = 'bg-success';
                        } else {
                            if(!empty($value->time_in)){
                            if((Carbon::parse($value->time_in)->format('Y-m-d'))== (Carbon::now()->format('Y-m-d')))
                            {
                                $status = 'Working';
                                $color = 'bg-warning';
                            }
                            }
                        }
                    @endphp
                    <tr>
                        <td>{{ $date }}</td>
                        <td>{{ !empty($value->time_in) ? \Carbon\Carbon::parse($value->time_in)->format('d-m-Y g:i A') : '-' }}</td>
                        <td>{{ !empty($value->time_out) ? \Carbon\Carbon::parse($value->time_out)->format('d-m-Y g:i A') : '-' }}</td>
                        <td>{{ !empty($value->total_working_hour) ? $value->total_working_hour : '-'}}</td>
                        <td>
                            <span class="badge badge-dot mr-4">
                                <i class="{{$color}}"></i>
                                <span class="status">{{$status}}</span>
                            </span>
                        </td>                    
                    </tr>
                @endforeach
            @else
                <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
            @endif
            </tbody>
          </table>
            <div class="card-footer py-4">
                <div class="common pagination">
                    {{ $attendance->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
                </div>
            </div>
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
