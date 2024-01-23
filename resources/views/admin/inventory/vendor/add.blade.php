@extends('layouts.page')
@section('content')
<style>
  .vendor_star{
    color: red;
  }
</style>
  <div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{url("admin/vendor")}}">Vendors</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Vendors</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid mt--6">
    <div class="row">
      <div class="col">
        <div class="card minHeight">
          <!-- Card header -->
          <div class="card-header border-0">
            <h3 class="mb-0">Add Vendor</h3>
          </div>
          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="editForm">
                {!! Form::open(array('action' => 'Admin\VendorController@store','method'=>'POST','id'=>'add_vendor')) !!}
                <div class="FormRIght">
                  <div class="form-group">
                    <label for="phone1">Enter Vender Name <span class="vendor_star">*</span></label>
                    {!! Form::text('name', null, ['placeholder'=>'Enter Vendor Name','class'=> 'form-control', 'maxlength' => '50']); !!}
                    <span class="text-danger">
                    {{$errors->first('name')}}
                  </div>
                  <div class="form-group" style="max-width: 13rem; ">
                    <label for="phone1">Enter Phone Number 1</label>
                    {!! Form::text('phone1', null, ['placeholder'=>'Enter Phone Number 1','class'=> 'form-control validNumber' , 'name' => 'phone1']); !!}
                    <span class="text-danger">
                    {{$errors->first('phone1')}}
                  </div>
                  <div class="form-group" style="max-width: 13rem; ">
                    <label for="phone2">Enter Phone Number 2</label>
                    {!! Form::text('phone2', null, ['placeholder'=>'Enter Phone Number 2','class'=> 'form-control validNumber' , 'name' => 'phone2']); !!}
                    <span class="text-danger">
                    {{$errors->first('phone2')}}
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    {!! Form::select('status', array('1' => 'Active', '0' => 'Inactive'),null,['class'=> 'form-control' , 'name' => 'status', 'style'=>"max-width: 13rem; "]) !!}
                    <span class="text-danger">
                    {{$errors->first('status')}}
                  </div>
                  <div class="">
                    {!! Form::submit('Submit',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
                  </div>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!--/.row-->
  </div>
@endsection
