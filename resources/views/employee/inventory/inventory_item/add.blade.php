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
        <a href="{{ URL('inventory_item') }}">{{ $title }}</a>
      </li>
      <li class="active">Add Inventory Item</li>
    </ol>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="pull-right">
          <a href="{{ url('inventory_item') }}" class="btn btn-primary add-user-btn add-topic-btn">Back </a><br/>
        </div>
        <div class="panel-heading">
          Add Inventory Item
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
              {!! Form::open(array('action' => 'Employee\InventoryItemController@store','method'=>'POST','enctype'=>'multipart/form-data','id'=>'add_inventoryItem')) !!}
              <table class="table input-lists">
                <tbody>
                  <tr>
                    <td>
                      {!! Form::text('generate_id', Helper::generate_id(), ['class'=> 'form-control','readonly'=>'true']); !!}
                      <span class="text-danger">
                        {{$errors->first('generate_id')}}
                      </span>
                    </td>
                  </tr>
                  @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['is_deleted'=>1]);
                  $array1 = [''=>'Select Category'];
                  $categories = $array1 + $categories;
                  @endphp
                  <tr class="category_tr">
                    <td>
                      {!! Form::select('category_id',$categories,null,['class'=> 'form-control change_category']) !!}
                      <span class="text-danger">
                        {{$errors->first('category_id')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::text('name', null, ['placeholder'=>'Enter Item Name','class'=> 'form-control']); !!}
                      <span class="text-danger">
                        {{$errors->first('name')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::text('company_name', null, ['placeholder'=>'Enter Company Name','class'=> 'form-control']); !!}
                      <span class="text-danger">
                        {{$errors->first('company_name')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::text('serial_no', null, ['placeholder'=>'Enter Serial No','class'=> 'form-control']); !!}
                      <span class="text-danger">
                        {{$errors->first('serial_no')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::text('d_o_p', null, ['placeholder'=>'Select Date of Purchase','id'=>'datepicker1','class'=> 'form-control']); !!}
                      <span class="text-danger">
                        {{$errors->first('d_o_p')}}
                      </span>
                    </td>
                  </tr>
                  @php $vendors = Helper::getTablaDataForDropDown('vendors','name','asc',['is_deleted'=>1]);
                  $array2 = [''=>'Select Vendor'];
                  $vendors = $array2+$vendors;
                  @endphp
                  <tr>
                    <td>
                      {!! Form::select('vendor_id',$vendors,null,['class'=> 'form-control']) !!}
                      <span class="text-danger">
                        {{$errors->first('vendor_id')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::text('purchase_amount', null, ['placeholder'=>'Enter Purchase Amount','class'=> 'form-control validNumber']); !!}
                      <span class="text-danger">
                        {{$errors->first('purchase_amount')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::file('invoice_image', ['placeholder'=>'Select invoice image','class'=> 'form-control','accept'=> 'image/*']); !!}
                      <span class="text-danger">
                        {{$errors->first('invoice_image')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::select('status', array('1' => 'Active', '0' => 'Deactivate'),null,['class'=> 'form-control']) !!}
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
