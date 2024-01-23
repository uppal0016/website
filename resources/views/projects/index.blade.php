@extends('layouts.page')
@section('content')

 

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>

      <li class="active">Projects</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Projects</h1>
      @if(session()->has('flash_message'))
        <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('flash_message') }}
        </div>
      @endif
    </div>
  </div><!--/.row-->
  <!-- <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Projects</h1>
    </div>
  </div><!-/.row-->
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading cst-panel-heading">
          <div class="row">
            <div class="col-md-6">
              <form id="searchForm" method="get" action="javascript:void(0);" role="search">
                <div class="input-group custom-searchfeild">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search..." aria-describedby="button-addon6">
                  <button class="btn btn-primary searchButton" type="submit" name="submit">
                    <i class="fa fa-search"></i>
                  </button>
                  <input type="hidden" name="action" value="/projects">
                </div>
              </form>
            </div>
            <div class="col-md-6">
              <div class="pull-right">
                <a href="{{ URL('pm/create_project') }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add Project </a><br/>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            {{-- <div>
              <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
            </div> --}}
            <div class="table-responsive" id="dynamicContent">
              <table class="table">
                <thead>
                  <tr>
                    <th class="th-pad">S.No</th>
                    <th class="th-pad">Project Name</th>
                    <th class="th-pad">Start Date</th>
                    <th class="th-pad">End Date</th>
                    <th class="th-pad">Action</th>
                  </tr>
                </thead>
                <tbody>
                @php $counter = 1; @endphp
                  @foreach($projects as $value)
                    <tr>
                      <td>{{ $counter }}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->start_date}}</td>
                      <td>{{$value->end_date}}</td>
                      <td>
                        <a href="{{ action('PM\ProjectController@edit',$value['en_id']) }}" title="Edit Project">
                          <i class="fa fa-edit"> </i>
                        </a> &nbsp
                        @php
                          $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                          $icon = !empty($value->status) ? 'fa fa-check' : 'fa fa-times';
                        @endphp
                        <a href="{{ url('/pm/project_status',$value['en_id']) }}" title="<?php echo $statusText; ?>">
                          <i class="<?php echo $icon; ?>"></i>
                        </a> &nbsp

                        <a href="{{ url('/pm/projects/destroy',$value['en_id']) }}" title="Delete Project">
                          <i class="fa fa-trash-o"></i>
                        </a>

                      </td>
                    </tr>
                    @php $counter++ @endphp
                  @endforeach
                  @if(!$projects->count())
                    <tr>
                      <td colspan="5" class="text-center"><b>No records found</b></td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="pagination">
                {{ $projects->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>  <!--/.main-->
<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
