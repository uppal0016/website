@extends('layouts.page')
@section('content')

@include('common.sidebar.sidebar_pm')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ URL('dashboard') }}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Project List</li>
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
         <div class="panel-heading pull-right">
          <a href="{{ URL('pm/create_project') }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add Project </a><br/>
        </div>
        <div class="panel-heading">
          Project List
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Project Name</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($projects as $value)
                    <tr>
                      <td>{{$value->name}}</td>
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
                  @endforeach

                  @if(!$projects->count())
                    <tr>
                      <td colspan="2"><b>No records found</b></td>
                      <!-- <td class="text-center" style="border: none"><b>No records found</b></td> -->
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="pagination">
                {{ $projects->appends(\Request::except('page'))->render() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->

</div>  <!--/.main-->

@endsection
