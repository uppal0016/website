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
              <li class="breadcrumb-item active" aria-current="page">Projects</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right fieldSearch">
        <form id="searchForm" method="get" action="javascript:void(0);" role="search">
          <div class="input-group custom-searchfeild customSearch ">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by project name" aria-describedby="button-addon6" id="search">
            <button class="btn btn-primary searchButton" type="submit" name="submit">
              <i class="fa fa-search"></i>
            </button>
            <input type="hidden" name="action" value="/projects">
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
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Project List</h3>
              <div class="plusBtn">
                <a href="{{ URL('admin/create_project') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add project">+ </a>
              </div>
          </div>

        {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
        </div> --}}
        <!-- Light table -->
        <div class="table-responsive" id ="dynamicContent">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col">Project Name</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody class="list">
            @if(!$projects->isEmpty())
            @foreach($projects as $value)
              <tr>
              <td>{{$value->name}}</td>
              <td>{{$value->start_date}}</td>
              <td>{{$value->end_date}}</td>
              <td>
                <a href="{{ action('Admin\ProjectController@edit',$value['en_id']) }}" title="Edit Project">
                  <i class="fa fa-edit"> </i>
                </a> &nbsp
                @php
                  $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                  $icon = !empty($value->status) ? 'fa fa-check' : 'fa fa-times';
                @endphp
                <a href="{{ url('/admin/project_status',$value['en_id']) }}" title="<?php echo $statusText; ?>">
                  <i class="<?php echo $icon; ?>"></i>
                </a> &nbsp

                <a href="{{ url('/admin/projects/destroy',$value['en_id']) }}" title="Delete Project" onclick="return confirm('Are you sure you want to delete this project?');">
                  <i class="fa fa-trash-o"></i>
                </a> &nbsp
                @if(!empty($value->status))
                <a href="{{ url('/admin/project/get_assigned_employees',$value['en_id']) }}" title="Assign Resource">
                  <i class="fa fa-address-book"></i>
                </a>
                &nbsp
                @if(Auth::user()->role_id == 1 && $value->project_type == 1)
                
                @if(($value->project_count <= ($userIds+1) && $value->project_count > ($userIds-60))) 
                <a  href="{{ url('/admin/project/un_assigned_all_employees',$value['en_id']) }}" title="Un Assign To All Resources"  class="assign-to-all unAssignToAll">
                 <i class="fa fa-icon-size fa-person-circle-xmark"></i>
        </a>
                
                @elseif($value->project_count >= 0 && $value->project_count <= ($userIds-60)) 
                <a  href="{{ url('/admin/project/assigned_all_employees',$value['en_id']) }}" title="Assign To All Resources" class="assign-to-all AssignToAll">
                <i class="fa fa-icon-size fa-person-circle-check"></i>
                </a>
                             @endif 
                @endif
                @endif
              </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
          <div class="pagination">
          {{ $projects->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
        </div> 
        </div>
        <!-- Card footer -->
       
      </div>
    </div>
  </div>
</div>
@section('script')
    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
