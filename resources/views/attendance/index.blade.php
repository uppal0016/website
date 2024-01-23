@extends('layouts.page')
@section('content')

<?php
  use Carbon\Carbon;
?>
<style type="text/css"> 
.red{
  color:red;
}

#reason{
  inline-size: 200px; 
   overflow-wrap: break-word;
}

@media (max-width: 700px) {
    .common.pagination {
      position: relative;
      width: 45rem;
    }
}
@media only screen and (max-width: 975px) and (min-width: 809px)  {
  .d-md-inline-block{
    display: none !important 
  }
}

@media only screen and (max-width: 809px) and (min-width: 400px)  {
  .commdiv.mr-4.ml-0{
    display: none !important 
  }

  .d-md-inline-block{
    display: none !important 
  }
  
  .common.pagination{
    margin-left: 11rem;
  }
}

@media only screen and (max-width: 563px) and (min-width: 333px)  {
  .input-group.custom-searchfeild{
    width: 9rem;
  }

  .common.pagination{
    margin-left: 23rem;
  }
}

.select_btn_icon {
  background-position: right 9px !important;
}



.dropdown {
    position: relative;
}

.dropdown-menu {
    display: none;
    position: absolute;
    z-index: 1000;
    left: -14rem;
    top: 55px;
    background-color: #ffffff;
    min-width: 12vw;
    border: 1px solid #ccc;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.show .dropdown-menu {
    display: block;
}

.dropdown-item {
    padding: 8px;
    cursor: pointer;
}

.dropdown-item:hover {
    background-color: #f2f2f2;
}

.work_mode_title{
  display: none;
}

#loader {
  position: absolute;
    left: 50%;
    right: 50%;
    top: 50%;
    bottom: 50%;
}

a.dsr_linking {
    color: #556382;
}
/* for sending the attendance mail */
.to_email_address, .from_email_address{
  width: 85%;
  margin: 4% 0 4% 0;
  border-radius: 4px;
  border: 2px solid #80808078;
  color: #4c4b4b;
}

.mail_date{
  width: 42%;
  border-radius: 4px;
  border: 2px solid #80808078;
  height: 2rem;
  color: black;
}

.send_email{
  cursor: pointer;
}

.date_error, .from_email_error, .to_email_error{
  color: red;
  font-size: 11px;
  font-weight: 600;
  margin: 7px 0 -7px 4.5rem;
}
</style>

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
        <div class="col-lg-6 col-5 text-right formResponsive"  >
        <form action="{{url('attendance/export_employee_attendance')}}" method="post" id="export_attendance_form">
          <div class="searchBoxes">
            <div class="commdiv mr-4 ml-0">
              <select name="work_mode" class="form-control stock_drpDwn select_btn_icon" rel="work_mode" id="work_mode" style="width: 11rem; height: 3rem;">
                <option value="" style="display:none">Select Work Mode</option>
                <option value="WFO">Work from Office</option>
                <option value="WFH">Work from Home</option>
                <option value="Hybrid">Hybrid</option>
            </select>
            </div>
            
          <div class="max-260 searchBox " style="margin-right: 18px; ">
            <div class="input-group custom-searchfeild">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <input autocomplete="off" name="search" type="text" class="form-control searchBox" placeholder="Search by name" aria-describedby="button-addon6" id="attendance-search" style="width: 14rem; height: 3rem;">
              <i class="fa fa-search"></i>
                <div id="suggestions" class="dropdown">
                  <ul class="dropdown-menu" id="suggestion-list"></ul>
              </div>
            </div>
          </div>
          <div class="max-260 " style="margin-right: 0px !important;">
            <input type="text" id="dates" autocomplete="off" placeholder="Date Range" name="dates" class="form-control" style="width: 13rem; height: 3rem;">
          </div>
          <div class="px-3">
            <a href="{{ route("attendance.attendance.list") }}" id="redirect_button">
                <button class="btn btn-danger" type="button" name="submit" style="width: 53px !important; height: 100% !important;" title="Reset the filters" id="reset_button">
                    <i class="fa fa-times"></i>
                </button>
              </a>
          </div>
          <div class="expBtn text-left">
              <button type="submit" class="btn btn-primary" id="attendanceExport" style="height: 3rem;">
                  Export
              </button>
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
                       $currentTimeIn =  $date->format('Y-m-d H:i A ');
                     }
                    }
                  }
                  $new_date = Carbon::parse($value['date_range'])->format('d-m-Y');
                  @endphp
                <tr>
                  @if($old_date != $new_date)
                      <?php $old_date = $new_date; ?>
                      @if($old_date != '')
                        <tr>
                          <td colspan="12" style="text-align: center;font-weight: bold;font-size: 18px;background: #555555;color: white">{{ $old_date }}</td>
                        </tr>
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
                      @endif
                  @endif
             
            </thead>
            <tbody class="list">
              <tr>
                {{-- <td class="date" title="Date" data-toggle="modal" data-target=".biometric_user_detail_modal" value="{{ \App\User::where('id', $value['user_id'])->value('employee_code') }}">{{ Carbon::parse($value['date_range'])->format('d-m-Y') }}  </td> --}}
                <td>
                  @if(!empty($value['user_profile']))
                  <a href="{{url('attendance/bio-metric-detail/'.$value['employee_code'].'/'.Carbon::parse($value['date_range'])->format('Y-m-d'))}}">{{ Carbon::parse($value['date_range'])->format('d-m-Y') }} </a>
                  @else 
                  <a href="{{url('attendance/bio-metric-detail/'.$value['employee_code'].'/'.Carbon::parse($value['date_range'])->format('Y-m-d'))}}">{{ Carbon::parse($value['date_range'])->format('d-m-Y') }} </a>
                  @endif
                 </td>
                <td>
                  @if(!empty($value['user_profile']))
                  {{ $value['user_profile']['first_name'] .' ' .$value['user_profile']['last_name'] }}
                  @else
                  {{ $value['first_name'] .' ' .$value['last_name']}}
                  @endif
                </td>
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
                  <td style="text-align: center;">
                    {{ !empty($value['total_working_hour']) ? $value['total_working_hour'] : '-' }}
                </td>                  
                <td style="text-align: center;">
                  {{ !empty($value['total_hours']) ? implode(', ', $value['total_hours']) : '-' }}
              </td>         
                {{-- <td style="text-align: center; ">{{isset($value['total_hours']) ? $value['total_hours']->format('H:i:s') : '-'}}</td> --}}
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
            {{ $attendance->appends(['search' => Request::get('search'),'work_mode' => Request::get('work_mode'),'daterange' => Request::get('daterange') ,'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
          </div>
        </div>
        <!-- Card footer -->
        <!-- <div class="card-footer py-4">
            
        </div> -->
      </div>
    </div>
  </div>
</div>
<div id="TimeInModal" class="modal fade" tabindex="-1" style="margin-top:200px; margin-left:100px;">
   <input type ="hidden" id= "leaveid" value="">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Attendance Details</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
           <div class="container">
            <div class="row"  style="margin-bottom:20px">
<div class="col-xl-12">
 
      <div class="">
         <p class="m-0 text-left"><strong> Actual Time In: </strong> <span id="Time"></span>
      
   </div>
</div>
<div class="col-xl-12">

<div class="">
   <p class="m-0 text-left"><strong> Late Reason: </strong><span id="reason" ></span></p>
</div>
</div>


</div>
         
         </div>
          
        
      </div>
   </div>
</div>
<div id="send_mail_modal" class="modal fade send_mail_modal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Attendance Details</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="container">
        <div class="row" style="margin-bottom: 20px">
          <div class="col-xl-12">
            <div class="email_to">
              <span style="margin-right: 6%; "> To : </span><input type="text" class="to_email_address" readonly value="">
            </div>
            <span class="to_email_error"></span>
            <div class="email_from">
              <span style="margin-right: 1.5%;"> From : </span><input type="text" class="from_email_address" readonly value="">
            </div>
            <span class="from_email_error"></span>
          </div>
          <div class="col-xl-12 d-flex" style="margin-top: 3%; ">
            <span style="width: 12.5%; "> Date : </span><input type="text" autocomplete="off" placeholder="Date Range" name="dates" class="form-control mail_date">
          </div>
          <span class="date_error"></span>
          <div class="modal_footer" style="margin: 5% 0 2% 16rem; ">
            <button type="button" class="btn btn-primary send_attendance_mail">Send Email</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@section('script')
<script>
   var url = window.location.href;
  const parts = url.split('/');
var searchUrl = parts.at(-1);
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
 
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script>
       $(document).ready(function () {          
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

  $('#dates').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
    let dates = $(this).val();
    var search = jQuery('#attendance-search').val();
    var work_mode = jQuery('#work_mode').val();
  
    var entriesperpage = jQuery('.entriesperpage :selected').val();
    var apply = 'apply';

   searchAttendance(search, dates ,entriesperpage, work_mode, apply )
});



$('#dates').on('cancel.daterangepicker', function () {
  $(this).val('');
  var entriesperpage = jQuery('.entriesperpage :selected').val();
  var search = jQuery('#attendance-search').val();
  var work_mode = jQuery('#work_mode').val();
 searchAttendance(search, '' ,entriesperpage, work_mode )
});

      $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });

      $(".fa-clock").click(function () {
  $("#Time").html($(this).attr("currentTimeIn"));
  $("#reason").html($(this).attr("lateReason"));
});

  </script>

  <script>
     $('#work_mode').on('change', function () {
        let selectedWorkMode = $(this).val();
        let searchKeyword = $('input[name="search"]').val();
        let dates = $("#dates").val();
        let entriesperpage = $(".entriesperpage :selected").val();
        searchAttendance(searchKeyword, dates, entriesperpage, selectedWorkMode);
    });
  </script>

<script>
  $(document).ready(function() {
      var suggestionsContainer = $('#suggestions');

      // Close dropdown when clicking outside
      $(document).on('click', function(event) {
          if (!suggestionsContainer.is(event.target) && suggestionsContainer.has(event.target).length === 0) {
              suggestionsContainer.removeClass('show');
          }
      });

      // $('.searchBox').on('input', function() {
      //     var inputVal = $(this).val().trim();
      //     $('#loader-body').css('display', 'none');
      //     if (inputVal.length > 3) {
      //         $.ajax({
      //             type: 'POST',
      //             url: '{{ route("attendance.searchSuggestions") }}',
      //             data: { 
      //               _token: $('input[name="_token"]').val(),
      //               query: inputVal 
      //             },
      //             success: function(response) {
      //                 var suggestionsContainer = $('#suggestion-list');
      //                 suggestionsContainer.empty();
      //                 if (response.length > 0) {
      //                     $.each(response, function(index, suggestion) {
      //                         var listItem = $('<li class="dropdown-item"></li>');
      //                         var link = $('<li class="suggestion-link">' + suggestion.text + '</li>');
      //                         link.on('click', function(event) {
      //                             event.preventDefault();
      //                             var suggestion_name = suggestion.text;
      //                             $('#attendance-search').val(suggestion_name);
      //                             $('#suggestions').removeClass('show');
      //                             search_attendance_by_name(suggestion_name); // Trigger the search by name
      //                         });

      //                         listItem.append(link);
      //                         suggestionsContainer.append(listItem);
      //                     });

      //                     $('#suggestions').addClass('show');
      //                 } else {
      //                     $('#suggestions').removeClass('show');
      //                     searchAttendance('', '', '', '');
      //                 }
      //             }
      //         }, 300);
      //     } else {
      //         $('#suggestions').removeClass('show');
      //       }
      // });

      $('.nameLink').on('click', function() {
       
        var name = $(this).html();
        search_attendance_by_name(name); // Trigger the search by name

      });

      function search_attendance_by_name(name) {
          let dates = $("#dates").val();
          let work_mode = $("#work_mode").val();
          var entriesperpage = jQuery(".entriesperpage :selected").val();
          searchAttendance(name, dates, entriesperpage, work_mode);
      }
  });
</script>

<script>
  document.getElementById("reset_button").addEventListener("click", function() {
    document.getElementById("loader-body").style.display = "block";
    setTimeout(function() {
      window.location.href = document.getElementById("redirect_button").getAttribute("href");
    }, 1000);
  });
</script>

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

$(document).ready(function() {
  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  
  // Add click event listener to the "Send Email" button
  $(".send_attendance_mail").click(function() {
    var toEmailAddress = $(".to_email_address").val();
    var fromEmailAddress = $(".from_email_address").val();
    var dateRange = $(".mail_date").val();
    
    // Perform client-side validation
    if (toEmailAddress.trim() === "") {
      $(".to_email_error").text("Please enter a valid 'To' email address.");
      return;
    } else {
      $(".to_email_error").text("");
    }
    
    if (fromEmailAddress.trim() === "") {
      $(".from_email_error").text("Please enter a valid 'From' email address.");
      return;
    } else {
      $(".from_email_error").text("");
    }
    
    if (dateRange.trim() === "") {
      $(".date_error").text("Please enter a valid date range.");
      return;
    } else {
      $(".date_error").text("");
    }
    
    // If all validation passes, proceed with the AJAX request
    var data = {
      to_email_address: toEmailAddress,
      from_email_address: fromEmailAddress,
      date_range: dateRange
    };
    
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
    });
    
    $.ajax({
      url: "/biometric/send_attendance_mail",
      type: "POST",
      data: data,
      success: function(response) {
        $("#success_message").css("display", "block");
        $(".send_mail_modal").modal("hide");

        setTimeout(function() {
          $("#success_message").css("display", "none");
        }, 5000); // 5000 milliseconds = 5 seconds
      },
      error: function(xhr, status, error) {
        // Handle any errors here
      }
    });
  });
});
</script>
@endsection
@endsection
