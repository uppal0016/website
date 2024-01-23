@extends('layouts.page')

@section('content')

@include('common.sidebar.sidebar_pm')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{ URL('dashboard') }}">
                    <em class="fa fa-home"></em>
                </a></li>
                <li class="active">Add User</li>
            </ol>
    </div> 
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Add User</h1>
      </div>
    </div><!--/.row--> 
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Add User
          </div>
          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="table-responsive">
                  <form id="add_user" action="{{ url('/pm/users') }}" method="post"/>
                      <table class="table input-lists">
                          <tr>    
                              <td>
                                  <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                  <input type="text" name="first_name" value="{{old('first_name')}}" id="first_name" placeholder="Enter First Name" class="form-control"/>
                                  <span class="text-danger">
                                      {{$errors->first('first_name')}}
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td><input type="text" name="last_name" id="last_name" value="{{old('last_name')}}"placeholder="Enter Last name" class="required form-control" />
                                   <span class="text-danger">
                                      {{$errors->first('last_name')}}
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td><input type="email" name="email" id="email" value="{{old('email')}}" placeholder="Enter Email" class="required form-control" />
                                  <span class="text-danger">
                                      {{$errors->first('email')}}
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td><input type="password" name="password" id="password" placeholder="Enter Password" class="required form-control" />
                                   <span class="text-danger">
                                      {{$errors->first('password')}}
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <select  class="form-control select_btn_icon" name="role_id" id="role_id">
                                      <option value="" disable="true">Select Role</option>
                                      @foreach($role as $value)
                                          <option value = "{{ucfirst($value->id)}}">  
                                              {{ucfirst($value->role)}}
                                          </option> 
                                      @endforeach        
                                  </select>
                                  <span class="text-danger">
                                      {{$errors->first('role_id')}}
                                  </span>
                              </td>
                          </tr>
                          <tr>
                              <td>   
                                  <button type="submit" class="btn btn-primary  add-user-btn " name="submit">Submit</button>
                              </td>
                          </tr>
                      </table>
                  </form>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>  <!--/.main-->
        

        <!-- <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"> <i class="fa fa-users"> Add User</i></h1>
            </div>
        </div> < /.row-->
   <!-- <div class="content-wrapper">
        <div class="container-fluid" style="margin-top: 4%">
            <div class="card mb-3"> 
                <form method="post" id="add_user" action="{{ url('/pm/users') }}">
                    {{csrf_field()}}
                    <div class="form-group">
                      <label><b>First Name</b></label>
                      <input maxlength="30" class="form-control " value="{{old('first_name')}}" id="first_name" name="first_name" placeholder=" Enter First Name" type="text">
                        <span class="text-danger">
                            {{$errors->first('first_name')}}
                        </span>
                    </div> 
                    <div class="form-group ">
                        <label><b>Last Name</b></label>
                        <input maxlength="30" class="form-control " value="{{old('last_name')}}" id="last_name" name="last_name" placeholder="Enter Last Name" type="text">
                        <span class="text-danger">
                            {{$errors->first('last_name')}}
                        </span>
                    </div> 
                    <div class="form-group ">
                        <label><b>Email</b></label>
                        <input maxlength="30" class="form-control " value="{{old('email')}}" id="email" name="email" placeholder="Email" type="email"> 
                         <span class="text-danger">
                            {{$errors->first('email')}}
                        </span>  
                    </div> 
                    <div class="form-group ">
                        <label><b>Password</b></label>
                        <input maxlength="30" class="form-control" value="{{old('password')}}" id="password" name="password" placeholder="Password" type="password">  
                        <span class="text-danger">
                            {{$errors->first('password')}}
                        </span>
                    </div> 
                    <div class="form-group">
                        <label><b>Role</b></label>
                        <select  class="form-control" name="role_id" id="role_id">
                            <option value="" disable="true">Select Role</option>
                                @foreach($role as $value)
                                        <option value = "{{ucfirst($value->id)}}">  
                                            {{ucfirst($value->role)}}
                                        </option> 
                                @endforeach 
                        </select>
                        <span class="text-danger">
                            {{$errors->first('role_id')}}
                        </span>
                    </div> 
                    <div> 
                        <button type="submit" class="btn btn-primary  add-user-btn " name="submit">Submit</button> 
                    </div>
                </form> 
            </div>
        </div>
    </div>
</div> -->
    

@endsection