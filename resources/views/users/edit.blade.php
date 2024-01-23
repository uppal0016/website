@extends('layouts.page')

@section('content')
<style> .interviewbox {
  border: 1px solid #dee2e6;
  padding: 10px;
  border-radius: 5px;
}

button.multiselect.dropdown-toggle.btn.btn-default {
    background-color: white;
    color: #a0a0a0;
    border: 0.5px solid #dddddd;
}

.btn-group {
    width: 100%;
}

ul.multiselect-container.dropdown-menu.show {
    width: 100%;
}

button.multiselect.dropdown-toggle.btn.btn-default::after {
    margin-left: 98%;
}

span.multiselect-selected-text {
    position: absolute;
	left: 17px;
    top: 9px;
}

button.multiselect.dropdown-toggle.btn.btn-default {
    overflow-x: hidden;
}
</style>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="{{ url('/admin/users') }}">Employees</a></li>
              <li class="breadcrumb-item active" aria-current="page">Edit Employee</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Edit Employee</h3>
        </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
                      {{-- <div class="employee_loader" style="display: none;">
                          <img style=" margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                      </div> --}}
                      <div class="editForm">
                      <?php
                $route = '/admin/users';
                ?>
					  @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3  || auth()->user()->role_id == 5 || auth()->user()->role_id == 2)
            <form id="add_user" method="post" action="{{ url($route,['id' => $user->en_id]) }}" enctype="multipart/form-data">
						<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            <div class="row">
              <div class="col-sm-2 profilePIC">
							<div class="form-group">
								<label class="placeholder" for="">User Profile Pic</label>
								<div class="input-file input-file-image">
								<div class="input-file input-file-image">
                  @if(!empty($user->image))
                    <img class="img-upload-preview" width="150" src="{{URL::asset('images/profile_picture/'.$user->image)}}" alt="preview">
                  @else
                    <img class="img-upload-preview" width="150" src="{{URL::asset('images/no-image.png')}}" alt="preview">
                  @endif

                  {!! Form::file('image',['class'=> 'form-control-file valImage','id'=>'uploadImg1']); !!}
                </div>
								</div>
							</div>	
            </div>
                  <div class="col-sm-10 FormRIght">
                  <div class="row">
                              <div class="form-group col-sm-4">
                                  <label for="exampleInputEmail1">Employee Code<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                  <div class="input-group input-group-merge input-group-alternative">
                                      <input type="text" name="employee_code" id="employee_code" value="{{$user->employee_code}}" placeholder="Enter Employee Code" class="form-control" autocomplete="off" disabled>
                                  </div>
                                  <span class="text-danger">{{$errors->first('employee_code')}}</span>
                              </div>
                              <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">First Name<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="first_name" value="{{$user->first_name}}" id="first_name" placeholder="Enter first name" class="form-control" autocomplete="off"/>
                                </div>
                                <span class="text-danger">{{$errors->first('first_name')}}</span>
                              </div>
							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Last Name<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="last_name" value="{{$user->last_name}}" id="last_name" placeholder="Enter last name" class="form-control" autocomplete="off"/>
                                </div>
                                <span class="text-danger">
							  	{{$errors->first('last_name')}}
                              </span>
                              </div>
                      

							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Email Address<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="email" name="email" value="{{$user->email}}" id="email" placeholder="Enter Email Address" class="form-control" autocomplete="off"/>
                                </div>
                                <span class="text-danger">
							  	{{$errors->first('email')}}
                              </span>
                              </div>
                            


							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Mobile Number 1<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="mobile_number" value="{{$user->mobile_number}}" id="mobile_number" placeholder="Enter Mobile number" class="form-control" autocomplete="off"/>
                                </div>
                                <span class="text-danger">
							  	{{$errors->first('mobile_number')}}
                              </span>
                              </div>
                            

							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Mobile Number 2</label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="phone_number" value="{{$user->phone_number}}" id="phone_number" placeholder="Enter Mobile number" class="form-control" autocomplete="off"/>
                                </div>
                                <span class="text-danger">
							  	{{$errors->first('phone_number')}}
                              </span>
                              </div>
                            

							
                              

							  <div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Date Of Birth</label>
									<div class="input-group input-group-merge input-group-alternative">
                  <input type="text" name="date_of_birth" id="datepickerDOB" value="{{old('date_of_birth',$user->dob)}}" placeholder="Enter Date Of Birth" class="form-control" autocomplete="off"/>
									</div>
                  <span class="text-danger">
							  	{{$errors->first('date_of_birth')}}
                              </span>
								</div>
                              

							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Date Of Joining<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
									<div class="input-group input-group-merge input-group-alternative">
                  <input type="text" name="date_of_joining" id="datepickerDOJ" value="{{old('date_of_joining',$user->joining_date)}}" placeholder="Enter Date Of Joining" class="form-control" autocomplete="off"/>
									</div>
                  <span class="text-danger">
							  	{{$errors->first('date_of_joining')}}
                              </span>
								</div>

                <div class="form-group col-sm-4">
									<label for="exampleInputEmail1">End of Probation</i></label>
									  <div class="input-group input-group-merge input-group-alternative">
                      <input type="text" name="date_of_exp_probation" id="datepickerDOPX" value="{{old('date_of_exp_probation', $user->end_probation)}}" placeholder="Enter Date Of Probation" class="form-control" autocomplete="off"/>
									  </div>
								  </div>

							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Pan Number</label>
								<div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="pan_number" id="pan_number" maxlength="10" value="{{$user->pan_number}}" placeholder="Enter Pan Number" class="form-control" autocomplete="off"/>
									</div>
                  <span class="text-danger">
							  	{{$errors->first('pan_number')}}
                              </span>
								</div>
                              

							  <div class="form-group col-sm-4">
                @php $dept = Helper::getDepartments(); @endphp

							  <label for="exampleInputEmail1">Department<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
									<select name="department" class="form-control select_btn_icon">
										<option value="" disable="true" style="font-weight: bold;">--Select Department--</option>
										@if($dept)
                      @foreach($dept as $key => $value)
                        @php
                          $selected='';
                        @endphp
                        @if($key==@$user->department_id)
                          @php $selected="selected"; @endphp
                        @endif
                        <option {{$selected}} value="{{ $key }}" {{ old('department') == $value ? 'selected' : '' }}>{{ $value }}</option>
                      @endforeach
                    @endif
									</select>									
								</div>
                
                <span class="text-danger">
							  	{{$errors->first('department')}}
                              </span>
							</div>

							  <div class="form-group col-sm-4">
                @php $desg = Helper::getDesignations(); @endphp

							  <label for="exampleInputEmail1">Designation<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
									<select name="designations" class="form-control select_btn_icon">
										<option value="" disable="true" style="font-weight: bold;">--Select Designation--</option>
										@if($desg)
                                @foreach($desg as $key=>$value)
                                  @php $selected = ''; @endphp
                                  @if ($key == $user->designation_id)
                                    @php $selected = 'selected'; @endphp
                                  @endif
                                  <option {{ $selected }} {{ old('designations') == $value ? 'selected' : '' }} value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                              @endif
									</select>									
								</div>
                <span class="text-danger">
							  	{{$errors->first('designations')}}
                              </span>
							</div>
                         
              @if( $user->email != 'shilpi@talentelgia.in' && $user->email != 'advait@talentelgia.in')
              {{-- <div class="form-group col-sm-4">
                @php $manager = Helper::getManagers(); @endphp
                <label for="exampleInputEmail1">Assigning Reporting Manager<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                <div class="input-group input-group-merge input-group-alternative">
                    <select name="reportingManager[]" id="reporting_manager_one" class="form-control select_btn_icon" multiple>
                        <option value="" disable="true" style="font-weight: bold;" disabled>--Select Assigning Reporting Manager--</option>
                        @if($manager)
                            @foreach($manager as $value)
                                @php $selected = in_array($value->id, explode(',', $user->reporting_manager_id)) ? 'selected' : ''; @endphp
                                <option value="{{ $value->id }}" {{ $selected }}>{{ $value->first_name.' '.$value->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="text-danger">
                    {{$errors->first('reportingManager')}}
                </span>
            </div> --}}
            
            <div class="form-group col-sm-4">
              @php $manager = Helper::getManagers(); @endphp
              <div class="d-flex">
                  <label for="exampleInputEmail1">Assigning Reporting Manager<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
              </div>
              <div class="input-group input-group-merge input-group-alternative">
                  <select name="reportingManager[]" id="reporting_manager_one" class="form-control select_btn_icon" multiple>
                      <option value="" disable="true" style="font-weight: bold;" disabled>--Select Assigning Reporting Manager--</option>
                      @if($manager)
                          @php
                              $sequence = explode(',', $user->reporting_manager_id);
                              $sortedManager = $manager->sortBy(function ($item) use ($sequence) {
                                  return array_search($item->id, $sequence);
                              });
                          @endphp
                          @foreach($sortedManager as $index => $value)
                              @php 
                                  $selected = in_array($value->id, $sequence) ? 'selected' : ''; 
                                  $class = 'sequence-' . (array_search($value->id, $sequence) !== false ? array_search($value->id, $sequence) + 1 : ''); 
                              @endphp
                              <option value="{{ $value->id }}" {{ $selected }} class="{{ $class }}">{{ $value->first_name.' '.$value->last_name }}</option>
                          @endforeach
                      @endif
                  </select>
              </div>
              <span class="text-danger" id="selectError">
                  {{$errors->first('reportingManager')}}
              </span>
          </div>
          
            @endif

							  <div class="form-grou
                p col-sm-4">
							  <label for="exampleInputEmail1">Status<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
								<select  class="form-control select_btn_icon" name="status" id="status">
                              @php $selected = ''; @endphp
                              @if ($user->status ==  1)
                                @php $selected = 'selected'; @endphp
                              @endif
                              <option value="" style="font-weight: bold;">--Select Status--</option>
                              <option value="1" {{$user->status ==  1 ? 'selected' : ''}}>Active</option>
                              <option value="0" {{$user->status ==  0 ? 'selected' : ''}}>Inactive</option>
                            </select>								
								</div>
                <span class="text-danger">
							  	{{$errors->first('status')}}
                              </span>
								</div>
                  <div class="form-group col-sm-4">
                <label for="exampleInputEmail1"> Shift Start Time<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                <div class="input-group input-group-merge input-group-alternative">
                 <input type="time" name="shift_start_time" class="form-control" value="{{$user->shift_start_time}}">
                </div>
                 
                </div>  
                <div class="form-group col-sm-4">
                <label for="exampleInputEmail1"> Google Meet  Link</label>
                <div class="input-group input-group-merge input-group-alternative">
                 <input type="text" name="g_meet_link" class="form-control" placeholder=" Enter Link" value="{{$user->g_meet_link}}">
                </div>
                </div>
                
                <div class="form-group col-sm-4">
                  <label for="exampleInputEmail1">Work Mode<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                  <div class="input-group input-group-merge input-group-alternative">
                      <select class="form-control select_btn_icon" name="work_mode" id="work_mode">
                          @php $selected = ''; @endphp
                          <option value="" style="font-weight: bold;">--Select Work Mode--</option>
                          <option value="WFO" {{ $user->work_mode == 'WFO' ? 'selected' : '' }}>Work from Office</option>
                          <option value="WFH" {{ $user->work_mode == 'WFH' ? 'selected' : '' }}>Work from Home</option>
                          <option value="Hybrid" {{ $user->work_mode == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                      </select>
                  </div>
                  <span class="text-danger">
                      {{ $errors->first('work_mode') }}
                  </span>
              </div>

                <div class="form-group col-sm-12">
								<label for="exampleInputEmail1">Address<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
									<textarea type="text" rows="5" cols="100" name="address" id="address" placeholder="Enter Address" class="form-control" autocomplete="off">{{$user->address}}</textarea>
								</div>
                <span class="text-danger">
							  	{{$errors->first('address')}}
                              </span>
							</div>
                      <div class="form-group col-sm-12">
                          <label for="exampleInputEmail1">Permanent Address<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                          <div class="input-group input-group-merge input-group-alternative">
                              <textarea type="text" rows="5" cols="100" name="permanent_address" id="permanent_address" placeholder="Enter Permanent Address" class="form-control" autocomplete="off">{{$user->permanent_address}}</textarea>
                          </div>
                          <span class="text-danger">
									{{$errors->first('permanent_address')}}
								  </span>
                      </div>
                      <div class="form-group col-sm-12">
                          <input type="checkbox" id="check_same_address"> Same as current address
                      </div>


							  <div class="form-group col-sm-12">
							  <label class="radio-inline">Role<i class="fa fa-asterisk" style="font-size:6px;color:red"></i> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</label>								
							  <div class="input-group input-group-merge input-group-alternative">
							  <label class="radio-inline">
                @foreach($data as $role)
                              <label class="radio-inline roles_radio">
                                <input  type="radio" name="role_id"  class="is_admin_cls dis" value="{{$role->id}}" {{$role->id == $user->role_id ? 'checked' : ''}}>
                                {{$role->role}}
                              </label>
                            @endforeach						
								</div>
                <span class="text-danger">
							  	{{$errors->first('role_id')}}
                              </span>
								</div>
                @if(Auth::user()->role_id==1)
                <div class="form-group col-sm-12" id="interviewpanel">
                  <div class="interviewbox row">
							  <label class="radio-inline "> <label class="radio-inline" style="margin-top:5px">Interview Panel</label>			 &nbsp&nbsp&nbsp  <label class="switch" style="margin:0px;">
             <input type="checkbox"  name="interviewPanelStatus" class="switch-input" @if($user->interviewPanelStatus ==1)  checked  @endif>
         <span class="switch-label" data-on="On" data-off="Off"></span>
         <span class="switch-handle"></span>
          </label>
                
    </label>								
		&nbsp&nbsp
    <div class="it_ticket_dashboard col-sm-6">
      <label class="radio-inline "> <label class="radio-inline" style="margin-top:5px">It Ticket Dashboard</label>			 &nbsp&nbsp&nbsp  <label class="switch" style="margin:0px;">
        <input type="checkbox"  name="it_ticket_dashboard"  class="switch-input" @if($user->it_ticket_dashboard ==1)  checked  @endif>			
        <span class="switch-label" data-on="On" data-off="Off"></span>
        <span class="switch-handle"></span>
        </label>              
        </label>	
    </div>						
    <div class="form-group col-sm-5" id="isSchedule">
                          <input type="checkbox"  name="canScheduleInterview"  id="canScheduleInterview" value="1" @if($user->canScheduleInterview==1)  checked @else unchecked  @endif> Can Schedule Interview
                      </div>    
                     
                     
								</div>
          
                      </div>       
@endif
                              <div class="mb-3 col-sm-12">                             
                              <button type="submit" class="btn btn-primary  add-user-btn"
                                    name="submitBtn" id="submitBtn">Submit
                            </button>                            
                            </div>
                        </form>
						@endif
                      </div>
                  </div></div>
                  </div>
              </div>
          </div>
      </div>
  </div><!--/.row-->
</div>
@section('script')
<script>
	$(document).ready(function() {       
		$('#reporting_manager_one').multiselect({		
			nonSelectedText: 'Select Reporting Manager'				
		});
	});
</script>

<script>
//For date of birth
$(document).ready(function() {
  var role_id = '<?php echo$user->role_id ?>';
   if(<?php echo auth()->user()->role_id ?> == 3){
     $('.form-control').prop('readonly',true);
  }
  if(role_id ==5){   
  $('.switch-input').click(function(){
      let isChecked = $('.switch-input')[0].checked
      if(isChecked){     
      $('#canScheduleInterview').prop('enable', true);
      $('#canScheduleInterview').prop('disabled', false);
      }else{
        $('#canScheduleInterview').prop('disabled', true);
        $('#canScheduleInterview').val(0);       
      }

    }); 
  $('#canScheduleInterview').click(function(){
 let isChecked = $('#canScheduleInterview')[0].checked
  if(isChecked){
 $('#canScheduleInterview').val(1);
  }else{
  $('#canScheduleInterview').val(0);
  }

  });
    $('#isSchedule').show();
  }else{
    $('#isSchedule').hide();
  } 
	
	$('.is_admin_cls').change(function(){
	
	if(this.value== 5){
    $("[name=interviewPanelStatus]").prop("checked",true);
  $('#isSchedule').css('display', 'block');

  $('input[type="checkbox"]').click(function(){
   
    if($(this).prop("checked") == true && this.value==5){

      $('#isSchedule').css('display', 'block');
	  
    }
    else if(this.value == 'on'){ 
		     
      $('#isSchedule').hide();
    }
});
}else{

	$("input[name=interviewPanelStatus]").prop("checked",false);
  $('#isSchedule').hide();
}
	});
//   $('.is_admin_cls').change(function(){
   
//   if(this.value !=5){
//     $('#interviewpanel').show();
//     $('#isSchedule').hide();
//   }else{
//     $('#interviewpanel').show();
//     $('#isSchedule').show();
//   }
//   });
//  var role_id = '<?php echo$user->role_id ?>';
// if(role_id== 5){
//   $('#isSchedule').css('display', 'block');
//   $('input[type="checkbox"]').click(function(){
   
//     if($(this).prop("checked") == true){
//       $('#isSchedule').css('display', 'block');
//     }
//     else if(this.value == 'on'){        
//       $('#isSchedule').hide();
//     }
// });
// }else{
//   $('#isSchedule').hide();
// }
//
   $('#datepickerDOB').datepicker({
    format: "yyyy-mm-dd",
    startDate: "1950-01-01",
    endDate: moment(new Date()).format('YYYY/MM/DD'),
    todayBtn: "linked",
    autoclose: true,
    todayHighlight: true,
  });

  $('#datepickerDOPX').datepicker({
		format: "yyyy-mm-dd",
		startDate: "1950-01-01",
		todayBtn: "linked",
		autoclose: true,
		todayHighlight: true,
	});
  
   $('#datepickerDOJ').datepicker({
    format: "yyyy-mm-dd",
    startDate: "1950-01-01",
    //            endDate: moment(new Date()).format('YYYY/MM/DD'),
    todayBtn: "linked",
    autoclose: true,
    todayHighlight: true,
  });

   <?php
        if(isset($user->reporting_manager_id2)){
   ?>
            var reporting_manager_two = {{ $user->reporting_manager_id2 }};
            var selects = $('select[name*="reportingManager"]');
            selects.find(":disabled").prop("disabled", false);
            selects.find("[value='" + reporting_manager_two + "']").prop("disabled", true);
   <?php
        }
   ?>

    <?php
        if(isset($user->reporting_manager_id)){
    ?>
            var reporting_manager_one = {{ $user->reporting_manager_id }};
            var selects = $('select[name*="reportingManager2"]');
            selects.find(":disabled").prop("disabled", false);
            selects.find("[value='" + reporting_manager_one + "']").prop("disabled", true);
    <?php
        }
    ?>



   jQuery(document).on('change', '.valImage', function () {
          var file = this.files[0];
          var fileType = file["type"];
          var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
          if ($.inArray(fileType, validImageTypes) < 0) {
              alert('Error', 'Please upload jpeg,jpg, gif and png files only', 2000);
              $(this).val('');
          } else {
              var reader = new FileReader();
              reader.onload = function () {
                  $('.img-upload-preview').attr('src', reader.result);
              }
              reader.readAsDataURL(file);
          }
      });



});
</script>

<script>
jQuery("#loader-body").hide();
// $('.add-user-btn').on('click', function(){
//   jQuery("#loader-body").show();
// })

  // for showing the Assigning reporting 
    $(document).ready(function () {
        $("#reporting_manager_one").on('change', function () {
            var selectedValues = $(this).val();
            var options = $(this).find("option");
            
            options.sort(function (a, b) {
                return $.inArray(a.value, selectedValues) - $.inArray(b.value, selectedValues);
            });
            
            $(this).html(options); 
        });
    });
</script>
@endsection
@endsection
