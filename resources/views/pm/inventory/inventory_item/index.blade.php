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
      <li class="active">{{ $title }}</li>
    </ol>
  </div><!--/.row-->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">{{ $title }}</h1>

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
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading cst-panel-heading">
          <div class="row">
            <div class="col-md-2">
              <!-- <form id="searchForm" method="get" action="javascript:void(0);" role="search"> -->
              <!-- <div class="input-group custom-searchfeild"> -->
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <input autocomplete="off" name="search" type="text" class="form-control inventory_item_filter" rel='name' placeholder="Search..." aria-describedby="button-addon6">
              <!-- <button class="btn btn-primary searchButtons" type="submit" name="submit">
              <i class="fa fa-search"></i>
            </button> -->
            <!-- </div> -->
          </div>
          <!-- <div class="clearfix"></div> -->
          @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['is_deleted'=>1]);
          $array1 = [''=>'Select Category'];
          $categories = $array1 + $categories;

          $vendors = Helper::getTablaDataForDropDown('vendors','name','asc',['is_deleted'=>1]);
          $array2 = [''=>'Select Vendor'];
          $vendors = $array2+$vendors;
          @endphp
          <div class="col-md-2">
            {!! Form::select('category_id',$categories,null,['class'=> 'form-control inventory_item_filter','rel'=>'category_id']) !!}
          </div>
          <div class="col-md-2">
            {!! Form::select('vendor_id',$vendors,null,['class'=> 'form-control inventory_item_filter','rel'=>'vendor_id']) !!}
          </div>
          <div class="col-md-2">
            {!! Form::text('d_o_p', null, ['placeholder'=>'Purchase Date','id'=>'datepicker11','class'=> 'form-control inventory_item_filter datepicker1','rel'=>'d_o_p']); !!}
          </div>
          <div class="col-md-2">
            {!! Form::select('status', array('' => 'Select Status','1' => 'Assigned', '0' => 'Spare'),null,['class'=> 'form-control inventory_item_filter stock_drpDwn select_btn_icon','rel'=>'avilability_status']) !!}
          </div>
          <div class="col-md-2">
            <div class="pull-right">
              <a href="{{ $url.'/inventory_item/create' }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add Inventory Item </a><br/>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="canvas-wrapper">
          {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
          </div> --}}
          <div class="table-responsive item_inv" id="dynamicContent">
            <table class="table">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Item Name</th>
                  <th>Category</th>
                  <th>Company Name</th>
                  <th>Serial No</th>
                  <th>Assigned To</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php
                $avail = ['1' => 'Assigned','0'=>'Spare']
                @endphp
                @if(!$inventoryItem->isEmpty())
                @foreach($inventoryItem as $index=>$value)
                <tr>
                  <td>{{ $value->generate_id }}</td>
                  <td>{{ isset($value->name)?$value->name:'N/A' }}</td>
                  <td>{{ $value->category->name }}</td>
                  <td>{{ $value->company_name }}</td>
                  <td>{{ $value->serial_no }}</td>
                  <td> @if($value->avilability_status == 1) {{ $value->user->full_name }} @else N/A @endif</td>
                  <td>{{ $avail[$value->avilability_status] }}</td>
                  <td>
                    <a href="{{ action('PM\InventoryItemController@edit',Crypt::encrypt($value->id)) }}" title="Edit Item">
                      <i class="fa fa-edit"> </i>
                    </a>
                    &nbsp
                    @php
                    $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
                    $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
                    @endphp
                    <a href="javascript:void(0);" class="@if($value->is_deleted == 1) change_status @endif" ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="assign_item" title="@if($value->is_deleted == 1) Assign Item @else Can't assign Because Item is Deactivated @endif">
                      <i class="fa fa-tasks"></i>
                    </a>
                    &nbsp
                    <a href="javascript:void(0);" class="@if($value->avilability_status == 0) change_status @endif" ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="change_status" title="@if($value->avilability_status == 0) {{ $statusText }} @else Can't Deactivate Because Item is Assigned @endif">
                      <i class="<?php echo $icon; ?>"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="9" class="text-center"><b>No record found</b></td></tr>
                @endif
              </tbody>
            </table>
            <div class="item_inv">
              {{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!--/.row-->
</div>  <!--/.main-->
<div id="myModal" class="modal fade" role="dialog">
</div>

<script>
var searchUrl = 'inventoryItem-search';
jQuery('.stock_drpDwn').val(0);
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
