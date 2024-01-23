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
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
  
 <?php
 $session = Session::get('condition');        
 if($session){
  $catid = isset($session['category_id'])? $session['category_id']:'';
  $name = isset($session['name'])? $session['name']:'';
  $avilability_status = isset($session['avilability_status'])? $session['avilability_status']:''; 
 }else{
 $name = '';
 $avilability_status = '';
 $catid = '';
 }            
              ?>
  <div class="container-fluid mt--6">
    <div class="row">
      <div class="col">
        <div class="card minHeight">
          <!-- Card header -->
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Inventory Items</h3>
            <div class="formRIght">
              <div class="commdiv searchInput">
                <!-- <form id="searchForm" method="get" action="javascript:void(0);" role="search"> -->
                <!-- <div class="input-group custom-searchfeild"> -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input autocomplete="off" name="search" type="text" class="form-control inventory_item_filter" value="{{$name ? $name : Request::get('name')}}" rel='name' placeholder="Search by company name" aria-describedby="button-addon6">
                <!-- <button class="btn btn-primary searchButtons" type="submit" name="submit">
                <i class="fa fa-search"></i>
              </button> -->
                <!-- </div> -->
              </div>
              <!-- <div class="clearfix"></div> -->
              @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['status'=>1]);
          $array1 = [''=>'Select Category'];
          $categories = $array1 + $categories;

          $vendors = Helper::getTablaDataForDropDown('vendors','name','asc',['is_deleted'=>1]);
          $array2 = [''=>'Select Vendor'];
          $vendors = $array2+$vendors;
              @endphp              
              <div class="commdiv ">
                  {!! Form::select('category_id',$categories,null,['class'=> 'form-control inventory_item_filter','rel'=>'category_id']) !!}
              </div>
              <div class="commdiv">
                {!! Form::select('vendor_id',$vendors,null,['class'=> 'form-control inventory_item_filter','rel'=>'vendor_id']) !!}
              </div>
              <div class="commdiv">
                {!! Form::text('d_o_p', null, ['placeholder'=>'Purchase Date','id'=>'datepicker11','autocomplete'=>'off','class'=> 'form-control inventory_item_filter datepicker1','rel'=>'d_o_p']); !!}
              </div>
              <div class="commdiv">
                <select name="" class="form-control inventory_item_filter stock_drpDwn select_btn_icon" id="availability_status" rel="avilability_status">
                <option value="" >Select Status</option>
                <option value="1" <?php if($avilability_status== 1){ echo"selected"; } ?>>Assigned</option>
                <option value="0"<?php if($avilability_status== 0){ echo"selected"; } ?>>Spare</option>
                <option value="2"<?php if($avilability_status== 2){ echo"selected"; } ?>>Damage</option>
                <option value="3"<?php if($avilability_status== 3){ echo"selected"; } ?>>Scrap</option>
                   </select>
           <!--      {!! Form::select('status', array('' => 'Select Status','1' => 'Assigned', '0' => 'Spare'),null,['class'=> 'form-control inventory_item_filter stock_drpDwn','rel'=>'avilability_status', 'id' => 'availability_status']) !!} -->
              </div>
              <div class="lastBtn">
                <div class="plusBtn">
                  <a href="{{ URL('admin/inventory_item/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Inventory Item">+</a>
                </div>
              </div>
            </div>
          </div>
          <!-- Light table -->
          <div class="table-responsive" id ="dynamicContent1">
            <div class="table-responsive" id ="dynamicContent">
            {{-- <div>
              <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
            </div> --}}
            <table class="table align-items-center table-flush">
              <thead>
              <tr>
                <th>Sr.No</th>
             <!--    <th>Id</th> -->
                <th>Name</th>
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
              $counter = 1;
                $avail = ['1' => 'Assigned','0'=>'Spare','2'=>'Damage','3'=>'Scrap']
              @endphp
              @if(Request::get('page') && ! empty(Request::get('page')))
                @php
                $page = Request::get('page') - 1;
                $counter = 10 * $page + 1;
                @endphp
              @endif
              @if(!$inventoryItem->isEmpty())
                @foreach($inventoryItem as $index=>$value)
                  <tr>
                    <td>{{  $counter}}</td>
                   <!--  <td>{{ $value->generate_id }}</td> -->
                    <td  ><a href="javascript:void(0);" title="Item Details"><span class="open_popop" data-url="{{$value->id}}" style="text-decoration: none;border-bottom: 1px solid #999999;"> {{ isset($value->name)?$value->name:'N/A' }}</span></a></td>
                    <td>{{ $value->category->name }}</td>
                    <td>{{ $value->company_name }}</td>
                    <td>{{ $value->serial_no }}</td>
                    @if($value->avilability_status == 3)
                    <td>N/A</td>
                    @else
                    <td> @if($value->avilability_status == 1) {{ isset($value->user->full_name)?$value->user->full_name:'N/A' }} @else N/A @endif</td>
                    @endif

                    <td>{{ $avail[$value->avilability_status] }}</td>

                    @if($value->avilability_status !== 3)
                    <td>
                      <a href="{{ action('Admin\InventoryItemController@edit',Crypt::encrypt($value->id)) }}?category_id={{$catid}}&name={{$name}}&catval={{$catid}}&avilability_status={{$avilability_status}}&page={{Request::get('page')}}" title="Edit Item">
                        <i class="fa fa-edit"> </i>
                      </a>
                      &nbsp;
                      <a href="javascript:void(0);" class="@if($value->is_deleted == 1) change_status @endif" ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="assign_item" title="@if($value->is_deleted == 1) Assign Item @else Can't assign Because Item is Deactivated @endif">
                        <i class="fa fa-tasks"></i>
                      </a>
                      &nbsp;
                      <a href="javascript:void(0);" class="@if($value->avilability_status == 0 || $value->avilability_status == 2) change_status @endif" @if($value->avilability_status == 1) onclick="alert('Can\'t Deactivate Because Item is Assigned')" @endif ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="change_status" title="@if($value->avilability_status == 0 || $value->avilability_status == 2) {{ !empty($value->is_deleted) ? 'Deactivate' : 'Activate' }} @else Can't Deactivate Because Item is Assigned @endif">
                        <i class="{{!empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times'}}"></i>
                      </a>
                     
                      &nbsp;
                      @if($value->avilability_status == 0)
                       <a href="" data-href="{{ url('/admin/inventory_item/destroy',Crypt::encrypt($value->id)) }}" class="delete_action" data-name=" Inventory"  title="Delete inventory"><i class="fa fa-trash"></i>
                        </a>
                        
                        @endif
                        &nbsp
                        @if($value->avilability_status !=2)
                        @if(isset($value->qr_code_id))
                          <a href="javascript:void(0);" id="myImg"  @if($value->qr_code_id == 0) onclick ="alert('Qr code Not Available')" @else  class="qr_code"   itemid="{{Crypt::encrypt($value->id)}}" @endif   ref="Download Qr code" data-type=""   title="view qr code" >
                         <i class="fa fa-qrcode" ></i>
                         </a>
                         @endif
                         @endif
                    </td>
                    @else 
                    <td><a href="javascript:void(0);" title="Scrap Item Details"><i class="fa-solid fa-s" id="scrap_details" data-url="{{$value->id}}" ></i></a></td>
                    @endif
                  </tr>
               @php      $counter++ @endphp
                @endforeach
              @else
                <tr><td colspan="9" class="text-center no_record"><b>No record found</b></td></tr>
              @endif
              </tbody>
            </table>
            <div class="pagination">
              {{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'category_id' =>$category_id,'name' => $name,'avilability_status'=>$availability_status,'_token'=>csrf_token()])->render() }}
            </div>
          </div>
          <!-- Card footer -->
        </div>
      </div>
    </div>
  </div>

<div id="inventory_details" class="modal fade" tabindex="-1" style="margin-top:100px">
  
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
              <h5 class="modal-title"><u>Specification Details</u></h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">    
        
        <table class="table"><thead><tr><th>Sr.No</th><th>Name</th><th>Value</th>
               </thead>
         <tbody class="datails">

         </tbody>
        </table>     
                  
            </div>

      </div>
   </div>
</div>

<div id="scrap_details_modal" class="modal fade" tabindex="-1" style="margin-top: 100px">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><u>Scrap Details</u></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th>Sr.No</th>
              <th>Sold Amount</th>
              <th>Sold Date</th>
           
          </thead>
          <tbody class="details">
            <!-- Modal content goes here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div id="viewqrcode" class="modal fade" tabindex="-1" style="margin-top:20px" >  
<div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
           <h3 class="m-0">QR Code</h3> <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
        <div class="modal-body pt-0">    
          <div class="container-fluid">   
            <div id="print_qr">

<style> 
  #print_qr {
    text-align: center;
  }
  .Qr-outer {
    display: inline-block;
    padding: 5px;
    border: 1px solid #d4d4d4;
    border-radius: 10px;
    text-align: center;
  }
  .QR-code {
    overflow: hidden;
    border: 1px solid #d4d4d4;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 5px;
  }
  .QR-code img {
    width: 70px;
  }

  .logo-img {
    width: 50px;
  }

</style>

              <div class="Qr-outer">
                <div id="caption"></div>
              </div> <!--qr_outer-->
            </div><!--print_qr-->
          </div><!--container_fluid-->
      </div><!--modal_body-->
      <div class="modal-footer pt-0 text-center">
        <button type="button" class="btn btn-primary w-100" style="transform:none !important" onclick="saveDiv()"> Print </button>
      </div>
    </div><!--modal_content-->
</div><!--modal_dialog-->
</div>

<div id="myModal" class="modal fade" role="dialog">
</div>
<div id="viewqrcode" class="modal fade" tabindex="-1" style="margin-top:20px" >  
  <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
             <h3 class="m-0">QR Code</h3> <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
          <div class="modal-body pt-0">    
            <div class="container-fluid">   
              <div id="print_qr">
  
  <style>  
    #print_qr {
      text-align: center;
    }
    .Qr-outer {
      display: inline-block;
      padding: 5px;
      border: 1px solid #d4d4d4;
      border-radius: 10px;
      text-align: center;
    }
    .QR-code {
      overflow: hidden;
      border: 1px solid #d4d4d4;
      border-radius: 10px;
      padding: 10px;
      margin-bottom: 5px;
    }
    .QR-code img {
      width: 70px;
    }
  
    .logo-img {
      width: 50px;
    }
  
  </style>
  
                <div class="Qr-outer">
                  <div id="caption"></div>
                </div> <!--qr_outer-->
              </div><!--print_qr-->
            </div><!--container_fluid-->
        </div><!--modal_body-->
        <div class="modal-footer pt-0 text-center">
          <button type="button" class="btn btn-primary w-100" style="transform:none !important" onclick="saveDiv()"> Print </button>
        </div>
      </div><!--modal_content-->
  </div><!--modal_dialog-->
  </div>
@section('script')
<script>
var searchUrl = 'inventoryItem-search';
jQuery('.stock_drpDwn').val(0);
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
<script type="text/javascript"> 
  $(document).ready(function(){
 $(document).on('click','.delete_action',function(e){
    e.preventDefault();
    var url = $(this).data('href');
    var title = $(this).data('name');
    var button = $(this);
    bootbox.dialog({
        size: "small",
        title: 'Confirm !!',
        message: "Are you sure you want to delete this "+title+"?",
        buttons: {
            Cancel: {
                label: 'Cancel',
                className: 'btn-danger btn-sm',
            },
            confirm: {
                label: 'OK',
                className: 'btn-info btn-sm',
                callback: function () {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                    
                            if (response.status == 'success') {
                              $('.ajax-success-alert-message').text(response.message);
                              $('.ajax-success-alert').show();
                              setTimeout(function(){
                                  $(".ajax-success-alert").fadeOut();
                                    
                              }, 5000)
                              location.reload();
                            }
                        }
                    });
                }
            },
        },
        callback: function (result) {
            //null
        }
    });
  });

    var avail_status = '{{ $availability_status }}';
    $('#availability_status').val(avail_status);
  });
</script>

<script>
    $("body").on("click", "#scrap_details", function () {
    var scrapid = $(this).data("url");
    $.get("scrap_details?id=" + scrapid, function (response) {
      $("#scrap_details_modal").modal("show");

      chtml = "";
      i = 1;
      $("#scrap_details_modal").modal("show");
      response.ScrapDetails.forEach(function (data, k) {
        chtml +=
          "<tr><td>" +
          i +
          "</td><td>" +
          data.sold_amount +
          "</td><td>" +
          data.date_of_sold +
          "</td></tr>";
        i++;
      });

      $(".details").html(chtml);
    });
  });
</script>
@endsection
@endsection