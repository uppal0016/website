@extends('layouts.page')

@section('content') 

@include('common.sidebar.sidebar_pm')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ URL('dashboard') }}">
        <em class="fa fa-home"></em>
        </a> 
      </li>
      <li class="active">Add Project</li>
    </ol>
  </div><!--/.row-->
  <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Project </h1>
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
                            <form  method="post" id="add_project" action="{{ url('/pm/projects') }}">
                                <table class="table input-lists">
                                    <tbody>
                                        <tr>    
                                            <td>
                                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                                  <input maxlength="30" class="form-control " value="{{old('name')}}" id="name" name="name" placeholder="Enter Project Name" type="text">
                                                  <span class="text-danger">
                                                      {{$errors->first('name')}}
                                                  </span>
                                            </td>
                                        </tr>
                                        <tr>    
                                            <td> 
                                              <select  class="form-control select_btn_icon" value="" name="status" id="status">
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
        
  <!-- <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header"> <i class="fa fa-file-code-o"> Add Project</i></h1>
      @if(session()->has('flash_message'))
        <div class="alert alert-success">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{ session()->get('flash_message') }}
        </div>
      @endif
    </div>
  </div></.row-->
  
  <!--<div class="content-wrapper">
    <div class="container-fluid" style="margin-top: 4%">

      <div class="card mb-3"> 
        <form method="post" id="add_project" action="{{ url('/pm/projects') }}">

          {{csrf_field()}}
          
          <div class="form-group">
            <label><b>Project Name</b></label>
            <input maxlength="30" class="form-control " value="{{old('name')}}" id="name" name="name" placeholder="Enter Project Name" type="text">
            <span class="text-danger">
                {{$errors->first('name')}}
            </span>
          </div> 

          <div class="form-group">
            <label><b>Status</b></label>
            <select  class="form-control" value="" name="status" id="status">
                <option value="1" checked>Active</option>
                <option value="0" >Inactive</option>
            </select>
            <span class="text-danger">
                {{$errors->first('status')}}
            </span>
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