@extends('layouts.page')
@section('content')
    @php $isEmployeeList = false @endphp
    @if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,4]) && Request::route()->getName() == "employee-list")
      @php $isEmployeeList = true @endphp
    @endif
    
  <div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7 secLeft">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Employees</li>
              </ol>
            </nav>
          </div>
          <div class="col-lg-6 col-5 text-right formResponsive userFOrm">
            <form id="searchForm" method="get" action="javascript:void(0);" role="search">
              <div class="input-group custom-searchfeild">
              @if(!$isEmployeeList)
                <div class="col-md-4">
                  <select name="status" id="status" class="form-control select_btn_icon">
                    <option value="all" @if($status == 'all') selected @endif>All</option>
                    <option value="0" @if($status == 0) selected @endif>Inactive</option>
                    <option value="1" @if($status == 1) selected @endif>Active</option>
                  </select>
                </div>              
                <div class="customSearch col-sm-6 padd_0">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by name" aria-describedby="button-addon6"id="search">
                  <button class="btn btn-primary searchButton" type="submit" name="submit" >
                    <i class="fa fa-search"></i>
                  </button>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-primary" id="employeeExport">
                    Export
                  </button>
                </div> 
              @endif  
                <input type="hidden" name="action" value="/users">
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
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Employees</h3>
            @if(!$isEmployeeList)
              <div class=" p-0">
                  <a href="{{ url('/admin/user/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Employee">+ </a><br/>
              </div>
            @endif
          </div>
            {{-- <div>
              <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
            </div> --}}
            <div class="table-responsive" id="dynamicContent">
            <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                  <th>S.No</th>
                  <th>Employee Name</th>
                  <th>Email</th>
                  @if($isEmployeeList)
                  <th>DOB</th>
                  @endif
                  <th>Department</th>
                  <th>Designation</th>
                  <th>Employee Code</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @php $counter = 1; @endphp

                @foreach($users as $value)
                  <tr>
                    <td class="row_counter">{{ $counter }}</td>
                    <td>{{$value->first_name}} {{$value->last_name}}</td>
                    <td><div class="{{(!$isEmployeeList)?'emTxt':''}}">{{ $value->email }}</div></td>
                    @if($isEmployeeList)
                      <td>{{(!empty($value->dob))? date('d-M-Y',strtotime($value->dob)):'-'}}</td>
                    @endif
                    <td>{{($value->department)?$value->department->name:'-'}}</td>
                    <td>{{($value->designation)?$value->designation->name:'-'}}</td>
                    @if(!$isEmployeeList ||Auth::user()->role_id == 3 )
                      <td >{{$value->employee_code}}</td>
                      <td >{{$value->role->role}}</td>
                      <td >{{!empty($value->status) ? 'Active' : 'Inactive'}}</td>
                      <td>
                        @if(Auth::user()->role_id == 3)  
                        <a href="{{ action('Admin\UserController@edit', $value['en_id']) }}" title="View User"><i class="fa fa-eye"></i></a>
                    @else
                        <a href="{{ action('Admin\UserController@edit', $value['en_id']) }}" title="Edit User"><i class="fa fa-edit"></i></a>
                    @endif
                        </a> &nbsp
                        @if(Auth::user()->role_id != 3)
                        <a href="" class="delete_action" data-name="employee" data-href="{{ url('/admin/destroy',$value['en_id']) }}" title="Delete User"><i class="fa fa-trash"></i>
                        </a> &nbsp
                        @endif
                      </td>
                    @endif
                  </tr>
                  @php $counter++ @endphp
                @endforeach

                @if(!$users->count())
                  <tr>
                    <td colspan="7" class="no_record"><b>No records found</b></td>
                  </tr>
                @endif
                </tbody>
              </table>
              {{ $users->appends(['search' => Request::get('search'),'status' => Request::get('status'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
            </div>
        </div>
      </div>
    </div>
  </div>
 


<!-- ==============Assign Project PopUp Start========= -->
<div class="modal fade questn-popup" id="questn-inner-popup" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title" id="username_popup"></h3>
      </div>
      <div class="modal-body">
        <?php
        if(auth()->user()->role_id == 3) {
          $assignedRoute = 'pm/user/assign';
        }else{
          $assignedRoute = 'hr/user/assign';
        }
        ?>
        <form method="post" action="{{url($assignedRoute) }}">
          {{csrf_field()}}
          <div class="row">
            <div class="col-lg-6">
              <!-- <h1 class="header"></h1> -->
              <div class="button-group ">
                <input type="hidden" name="user_id" id="set_user_id">
                <label><b>coolAssigned Projects:</b></label> &nbsp
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                  <span>Assigned Projects</span> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu assignbox" id="projectsDropdown">
                </ul>
              </div>
              <br/>
              <input type="submit" id="btn" class="btn btn-primary  pull-center"  value="Assign" />
            </div>
          </div><!--/.row-->
        </form>
      </div>
      <div class="modal-footer">
        <!-- <a href=""><i class="fa fa-download"></i> Download File</a> -->
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
        <input type="hidden" name="user_id" id="set_user_id">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title" id="username"></h3>
      </div>
      <div class="modal-body">
        <form id="timeEstimateForm" autocomplete="off">
          {{csrf_field()}}
          <div class="row">
            <div class="col-lg-6">
              <h1 class="header"></h1>
              <div class="button-group ">
                <input type="hidden" name="user_id" id="set_user_id_te">
                <div class="form-group">
                  <label><b>Project:</b></label>
                  <select class="form-control select_btn_icon" id="project-select-te" name="project_id">
                    <option value="">Select a project</option>
                    <!--                       <option value="0">Other</option> -->
                  </select>
                </div>
                <div class="form-group">
                  <label><b>Start Date:</b></label>
                  <input id="datepicker1" class="form-control" name="start_date" type="text" >
                  <label><b>End Date:</b></label>
                  <input id="datepicker2" class="form-control" name="end_date" type="text" >
                </div>
              </div>
              <br/>
              <input type="submit" id="btn" class="btn btn-primary  pull-center"  value="Submit" />
            </div>
            <div class="col-lg-6 text-center">
              <h3 class="header">&nbsp;Time Estimates</h3>
              <i class="fa fa-clock-o fa-5x"></i>

              <h2 id="time_estimates" class="header"> </h2>
            </div>
          </div><!--/.row-->
        </form>
      </div>
      <div class="modal-footer">
        <!-- <a href=""><i class="fa fa-download"></i> Download File</a> -->
      </div>
    </div>
  </div>
</div>
<form action="{{ route('admin.export_employees') }}" class="employeeExportForm">
    {{ csrf_field() }}
    <input type="hidden" name="status" class="status">
    <input type="hidden" name="employee_name" class="employee_name">
</form>
@section('script')
  <!-- ==============Time Estimate Popup End============== -->
  <script type="text/javascript">
  //Delete User
  $(document).on('click','.delete_action',function(e){
    e.preventDefault();
    var url = $(this).data('href');
    var title = $(this).data('name');
    var button = $(this);
    bootbox.dialog({
        size: "small",
        title: 'Confirm !!',
        message: "Are you sure you want to delete this "+title+"?",
        buttons: {
            Cancel: {
                label: 'Cancel',
                className: 'btn-danger btn-sm',
            },
            confirm: {
                label: 'OK',
                className: 'btn-info btn-sm',
                callback: function () {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status == 'success') {
                              $('.ajax-success-alert-message').text(response.message);
                              $('.ajax-success-alert').show();
                              setTimeout(function(){
                                  $(".ajax-success-alert").fadeOut();
                              }, 5000)
                              button.closest("tr").hide();
                              var i = 0; 
                              $('.row_counter').each(function (i) { 
                                $('.row_counter').eq(i).html(i + 1); 
                              });
                            } else {
                              $('.ajax-danger-alert-message').text(response.message);
                              $('.ajax-danger-alert').show();
                              setTimeout(function(){
                                  $(".ajax-danger-alert").fadeOut();
                              }, 5000)
                            }
                        }
                    });
                }
            },
        },
        callback: function (result) {
            //null
        }
    });
  });

  $(document).on('click','#employeeExport',function(){
    // $(this).addAttr('disabled');
    var statusField = $('#searchForm #status').val();
    var nameField = $('#searchForm #search').val();
    $('.employeeExportForm .status').val(statusField);
    $('.employeeExportForm .employee_name').val(nameField);
    $('.employeeExportForm').submit();
  });
  $(document).ready(function(){
    $('#datepicker1').datepicker({
      format: "yyyy-mm-dd",
      startDate: "2019-01-01",
      endDate: moment(new Date()).format('YYYY/MM/DD'),
      todayBtn: "linked",
      autoclose: true,
      todayHighlight: true,
      container: '#time-est-popup modal-header'
    });

    $('#datepicker2').datepicker({
      format: "yyyy-mm-dd",
      startDate: "2019-01-01",
      endDate: moment(new Date()).format('YYYY/MM/DD'),
      todayBtn: "linked",
      autoclose: true,
      todayHighlight: true,
      container: '#time-est-popup modal-header'
    });
    $('.assign').click(function(){
      // alert($(this).attr('id'));
      var getUserId = $(this).attr('id'),
      // allUsers = [],
      getProjectIds = $(this).attr('data'),
      getUserName = $(this).attr('data-name'),
      projectDD = '',
      projects = [];
      <?php if($pro->count()){ ?> projects = <?php echo $pro; } ?>;
      if(getProjectIds){
        getProjectIds = getProjectIds.split(',');
      }
      $.each(projects, function(k, project){
        projectDD += '<li><a href="javascript:void(0)"><input type="checkbox" '+(getProjectIds.includes(project['id'].toString()) ? 'checked ' : '')+ 'name="assign[]" value="'+project['id']+'"/> &nbsp; '+project['name']+'</a></li>';
      });
      $('#projectsDropdown').html(projectDD);
      $('#username_popup').text(getUserName);
      getUserId = getUserId.split('_');
      getUserId = getUserId[1];
      $('#questn-inner-popup').modal('show');
      $('#set_user_id').val(getUserId);
      $('#set_user_name').val(getUserId);
    });
    $('.time_estimate').click(function(){
      var getUserId = $(this).attr('id'),
      getProjectIds = $(this).attr('data'),
      getUserName = $(this).attr('data-name')
      options = '<option value="">Select a project</option>',
      option = '',
      optgroup1 = '<optgroup label="Assigned Projects">',
      optgroup2 = '<optgroup label="Other Projects">',
      projects = [];
      <?php if($pro->count()){ ?> projects = <?php echo $pro; } ?>;

      getUserId = getUserId.split('_');
      getUserId = getUserId[1];

      if(getProjectIds){
        getProjectIds = getProjectIds.split(',');
      }
      $.each(projects, function(k, project){
        option = '<option value="'+project['id']+'">'+capitalizeFirstLetter(project['name'])+'</option>';
        if(getProjectIds.includes(project['id'].toString())){
          optgroup1 += option;
        }else{
          optgroup2 += option;
        }
      });

      optgroup1 += '</optgroup>';
      optgroup2 += '<option value="0">Other</option></optgroup>';
      options += optgroup1+optgroup2;
      $('#project-select-te').html(options)
      $('#time_estimates').text('');
      $('#timeEstimateForm').trigger("reset");
      $('#set_user_id_te').val(getUserId);
      $('#username').text(getUserName);
      $('#time-est-popup').modal('show');
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
        url:"<?php echo url('pm/user/get_time_estimates'); ?>",
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
          $.toaster({ priority : 'danger', title : 'Error', message : error.responseJSON.message});
        }
      });
      return false;
    });
    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }
  });
  </script>
  <script src="{{ URL::asset('js/custom.js') }}"></script>
  <script type="text/javascript">
    $(document).ready(function(){
      var user_status = '{{ $status }}';
      $('#status').val(user_status);
    });
  </script>
@endsection
@endsection
