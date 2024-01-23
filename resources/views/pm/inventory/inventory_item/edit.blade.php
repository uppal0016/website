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
        <a href="{{ URL('pm/projects') }}">{{ $title }}</a>
      </li>
      <li class="active">Edit Inventory Item</li>
    </ol>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="pull-right">
          <a href="{{ url('pm/inventory_item') }}" class="btn btn-primary add-user-btn add-topic-btn">Back </a><br/>
        </div>
        <div class="panel-heading">
          Edit Inventory Item
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
              <form action="{{ route('pm.inventory_item.update',$inventoryItem->id) }}" method="POST" id="add_vendor" enctype="multipart/form-data">
                <table class="table input-lists">
                  <tbody>
                    {!! Form::hidden('id', $inventoryItem->id) !!}
                    {!! Form::hidden('_token', Session::token()) !!}
                    {!! Form::hidden('_method', 'PUT') !!}
                    <tr>
                      <td>
                        {!! Form::text('generate_id', $inventoryItem->generate_id, ['class'=> 'form-control generate_id','readonly'=>'true']); !!}
                        {!! Form::hidden('last_generate_id', Helper::generate_id()-1, ['class'=> 'form-control last_generate_id','readonly'=>'true']); !!}
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
                        {!! Form::hidden('cat_id', $inventoryItem->category_id, ['class'=> 'form-control cat_id','readonly'=>'true']); !!}
                        {!! Form::select('category_id',$categories,$inventoryItem->category_id,['class'=> 'form-control change_category','ref' => $inventoryItem->id]) !!}
                        <span class="text-danger">
                          {{$errors->first('category_id')}}
                        </span>
                      </td>
                    </tr>
                    @if($inventoryItem->parameters != '')
                    @php
                    $parameters = unserialize($inventoryItem->parameters);
                    @endphp
                    @foreach($parameters as $key => $parameter)
                    <tr class="parameter_tr">
                      <td>
                        <div class="col-md-2">
                          {!! Form::Label($key) !!}
                        </div>
                        <div class="col-md-10">
                          <div class="form-group clearfix">
                            {!! Form::hidden('parameter_name[]',$key,['placeholder'=>'Enter Parameter','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                            {!! Form::text('parameters[]',$parameter,['placeholder'=>'Enter Parameter','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                            <span class="text-danger">{{ $errors->first($parameter) }}</span>
                          </div>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                    @endif
                    <tr>
                      <td>
                        {!! Form::text('name', $inventoryItem->name, ['placeholder'=>'Enter Item Name','class'=> 'form-control']); !!}
                        <span class="text-danger">
                          {{$errors->first('name')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::text('company_name', $inventoryItem->company_name, ['placeholder'=>'Enter Company Name','class'=> 'form-control']); !!}
                        <span class="text-danger">
                          {{$errors->first('company_name')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::text('serial_no', $inventoryItem->serial_no, ['placeholder'=>'Enter Serial No','class'=> 'form-control']); !!}
                        <span class="text-danger">
                          {{$errors->first('serial_no')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::text('d_o_p', $inventoryItem->d_o_p, ['placeholder'=>'Select Date of Purchase','id'=>'datepicker1','class'=> 'form-control']); !!}
                        <span class="text-danger">
                          {{$errors->first('d_o_p')}}
                        </span>
                      </td>
                    </tr>
                    @php $inventoryItems = Helper::getTablaDataForDropDown('vendors','name','asc',['is_deleted'=>1]);
                    $array2 = [''=>'Select Vendor'];
                    $inventoryItems = $array2+$inventoryItems;
                    @endphp
                    <tr>
                      <td>
                        {!! Form::select('vendor_id',$inventoryItems,$inventoryItem->vendor_id,['class'=> 'form-control']) !!}
                        <span class="text-danger">
                          {{$errors->first('vendor_id')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::text('purchase_amount', $inventoryItem->purchase_amount, ['placeholder'=>'Enter Purchase Amount','class'=> 'form-control validNumber']); !!}
                        <span class="text-danger">
                          {{$errors->first('purchase_amount')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        @if($inventoryItem->invoice_image != '')
                        <img width='100px' heigth='100px' src="{{ '/images/inventory_items/'.$inventoryItem->invoice_image }}">
                        @endif
                        {!! Form::file('invoice_image', ['class'=> 'form-control','accept'=> 'image/*']); !!}
                        <span class="text-danger">
                          {{$errors->first('invoice_image')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::select('status', array('1' => 'Active', '0' => 'Deactivate'),$inventoryItem->is_deleted,['class'=> 'form-control']) !!}
                        <span class="text-danger">
                          {{$errors->first('status')}}
                        </span>
                      </td>
                    </tr>
                    <td>
                      {!! Form::submit('Submit',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
                    </button>
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
