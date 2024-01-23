@extends('layouts.page')
@section('content')
  <div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="{{ url('admin/vendors') }}">Vendors</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Vendor</li>
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
            <h3 class="mb-0">Edit Vendor</h3>
          </div>
          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="editForm">
                <form action="{{ route('admin.vendor.update',$vendor->id) }}" method="POST" id="add_vendor">
                  <div class="FormRIght">
                    {!! Form::hidden('id', $vendor->id) !!}
                    {!! Form::hidden('_token', Session::token()) !!}
                    {!! Form::hidden('_method', 'PUT') !!}
                    <div class="form-group required">
                      <label for="name">Enter Vendor Name</label>
                      {!! Form::text('name', $vendor->name, ['placeholder'=>'Enter Vendor Name','class'=> 'form-control' ,'name' => 'name']); !!}
                      <span class="text-danger">{{$errors->first('name')}}</span>
                    </div>
                    <div class="form-group">
                      <label for="phone1">Enter Phone Number 1</label>
                      {!! Form::text('phone1', $vendor->phone1, ['placeholder'=>'Enter Phone 1','class'=> 'form-control' , 'name' => 'phone1', 'style' => "max-width: 13rem; "]); !!}
                      <span class="text-danger">{{$errors->first('phone1')}}</span>
                    </div>
                    <div class="form-group">
                      <label for="phone2">Enter Phone Number 2</label>
                      {!! Form::text('phone2', $vendor->phone2, ['placeholder'=>'Enter Phone 2','class'=> 'form-control' , 'name'=>'phone2', 'style' => "max-width: 13rem; "]); !!}
                      <span class="text-danger">{{$errors->first('phone2')}}</span>
                    </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      {!! Form::select('status', array('1' => 'Active', '0' => 'Inactive'),$vendor->is_deleted,['class'=> 'form-control' ,'name' => 'status', 'style' => "max-width: 13rem; "]) !!}
                      <span class="text-danger">{{$errors->first('status')}}</span>
                    </div>
                    <div>
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
