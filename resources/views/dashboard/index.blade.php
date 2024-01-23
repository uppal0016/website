@extends('layouts.page')

@section('content')
<?php  date_default_timezone_set("Asia/Kolkata"); 
use Carbon\Carbon;

$current_time_in = Carbon::parse(Carbon::now()->format('d-m-Y H:i:s'));

$env_time_in = env('TimeIn', '00:00');
$date_time_string = now()->format('d-m-Y') . ' ' . $env_time_in . ':00';
$current_shift_time_in = Carbon::parse($date_time_string);

if(\App\Attendance::where('user_id', Auth::id())){
    $desired_date = now()->format('Y-m-d');
    $attendance = \App\Attendance::where('user_id', Auth::id())->whereDate('time_in', $desired_date)->first();
    if(Auth::user()->shift_start_time) {//< \Carbon\Carbon::parse()->format('d-m-Y '.Auth::user()->shift_start_time.''
        if ($attendance && strtotime($attendance['time_in']) > strtotime(\Carbon\Carbon::parse()->format('d-m-Y '.Auth::user()->shift_start_time.'')) && empty($attendance['late_reason'])) {
            echo '<script>
                    setInterval(()=>{
                        $("#myModal").modal("show");
                    },500); 
                </script>';
        } else {
            echo '<script>$("#myModal").modal("hide");</script>';
        }
    } else {
        if ($attendance && strtotime($attendance['time_in']) > strtotime($current_shift_time_in) && empty($attendance['late_reason'])) {
            echo '<script>
                    setInterval(()=>{
                        $("#myModal").modal("show");
                    },500); 
                </script>';
        } else {
            echo '<script>$("#myModal").modal("hide");</script>';
        }
    }
}
?>
<style type="text/css">.clockpicker-popover 
{
   z-index: 999999 !important;  
}
 .swal-text {
    font-size: 15px;
}
button.close {
    top: 19px !important;
}
</style>
<!-- Header -->
<!-- Header -->
<link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-6">
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-6 col-6 cardTime hide-on-mobile">
                    <div class="card_box  @if(@$time_in || @$today_attendance) @else time_in @endif">
                        @if (( auth()->user()->role_id == 3) || ( auth()->user()->role_id == 4) || ( auth()->user()->role_id == 5))
                            {{-- <div class="mb-2">                        
                            @if(\Carbon\Carbon::now()->format('d-m-Y H:i:s') < \Carbon\Carbon::parse()->format('d-m-Y '.Auth::user()->shift_start_time.''))
                                <a href="{{ url('attendance/time_in') }}" dataid="ddd" id="time_in_button" class="btn btn-primary">Time In</a>                                 
                                   @else
                                  @if(\Carbon\Carbon::now()->format('d-m-Y H:i:s') < \Carbon\Carbon::parse()->format('d-m-Y '.env('TimeIn').':00') && Auth::user()->shift_start_time == '')
                                 <a href="{{ url('attendance/time_in') }}" dataid="{{Auth::user()->shift_start_time}}"  id="time_in_button" class="btn btn-primary">Time In</a>
                                 @else
                                   <a href="javascript:void(0);"  data-toggle="modal" data-target="#myModal" id="time_in_button" dataid="{{Auth::user()->shift_start_time}}" class="btn btn-primary">Time In</a>
                                   @endif
                                @endif


                            </div> --}}

                            <form action="{{ url('attendance/time_in') }}" method="POST">
                                @csrf
                                {{-- @if(Auth::user()->work_mode != 'WFO' ) --}}
                                    @if(\Carbon\Carbon::now()->format('d-m-Y H:i:s') < \Carbon\Carbon::parse()->format('d-m-Y '.Auth::user()->shift_start_time.'')) 
                                        <button type="submit" class="btn btn-primary" dataid="ddd" id="time_in_button">Time In</button>
                                    @else
                                        @if($current_time_in < $current_shift_time_in && Auth::user()->shift_start_time == '')
                                        {{-- Check here whether the system time is < or > than the env time in i.e. 09:30 --}}
                                            <button type="submit" class="btn btn-primary" dataid="{{Auth::user()->shift_start_time}}"  id="time_in_button" >Time In</button>
                                        @else
                                            <button type="button" data-toggle="modal" data-target="#myModal" id="time_in_button" dataid="{{Auth::user()->shift_start_time}}" class="btn btn-primary">Time In</button>
                                        @endif
                                    @endif
                                {{-- @endif --}}
                            </form>


                            <div class ="mb-2">
                                @if(@$time_in)
                                    <span class="countdown"><span class="h1 font-weight-bold">Time in at {{ \Carbon\Carbon::parse($time_in)->format('g:i a')}}</span></span>
                                @endif
                            </div>
                            <div class="mb-2">
                                <span class="countdown text-bold" ><span class="font-weight-bolder" id="timer_id"></span></span>
                                {{--<input type="hidden" id="today_att_exists" value="{{ \Carbon\Carbon::parse(@$time_in)->format('d-m-Y H:i:s')  }}">--}}
                                <input type="hidden" id="today_att_exists" value="{{ @$time_in  }}">
                                <input type="hidden" id="complete_attendance" value="{{ @$today_attendance }}">
                            </div>
                            {{-- @if(Auth::user()->work_mode != 'WFO' ) --}}
                                <div class="hide-on-mobile">
                                    <a href="{{url('attendance/time_out?date='.@$time_in_date)}}" onclick="return confirm('Are you sure you want to time out ?');" id="time_out_button" class="btn btn-primary">Time Out</a>
                                </div>
                            {{-- @endif --}}
                        @endif
                    </div>
                </div>
            </div>
            <!-- Card stats -->
            <div class="row font-size-h5">
                @if((auth()->user()->role_id == 1) || (auth()->user()->role_id == 2) || (auth()->user()->role_id == 3))
                <div class="col-xl-4 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total DSRs</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ isset($dashboardCounts['dsrs_received']) ? $dashboardCounts['dsrs_received'] : '0'}}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="ni ni-active-40"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Projects</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                    {{ isset($dashboardCounts['total_projects']) ? $dashboardCounts['total_projects'] : '0'}}

                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="ni ni-chart-pie-35"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Users</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                    {{ isset($dashboardCounts['total_users']) ? $dashboardCounts['total_users'] : '0'}}

                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="ni ni-money-coins"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                @else 
                <div class="col-xl-4 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-2">Total Assigned Projects</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                        {{ isset($dashboardCounts['projects_assigned']) ? $dashboardCounts['projects_assigned'] : '0'}}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="ni ni-active-40"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">   Total Leave Assigned : 
                                    <strong style="color: #32325d">  {{ $calculateleave['assingedLeave']}}  </strong>
                                    </br>
                                    Total approved Leave : 
                                    <strong style="color: #32325d">  {{ $calculateleave['approvedLeave']}}

                                    </strong>
                                   
                                    </br>
                                   Extra Leave :
                                   <strong style="color: #32325d"> 
                                    {{ $calculateleave['extraLeave']}}
                            </strong>
                              </h5>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="ni ni-chart-pie-35"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card card-stats">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-2">Total DSR received</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                    {{ isset($dashboardCounts['dsrs_received']) ? $dashboardCounts['dsrs_received'] : '0'}}

                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                        <i class="ni ni-money-coins"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-stats">
                        <!-- &lt;!&ndash; Card body &ndash;&gt; -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Quote of the day</h5>
                                    <span class="h2 font-weight-bold font-italic mb-0">
                                        {{ $quotation }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- @if(Auth::user()->role_id ==4)
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-stats">
                        <!-- &lt;!&ndash; Card body &ndash;&gt; -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="h3 mb-0">Document Manage  ( Over All Time :-{{$totaltime}})</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                         <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            @if($documentRead->isNotEmpty())
                            <table class="table align-items-center table-flush">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>User Name</th>
                                         <th> File Name</th>
                                        <th> Document Time</th>
                                        <th> Last Page</th>
                                        <th> Total read Page </th>
                                        <th> Total doc Page </th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach($documentRead as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value->user->first_name .' '.$value->user->last_name}}</td>
                                            <td><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"> </i> {{preg_replace('/\\.[^.\\s]{3,4}$/', '', $value->documents)}}
                                         </td>
                                         <td><?php  
                                        //    $secs = $value->time % 60;
                                        //    $hrs =$value->time / 60;
                                        //    $mins = $hrs % 60;                                           
                                        //    $hrs = $hrs / 60;
                                        //   echo (int)$hrs . ":" . (int)$mins . ":" . (int)$secs;
                                            ?></td>
                                           <td>{{$value->last_page}}</td>
                                           <td>{{$value->pages }}</td>
                                           <td> <?php 
                                        //    $path = public_path('images/document/'.$value->documents);
                                        //    $pdf = file_get_contents($path);
                                        //    $number = preg_match_all("/\/Page\W/", $pdf, $mdumy);
                                        //     echo $number; ?></td>
                                        </tr>
                                    @endforeach
                              
                                    
                                </tbody>
                            </table>
                              @else
                                
                                        <h5 colspan="3" class="text-center" style="font-size:13px">No Holidays in  this month.</h5>
                                   
                                @endif
                        </div>
                    </div>
                </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
            @endif --}}
             <div class="row">
                <div class="col-xl-12">
                    <div class="card card-stats">
                        <!-- &lt;!&ndash; Card body &ndash;&gt; -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="h3 mb-0">Current Month  Holidays</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                         <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            @if($upcoming_holiday->isNotEmpty())
                            <table class="table align-items-center table-flush">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Holiday Name </th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach($upcoming_holiday as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$value->title}}</td>
                                            <td>{{\Carbon\Carbon::parse($value->date)->format('d F ') }}</td>
                                        </tr>
                                    @endforeach
                              
                                    
                                </tbody>
                            </table>
                              @else
                                
                                        <h5 colspan="3" class="text-center" style="font-size:13px">No Holidays in  this month.</h5>
                                   
                                @endif
                        </div>
                    </div>
                </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-stats">
                        <!-- &lt;!&ndash; Card body &ndash;&gt; -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="h3 mb-0">Current Month  Work Anniversary</h5>
                                    <span class="h2 font-weight-bold mb-0">
                                         <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            
                            @if (isset($upcoming_work_anniversary))
                            @if($upcoming_work_anniversary->isNotEmpty())
                            <table class="table align-items-center table-flush">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Emp Name</th>
                                        <th>Designation</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                    @foreach($upcoming_work_anniversary as $value)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$value->first_name.' '.$value->last_name}}</td>
                                            <td>{{$value->name}}</td>
                                            <td>{{\Carbon\Carbon::parse($value->date)->format('d F ') }}</td>
                                        </tr>
                                    @endforeach
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Quote on Annivesary</h5>
                                                <span class="h2 font-weight-bold font-italic mb-0">
                                                    {{ $aniversary_quote }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </tbody>
                            </table>
                              @else
                                
                                        <h5 colspan="3" class="text-center" style="font-size:13px">No Anniversary Today.</h5>
                                   
                                @endif
                                                            
                                @endif
                        </div>
                    </div>
                </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6 {{!empty(@$time_in) ? 'dashTimein' : '' }}">
    <div class="row">
        <div class="col-xl-12">
            <div class="card {{ (auth()->user()->role_id == 1) || (auth()->user()->role_id == 2) ? 'adminMinHeightDash' : 'minHeightDash'}}">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="h3 mb-0">Upcoming Birthdays</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Employee Name</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if($date_of_birth->isNotEmpty())
                                    @foreach($date_of_birth as $date)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$date->first_name.' '.$date->last_name}}</td>
                                            <td>{{!empty($date->dob) ? \Carbon\Carbon::parse($date->dob)->format('d F') : '-'}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center">No Record Found</td>
                                    </tr>
                                @endif
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-6">
            <div class="card {{ (auth()->user()->role_id == 1) || (auth()->user()->role_id == 2) ? 'adminMinHeightDash' : 'minHeightDash'}}">
                <div class="card-header bg-transparent">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="h3 mb-0">List of Holidays</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <!-- Projects table -->
                            <table class="table align-items-center table-flush">
                                <thead>
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Festival Name</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($festival_dates->isNotEmpty())
                                        @foreach($festival_dates as $date)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{$date->title}}</td>
                                                <td>{{!empty($date->festival_date) ? \Carbon\Carbon::parse($date->festival_date)->format('d F') : '-'}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center">No Record Found</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
    </div>
</div>

<div id="myModal" class="modal" tabindex="-1" style="margin-top:100px">
<form action="{{ url('attendance/time_in') }}" id="lateTimeIn" method="POST">
    @csrf
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title"></h5>
            @if (Carbon::parse(\App\Attendance::where('user_id', Auth::id())->latest()->value('time_in'))->format('Y-m-d') === Carbon::now()->format('Y-m-d'))
            @else 
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            @endif
         </div>
         <div class="modal-body">
            &nbsp

           <div class="row" style="margin: 0; ">
           <input type ="hidden"  name="TimeIn" value="true">
             <label class="placeholder" for="">  Select Time For Time In  <span style="color:red">*</span></label>                
            @if (Carbon::parse(\App\Attendance::where('user_id', Auth::id())->latest()->value('time_in'))->format('Y-m-d') === Carbon::now()->format('Y-m-d'))
              <input type ="hidden"  name="time" value="{{ \Carbon\Carbon::parse(\App\Attendance::where('user_id', Auth::id())->latest()->value('time_in'))->format('H:i') }}">
              <input class="form-control timer" name="time" id="timein" type="text" placeholder="Select Time" autocomplete="off" value="{{ \Carbon\Carbon::parse(\App\Attendance::where('user_id', Auth::id())->latest()->value('time_in'))->format('H:i') }}" readonly disabled>
            @else
              <input type ="hidden"  name="time" value="{{ Carbon::now()->format('H:i') }}">
              <input class="form-control timer"  name="time"  id="timein" type="text" placeholder="Select Time" autocomplete="off" value="{{ Carbon::now()->addHours(5)->addMinutes(30)->format('H:i') }}">
            @endif
            </div>
             &nbsp &nbsp
            <div class="row" style="margin: 0; ">
             <label class="placeholder" for=""> Late Reason <span style="color:red">*</span></label>   
            <textarea class="form-control"  id="description" name="LateReason" placeholder=" Late Reason for 'Time In'" length="500" maxlength="500"></textarea>
            <span class="validate" style="color:red; font-size:11px;"></span>
         </div>
                  
            </div>

         <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Time In</button>
            
         </div>
      </div>
   </div>
</form>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">  $(".timer").clockpicker({
              placement: 'bottom',
              align: 'left',
              autoclose: true,
              default: 'now',
              donetext: "Select",
             
          });

          $('.timer').on('change', function (){             
    var today = new Date();
    var currentTime = today.getHours() + ":" + today.getMinutes();
    var timein =  this.value;
        
    if(timein > currentTime ){
        swal("Time you have selected should be before the Current Time.");
          $('.timer').val('');
           return false;
      }
          });
 $('.timer').keypress(function(e) {
    e.preventDefault();
 });
    $("#description").keyup(function(){
     var des = document.getElementsByTagName('textarea');
    var iChars = "!`@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
    for (let i = 0; i <= des.length - 1; i++) {   
      if (des[i].value == 0  &&  des[i].value != '' ||  iChars.indexOf(des[i].value.trimStart().charAt(i)) != -1){ 
      $(".validate").html("Space  and  special characters  not allowed.");
       $('button[type="submit"]').attr('disabled' , true);  
           return false;       
      }else{
        $('button[type="submit"]').attr('disabled' , false);
         $(".validate").html("");  
      }
  }
  });

  var formSubmitted = false;

$("#lateTimeIn").submit(function(e) {
    var timeInput = $('.timer').val();
    var descriptionInput = $('#description').val();

    var timeError = $('#timeError');
    var descriptionError = $('#descriptionError');

    timeError.remove();
    descriptionError.remove();

    if (timeInput === '') {
        e.preventDefault();
        $('<span class="error-message">Please select a time.</span>').attr('id', 'timeError').appendTo($('#timein').parent());
    }

    if (descriptionInput === '') {
        e.preventDefault();
        $('<span class="error-message">Please provide a description.</span>').attr('id', 'descriptionError').appendTo($('#description').parent());
    }

    if (formSubmitted) {
        e.preventDefault();
    } else {
        formSubmitted = true;
        $('button[type="submit"]').attr('disabled', true);
        setTimeout(function() {
            $('button[type="submit"]').attr('disabled', false);
            formSubmitted = false;
        }, 5000);
    }
});

// Remove validation messages when input fields gain focus
$('.timer').focus(function() {
    $('#timeError').remove();
});

$('#description').focus(function() {
    $('#descriptionError').remove();
});

</script>

@endsection
