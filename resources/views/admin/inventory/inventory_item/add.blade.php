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
                <li class="breadcrumb-item" aria-current="page"><a href="{{ url('admin/inventory_item') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Inventory Item</li>
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
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Add Inventory Item</h3>
              <a href="{{ URL('admin/inventory_item') }}" class="btn btn-primary add-user-btn add-topic-btn">Back</a>
          </div>

          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="editForm">
                {!! Form::open(array('action' => 'Admin\InventoryItemController@store','method'=>'POST','enctype'=>'multipart/form-data','id'=>'add_inventoryItem')) !!}
                <div class="FormRIght">
                  <div class="form-group">
                    {!! Form::text('generate_id', Helper::generate_id(), ['class'=> 'form-control','hidden'=>'true']); !!}
                    <span class="text-danger">
                        {{$errors->first('generate_id')}}
                      </span>
                  </div>
                  @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['status'=>1]);
                  $array1 = [''=>'Select Category'];
                  $categories = $array1 + $categories;
                  @endphp
                  <div class="form-group">
                    {!! Form::select('category_id',$categories,null,['class'=> 'form-control change_category']) !!}
                    <span class="text-danger">
                        {{$errors->first('category_id')}}
                      </span>
                  </div>
                  <div class="form-group">
                    {!! Form::text('name', null, ['placeholder'=>'Enter Item Name','class'=> 'form-control','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('name')}}
                      </span>
                  </div>
                  <div class="form-group">
                    {!! Form::text('company_name', null, ['placeholder'=>'Enter Company Name','class'=> 'form-control','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('company_name')}}
                      </span>
                  </div>

                <div class="form-group">
                    {!! Form::text('serial_no', null, ['placeholder'=>'Enter Serial No','class'=> 'form-control','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('serial_no')}}
                      </span>
                  </div>
               <h4><u>Add More Specifications</u> </h4>       
                  <div class="input-lists">
                 <div class="row hardwareList" >
                    <div class="col-md-5">
                  <div class="form-group">
                    {!! Form::text('add_more_[0][hardware_name]', null, ['placeholder'=>'Enter  Name','class'=> 'form-control required','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('serial_no')}}
                      </span>
                  </div>
                  </div>
                  <div class="col-md-5">
                  <div class="form-group">
                    {!! Form::text('add_more_[0][hardware_value]', null, ['placeholder'=>'Enter  Value','class'=> 'form-control required','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('hardware_value')}}
                      </span>
                  </div>
                </div>
                  <div class="col-md-1">
                   <a href="javascript:void(0);" data-row="0" data-sub-row="0" class="btn btn-primary btn-md btn-circle add-rows"  title="Add hardware Specification"><i class="fa fa-plus" aria-hidden="true" ></i></a>
                  </div>
                    </div>
                    </div>
                    <div class="row hardwareList" >
                  <div class="form-group col-md-5">
                    {!! Form::text('d_o_p', null, ['placeholder'=>'Select Date of Purchase','id'=>'datepicker1','class'=> 'form-control','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('d_o_p')}}
                      </span>
                  </div>
                  <div class="form-group col-md-5">
                    {!! Form::text('purchase_amount', null, ['placeholder'=>'Enter Purchase Amount','class'=> 'form-control validNumber','autocomplete'=>'off']); !!}
                    <span class="text-danger">
                        {{$errors->first('purchase_amount')}}
                      </span>
                  </div>
                  </div>
                  @php $vendors = Helper::getTablaDataForDropDown('vendors','name','asc',['is_deleted'=>1]);
                  $array2 = [''=>'Select Vendor'];
                  $vendors = $array2+$vendors;
                  @endphp
                  <div class="form-group">
                    {!! Form::select('vendor_id',$vendors,null,['class'=> 'form-control']) !!}
                    <span class="text-danger">
                        {{$errors->first('vendor_id')}}
                      </span>
                  </div>
                  
                  <div class="form-group">
                    {!! Form::file('invoice_image', ['placeholder'=>'Select invoice image','class'=> 'form-control','accept'=> 'image/*']); !!}
                    <span class="text-danger">
                        {{$errors->first('invoice_image')}}
                      </span>
                  </div>
                  <div class="form-group">
                    {!! Form::select('status', array('1' => 'Active', '0' => 'Deactivate'),null,['class'=> 'form-control', 'style' => "max-width: 13rem; "]) !!}
                    <span class="text-danger">
                        {{$errors->first('status')}}
                      </span>
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

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>

@endsection
