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
          <div class="col-lg-6 col-7 text-right formResponsive userFOrm">
            <form id="searchForm" method="get" action="javascript:void(0);" role="search">
              <div class="input-group custom-searchfeild"> 
              <div class="col-sm-5 padd_0"></div>                    
                <div class="customSearch col-sm-7">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by name" aria-describedby="button-addon6"id="search">
                  <button class="btn btn-primary searchButton" type="submit" name="submit" >
                    <i class="fa fa-search"></i>
                  </button>

                </div>
                <input type="hidden" name="action" value="/assigned_stock">
               </form>
              
               </div>
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
            <h3 class="mb-0">Assigned Stock</h3>
          </div>
          <!-- Light table -->
          <div class="table-responsive" id ="dynamicContent">
            <table class="table">
              <thead>
              <tr>
                      <th>Sr.No</th>
                <!-- <th>Id</th> -->
                <th>Assigned To</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Company Name</th>
                <th>Serial No</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @php  $counter = 1;  @endphp
              @if(!$inventoryItem->isEmpty())
                @foreach($inventoryItem as $index=>$value)
                  <tr>
                  <td>{{  $counter}}</td>
                    <!-- <td>{{ $value->generate_id }}</td> -->
                    <td>{{ $value->user->full_name }}</td>
                    <td>{{ isset($value->name)?$value->name:'N/A' }}</td>
                    <td>{{ $value->category->name }}</td>
                    <td>{{ $value->company_name }}</td>
                    <td>{{ $value->serial_no }}</td>
                    <td>
                      @php
                        $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
                        $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
                      @endphp
                      <a href="javascript:void(0);" class="change_status" rel="{{ \Crypt::encrypt($value->id) }}" ref="assigned_stock" data-type="assign_item" title="Assign Item">
                        <i class="fa fa-tasks"></i>
                      </a>
                      &nbsp
                      <a href="javascript:void(0);" class="change_status" rel="{{ \Crypt::encrypt($value->id) }}" ref="assigned_stock" data-type="change_availabilty_status" title="<?php echo $statusText; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                      </a>                     
                      &nbsp
                       <a href="javascript:void(0);" id="myImg"  @if($value->qr_code_id == 0) onclick ="alert('Qr code Not Available')" @else  class="qr_code"   itemid="{{Crypt::encrypt($value->id)}}" @endif   ref="Download Qr code" data-type=""   title="view qr code" >
                      <i class="fa fa-qrcode" ></i>
                      </a>

                    
                    </td>
                  </tr>
                  @php      $counter++ @endphp
                @endforeach
              @else
                <tr><td colspan="7" class="text-center"><b>No record found</b></td></tr>
              @endif
              </tbody>
            </table>
            <div class="item_inv">
              {{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
            </div>
          </div>
          <!-- Card footer -->
        </div>
      </div>
    </div>
  </div>
 
<div id="myModal" class="modal fade" role="dialog">
</div>  <!--/.main-->


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
@endsection
@endsection
