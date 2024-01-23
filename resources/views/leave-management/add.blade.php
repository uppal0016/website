@extends('layouts.page')
@section('content')
<?php 
   use Carbon\Carbon;
   ?>
<style>
   .warning{
      color: red;
   }
</style>
@php 

$words = explode(' ', $_SERVER['REQUEST_URI']);
$showword = trim($words[count($words) - 1], '/');
if($showword == 'cancel/leave'){
$request_type = 'Cancel';}
else{
$request_type  = 'Apply';
}

@endphp
<script src="{{ asset('js/jquery.min.js') }}"></script>
<link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
<div class="header bg-primary pb-6">
   <div class="container-fluid">
      <div class="header-body">
         <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7 secLeft">
               <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
               <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                     <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Leave</li>
                  </ol>
               </nav>
            </div>
            <div class="col-lg-6 col-5 text-right formResponsive">
            </div>
         </div>
      </div>
   </div>
</div>
<div class="container-fluid mt--6">
   <div class="row">
      <div class="col" style="max-width: 100%; flex-basis:auto;">
         <div class="card minHeight">
            <!-- Card header -->
            <div class="card-header mb-3">
               <h3 class="mb-0">{{$request_type}} Leave</h3>
               @if (Auth::user()->end_probation != null)
                 @if (date('Y-m-d') < Auth::user()->end_probation)
                   <h5 class="ml-auto text-right" style="color:red">Note: You are on probation period and your applied leaves are unpaid.</h5>                    
                 @endif     
               @endif
             </div>
             
            <!-- Light table -->
            <div class="card-body">
               <div class="canvas-wrapper">
                  <div class="table__wrap">
                     <form action="javascript:void(0);" method="POST"  autocomplete="off" id="create-leave-form" enctype="multipart/form-data">
                        <table class="table input-lists">
                           <tr>
                              <div class="login-form">
                                 <div class="row">
                                    <div class="col-3">
                                       <input type="hidden" name="request_type" value="{{$request_type}}">
                                       <div class="form-group">
                                          <label class="placeholder" for="">Type <span class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             {!! Form::select('add_more_[0][type]',['full_day' => "Full Day",'half_day' => "Half Day",'short_leave' => "Short Leave", 'WFH' => "Work From Home"],null,['placeholder'=>'Select Type','class'=>'form-control required','id'=>'type']) !!}
                                          </div>
                                          <span class="text-danger">
                                          {{$errors->first('type')}}
                                          </span>
                                       </div>
                                    </div>
                                    <div class="col-3" id="fDate">
                                       <div class="form-group">
                                          <label class="placeholder" for="">Start Date <span class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             {!! Form::text('add_more_[0][start_date]', null,['placeholder'=>"From Date",'class'=>'form-control frodate required','autocomplete'=>'off' ,'id'=>'frodate' ]) !!}
                                             <span class="text-danger">
                                             {{$errors->first('start_date')}}
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-3" id="endDate">
                                       <div class="form-group">
                                          <label class="placeholder" for="">End Date <span class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             {!! Form::text('add_more_[0][end_date]', null,['placeholder'=>"To Date",'class'=>'form-control  enddate required','autocomplete'=>'off' ,'id'=>'enddate']) !!}
                                          </div>
                                          <span class="text-danger" >
                                          {{$errors->first('end_date')}}
                                          </span>
                                       </div>
                                    </div>
                                    <div class="col-3" id="date">
                                       <div class="form-group">
                                          <label class="placeholder" for=""> Date <span class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             {!! Form::text('add_more_[0][current_date]', null,['placeholder'=>"Date",'class'=>'form-control current_date required','autocomplete'=>'off', 'id'=>'current_date','value'=>'']) !!}
                                             <span class="text-danger">
                                             {{$errors->first('date')}}
                                             </span>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-3" id="date_select">
                                       <div class="form-group">
                                          <label class="placeholder" for=""> Shift <span class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             {!! Form::select('add_more_[0][shift]',['first_half' => "First Half",'second_half' => "Second Half"],null,['placeholder'=>'Select Shift','class'=>'form-control required','id'=>'']) !!}
                                          </div>
                                          <span class="text-danger" style="font-size:4px">
                                          {{$errors->first('shift')}}
                                          </span>
                                       </div>
                                    </div>
                                    <div class="col-3" id="stime">
                                       <div class="form-group">
                                          <label class="placeholder" for=""> Start Time <span class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             <select class="form-control select_btn_icon" id="start_time" name="add_more_[0][start_time]" required>
                                                <option value="">Start Time</option>
                                                <option value="09:00 AM">09:00 AM</option>
                                                <option value="09:30 AM">09:30 AM</option>
                                                <option value="10:00 AM">10:00 AM</option>
                                                <option value="10:30 AM">10:30 AM</option>
                                                <option value="11:00 AM">11:00 AM</option>
                                                <option value="11:30 AM">11:30 AM</option>
                                                <option value="12:00 PM">12:00 PM</option>
                                                <option value="03:00 PM">03:00 PM</option>
                                                <option value="03:30 PM">03:30 PM</option>
                                                <option value="04:00 PM">04:00 PM</option>
                                                <option value="04:30 PM">04:30 PM</option>
                                             </select>
                                          </div>
                                          <span class="text-danger">
                                          {{$errors->first('start_time')}}
                                          </span>
                                       </div>
                                    </div>
                                    <div class="col-2" style="padding-top:30px">
                                       <a href="javascript:void(0);" data-row="0" data-sub-row="0" class="btn btn-primary btn-md btn-circle add-rows" ><i class="fa fa-plus" aria-hidden="true" ></i></a>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-12">
                                       <div class="form-group mb-0">
                                          <label class="placeholder" for=""> Reason <span  class="warning">*</span></label>
                                          <div class="parent-selectbox">
                                             {!! Form::textarea('add_more_[0][description]',  null,['placeholder'=>"Enter Leave Reason",'rows' =>'2','autocomplete'=>'off','class'=>'form-control required']) !!}
                                          </div>
                                          <span class="text-danger" >
                                          {{$errors->first('description')}}
                                          </span>

                                          <label class="description_0" style="color:red; font-size:11px"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <hr/>
                                 <table class="table input-list">
                                 </table>
                                 <div class="row">
                                    <div class ="col-md-12">
                                       <div id="send_to">
                                          <label><b>Send To:</b></label> 
                                          @if(!empty($email_users))
                                          @foreach($email_users as $user)
                                          <?php
                                             $managerIds = explode(',', Auth::user()->reporting_manager_id);
                                             $checked = ($user->role_id == 2 || in_array($user->id, $managerIds)) ? 'checked' : '';
                                             $return = ($user->role_id == 2 || in_array($user->id, $managerIds)) ? 'false' : 'true';                                       
                                             ?>
                                          <input {{ $checked }} type="checkbox" id="check_{{$user->id}}" data-exp="{{$return == 'false' ? 'exp':''}}" name="send_to[]" onclick="return {{$return}} " value="{{ $user->id }}"> {{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} &nbsp;&nbsp;
                                          @endforeach
                                          @endif
                                       </div>
                                    </div>
                                 </div>
                                    <br>
                                    <div class="row">
                                       <div class ="col-md-12">
                                          <div class="button-group">
                                             <label><b>Add Cc:</b></label> &nbsp
                                             <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                             <span>Add Cc</span>
                                             <span class="caret"></span>
                                             </button>
                                             <ul class="dropdown-menu user_lists ccbox">
                                                <div class="row">
                                                   <div class="col-md-4"></div>
                                                   <div class="col-md-4">
                                                      <input type="text"  placeholder="Search Employee" id="ccuserserch" >
                                                      <i id="filtersubmit" class="fa fa-search" style="pointer-events:none"></i>
                                                   </div>
                                                </div>
                                                &nbsp
                                                <div class="panel-body">
                                                   <div class="table-responsive">
                                                      <div class="ccbox-list">
                                                         <table class="table-condensed">
                                                            <tbody>
                                                               <?php $tdCount = 0; ?>
                                                               @foreach($cc_users as $cc_user)
                                                               <?php 
                                                      $checked = ($teamLead == $cc_user->id) ? 'checked' : '';
                                                      $return = ($teamLead == $cc_user->id) ? 'false' : 'true';
                                                            ?>
                                                               @if($tdCount == 0)
                                                               <tr>
                                                                  @endif
                                                                  <td >
                                                                     <li>
                                                                        <input type="checkbox"   name="add_cc[]" {{$checked}}   onclick="return {{$return}} " value="{{$cc_user->id}}"/>&nbsp; {{ucfirst($cc_user->first_name)}} {{ucfirst($cc_user->last_name)}}
                                                                     </li>
                                                                  </td>
                                                                  @if($tdCount == 3)
                                                               </tr>
                                                               <?php $tdCount = 0; ?>
                                                               @else
                                                               <?php $tdCount++; ?>
                                                               @endif
                                                               @endforeach
                                                            </tbody>
                                                         </table>
                                                      </div>
                                                   </div>
                                                </div>
                                             </ul>
                                          </div>
                                          <br/>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              </br>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <input type="submit" id="btn" class="btn btn-primary  pull-center" value="Submit" />
                                 </div>
                              </div>
                           
                           </tr>
                        </table>
                     <form>
                  </div>
            </div>
         </div>
         <!-- Card footer -->
         <!-- <div class="card-footer py-4">
         </div> -->
      </div>
   </div>
</div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript"></script>
<script>   
    $(document).ready(function(){ 
     $("#frodate").datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true}).on('changeDate', (selected) => {
       var minDate = new Date(selected.date.valueOf());
       $('#enddate').val('');
       $('#enddate').datepicker({format: 'yyyy-mm-d',autoclose: true}).datepicker('setStartDate', minDate);
       
     });
    $('#current_date').datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true});
   
    if($('#type').val() ==''){
       $('#date_select').css('display', 'none');
       $('#date').css('display', 'none');
       $('#stime').css('display', 'none');
   
    }
    $("#type").change(function () {   
     var value = this.value;   
     if(value=='full_day' || value=='WFH'){  
       $("#current_date").val(''); 
       $('#fDate').css('display', 'block');
       $('#endDate').css('display', 'block');
       $('#date_select').css('display', 'none');
       $('#date').css('display', 'none');
       $('#stime').css('display', 'none');    
     }
     else if(value=='half_day'){ 
       $("#frodate").val('');
       $("#enddate").val(''); 
       $('#date_select').css('display', 'block');
       $('#date').css('display', 'block')
       $('#fDate').css('display', 'none');
       $('#endDate').css('display', 'none');
       $('#stime').css('display', 'none');      
     }
     else {
       $("#frodate").val('');
       $("#enddate").val(''); 
       $('#date_select').css('display', 'none');
       $('#date').css('display', 'block')
       $('#fDate').css('display', 'none');
       $('#endDate').css('display', 'none');
       $('#stime').css('display', 'block');    
    
     }
    
    });
   
    // append section start
    var i = 1,
    j = 1,
    leaveTypes = [],
    options = '',
    subRow = [0];

    <?php
      if($leaveTypes){ ?>
        leaveTypes = <?php echo $leaveTypes; ?>
    <?php } ?>

    leaveTypes.forEach(function(leaveType){
      options +='<option  value ="'+leaveType['type']+'">'+leaveType['type']+'</option>';
    });

     $("body").on("click", ".add-rows", function () {
    
    var type = $("#type").val();                                         
    var tpl = '<div class="row form_field_outer_row">'+'<div class="col-3"><div class="form-group"><label class="placeholder" for="">Type <span class="warning">*</span></label><div class="parent-selectbox"><select class="form-control select_btn_icon" aria-required="true" id="leavetypes'+i+'" name="add_more_['+i+'][type]" required > <option value="" >Select Type</option><option value="full_day">Full Day</option><option value="half_day">Half Day</option><option value="short_leave">Short Leave</option><option value="WFH">Work From Home</option></select></div></div></div>'+'<div class="col-3" id="fDate_'+i+'"><div class="form-group"><label class="placeholder" for="">Start Date <span class="warning">*</span></label><div class="parent-selectbox"><input placeholder="From Date" class="form-control froDate" name="add_more_['+i+'][start_date]" type="text" autocomplete="off" id="frodate_'+i+'" required></div></div></div><div class="col-3" id="endDate_'+i+'"><div class="form-group"><label class="placeholder" for="">End Date <span class="warning">*</span></label><div class="parent-selectbox"><input type ="text"  class="form-control datepicker" autocomplete="off"  id ="toDate_'+i+'" placeholder="To Date" name="add_more_['+i+'][end_date]" required></div></div></div><div class="col-3" id="date_'+i+'"><div class="form-group"><label class="placeholder" for=""> Date <span class="warning">*</span></label> <div class="parent-selectbox"><input type="text" class="form-control" id ="current_date_'+i+'" name="add_more_['+i+'][current_date]" placeholder="Date" autocomplete="off"  value ="" required></div></div></div>'+'<div class="col-3" id="date_select_'+i+'"><div class="form-group"><label class="placeholder" for=""> Shift <span class="warning">*</span></label><div class="parent-selectbox"><select class ="form-control select_btn_icon" name="add_more_['+i+'][shift]" required><option value="">Select Shift</option><option value="first_half">First Half</option><option value="second_half">Second Half</option></select></div></div></div><div class="col-3" id="stime_'+i+'"><div class="form-group"><label class="placeholder" for=""> Start Time <span class="warning">*</span></label><div class="parent-selectbox"><select class="form-control select_btn_icon" id="start_time'+i+'" name="add_more_['+i+'][start_time]" required><option value="">Start Time</option><option value="09:00">09:00 AM</option><option value="09:30 AM">09:30 AM</option><option value="10:00 AM">10:00 AM</option><option value="10:30 AM">10:30 AM</option><option value="11:00 AM">11:00 AM</option> <option value="11:30 AM">11:30 AM</option> <option value="12:00 PM">12:00 PM</option><option value="03:00 PM">03:00 PM</option>  <option value="03:30 PM">03:30 PM</option>  <option value="04:00 PM">04:00 PM</option> <option value="04:30 PM">04:30 PM</option> </select></div></div></div>' +'<div class="col-2" style="padding-top:35px"><a href="javascript:void(0);" data-row="['+i+']" data-sub-row="['+i+']" class="btn btn-success btn-sm btn-circle add-rows" style="border-radius:15px;" style="background-color:#05b1c5;"><i class="fa fa-plus" aria-hidden="true"></i></a></div>'+'<div class="col-9"><div class="form-group"><label class="placeholder" for="">Reason <span class="warning">*</span></label><div class="parent-selectbox"><textarea class="form-control" name="add_more_['+i+'][description]" autocomplete="off" rows="2" cols="50"  required placeholder="Enter Leave Reseon"  ></textarea><label class="description_'+i+'" style="color:red;font-size:11px"></label></div></div><hr/></div><div class="col-2" style="padding-top:35px"><a href="javascript:void(0);" data-row="['+i+']" data-sub-row="['+i+']" class="btn btn-danger btn-sm btn-circle remove-leave" style="border-radius:15px;"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
       
    var tpl1= '<div class="row form_field_outer_row">'+'<div class="col-3"><div class="form-group"><label class="placeholder" for="">Type <span class="warning">*</span></label><div class="parent-selectbox"><select class="form-control select_btn_icon" aria-required="true" id="leavetypes'+i+'" name="add_more_['+i+'][type]" required > <option value="" >Select Type</option><option value="full_day">Full Day</option><option value="half_day">Half Day</option><option value="short_leave">Short Leave</option><option value="WFH">Work From Home</option></select></div></div></div>'+'<div class="col-3" id="fDate_'+i+'"><div class="form-group"><label class="placeholder" for="">Start Date <span class="warning">*</span></label><div class="parent-selectbox"><input placeholder="From Date" class="form-control froDate" name="add_more_['+i+'][start_date]" type="text" autocomplete="off" id="frodate_'+i+'" required></div></div></div><div class="col-3" id="endDate_'+i+'"><div class="form-group"><label class="placeholder" for="">End Date <span class="warning">*</span></label><div class="parent-selectbox"><input type ="text"  class="form-control datepicker" autocomplete="off"  id ="toDate_'+i+'" placeholder="To Date" name="add_more_['+i+'][end_date]" required></div></div></div><div class="col-3" id="date_'+i+'"><div class="form-group"><label class="placeholder" for=""> Date <span class="warning">*</span></label> <div class="parent-selectbox"><input type="text" class="form-control" id ="current_date_'+i+'" name="add_more_['+i+'][current_date]" placeholder="Date" autocomplete="off"  value ="" required></div></div></div>'+'<div class="col-3" id="date_select_'+i+'"><div class="form-group"><label class="placeholder" for=""> Shift <span class="warning">*</span></label><div class="parent-selectbox"><select class ="form-control select_btn_icon" name="add_more_['+i+'][shift]" required><option value="">Select Shift</option><option value="first_half">First Half</option><option value="second_half">Second Half</option></select></div></div></div><div class="col-3" id="stime_'+i+'"><div class="form-group"><label class="placeholder" for=""> Start Time <span class="warning">*</span></label><div class="parent-selectbox"><select class="form-control select_btn_icon" id="start_time'+i+'" name="add_more_['+i+'][start_time]" required><option value="">Start Time</option><option value="09:00">09:00 AM</option><option value="09:30 AM">09:30 AM</option><option value="10:00 AM">10:00 AM</option><option value="10:30 AM">10:30 AM</option><option value="11:00 AM">11:00 AM</option> <option value="11:30 AM">11:30 AM</option> <option value="12:00 PM">12:00 PM</option><option value="03:00 PM">03:00 PM</option>  <option value="03:30 PM">03:30 PM</option>  <option value="04:00 PM">04:00 PM</option> <option value="04:30 PM">04:30 PM</option> </select></div></div></div>' +'<div class="col-2" style="padding-top:35px"><a href="javascript:void(0);" data-row="['+i+']" data-sub-row="['+i+']" class="btn btn-success btn-sm btn-circle add-rows" style="border-radius:15px;" style="background-color:#05b1c5;"><i class="fa fa-plus" aria-hidden="true"></i></a></div>'+'<div class="col-9"><div class="form-group"><label class="placeholder" for="">Reason <span class="warning">*</span></label><div class="parent-selectbox"><textarea class="form-control" name="add_more_['+i+'][description]" autocomplete="off" rows="2" cols="50" required placeholder="Enter Leave Reseon"  ></textarea><label class="description_'+i+'" style="color:red; font-size:11px"></label></div></div><hr/></div><div class="col-2" style="padding-top:35px"><a href="javascript:void(0);" data-row="['+i+']" data-sub-row="['+i+']" class="btn btn-danger btn-sm btn-circle remove-leave" style="border-radius:15px;"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
   
    var tpl2 = '<div class="row form_field_outer_row">'+'<div class="col-3"><div class="form-group"><label class="placeholder" for="">Type <span class="warning">*</span></label><div class="parent-selectbox"><select class="form-control select_btn_icon" aria-required="true" id="leavetypes'+i+'" name="add_more_['+i+'][type]" required > <option value="" >Select Type</option><option value="full_day">Full Day</option><option value="half_day">Half Day</option><option value="short_leave">Short Leave</option><option value="WFH">Work From Home</option></select></div></div></div>'+'<div class="col-3" id="fDate_'+i+'"><div class="form-group"><label class="placeholder" for="">Start Date <span class="warning">*</span></label><div class="parent-selectbox"><input placeholder="From Date" class="form-control froDate" name="add_more_['+i+'][start_date]" type="text" autocomplete="off" id="frodate_'+i+'" required></div></div></div><div class="col-3" id="endDate_'+i+'"><div class="form-group"><label class="placeholder" for="">End Date <span class="warning">*</span></label><div class="parent-selectbox"><input type ="text"  class="form-control datepicker" autocomplete="off"  id ="toDate_'+i+'" placeholder="To Date" name="add_more_['+i+'][end_date]" required></div></div></div><div class="col-3" id="date_'+i+'"><div class="form-group"><label class="placeholder" for=""> Date <span class="warning">*</span></label> <div class="parent-selectbox"><input type="text" class="form-control" id ="current_date_'+i+'" name="add_more_['+i+'][current_date]" placeholder="Date" autocomplete="off"  value ="" required></div></div></div>'+'<div class="col-3" id="date_select_'+i+'"><div class="form-group"><label class="placeholder" for=""> Shift <span class="warning">*</span></label><div class="parent-selectbox"><select class ="form-control select_btn_icon" name="add_more_['+i+'][shift]" required><option value="">Select Shift</option><option value="first_half">First Half</option><option value="second_half">Second Half</option></select></div></div></div><div class="col-3" id="stime_'+i+'"><div class="form-group"><label class="placeholder" for=""> Start Time <span class="warning">*</span></label><div class="parent-selectbox"><select class="form-control select_btn_icon" id="start_time'+i+'" name="add_more_['+i+'][start_time]" required><option value="">Start Time</option><option value="09:00">09:00 AM</option><option value="09:30 AM">09:30 AM</option><option value="10:00 AM">10:00 AM</option><option value="10:30 AM">10:30 AM</option><option value="11:00 AM">11:00 AM</option> <option value="11:30 AM">11:30 AM</option> <option value="12:00 PM">12:00 PM</option><option value="03:00 PM">03:00 PM</option>  <option value="03:30 PM">03:30 PM</option>  <option value="04:00 PM">04:00 PM</option> <option value="04:30 PM">04:30 PM</option> </select></div></div></div>' +'<div class="col-2" style="padding-top:35px"><a href="javascript:void(0);" data-row="['+i+']" data-sub-row="['+i+']" class="btn btn-success btn-sm btn-circle add-rows" style="border-radius:15px;" style="background-color:#05b1c5;"><i class="fa fa-plus" aria-hidden="true"></i></a></div>'+'<div class="col-9"><div class="form-group"><label class="placeholder" for="">Reason <span class="warning">*</span></label><div class="parent-selectbox"><textarea class="form-control" name="add_more_['+i+'][description]" autocomplete="off" rows="2" cols="50"  required placeholder="Enter Leave Reseon"  ></textarea><label class="description_'+i+'" style="color:red;font-size:11px"></label></div></div><hr/></div><div class="col-2" style="padding-top:35px"><a href="javascript:void(0);" data-row="['+i+']" data-sub-row="['+i+']" class="btn btn-danger btn-sm btn-circle remove-leave" style="border-radius:15px;"><i class="fa fa-times" aria-hidden="true"></i></a></div></div>';
     var k = (i); 
   
      if(type=='full_day'|| type=='WFH' ||type==''){
   
      $('.input-list').append(tpl);  
   
       $("#frodate_"+k).datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true}).on('changeDate', (selected) => {
         var minDate = new Date(selected.date.valueOf());
         $('#toDate_'+k).val('');
         $('#toDate_'+k).datepicker({format: 'yyyy-mm-d',autoclose: true}).datepicker('setStartDate', minDate);
     });
      
       $('#fDate_'+i).css('display', 'block');
       $('#endDate_'+i).css('display', 'block');
       $('#date_select_'+i).css('display', 'none');
       $('#date_'+i).css('display', 'none');
       $('#stime_'+i).css('display', 'none');
       $('#etime_'+i).css('display', 'none'); 
       $("#frodate_"+k).on("change",function(){
             var date = $(this).val();
             if( new Date ($("#frodate").val()) <=  new Date(date) && new Date($("#enddate").val()) >=  new Date(date) ){
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }                 
             });
       $("#toDate_"+k).on("change",function(){
             var date = $(this).val();
             if( new Date ($("#frodate").val()) <=  new Date(date) && new Date($("#enddate").val()) >=  new Date(date)){
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }
                 
             });
     }
     else if(type=='half_day'){     
       $('.input-list').append(tpl1);
       $('#current_date_'+k).datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true});   
       $('#date_select_'+i).css('display', 'block');
       $('#date_'+i).css('display', 'block')
       $('#fDate_'+i).css('display', 'none');
       $('#endDate_'+i).css('display', 'none');
       $('#stime_'+i).css('display', 'none');
       $('#current_date_'+k).on("change",function(){
             var date1 = $(this).val();             
             if(Date.parse($("#current_date").val())  == Date.parse(date1) || Date.parse($("#enddte").val())  == Date.parse(date1) ){              
               this.value = '';
               swal("This Date you are already selected!");    
                 return false;
                }
                 
             });    
     }
     else {
       $('.input-list').append(tpl2); 
       $('#current_date_'+k).datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true});      
       $('#date_select_'+i).css('display', 'none');
       $('#date_'+i).css('display', 'block')
       $('#fDate_'+i).css('display', 'none');
       $('#endDate_'+i).css('display', 'none');
       $('#stime_'+i).css('display', 'block');
       $('#current_date_'+k).on("change",function(){
             var date1 = $(this).val();             
             if(Date.parse($("#current_date").val())  == Date.parse(date1) || Date.parse($("#enddte").val())  == Date.parse(date1) ){              
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }
                 
             });
     }   
   $('#leavetypes'+i+'').change(function () {
     var value1 = this.value;  
     if(value1=='full_day' || value1=='WFH'){      
       $("#curent_date_"+k).val('');
       $("#frodate_"+k).datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true}).on('changeDate', (selected) => {
         var minDate = new Date(selected.date.valueOf());
       $('#toDate_'+k).val('');
       $('#toDate_'+k).datepicker({format: 'yyyy-mm-d',autoclose: true}).datepicker('setStartDate', minDate);
     });      
       $('#fDate_'+k).css('display', 'block');
       $('#endDate_'+k).css('display', 'block');
       $('#date_select_'+k).css('display', 'none');
       $('#date_'+k).css('display', 'none');
       $('#stime_'+k).css('display', 'none');
       $("#frodate_"+k).on("change",function(){
          var date = $(this).val();
          if( new Date ($("#frodate").val()) <= new Date(date) && new Date($("#enddate").val()) >=  new Date(date) || Date.parse($("#current_date").val())  == Date.parse(date)){
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }
                 
             });
             $("#toDate_"+k).on("change",function(){
          var date = $(this).val();
          if( new Date ($("#frodate").val()) <= new Date(date) && new Date($("#enddate").val()) >=  new Date(date) || Date.parse($("#current_date").val())  == Date.parse(date)){
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }
                 
             });
     }
     else if(value1=='half_day'){     
       $("#frodate_"+k).val('');
       $("#toDate_"+k).val('');
       $('#current_date_'+k).datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true});  
       $('#date_select_'+k).css('display', 'block');
       $('#date_'+k).css('display', 'block')
       $('#fDate_'+k).css('display', 'none');
       $('#endDate_'+k).css('display', 'none');
       $('#stime_'+k).css('display', 'none');
       $('#current_date_'+k).on("change",function(){
             var date1 = $(this).val();             
             if(Date.parse($("#current_date").val())  == Date.parse(date1) || new Date ($("#frodate").val()) <= new Date(date1) && new Date($("#enddate").val()) >=  new Date(date1) ){              
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }
                 
             });
     
     }
     else { 
       $("#frodate_"+k).val('');
       $("#toDate_"+k).val('');  
       $('#current_date_'+k).datepicker({startDate: '-1m',format: 'yyyy-mm-d',autoclose: true});   
       $('#date_select_'+k).css('display', 'none');
       $('#date_'+k).css('display', 'block')
       $('#fDate_'+k).css('display', 'none');
       $('#endDate_'+k).css('display', 'none');
       $('#stime_'+k).css('display', 'block');
       $('#current_date_'+k).on("change",function(){
             var date1 = $(this).val();             
             if(Date.parse($("#current_date").val())  == Date.parse(date1) || new Date ($("#frodate").val()) <= new Date(date1) && new Date($("#enddate").val()) >=  new Date(date1) ){              
               this.value = '';
               swal("This Date you are already selected.");    
                 return false;
                }
                 
             });
     }
   });
    i++;
    
    });    
$(document).on('click', '.remove-leave', function(){

      var c = confirm('Are you sure want to delete this row?');
      if(true == c){
       $(this).closest(".form_field_outer_row").remove();
       
        return true;
      } else{
        return false;
      }
    });
    
    //end append section 
    // validation Weakends days
    $(document).on('change', 'input[type=text]', function(e){   
    var day = new Date(this.value).getDay(); 
    if([6,0].includes(day)){
     e.preventDefault();
     this.value = '';
     swal("Please select working days do not apply  for weekends .");    
           return false;
     }
     });
     //end weakends Days
     //form submit
     $('form').on('keyup', function() { 
      const ele = document.getElementsByTagName('textarea');
   for (let i = 0; i <= ele.length - 1; i++) { 
     if (ele[i].value == ''){   
      $(".description_"+i).html("");  
           return false;       
      }
   }
     });
   $('form').on('submit', function() {  
   var datastring = $("#create-leave-form").serialize();
    const ele = document.getElementsByTagName('textarea');
    var iChars = "!`@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";  

    for (let i = 0; i <= ele.length - 1; i++) { 
     if (ele[i].value == ''){   
      $(".description_"+i).html(""); 
           return false;       
      } else if (ele[i].value == 0  &&  ele[i].value != '' ||iChars.indexOf(ele[i].value.charAt(i)) != -1){   
      $(".description_"+i).html("Space  and  special characters  not allowed.");  

           return false;       
      } 
      else{
        $(".description_"+i).html("");    
      }
   }
  
   $.ajax({
   headers: {
   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   },              
   url: "{{ url('leave/store') }}",
   method: 'post',
   data:datastring,   
   success: function(result){
   if(result.success == true){
   if(result.role == 4){   
   var url ="{{ url('/leave') }}"; //the url I want to redirect to
   setTimeout(function() {           
         $("#loader-body").fadeIn();
         window.location = url; 
      }, 100)
  
   }else{              
    var url ="{{ url('my/leave') }}";
     setTimeout(function() {        
         $("#loader-body").fadeIn();
         window.location = url; 
      }, 100)
      }
   }else{
   $(window).scrollTop(0);
   $('.ajax-danger-alert').show();
   $('.ajax-danger-alert').html(result.error);
   setTimeout(function(){
   $(".ajax-danger-alert").fadeOut();
   }, 3000);
   return false;
   }
   }
   });
   });
   //end submit   
   $('.ff').click(function(){
         $("#loader-body").fadeIn();
       });
   
   });
   
</script>
@endsection