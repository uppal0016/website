@extends('layouts.page')

@section('content') 

 
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>

        <li>
        <a href="{{ URL('pm/projects') }}">Projects</a> 
      </li>
      <li class="active">Add Project</li> 
    </ol>
  </div><!--/.row-->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Add Project </h1>
    </div>
  </div><!--/.row-->
  
  <div class="row">
      <div class="col-md-12">
          <div class="panel panel-default">
              <div class="panel-heading">
                  Add Project
              </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
                      <div class="table-responsive">
                          <form  method="post" id="add_project" action="{{ url('/pm/create_project/create') }}">
                              <table class="table input-lists">
                                  <tbody>
                                      <tr>    
                                          <td>
                                              <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                                <input maxlength="30" class="form-control" value="{{old('name')}}" id="name" name="name" autocomplete="off" placeholder="Enter project name" type="text">
                                                <span class="text-danger">
                                                    {{$errors->first('name')}}
                                                </span>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td class="form-group">
                                              <input id="datepicker1" class="form-control" name="start_date" type="text" autocomplete="off" placeholder="Start Date">
                                              <span class="text-danger">
                                                {{$errors->first('start_date')}}
                                              <span>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td class="form-group">
                                            <input id="datepicker2" class="form-control" name="end_date" type="text" autocomplete="off" placeholder="End Date">
                                            <span class="text-danger">
                                              {{$errors->first('end_date')}}
                                            </span>
                                          </td>
                                      </tr>
                                      <tr>    
                                          <td> 
                                            <select  class="form-control select_btn_icon" name="status" id="status">
                                              <option value="1" checked>Active</option>
                                              <option value="0" >Inactive</option>
                                            </select>
                                            <span class="text-danger">
                                                {{$errors->first('status')}}
                                            </span>
                                          </td>
                                      </tr>    
                                      <tr>
                                          <td>   
                                              <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit
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
            //startDate: "2019-01-01",
//            endDate: moment(new Date()).format('YYYY/MM/DD'),
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#time-est-popup modal-header'
        });

        $('#datepicker2').datepicker({
            format: "yyyy-mm-dd",
//            startDate: moment(new Date()).format('YYYY/MM/DD'),
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            container: '#time-est-popup modal-header'
        });
    });
</script>
@endsection