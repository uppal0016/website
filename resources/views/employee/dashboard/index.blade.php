@extends('layouts.page')
@section('content')

@include('common.sidebar.sidebar_emp')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
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
          <div class="row no-padding"><em class="fa fa-xl fa-paper-plane color-blue"></em>
            <div class="large">{{ $dashboardCounts['dsrs_sent'] }}</div>
            <div class="text-muted">DSRs Sent</div>
          </div>
        </div>
      </div>
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
          <div class="row no-padding"><em class="fa fa-xl fa-book color-orange"></em>
            <div class="large">{{ $dashboardCounts['projects_assigned'] }}</div>
            <div class="text-muted">Projects Assigned</div>
          </div>
        </div>
      </div>
      <!-- <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
        <div class="panel panel-orange panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-users color-teal"></em>
            <div class="large">24</div>
            <div class="text-muted">New Users</div>
          </div>
        </div>
      </div>
      <div class="col-xs-6 col-md-3 col-lg-3 no-padding">
        <div class="panel panel-red panel-widget ">
          <div class="row no-padding"><em class="fa fa-xl fa-search color-red"></em>
            <div class="large">25.2k</div>
            <div class="text-muted">Page Views</div>
          </div>
        </div>
      </div> -->
    </div><!--/.row-->
  </div>
  
</div>	<!--/.main-->

@endsection
