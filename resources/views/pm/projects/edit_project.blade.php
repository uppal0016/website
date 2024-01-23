@extends('layouts.page')

@section('content')

@include('common.sidebar.sidebar_pm')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ URL('dashboard') }}">
        <em class="fa fa-home"></em>
      </a></li>
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
                            <form id="add_user" method="post" action="{{ url('/pm/projects/'.$project->en_id) }}">
                                <table class="table input-lists">
                                    <tbody>
                                        <tr>    
                                            <td>
                                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                                <input type="hidden" name="_method" value="patch">
                                                <input maxlength="30" class="form-control " value="{{$project->name}}" id="name" name="name" placeholder="Enter Project Name" type="text">
                                                <span class="text-danger">
                                                    {{$errors->first('name')}}
                                                </span>
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
  
  <!-- <div class="content-wrapper">
    <div class="container-fluid" style="margin-top: 4%">

      <div class="card mb-3"> 
        <form method="post" id="add_project" action="{{ url('/pm/projects/'.$project->en_id) }}">

          {{csrf_field()}}
          <input type="hidden" name="_method" value="PUT"> 
          
          <div class="form-group">
            <label><b>Project Name</b></label>
            <input maxlength="30" class="form-control " value="{{$project->name}}" id="name" name="name" placeholder="Enter Project Name" type="text">
          </div> 

          <div class="form-group ">
            <button type="submit" class="btn btn-primary  add-user-btn " name="submit">Submit</button>
          </div>

        </form> 
      </div>
    </div>

  </div> -->
</div>
    

@endsection