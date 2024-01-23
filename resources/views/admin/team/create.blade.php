@extends('layouts.page')

@section('content')
<style type="text/css">.FormRIght .error{
  font-size:11px;
} .multiselect-dropdown span.maxselected {
  width: 80%;
} </style>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="{{ url('/admin/projects') }}">Team</a></li>
            <!--   <li class="breadcrumb-item active" aria-current="page">Add Team</li> -->
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
          <h3 class="mb-0">Add Team</h3>
        </div>
              <div class="panel-body" style="height:600px">
                  <div class="canvas-wrapper">
                      <div class="editForm">
                          <form  method="post" id="add_project_form" action="{{ url('/admin/team/store') }}">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="FormRIght">
                                <div class="row">
                                    
                                    <div class="form-group col-sm-5">
                                        <label for="exampleInputEmail1" style="font">Team Lead<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select class="form-control" name="team_lead">
                                                <option value="">Select Team Lead</option>
                                                @foreach($project_managers as $managers)
                                                    <option value="{{ $managers->id }}">{{ $managers->first_name }} {{ $managers->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger error">
                          {{$errors->first('project_manager')}}
                                        </span>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <label for="exampleInputEmail1">Employee<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select class="form-control" name="employee[]"  multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2" required>
                                                                                
                                                @foreach($employees as $user)
                                                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger">
                                {{$errors->first('team_lead')}}
                                        </span>
                                    </div>
                                  </div>
                                      <div class="row">
                                      <div class="form-group col-sm-3"> <label for="exampleInputEmail1">Leave Approval</label> <label for="exampleInputEmail1"></label><input type="checkbox" id="leave_approve" name="leave_approve" value="0"></div>
                                      <div class="form-group col-sm-3" > <label for="exampleInputEmail1">DSR Approval</label> <input type="checkbox" id="dsr_approve" name="dsr_approve" value="0"></div>
                                       <div class="form-group col-sm-3" > <label for="exampleInputEmail1">Attendance Approval</label> <input type="checkbox" id="attendance_approve"  name="attendance_approve" value="0"></div>
                                       </div>
                                      <div class="row">
                                       <div class="form-group col-sm-3" style="padding-left:30px;" > <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit</button></div>
                                </div>
                               
                            </div>
                        </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div><!--/.row-->
</div>
<script src="{{ asset('js/multiselect-dropdown.js') }}"></script>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">$(document).ready(function(){ 
$('#dsr_approve').click(function () {
    $(this).prop("checked") ? $(this).val("1") : $(this).val("0")
});
$('#leave_approve').click(function () {
    $(this).prop("checked") ? $(this).val("1") : $(this).val("0")
});
$('#attendance_approve').click(function () {
    $(this).prop("checked") ? $(this).val("1") : $(this).val("0")
});
 });</script>
@endsection