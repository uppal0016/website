@extends('layouts.page')
@section('content')

@include('common.sidebar.sidebar_pm')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ URL('dashboard') }}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Projects/List</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Projects</h1>
    </div>
  </div><!--/.row-->

  
</div>



<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="content-wrapper">
        <div class="container-fluid" style="margin-top: 4%">

          <!-- Example DataTables Card-->
          <div class="card mb-3">

            <div class="card-header">

              <i class="fa fa-table"></i> <b> Projects List <small>List of projects</small></b>
            </div>

            </div>

                <table class="table table-bordered"  width="100%" cellspacing="0">

                  <thead>
                    <tr>
                      <th>Project Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody>
                      @foreach($projects as $p)
                        <tr>
                          <td>{{$p->name}}</td>
                          <td>[edit][delete]</td>
                        </tr>
                      @endforeach

                  </tbody>
                </table>
                {{ $projects->appends(\Request::except('page'))->render() }}

              </div>
            </div>

</div>
@endsection
