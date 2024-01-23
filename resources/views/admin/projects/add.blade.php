@extends('layouts.page')

@section('content')
<style>
    .select2-container .select2-selection--single{
        height: 40px !important;
        font-size: 14px !important;
        color: #dee2e6;
    }
    .select2-container--default .select2-selection--single{
        border: 1px solid #dee2e6 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        color: #8898aa !important;
        line-height: 15px !important;
    }
    .select2-container .select2-selection--single .select2-selection__rendered{
        padding-left: 0px !important;
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
              <li class="breadcrumb-item"><a href="{{ url('/admin/projects') }}">Projects</a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Project</li>
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
          <h3 class="mb-0">Add Project</h3>
        </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
                      <div class="editForm">
                          <form  method="post" id="add_project_form" action="{{ url('/admin/create_project/create') }}">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="FormRIght">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Name<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="name" value="{{old('name')}}" id="name" placeholder="Enter project name" class="form-control" autocomplete="off"/>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('name')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Start Date<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control start_date" name="start_date" id="start_date" type="text" autocomplete="off" placeholder="Start Date" value="{{old('start_date')}}" >
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('start_date')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">End Date</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control end_date" name="end_date" type="text" autocomplete="off" placeholder="End Date" value="{{old('end_date')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('end_date')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Client Name </label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" name="client_name" type="text" autocomplete="off" placeholder="Client Name" value="{{old('client_name')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('client_name')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Address</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <textarea class="form-control" name="address" autocomplete="off" placeholder="Address">{{ old('address') }}</textarea>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('address')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Physical Address</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <textarea class="form-control" name="physical_address" autocomplete="off" placeholder="Physical Address"> {{old('physical_address')}}</textarea>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('physical_address')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Manager<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select class="form-control select_btn_icon" name="project_manager">
                                                <option value="">Select Project Manager</option>
                                                @foreach($project_managers as $managers)
                                                    <option value="{{ $managers->id }}">{{ $managers->first_name }} {{ $managers->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_manager')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Team Lead<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select class="form-control select_btn_icon team-lead-search" name="team_lead">
                                                <option value="">Select Team Lead</option>
                                                @foreach($employees as $user)
                                                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('team_lead')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Hours Approved/Spent<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="hours_approved_or_spent" placeholder="Hours approved/spent"  value="{{old('hours_approved_or_spent')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('hours_approved_or_spent')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_url" placeholder="Project URL"  value="{{old('project_url')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Technology <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="technology" placeholder="Technology"  value="{{old('technology')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('technology')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Dev Server URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="dev_server_url" placeholder="Dev Server URL"  value="{{old('dev_server_url')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('dev_server_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">QA Server URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="qa_server_url" placeholder="QA server URL" value="{{old('qa_server_url')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('qa_server_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">GIT/SVN URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="git_url" placeholder="Git or SVN URL" value="{{old('git_url')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('git_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Document URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_document_url" placeholder="Project Document URL" value="{{old('project_document_url')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_document_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Management Tool</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_management_tool" placeholder="Project Management Tool" value="{{old('project_management_tool')}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_management_tool')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Video URL(if any)</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_video" placeholder="Project Video">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_video')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Current Status <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select  class="form-control select_btn_icon" name="current_status" id="current_status">
                                                <option value="" style="font-weight: bold;">--Select Status--</option>
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
{{--


                                            <input type="text" class="form-control" name="current_status" placeholder="Current Status" value="{{old('current_status')}}">--}}
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('current_status')}}
                              	        </span>
                                    </div>
                                </div>
                                <div class="">
                                  <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit</button>
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
@section('script')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    
    $(document).ready(function(){
        $('.team-lead-search').select2();
        var today = new Date();
        $('.start_date').datepicker({
            format: "yyyy-mm-dd",
            startDate:'01/01/1997',
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#time-est-popup modal-header'
        });

        $('.end_date').datepicker({
            format: "yyyy-mm-dd",
            startDate:'01/01/1997',
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#time-est-popup modal-header'
        });




    });
</script>
@endsection
@endsection
