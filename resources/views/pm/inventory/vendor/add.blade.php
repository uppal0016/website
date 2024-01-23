@extends('layouts.page')
@section('content')
 
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li>
        <a href="{{ URL('/dashboard') }}">
          <em class="fa fa-home"></em>
        </a>
      </li>
      <li>
        <a href="{{ URL('pm/projects') }}">Vendors</a>
      </li>
      <li class="active">Add Vendor</li>
    </ol>
  </div><!--/.row-->
  <!-- <div class="row">
  <div class="col-lg-12">
  <h1 class="page-header">Add Vendor </h1>
</div>
</div><!/.row-->

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="pull-right">
        <a href="{{ url('pm/vendor') }}" class="btn btn-primary add-user-btn add-topic-btn">Back </a><br/>
      </div>
      <div class="panel-heading">
        Add Vendor
      </div>

      @if ($message = Session::get('success'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif

      @if ($message = Session::get('error'))
      <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif

      <div class="panel-body">
        <div class="canvas-wrapper">
          <div class="table-responsive">
            {!! Form::open(array('action' => 'PM\VendorController@store','method'=>'POST','id'=>'add_vendor')) !!}
            <table class="table input-lists">
              <tbody>
                <tr>
                  <td>
                    {!! Form::text('name', null, ['placeholder'=>'Enter Vendor Name','class'=> 'form-control']); !!}
                    <span class="text-danger">
                      {{$errors->first('name')}}
                    </span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {!! Form::text('phone1', null, ['placeholder'=>'Enter Phone 1','class'=> 'form-control validNumber']); !!}
                    <span class="text-danger">
                      {{$errors->first('phone1')}}
                    </span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {!! Form::text('phone2', null, ['placeholder'=>'Enter Phone 2','class'=> 'form-control validNumber']); !!}
                    <span class="text-danger">
                      {{$errors->first('phone2')}}
                    </span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {!! Form::select('status', array('1' => 'Active', '0' => 'Inactive'),null,['class'=> 'form-control']) !!}
                    <span class="text-danger">
                      {{$errors->first('status')}}
                    </span>
                  </td>
                </tr>
                <tr>
                  <td>
                    {!! Form::submit('Submit',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
                  </td>
                </tr>
              </tbody>
            </table>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!--/.row-->
</div>

@endsection
