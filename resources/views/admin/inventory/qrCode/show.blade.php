@extends('layouts.page')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style> 
 .error{
  font-size:11px;
} .multiselect-dropdown span.maxselected {
  width: 80%;
}
.select2-container .select2-selection--single{
    height: 40px!important;
}
.select2-container--default .select2-selection--single{
    border: 1px solid lightgray;
}
.select2-container--default .select2-selection--single .select2-selection__rendered{
    color: gray;
    line-height: 18px;
}
.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable{
    background-color: #05b1c5;
}
.error{
  font-size: 0.8rem;
  color: gray;
}
#search_team_lead-error{
  color: red;
}
#employeeOptions-error{
  color: red;
}

  #print_qr {
    text-align: left;
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
@media(min-width: 768px){
.w-md-auto{
  width: auto !important;
}
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
            
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('admin/inventory_item') }}">Inventory Item</a></li>
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
        <div class="card minHeight">           <!-- Card header -->
          
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
          {!! Form::open(array('action' => 'Admin\QrCodeController@assigned_item','method'=>'POST','id'=>'inventory_assign_spare')) !!}
          <div class ="row"> <div class="col-md-4"> <div class="Qr-outer"><div class="QR-code"><img src="{{asset('images/qrcode/'.$qrcode->qr_image )}}"></div><img class="logo-img" src="https://uploads-ssl.webflow.com/616818d939434d23bf997966/63340352fe95fbe37bcd31f4_logo.png"></div></div></div></div>
          &nbsp
          <div class="container">
          <div class="row align-items-end">
        
          <input type="hidden" name="qr_code_id" value="{{$qrcode->id}}">
          <div class="col-md-4">
          <div class="form-group">
          <label class="placeholder" for=""> Select Inventory <span style="color:red">*</span></label>
          <select name="item_id" id="item_id" class="form-control usr_cls js-example-basic-single select_btn_icon"required>
              <option value="">Select Inventory</option>
              @foreach($inventoryItems as $key=>$item)
              <option @if(!empty($showdetails)) @if($showdetails->id == $item->id) selected @endif @endif value="{{ $item->id }}">{{ $item->name .' ('.$item->serial_no.')'}}</option>
              @endforeach
              </select>
              <p style="color:red" id="inv_err"></p>
</div>

          </div>
          @php
          $users = Helper::getTablaDataOrderBy('users','first_name','asc',['is_deleted'=>0]);
          @endphp
          <div class="col-md-4"> 
          <div class="form-group">
          <label class="placeholder" for=""> Select User</label>
          <select name="assigned_to" id="assigned_to" class="form-control usr_cls js-example-basic-single select_btn_icon" required>
              <option value="">Select User</option>
              @foreach($users as $key=>$user)
              <option @if(!empty($showdetails))@if($showdetails->assigned_to == $user->id) selected @endif  @endif value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
              @endforeach
              </select>
              {{-- <p style="color:red" id="user_err"></p> --}}
</div>
              </div>
      
          <div class="col-md-2">
          <div class="form-group">
            {!! Form::submit('Assign',['class'=>'btn btn-primary  add-user-btn w-100 w-md-auto','id' => 'AssignSubmit','name'=> 'Assign']) !!}
             </div>
          </div>
          @if (!$show_spare_btn)
          <div class="col-md-2">
            <div class="form-group">
              {!! Form::submit('Spare',['class'=>'btn btn-primary  add-user-btn  w-100 w-md-auto','name'=> 'Spare']) !!}
            </div>
            </div>   
          @endif
         
</div>
          </div>
         {!! Form::close() !!}      
          </div>
          </div>
    </div>
  </div>
          <!-- Light table -->
          @if(!empty($showdetails))
         <div class="container">
         <div class="row">
      <div class="col">
        <div class="card minHeight">
          <!-- Card header -->
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Inventory Item Details</h3>
            <div class="formRIght">
              <div class="commdiv searchInput">
                
                <!-- </div> -->
              </div>
              <!-- <div class="clearfix"></div> -->
             
              
             
            </div>
          </div>
          <!-- Light table -->
         
         <div class="container">
          <div class="row">
         <div class="card-body">
           <div class="canvas-wrapper dsr-details-content"><div class="row"><div class="col-8"></div></div><div class="row mb-4 dsr-details-list"><div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong>Item Name: </strong>{{ isset($showdetails->name)?$showdetails->name:'N/A' }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Category: </strong>{{ isset($showdetails->category->name )?$showdetails->category->name :'N/A' }} {{ $showdetails->category->name }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Company Name: </strong>{{ $showdetails->company_name }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong>Serial No: </strong>{{ $showdetails->serial_no }}</p><p class="m-0 text-right"></div>
        </div>
       
      
     </div>
          <!-- Card footer -->
        </div>
           @if(!empty($showdetails->assigned_to))

              <h3 class="mb-0">Assigned  Details</h3>

              <div class="canvas-wrapper dsr-details-content" style="margin-top:20px"><div class="row"><div class="col-8"></div></div><div class="row mb-4 dsr-details-list"><div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Name: </strong>{{ isset($showdetails->user)?$showdetails->user->full_name:N/A }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Employee code: </strong>{{ $showdetails->user->employee_code  }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Email: </strong>{{ $showdetails->user->email  }}</p><p class="m-0 text-right"></div>
        </div>
      
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Assigned Date: </strong>{{ $showdetails->assign_date  }}</p><p class="m-0 text-right"></div>
        </div>
      
     </div>
          <!-- Card footer -->
        </div>
        @else
          <tr><td colspan="9" class="text-center no_record"><b>No Assignee </b></td></tr>
        @endif
      </div>
    </div>
  </div>
@endif

@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>

$(document).ready(function(){
  $('.js-example-basic-single').select2();
});
</script>

        <!-- Card header -->
        
        <!-- Card header -->
        