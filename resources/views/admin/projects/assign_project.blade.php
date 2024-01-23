@extends('layouts.page')
@section('content')
<style>
  ul#ui-id-1 {
    width: 230px !important;
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
              <li class="breadcrumb-item"><a href="{{ url('/admin/projects') }}">Projects</i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Assigned Project</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right formResponsive">
            <form method="get" action="" autocomplete="off">
                <div class="input-group custom-searchfeild fieldSearch">
                    {{ csrf_field() }}
                    <input placeholder="Search by name..." type="text" id="search_user" name="search" class="form-control" value="{{ request('search') }}"/>
                    <button class="btn btn-primary" type="submit" name="submit">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="{{ url('admin/project/get_assigned_employees',$en_pid) }}">
                        <button class="btn btn-danger ml-3 " type="button" name="submit">
                            <i class="fa fa-times"></i>
                        </button>
                    </a>
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
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Project: <b>{{$project->name}}</b></h3>
        </div>
      
        {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
        </div> --}}
        <!-- Light table -->
        <div class="table-responsive" id ="dynamicContent">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col">User Name</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody class="list">
            @if(!$userList->isEmpty())
            @foreach($userList as $list)
                <tr>
                    <td>{{$list->full_name}}</td>
                    <?php
                        $checked = count($list->project_assign) > 0 ? 'checked' : '';
                        $title = count($list->project_assign) > 0 ? 'Assigned' : 'Not Assigned';
                    ?>
                    <td>
                        <label class="switch" id="row_{{$list->id}}" title="{{$title}}">
                            <input type="checkbox" class="check_assign" data-user-id="{{$list->id}}" data-project-id="{{$en_pid}}" name="{{$list->id}}" {{$checked}}>
                            <span class="slider round"></span>
                        </label>
                    </td>
                </tr>
            @endforeach
            @else
            <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
            @endif
            </tbody>
          </table>
          <div class="pagination">
          {{ $userList->appends(\Request::except('page'))->render() }}
        </div> 
        </div>
        <!-- Card footer -->
       
      </div>
    </div>
  </div>
</div>
@section('script')
    <script>
    $(document).ready(function(){
            var search_user_ajax
            $('#search_user').autocomplete({ 
                autoFocus: true,
                minLength:3,
                source: function (request, response) {
                    if(search_user_ajax){
                      search_user_ajax.abort()
                    }
                    var DTO = { "term": request.term };
                    search_user_ajax = $.ajax({
                        data: DTO,
                        global: false,
                        type: 'GET',
                        url: 'search/autocomplete',
                        success: function (jobNumbers) {
                            return response(jobNumbers);
                        }
                    });
                }
            });

            $(document).on('change','.check_assign',function(){
                var userId = $(this).attr("data-user-id");
                var projectId = $(this).attr("data-project-id");

                $.ajax({
                    url:"<?php echo url('/admin/project/update_assigned_employees'); ?>",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method:"POST",
                    data:{ user_id: userId, project_id: projectId },
                    success: function(response){
                        if(response.status == 1){
                            $('.ajax-success-alert-message').text(response.message);
                            $('#row_'+userId).attr('title',response.title);
                        }else{
                            $('.ajax-success-alert-message').text(response.message);
                        }
                        $('.ajax-success-alert').show();
                        setTimeout(function(){
                            $(".ajax-success-alert").fadeOut();
                        }, 5000)
                    },
                    error: function(response){
                        $('.ajax-danger-alert-message').text(response.message);
                        $('.ajax-danger-alert').show();
                        setTimeout(function(){
                            $(".ajax-danger-alert").fadeOut();
                        }, 5000)
                    }
                });
                return false;
            });
        });
    </script>

@endsection
@endsection
