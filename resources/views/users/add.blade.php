@extends('layouts.page')

@section('content')
<style> 
.interviewbox {
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

.text-danger2{
	font-weight: 600;
    color: red;
    box-shadow: none;
    font-size: 13px;
}

button.multiselect.dropdown-toggle.btn.btn-default::after {
    margin-left: 98%;
}

span.multiselect-selected-text {
    position: absolute;
	left: 17px;
    top: 9px;
}

span#selectError {
    font-size: 13px;
    font-weight: 700;
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
              <li class="breadcrumb-item active" aria-current="page">Add Employee</li>
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
      <div class="card minHeight">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Add Employee</h3>
        </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
					  {{-- <div class="employee_loader" style="display: none;">
						  <img style="margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
					  </div> --}}
                      <div class="editForm">
					  @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 5 || auth()->user()->role_id == 2)
					  <form id="add_user" action="{{ url('/admin/user/create') }}" method="post" enctype="multipart/form-data">
						<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

						<div class="row">
              				<div class="col-sm-2 profilePIC">
							<div class="form-group">
								<label class="placeholder" for="">User Profile Pic</label>
								<div class="input-file input-file-image">
									<img class="img-upload-preview" width="150" src="{{URL::asset('images/no-image.png')}}" alt="preview">

									{!! Form::file('image',['class'=> 'form-control-file valImage','id'=>'uploadImg1']); !!}

								</div>
							</div>
							</div>
							<div class="col-sm-10 FormRIght">
                 				 <div class="row">
                            		<div class="form-group col-sm-4">
										<label for="exampleInputEmail1">Employee Code<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
										<div class="input-group input-group-merge input-group-alternative">
										<input type="text" name="employee_code" id="employee_code" value="{{$empId}}" placeholder="Enter Employee Code" class="form-control" autocomplete="off">
										</div>
										<span class="text-danger">
                                  			{{$errors->first('employee_code')}}
                              		 	</span>
                              		</div>

                              <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">First Name<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="first_name" value="{{old('first_name')}}" id="first_name" placeholder="Enter first name" class="form-control" autocomplete="off"/>
                                </div>
								  <span class="text-danger">
							  			{{$errors->first('first_name')}}
                              	  </span>
                              </div>

                              
							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Last Name<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="last_name" value="{{old('last_name')}}" id="last_name" placeholder="Enter last name" class="form-control" autocomplete="off"/>
                                </div>
								  <span class="text-danger">
							  	{{$errors->first('last_name')}}
                              </span>
                              </div>


							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Email Address<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="email" name="email" value="{{old('email')}}" id="email" placeholder="Enter Email Address" class="form-control" autocomplete="off"/>
                                </div>
								  <span class="text-danger">
							  	{{$errors->first('email')}}
                              </span>
                              </div>



							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Password<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="password" name="password" value="{{old('password')}}" id="password" placeholder="Enter password" class="form-control" autocomplete="off"/>
                                </div>
								  <span class="text-danger">
							  	{{$errors->first('password')}}
                              </span>
                              </div>


							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Mobile Number 1<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="mobile_number" value="{{old('mobile_number')}}" id="mobile_number" placeholder="Enter Mobile number" class="form-control" autocomplete="off"/>
                                </div>
								  <span class="text-danger">
							  	{{$errors->first('mobile_number')}}
                              </span>
                              </div>


							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Mobile Number 2</label>
                                <div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="phone_number" value="{{old('phone_number')}}" id="phone_number" placeholder="Enter Mobile number" class="form-control" autocomplete="off"/>
                                </div>
								  <span class="text-danger">
							  	{{$errors->first('phone_number')}}
                              </span>
                              </div>





							  <div class="form-group col-sm-4">
								<label for="exampleInputEmail1">Date Of Birth</label>
									<div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="date_of_birth" id="datepickerDOB" value="{{old('date_of_birth')}}" placeholder="Enter Date Of Birth" class="form-control" autocomplete="off"/>
									</div>
								  <span class="text-danger">
							  	{{$errors->first('date_of_birth')}}
                              </span>
								</div>


							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Date Of Joining<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
									<div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="date_of_joining" id="datepickerDOJ" value="{{old('date_of_joining')}}" placeholder="Enter Date Of Joining" class="form-control" autocomplete="off"/>
									</div>
								  <span class="text-danger">
							  	{{$errors->first('date_of_joining')}}
                              </span>
								</div>

								<div class="form-group col-sm-4">
									<label for="exampleInputEmail1">End of Probation</i></label>
									  <div class="input-group input-group-merge input-group-alternative">
									  <input type="text" name="date_of_exp_probation" id="datepickerDOPX" value="{{old('date_of_joining')}}" placeholder="Enter Date Of Probation" class="form-control" autocomplete="off"/>
									  </div>
								  </div>

							  <div class="form-group col-sm-4">
							  	<label for="exampleInputEmail1">Pan Number</label>
								<div class="input-group input-group-merge input-group-alternative">
									<input type="text" name="pan_number" id="pan_number" maxlength="10" value="{{old('pan_number')}}" placeholder="Enter Pan Number" class="form-control" autocomplete="off"/>
									</div>
								  <span class="text-danger">
							  	{{$errors->first('pan_number')}}
                              </span>
							  </div>


							  <div class="form-group col-sm-4">
							  <label for="exampleInputEmail1">Department<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
									<select name="department" class="form-control select_btn_icon">
										<option value="" disable="true" style="font-weight: bold;">--Select Department--</option>
										@if($dept)
											@foreach($dept as $key => $value)
												<option value="{{ $value->id }}" {{ old('department') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
											@endforeach
										@endif
									</select>									
								</div>
								  <span class="text-danger">
							  	{{$errors->first('department')}}
                              </span>
							</div>


							  <div class="form-group col-sm-4">
							  <label for="exampleInputEmail1">Designation<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
									<select name="designations" class="form-control select_btn_icon">
										<option value="" disable="true" style="font-weight: bold;">--Select Designation--</option>
										@if($desg)
											@foreach($desg as $key => $value)
												<option value="{{ $value->id }}" {{ old('designations') == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
											@endforeach
										@endif
									</select>									
								</div>
								  <span class="text-danger">
							  	{{$errors->first('designations')}}
                              </span>
							</div>

							{{-- <div class="form-group col-sm-4">
								@php $manager = Helper::getManagers(); @endphp
								<label for="exampleInputEmail1">Assigning Reporting Manager<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
									<select name="reportingManager[]" id="reporting_manager_one" class="form-control select_btn_icon" multiple>
										<option value="" disable="true" style="font-weight: bold;" disabled>--Select Assigning Reporting Manager--</option>
										@if($manager)
											@foreach($manager as $value)
												<option value="{{ $value->id }}" {{ in_array($value->id, old('reportingManager', [])) ? 'selected' : '' }}>{{ $value->first_name.' '.$value->last_name }}</option>
											@endforeach
										@endif
									</select>
								</div>
								<span class="text-danger" id="selectError">
									{{$errors->first('reportingManager')}}
								</span>
							</div>					 --}}
							<div class="form-group col-sm-4">
								@php $manager = Helper::getManagers(); @endphp
								<div class="d-flex">
								<label for="exampleInputEmail1">Assigning Reporting Manager<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								</div>
								<div class="input-group input-group-merge input-group-alternative">
									<select name="reportingManager[]" id="reporting_manager_one" class="form-control select_btn_icon" multiple>
										<option value="" disable="true" style="font-weight: bold;" disabled>--Select Assigning Reporting Manager--</option>
										@if($manager)
											@foreach($manager as $value)
												<option value="{{ $value->id }}" {{ in_array($value->id, old('reportingManager', [])) ? 'selected' : '' }}>{{ $value->first_name.' '.$value->last_name }}</option>
											@endforeach
										@endif
									</select>
								</div>
								<span class="text-danger" id="selectError">
									{{$errors->first('reportingManager')}}
								</span>
							</div>												

							  <div class="form-group col-sm-4">
							  <label for="exampleInputEmail1">Status<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
								<div class="input-group input-group-merge input-group-alternative">
								<select  class="form-control select_btn_icon" name="status" id="status">
									<option value="" style="font-weight: bold;">--Select Status--</option>
									<option value="1" selected>Active</option>
									<option value="0">Inactive</option>
								</select>
								</div>
								  <span class="text-danger">
							  	{{$errors->first('status')}}
                              </span>
								</div>
             <div class="form-group col-sm-4">
                <label for="exampleInputEmail1"> Shift Start Time<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
                <div class="input-group input-group-merge input-group-alternative">
                 <input type="time" name="shift_start_time" class="form-control" value="09:30">
                </div>
                 
                </div>
               <div class="form-group col-sm-4">
                <label for="exampleInputEmail1"> Google Meet  Link</label>
                <div class="input-group input-group-merge input-group-alternative">
                 <input type="text" name="g_meet_link" class="form-control"  placeholder=" Enter Link">
                </div>
                 
                </div>

				<div class="form-group col-sm-4">
					<label for="exampleInputEmail1">Work Mode<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
					  <div class="input-group input-group-merge input-group-alternative">
					  <select  class="form-control select_btn_icon" name="work_mode" id="work_mode">
						  <option value="" style="font-weight: bold;">-- Select Work Mode --</option>
						  <option value="WFO">Work from Office</option>
						  <option value="WFH">Work from Home</option>
						  <option value="Hybrid">Hybrid</option>
					  </select>
					  </div>
						<span class="text-danger">
						{{$errors->first('work_mode')}}
					</span>
					  </div>

							  <div class="form-group col-sm-12">
									<label for="exampleInputEmail1">Current Address<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
									<div class="input-group input-group-merge input-group-alternative">
										<textarea type="text" rows="5" cols="100" name="address" id="address" placeholder="Enter Address" class="form-control" autocomplete="off">{{old('address')}}</textarea>
									</div>
									<span class="text-danger">{{$errors->first('address')}}
								  </span>
							  </div>
									 <div class="form-group col-sm-12">
										 <label for="exampleInputEmail1">Permanent Address<i class="fa fa-asterisk" style="font-size:6px;color:red"></i></label>
										 <div class="input-group input-group-merge input-group-alternative">
											 <textarea type="text" rows="5" cols="100" name="permanent_address" id="permanent_address" placeholder="Enter Permanent Address" class="form-control" autocomplete="off">{{old('permanent_address')}}</textarea>
										 </div>
										 <span class="text-danger">
									{{$errors->first('permanent_address')}}
								  </span>
									 </div>
							  <div class="form-group col-sm-12" style="cursor: default;">
								  <input type="checkbox" id="check_same_address"> Same as current address
							  </div>


							  <div class="form-group col-sm-12">
								  <p class="radio-inline">Role<i class="fa fa-asterisk" style="font-size:6px;color:red"></i> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</p>
								  <div class="input-group input-group-merge input-group-alternative">
								  <label class="radio-inline">
									@foreach($roles as $role)
										<label class="radio-inline roles_radio">
										<input  type="radio" name="role_id"  class="is_admin_cls user_role" value="{{$role->id}}" {{ old('role_id') == $role->id ? 'checked' : '' }}>

											{{$role->role}}
										</label>
									@endforeach
								  </label>
									</div>
									</div>
								  <span class="text-danger">
									{{$errors->first('role_id')}}
								  </span>
								  @if(Auth::user()->role_id==1)
				  <div class="form-group col-sm-12" id="interviewpanel">
                   <div class="interviewbox row">
				   <label class="radio-inline"> <label class="radio-inline" style="margin-top:5px">Interview Panel</label>			 &nbsp&nbsp&nbsp  <label class="switch" style="margin:0px;">
                  <input type="checkbox"  name="interviewPanelStatus"  class="switch-input">			
                  <span class="switch-label" data-on="On" data-off="Off"></span>
                  <span class="switch-handle"></span>
                  </label>              
                  </label>								
		          &nbsp&nbsp	
				  <div class="it_ticket_dashboard col-sm-6">
					<label class="radio-inline"> <label class="radio-inline" style="margin-top:5px">It Ticket Dashboard</label>			 &nbsp&nbsp&nbsp  <label class="switch" style="margin:0px;">
						<input type="checkbox"  name="it_ticket_dashboard"  class="switch-input">			
						<span class="switch-label" data-on="On" data-off="Off"></span>
						<span class="switch-handle"></span>
						</label>              
						</label>	
				</div>					
                     <div class="form-group col-sm-5" id="isSchedule">
                          <input type="checkbox"  name="canScheduleInterview" id ="canScheduleInterview" value="1"> Can Schedule Interview
                      </div>    

                     
								</div>
                      </div>
					  @endif 
                              <div class="col-sm-12 mb-3">                             
							  <button type="submit" class="btn btn-primary  add-user-btn " name="submit">Submit</button>
                            </div>
							</div>
							</div>
							</div>
                        </form>
						@endif
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div><!--/.row-->
</div>
@section('script')
<script>
//For date of birth
$(document).ready(function() {
	$('#isSchedule').hide();	
	$('.is_admin_cls').change(function(){	
	if(this.value== 5){
	$("[name=interviewPanelStatus]").prop("checked",true);
	$("#canScheduleInterview").prop("checked",false);
  $('#isSchedule').css('display', 'block');
 $('#canScheduleInterview').click(function(){
  let isChecked = $('#canScheduleInterview')[0].checked
  if(isChecked){
  $('#canScheduleInterview').val(1);
  }else{
  $('#canScheduleInterview').val(0);
  }
   $('#isSchedule').css('display', 'block');  
   
});
}else{

	$("input[name=interviewPanelStatus]").prop("checked",false);
  $('#isSchedule').hide();
}
	});
	$('#datepickerDOB').datepicker({
		format: "yyyy-mm-dd",
		endDate: '-18y',
		todayBtn: "linked",
		autoclose: true,
		todayHighlight: true,
	});

//For date of joining
	$('#datepickerDOJ').datepicker({
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
	$(document).ready(function() {       
		$('#reporting_manager_one').multiselect({		
			nonSelectedText: 'Select Reporting Manager'				
		});
	});
</script>	
<script>
	document.addEventListener("DOMContentLoaded", function () {
	const form = document.getElementById("add_user");
	const selectElement = document.getElementById("reporting_manager_one");
	const errorSpan = document.getElementById("selectError");

	form.addEventListener("submit", function (event) {
		if (selectElement.selectedOptions.length === 0) {
		event.preventDefault();
		errorSpan.textContent = "Please select at least one reporting manager.";
		} else {
		errorSpan.textContent = ""; // Clear the error message
		}
	});
	});
</script>  
<script>
	// for showing the Assigning reporting 
    $(document).ready(function () {
        $("#reporting_manager_one").on('change', function () {
            var selectedValues = $(this).val();
            var options = $(this).find("option");
            
            options.sort(function (a, b) {
                return $.inArray(a.value, selectedValues) - $.inArray(b.value, selectedValues);
            });
            
            $(this).html(options);
			console.log(selectedValues);
        });
    });
</script>
@endsection
@endsection
