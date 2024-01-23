@extends('layouts.page')
@section('content')
<?php ?>
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
        <div class="card minHeight">
          <!-- Card header -->
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Inventory Item Details</h3>
            <div class="formRIght">
              <div class="commdiv searchInput">
                
                <!-- </div> -->
              </div>
              <!-- <div class="clearfix"></div> -->
             
              
              <div class="lastBtn">
              
              </div>
            </div>
          </div>
          <!-- Light table -->
         <div class="container">
          <div class="row">
         <div class="card-body">
           <div class="canvas-wrapper dsr-details-content"><div class="row"><div class="col-8"></div></div><div class="row mb-4 dsr-details-list"><div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong>Item Name: </strong>{{ isset($inventoryItems->name)?$inventoryItems->name:'N/A' }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Category: </strong>{{ isset($inventoryItems->category->name )?$inventoryItems->category->name :'N/A' }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Company Name: </strong>{{ $inventoryItems->company_name }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong>Serial No: </strong>{{ $inventoryItems->serial_no }}</p><p class="m-0 text-right"></div>
        </div>       
      
      
     </div>
     @if(!$inventorydetails->isEmpty())
        <table class="table"><thead><tr><th>Name</th><th>Value</th>
               </thead>
         <tbody class="datails">          
      
                @foreach($inventorydetails as $index=>$value)
                <tr>
                
                    <td>{{ $value->hardware_name}}</td>
                    <td>{{ $value->hardware_value}}</td>
          
                @endforeach
           
         </tbody>
         
        </table>
        
        @endif 
          <!-- Card footer -->
        </div> 
        <hr/>      
           @if(!empty($inventoryItems->assigned_to))

              <h3 class="mb-0">Assigned  Details</h3>

              <div class="canvas-wrapper dsr-details-content" style="margin-top:20px"><div class="row"><div class="col-8"></div></div><div class="row mb-4 dsr-details-list"><div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Name: </strong>{{ isset($inventoryItems->user)?$inventoryItems->user->full_name:N/A }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Employee code: </strong>{{ $inventoryItems->user->employee_code  }}</p><p class="m-0 text-right"></div>
        </div>
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Email: </strong>{{ $inventoryItems->user->email  }}</p><p class="m-0 text-right"></div>
        </div>
      
        <div class="col-xl-4"><div class="time_estimate"><p class="m-0 text-left"><strong> Assigned Date: </strong>{{ $inventoryItems->assign_date  }}</p><p class="m-0 text-right"></div>
        </div>
      
     </div>
          <!-- Card footer -->
        </div>
        @else
          <tr><td colspan="9" class="text-center no_record"><b>No Assignee Assigned</b></td></tr>
          @if(isset($qr_id))
          <a href="/admin/qr_code/{{$qr_id}}" class="btn btn-primary">Click to Assign</a>
          @endif
        @endif
        @if ($inventoryItems->assigned_to && !empty($inventoryItems) && isset($qr_id))
        <a href="/admin/qr_code/{{$qr_id}}" class="btn btn-primary">Click to Reassign</a>
        @endif
      </div>
   
    </div>
  </div>


 
<div id="myModal" class="modal fade" role="dialog">
</div>

@endsection

        <!-- Card header -->
        