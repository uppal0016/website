@extends('layouts.page')
@section('content')
<?php 
   use Carbon\Carbon;
   ?>
<script src="{{ asset('js/jquery.min.js') }}"></script>

 

<div class="header bg-primary pb-6">
   <div class="container-fluid">
      <div class="header-body">
         <div class="row align-items-center py-0">
            <div class="col-lg-6 col-7 secLeft">
               <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
               <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                     <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Leave</li>
                  </ol>
               </nav>
            </div>
            <div class="col-lg-2" ></div>
            <div class="col-lg-4 col-7 secRight">
                        <!-- Card body -->
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                <h5 class="card-title text-uppercase text-muted mb-0" style="font-size: 12px;">   Total  Assigned Leave - 
                                    <strong>  {{ $calculateleave['assingedLeave'] === null ? 0 :$calculateleave['assingedLeave'] }}  </strong>
                                    </br>
                                    Total Approved Leave :
                                    <strong > {{$calculateleave['approvedLeave'] === null ? 0 :$calculateleave['approvedLeave'] }}

                                    </strong>
                                   
                                    </br>
                                   Extra Leave :
                                    <strong > 
                                    {{$calculateleave['extraLeave'] === null ? 0 :$calculateleave['extraLeave'] }}
                            </strong></h5>
                                   
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                        <i class="ni ni-chart-pie-35"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            <div class="col-lg-6 col-5 text-right formResponsive">
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

            <div>
                  <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loaderList" src="{{asset('images/small_loader.gif')}}">
                </div>
               <div class="row align-items-end">
                  
                 
                
                  <div class="col-md-4">
                     <label for="">From</label>
                     <div class="input-group custom-searchfeild">
                        <input type="text" id="from" autocomplete="off" placeholder="From date"  name="from" class="form-control">
                     </div>
                  </div>
                  <div class="col-md-4">
                     <label for="">To</label>
                     <div class="input-group custom-searchfeild">
                        <input type="text" id="to" autocomplete="off" placeholder="End Date" name="to" class="form-control">
                     </div>
                  </div>
                      <div class="col-md-2">
                      <label for="">Status</label>
                      <select class="form-control select_btn_icon" id="leave_status"> 
                      <option value="all">All</option>
                      <option value="approved">Approved</option>
                     <option value="not_approved">Not Aproved</option>
                     <option value="Pending">Pending</option>
                     </select>
                    
                       </div>
                  <div class="col-md-2">
                     <div class="input-group custom-searchfeild">
                        <button type="button" class="btn btn-primary" onclick="daterangeSearch()">Search</button>
                     </div>
                  </div>
                  
                
               </div>
               </form>
            </div>
            <!-- Light table -->
            <div class="canvas-wrapper">
                  <div class="table-responsive" id="dynamicContent">
                     <table class="table">
                        <thead>
                           <tr>
                              <th class="th-pad">Sr.No</th>
                             
                              <th class="th-pad">From </th>
                              <th class="th-pad">To</th>
                              <th class="th-pad">Shift </th>                              
                              <th class="th-pad">Status</th>
                              <th class="th-pad">Action</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php $counter = 1; @endphp
                           @if(Request::get('page') && ! empty(Request::get('page')))
                           @php
                           $page = Request::get('page') - 1;
                           $counter = 10 * $page + 1;
                           @endphp
                           @endif
                           @foreach($leaves as $value)
                           <tr>
                              @php $encryptId = Crypt::encrypt($value->id); @endphp
                              <td>{{ $counter }}</td>
                            
                              
                              <td>{{$value->start_date}}</td>
                              <td>{{$value->end_date}}</td>
                             
                              <td>@if($value->leave_type_id == 1) Full day @elseif($value->leave_type_id == 2) Half Leave </br> @if($value->half_day_type=='first_half') First Half  @elseif($value->half_day_type=='second_half') Second Half @endif @elseif($value->leave_type_id == 4) Work From Home @else  Short leave </br>@if(!empty($value->start_time)){{ date('h:i A', strtotime($value->start_time))}} <br>@if(!empty($value->end_time)){{ date('h:i A', strtotime($value->end_time))}}@endif @endif</br>   @endif</td>
                           
                         
                              @if($value->leave_status == 'approved') 
                              <td style="color: green">Approved @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif</td>
                              @elseif($value->leave_status == 'not_approved') 
                              <td style="color: red">Not Approved @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif</td>
                              @elseif($value->leave_status == 'cancelled') 
                              <td style="color:#e60000">Cancelled</td>
                              @else
                              <td style="color:blue">Pending @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif</td>
                              
                              @endif
                              <td><a href="javascript:void(0);"  onclick="viewdetails('{{$value->id}}')" title="view details">
                                 <i class="fa fa-eye" style="font-size:20px;"></i>
                                 </a> 
                                                         
                               
                                      
                                                            
                              </td>
                           </tr>
                           @php $counter++ @endphp
                           @endforeach
                           @if(!$leaves->count())
                           <tr>
                              <td colspan="8" class="text-center"><b>No records found</b></td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                     <div class="pagination">
                        {{ $leaves->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
</div>

<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="cancelModalLabel">Cancel Leave Reason</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
            <div class="reason_heading"><h3>Reason : </h3></div>
               <div class="employee_details">
                  <div class="cancel_leave_reason"></div>
               </div>
            </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
           </div>
       </div>
   </div>
</div>

<script src="{{ URL::asset('js/custom.js') }}"></script>
<script>
   function showCancelModal(dataLeaveId) {
      // Showing cancel reason modal when click on cancel text
      const csrfToken = "{{ csrf_token() }}";
      $.ajax({
         url: '/cancel_reasons/' + dataLeaveId,
         type: 'POST',
         headers: {
            'X-CSRF-TOKEN': csrfToken
         },
         success: function(response) {
            $('#cancelModal').modal('show');
            $('.cancel_leave_reason').text(response.cancel_reason);
         },
         error: function(xhr, status, error) {
            console.error(error);
         }
      });
   }
</script>
@include('leave-management.show')
@endsection
@include('leave-management.commonjs')