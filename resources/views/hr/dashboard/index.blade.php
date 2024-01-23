@extends('layouts.page')
@section('content')
@include('common.sidebar.sidebar_pm')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ URL('dashboard') }}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Dashboard</li>
    </ol> 
  </div><!--/.row--> 

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Dashboard</h1>
    </div>
  </div><!--/.row-->

  <div class="panel panel-container">
    <div class="row">
      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <div class="panel panel-teal panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-inbox color-blue"></em>
            <div class="large">{{ $dashboardCounts['dsrs_received'] }}</div>
            <div class="text-muted">Dsrs Received</div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <div class="panel panel-blue panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-file-code-o color-orange"></em>
            <div class="large">{{ $dashboardCounts['total_projects'] }}</div>
            <div class="text-muted">Total Projects</div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <div class="panel panel-orange panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-users color-red"></em>
            <div class="large">{{$dashboardCounts['total_users']}}</div>
            <div class="text-muted">Total Users</div>
          </div>
        </div>
      </div>
    </div><!--/.row-->
  </div>
</div>	<!--/.main-->

@endsection
