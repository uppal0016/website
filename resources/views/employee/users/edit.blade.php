@extends('layouts.page')
@section('content')  

@include('common.sidebar.sidebar_adm')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
          <li><a href="{{ url('/admin/dashboard') }}">
            <em class="fa fa-home"></em>
          </a></li>
          <li class="active">Edit User</li>
        </ol>
    </div><!--/.row-->

    <div class="row">
        <div class="col-lg-12">
          <h1 class="page-header">Edit User </h1>
          @if(session()->has('flash_message'))
            <div class="alert alert-success">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              {{ session()->get('flash_message') }}
            </div>
          @endif
        </div>
    </div><!--/.row-->
  
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit User
                </div>
                <div class="panel-body">
                    <div class="canvas-wrapper">
                        <div class="table-responsive">
                            <form id="add_user" method="post" action="{{ url('/admin/users',['id' => $user->en_id]) }}">
                                <table class="table input-lists">
                                    <tbody>
                                        <tr>    
                                            <td>
                                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                                <input type="hidden" name="_method" value="patch">
                                                <input maxlength="30" class="form-control " value="{{$user->first_name}}" name="first_name" placeholder=" Enter First Name" type="text">
                                                <span class="text-danger">
                                                    {{$errors->first('first_name')}}
                                                </span> 
                                            </td>
                                            <td>
                                                <input maxlength="30" class="form-control " value="{{$user->last_name}}" name="last_name" placeholder="Enter Last Name" type="text">
                                                <span class="text-danger">
                                                    {{$errors->first('last_name')}}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td> 
                                                <select  class="form-control select_btn_icon" name="role_id" value="{{$user->role_id}}">
                                                    <option value="">--Select Role--</option>
                                                    @if(@$user)

                                                        @foreach($data as $value)
                                                        
                                                            <?php
                                                                $selectedUser=' ';
                                                            if($value->id==@$user->role_id){
                                                                $selectedUser='selected="selected"';
                                                            } ?>
                                                            <option <?php echo $selectedUser; ?>value="{{ucfirst($value->id)}}">  
                                                            {{ucfirst($value->role)}}
                                                            </option> 
                                                        @endforeach 
                                         
                                                    @endif      
                                                </select>
                                                <span class="text-danger">
                                                    {{$errors->first('role_id')}}
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

</div>  <!--/.main-->

@endsection
