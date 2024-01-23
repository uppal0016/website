@extends('layouts.page')

@section('content')

 
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
        <li>
            <a href="{{ URL('pm/projects') }}">Projects</a> 
         </li>
        <li class="active">Edit Project</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"> <i class="fa fa-file-code-o"> Edit Project</i></h1>
    </div>
  </div><!--/.row-->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit Project
                </div>
                <div class="panel-body">
                    <div class="canvas-wrapper">
                        <div class="table-responsive">
                            <form id="add_project" method="post" action="{{ url('/pm/projects/'.$project->en_id) }}">
                                <table class="table input-lists">
                                    <tbody>
                                        <tr>    
                                            <td>
                                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                                <input type="hidden" name="_method" value="patch">
                                                <input maxlength="30" class="form-control" value="{{$project->name}}" id="name" name="name" placeholder="Enter project name" type="text">
                                                <span class="text-danger">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="form-group">
                                                <input id="datepicker1" class="form-control" value="{{$project->start_date}}" name="start_date" type="text" autocomplete="off" placeholder="Start Date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="form-group">
                                                <input id="datepicker2" class="form-control" value="{{$project->end_date}}" name="end_date" type="text" autocomplete="off" placeholder="End Date">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>   
                                                <button type="submit" class="btn btn-primary  add-user-btn" 
                                                        name="submit">Submit
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>  
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.row-->
</div>

<script>
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
            startDate: moment(new Date()).format('YYYY/MM/DD'),
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#time-est-popup modal-header'
        });
    });
</script>

@endsection