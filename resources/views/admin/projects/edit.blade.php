@extends('layouts.page')

@section('content')

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
              <li class="breadcrumb-item active" aria-current="page">Edit Project</li>
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
          <h3 class="mb-0">Edit Project</h3>
        </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
                      <div class="editForm">
                        <form id="add_project_form" method="post" action="{{ url('/admin/projects/'.$project->en_id) }}">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                               <div class="FormRIght">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Name<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="name" value="{{$project->name}}" id="name" placeholder="Enter project name" class="form-control" autocomplete="off"/>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('name')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Start Date<span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control start_date" name="start_date" id="start_date" type="text" autocomplete="off" placeholder="Start Date" value="{{$project->start_date}}" >
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('start_date')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">End Date</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control end_date" name="end_date" type="text" autocomplete="off" placeholder="End Date" value="{{$project->end_date}}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('end_date')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Client Name </label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" name="client_name" type="text" autocomplete="off" placeholder="Client Name" value="{{ $project->client_name }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('client_name')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Address</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <textarea class="form-control" name="address" autocomplete="off" placeholder="Address">{{ $project->address }}</textarea>
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('address')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Physical Address</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <textarea class="form-control" name="physical_address" autocomplete="off" placeholder="Physical Address"> {{ $project->physical_address }}</textarea>
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
                                                    <option value="{{ $managers->id }}" @if($project->project_manager == $managers->id) selected @endif>{{ $managers->first_name }} {{ $managers->last_name }}</option>
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
                                            <select class="form-control select_btn_icon" name="team_lead">
                                                <option value="">Select Team Lead</option>
                                                @foreach($employees as $user)
                                                    <option value="{{ $user->id }}" @if($project->team_lead == $user->id) selected @endif>{{ $user->first_name }} {{ $user->last_name }}</option>
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
                                            <input type="text" class="form-control" name="hours_approved_or_spent" placeholder="Hours approved/spent"  value="{{ $project->hours_approved_or_spent }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('hours_approved_or_spent')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_url" placeholder="Project URL"  value="{{ $project->project_url }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Technology <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="technology" placeholder="Technology"  value="{{ $project->technology }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('technology')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Dev Server URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="dev_server_url" placeholder="Dev Server URL"  value="{{ $project->dev_server_url }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('dev_server_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">QA Server URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="qa_server_url" placeholder="QA server URL" value="{{ $project->qa_server_url }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('qa_server_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">GIT/SVN URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="git_url" placeholder="Git or SVN URL" value="{{ $project->git_url }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('git_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Document URL</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_document_url" placeholder="Project Document URL" value="{{ $project->project_document_url }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_document_url')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Management Tool</label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" class="form-control" name="project_management_tool" placeholder="Project Management Tool" value="{{ $project->project_management_tool }}">
                                        </div>
                                        <span class="text-danger">
							  			    {{$errors->first('project_management_tool')}}
                              	        </span>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Project Video (if any)</label>
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
                                                <option value="1" @if($project->current_status == 1) selected @endif>Active</option>
                                                <option value="0" @if($project->current_status == 0) selected @endif>Inactive</option>
                                            </select>
                                            {{--<input type="text" class="form-control" name="current_status" placeholder="Current Status" value="{{ $project->current_status }}">--}}
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
<script>
    $(document).ready(function(){
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
