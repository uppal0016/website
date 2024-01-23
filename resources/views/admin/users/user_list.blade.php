@extends('layouts.page')
@section('content')

@include('common.sidebar.sidebar_adm')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ url('/admin/dashboard') }}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Users List</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Users</h1>
      @if(session()->has('flash_message'))
        <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('flash_message') }}
        </div>
      @endif
    </div>
  </div><!--/.row-->

   <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading pull-right">
          <a href="{{ url('/admin/user/create') }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add User </a>
        </div>
        <div class="panel-heading">
          Users List
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="table-responsive">
              <table class="table input-lists">
                <thead>
                  <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $value)
                    <tr>
                      <td>{{$value->first_name}}</td>
                      <td>{{$value->last_name}}</td>
                      <td>{{$value->email}}</td>
                      <td>{{$value->role->role}}</td>
                      <td>
                          <a href="{{action('Admin\UserController@edit',$value['en_id'])}}"        title="Edit User"><i class="fa fa-edit"></i>
                          </a> &nbsp
                          <a href="{{ url('/admin/destroy',$value['en_id']) }}" title="Delete User"><i class="fa fa-trash-o"></i>
                          </a> &nbsp
                          <!-- <a href="#" class="assign" id="user_{{$value['en_id']}}" title="Assigned Project">
                            <i class="fa fa-plus"></i>
                          </a> &nbsp
                          <a href="javascript:void(0)" class="time_estimate" id="user_{{$value['en_id']}}" title="Assigned Project">
                            <i class="fa fa-clock-o"></i>
                          </a> -->
                      </td>
                    </tr>
                  @endforeach

                  @if(!$users->count())
                    <tr>
                      <td colspan="5" class="text-center"><b>No records found</b></td>
                      <!-- <td class="text-center" style="border: none"><b>No records found</b></td> -->
                    </tr>
                  @endif
                </tbody>
              </table>
              {{ $users->appends(\Request::except('page'))->render() }}
            </div>
        </div>
        </div>
      </div>
    </div>
  </div><!--/.row -->

</div>  <!--/.main-->
<!-- ==============Assign Project PopUp Start========= -->
    <div class="modal fade questn-popup" id="questn-inner-popup" role="dialog">
      <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <form method="post" action="{{url('admin/assign') }}">
                {{csrf_field()}}
                <div class="row">
                  <div class="col-lg-6">
                    <h1 class="header"></h1>
                  <div class="button-group ">
                    <input type="hidden" name="user_id" id="set_user_id">
                    <label><b>Assigned Projects:</b></label> &nbsp
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                      <span>Assigned Projects</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                      @foreach($projects as $project)
                      <li>
                        <a href="#">
                          <input type="checkbox" name="assign[]" value="{{$project->id}}"/> &nbsp; {{ucfirst($project->name)}}
                        </a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  <br/>
                  <input type="submit" id="btn" class="btn btn-primary  pull-center"  value="Assign" />
                  </div>
                </div><!--/.row-->
              </form>
            </div>
          </div>
      </div>
    </div>
    <!-- ==============Assign Project Popup End============== -->

              <!-- ==============Time Estimate PopUp Start========= -->
              <div class="modal fade questn-popup" id="time-est-popup" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <form id="timeEstimateForm">
                          {{csrf_field()}}
                          <div class="row">
                            <div class="col-lg-6">
                              <h1 class="header"></h1>

                              <div class="button-group ">
                                <input type="hidden" name="user_id" id="set_user_id_te">
                                <div class="form-group">

                                  <label><b>Project:</b></label>
                                  <select class="form-control" name="project_id">
                                    <option value="">Select a project</option>

                                    @foreach($projects as $project)
                                      <option value="{{$project->id}}">{{ucfirst($project->name)}}</option>
                                    @endforeach
                                  </select>
                                </div>

                                <div class="form-group">

                                  <label><b>Start Date:</b></label>
                                  <input id="datepicker1" class="form-control" name="start_date" type="text" >


                                  <label><b>End Date:</b></label>
                                  <input id="datepicker2" class="form-control" name="end_date" type="text" >

                                  <!-- <label><b>End Date:</b></label>
                                  <input type="text" data-toggle="datepicker"> -->
                                </div>

                              </div>

                              <br/>

                              <input type="submit" id="btn" class="btn btn-primary  pull-center"  value="Submit" />
                            </div>
                            <div class="col-lg-6">
                              <h3 class="header">Time Estimates</h3>
                              <h2 id="time_estimates" class="header"> </h2>


                            </div>
                          </div><!--/.row-->
                        </form>
                      </div>
                    </div>
                </div>
              </div>
              <!-- ==============Time Estimate Popup End============== -->

 <script type="text/javascript">

  $(document).ready(function(){
        
    $('#datepicker1').datepicker({
      format: "yyyy-mm-dd",
      startDate: "2011-01-01",
      endDate: moment(new Date()).format('YYYY/MM/DD'),
      todayBtn: "linked",
      autoclose: true,
      todayHighlight: true,
      container: '#time-est-popup modal-header'
    });

    $('#datepicker2').datepicker({
      format: "yyyy-mm-dd",
      startDate: "2011-01-01",
      endDate: moment(new Date()).format('YYYY/MM/DD'),
      todayBtn: "linked",
      autoclose: true,
      todayHighlight: true,
      container: '#time-est-popup modal-header'
    });

    $('.assign').click(function(){
      var getUserId = $(this).attr('id');
      getUserId = getUserId.split('_');
      getUserId = getUserId[1];
      $('#questn-inner-popup').modal('show');
      $('#set_user_id').val(getUserId);
    });

    $('.time_estimate').click(function(){
      var getUserId = $(this).attr('id');
      getUserId = getUserId.split('_');
      getUserId = getUserId[1];
      $('#time-est-popup').modal('show');
      $('#set_user_id_te').val(getUserId);
    });

    // $("#time-est-popup").on("hidden", function () {
    //   console.log("closed");
    // });

    $('#timeEstimateForm').submit(function(){

      var values = {};
      $.each($(this).serializeArray(), function(i, field) {
          values[field.name] = field.value;
      });

      $.ajax({
        url:"<?php echo url('/admin/user/get_time_estimates'); ?>",
        method:"POST",
        data:values,
        success: function(response){

          if(!response.success){
            return;
          }

          var totalHours = response.data.total_hours;

          $('#time_estimates').text(totalHours + ((totalHours > 1) ? " Hrs":" Hr"));
        },
        error: function(error){

          $('#time_estimates').text("");
          console.log("error", error);
        }
      });
      return false;

    });

  });
 </script>


@endsection
