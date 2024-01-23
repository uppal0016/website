@extends('layouts.page')
@section('content')
<style>
  #status-select  option:nth-child(2){
    display: none;
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
                <li class="breadcrumb-item" aria-current="page"><a href="{{ url('admin/inventory_item') }}">{{ $title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Inventory Item</li>
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
            <h3 class="mb-0">Edit Inventory Item</h3>
              <a href="{{ URL('admin/inventory_item') }}" class="btn btn-primary add-user-btn add-topic-btn">Back</a>
          </div>
          
          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="editForm">
                <form action="{{ route('admin.inventory_item.update',$inventoryItem->id) }}" method="POST" id="add_vendor" enctype="multipart/form-data">
                  <input type="hidden" name="catid" value="{{$inventoryItem->category_id}}">
                   <input type="hidden" name="avilability_status" value="{{$inventoryItem->avilability_status}}">
                  <div class="FormRIght">
                    {!! Form::hidden('id', $inventoryItem->id) !!}
                    {!! Form::hidden('_token', Session::token()) !!}
                    {!! Form::hidden('_method', 'PUT') !!}
                   <div class="form-group">
                      {!! Form::text('generate_id', $inventoryItem->generate_id, ['class'=> 'form-control','hidden'=>'true']); !!}
                      <span class="text-danger">{{$errors->first('generate_id')}}</span>
                    </div>
                    @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['status'=>1]);
                    $array1 = [''=>'Select Category'];
                    $categories = $array1 + $categories;
                    @endphp
                    <div class="form-group">
                      {!! Form::hidden('cat_id', $inventoryItem->category_id, ['class'=> 'form-control cat_id','readonly'=>'true']); !!}
                      {!! Form::select('category_id',$categories,$inventoryItem->category_id,['class'=> 'form-control change_category','ref' => $inventoryItem->id]) !!}
                      <span class="text-danger">{{$errors->first('category_id')}}</span>
                    </div>
                    @if($inventoryItem->parameters != '')
                      @php
                        $parameters = unserialize($inventoryItem->parameters);
                      @endphp
                      @foreach($parameters as $key => $parameter)
                        <div class="form-group">
                          {!! Form::hidden('parameter_name[]',$key,['placeholder'=>'Enter Parameter','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                          {!! Form::text('parameters[]',$parameter,['placeholder'=>'Enter Parameter','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
                          <span class="text-danger">{{ $errors->first($parameter) }}</span>
                        </div>
                      @endforeach
                    @endif
                    <div class="form-group">
                      {!! Form::text('name', $inventoryItem->name, ['placeholder'=>'Enter Item Name','class'=> 'form-control']); !!}
                      <span class="text-danger">
                          {{$errors->first('name')}}
                        </span>
                    </div>
                    <div class="form-group">
                      {!! Form::text('company_name', $inventoryItem->company_name, ['placeholder'=>'Enter Company Name','class'=> 'form-control']); !!}
                      <span class="text-danger">
                          {{$errors->first('company_name')}}
                        </span>
                    </div>
                    <div class="form-group">
                      {!! Form::text('serial_no', $inventoryItem->serial_no, ['placeholder'=>'Enter Serial No','class'=> 'form-control']); !!}
                      <span class="text-danger">
                          {{$errors->first('serial_no')}}
                        </span>
                    </div>
                    <h4><u>Add More Specifications</u> </h4> 
                                                
                     <div class="input-lists">
                 <div class="row hardwareList" >

                @php $i = 1;  $count = count($inventoryItemDetils)+1;     @endphp
               
                  @foreach($inventoryItemDetils as $val)
                   <input type="hidden" name="update_[{{$i}}][detailsid]" value="{{$val->id}}">
                    <div class="col-md-5">
                  <div class="form-group">
                  <input placeholder="Enter  Name" id="hardware_name_{{$i}}" class="form-control required valid" name="update_[{{$i}}][hardware_name]" autocomplete="off" type="text" value="{{$val->hardware_name}}" aria-invalid="false">
                    <span class="text-danger">
                        {{$errors->first('serial_no')}}
                      </span>
                  </div>
                  </div>
                  <div class="col-md-5">
                  <div class="form-group">
                 <input placeholder="Enter  Value" id="hardware_value_{{$i}}" class="form-control required valid" name="update_[{{$i}}][hardware_value]" autocomplete="off" type="text" value="{{$val->hardware_value}}" aria-invalid="false">
                    <span class="text-danger">
                        {{$errors->first('hardware_value')}}
                      </span>
                  </div>
                </div>
                  <div class="col-md-1" style="display:flex;">
               <span >
                  <a href="javascript:void(0);" id="{{ $val->id }}" data-row="{{$i}}" class="btn btn-danger btn-sm btn-circle remove-inventory-rows "  title="Delete inventroy" return style="border-radius:15px;"><i _ngcontent-jll-c150="" aria-hidden="true" class="fa fa-minus"></i></a>
                </span>
                 &nbsp
                 @if($i==1)
                 <span >
               <a href="javascript:void(0);" data-row="{{ $count}}" data-sub-row="['+i+']" class="btn btn-success btn-sm btn-circle add-rows" style="border-radius:15px;"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    
                </span>
                @endif
                   </div>
                    @php $i++ @endphp
                @endforeach 

                 @if(count($inventoryItemDetils) == 0)
                <div class="col-md-10"></div>
                 <div class="col-md-1">
                  <span style="display:flex;"> 
                   <a href="javascript:void(0);" data-row="{{ $count}}" data-sub-row="['+i+']" class="btn btn-primary btn-md btn-circle add-rows" ><i class="fa fa-plus" aria-hidden="true" ></i></a> 
                   </span>
                      </div>     
                @endif
                  
                    </div>
                    </div>
                       &nbsp
                       <div class="row hardwareList" >
                       <div class="form-group col-md-5">
                      {!! Form::text('d_o_p', $inventoryItem->d_o_p, ['placeholder'=>'Select Date of Purchase','id'=>'datepicker1','class'=> 'form-control']); !!}
                      <span class="text-danger">
                          {{$errors->first('d_o_p')}}
                        </span>
                    </div>
                    <div class="form-group col-md-5">
                      {!! Form::text('purchase_amount', $inventoryItem->purchase_amount, ['placeholder'=>'Enter Purchase Amount','class'=> 'form-control validNumber']); !!}
                      <span class="text-danger">
                          {{$errors->first('purchase_amount')}}
                        </span>
                    </div>
                    </div>
                    @php $inventoryItems = Helper::getTablaDataForDropDown('vendors','name','asc',['is_deleted'=>1]);
                    $array2 = [''=>'Select Vendor'];
                    $inventoryItems = $array2+$inventoryItems;
                    @endphp
                    <div class="form-group">
                      {!! Form::select('vendor_id',$inventoryItems,$inventoryItem->vendor_id,['class'=> 'form-control']) !!}
                      <span class="text-danger">
                          {{$errors->first('vendor_id')}}
                        </span>
                    </div>
                    <div class="form-group">
                      @if($inventoryItem->invoice_image != '')
                        <img width='100px' heigth='100px' src="{{ url('/images/inventory_items/'.$inventoryItem->invoice_image) }}">
                      @endif
                      {!! Form::file('invoice_image', ['class'=> 'form-control','accept'=> 'image/*']); !!}
                      <span class="text-danger">
                          {{$errors->first('invoice_image')}}
                        </span>
                    </div>
                    <div class="form-groupp" style="margin-bottom: 1rem;">
                      @if($inventoryItem->avilability_status == 2)
                      <div class="form-group">
                      {!! Form::select('status', array('1' => 'Active', '0' => 'Deactivate', '2' => 'Scrap'), $inventoryItem->is_deleted, ['class' => 'form-control', 'id' => 'status-select', 'style' => "max-width: 13rem; "]) !!}
                      </div>
                      <div class="row hardwareList"  id="scrap-fields" style="display: none;">
                        <div class="form-group col-md-6">
                        {!! Form::label('amount', 'Amount') !!}
                        {!! Form::text('sold_amount', null, ['class' => 'form-control validNumber', 'placeholder'=>'Enter sold amount']) !!}
                          <span class="text-danger">
                            {{$errors->first('sold_amount')}}
                          </span>
                        </div>
                        <div class="form-group col-md-6">
                          {!! Form::label('date', 'Date') !!}
                          {!! Form::text('date_of_sold', null, ['class'=> 'form-control', 'placeholder'=>'Select Sold Date','id'=>'datepicker2']) !!}
                          <span class="text-danger">
                            {{$errors->first('date_of_sold')}}
                          </span>
                        </div>
                    </div>
                      @else
                      {!! Form::select('status', array('1' => 'Active', '0' => 'Deactivate'),$inventoryItem->is_deleted,['class'=> 'form-control', 'style' => "max-width: 13rem; "]) !!}
                      @endif
                      <span class="text-danger">
                          {{$errors->first('status')}}
                        </span>
                    </div>
                    <div>
                      {!! Form::submit('Submit',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
                    </div>
                  </div>
                  <input type="hidden" name="page" value="{{Request::get('page')}}">
                  <input type="hidden" name="search_name" value="{{Request::get('name')}}">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!--/.row-->
  </div>
  <script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script>
  $(document).ready(function() {
      $('#status-select').change(function() {
          if ($(this).val() === '2') {
              $('#scrap-fields').show();
          } else {
              $('#scrap-fields').hide();
          }
      });
  });
</script>
@endsection
