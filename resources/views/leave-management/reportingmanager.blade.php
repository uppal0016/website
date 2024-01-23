@extends('layouts.page')
@section('content')
<?php 
   use Carbon\Carbon;
   ?>
   
<div class="header bg-primary pb-6">
   <div class="container-fluid">
      <div class="header-body">
     
     
         <div class="row align-items-center py-4">
         
            <div class="col-lg-8 col-7 secLeft">
               <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
               <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                     <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                     <li class="breadcrumb-item active" aria-current="page">Leave</li>
                  </ol>
               </nav>
            </div>
            <div class="col-lg-4 col-5 text-right formResponsive">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
          
          <div class="input-group custom-searchfeild">
          <div class="col-sm-12 customSearch padd_0">
            <input type="hidden" id="token" name="_token" value="nwtBN9MPjw4lU6ukGsiqDOdokiLuKz5VtpatAxHC">
            <input autocomplete="off" name="employeSearch" type="text"  id ="employeSearch"class="form-control search-length" placeholder="Search by employee name" aria-describedby="button-addon6">
            <button class="btn btn-primary searchButton" type="button" name="submit" id="search">
              <i class="fa fa-search"></i>
            </button>
           
           </div>
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
            <div class="card-header border-0">
               {{ Form::open(array('url'=>url('leave/export'),'autocomplete' => 'off')) }}
               <div class="row align-items-center">
                   <div class="col-md-2">
                       <label for="">From</label>
                       <div class="input-group custom-searchfeild">
                           <input type="text" id="from" autocomplete="off" placeholder="From date"  name="from" class="form-control">
                       </div>
                   </div>
                   <div class="col-md-2">
                       <label for="">To</label>
                       <div class="input-group custom-searchfeild">
                           <input type="text" id="to" autocomplete="off" placeholder="End Date" name="to" class="form-control">
                       </div>
                   </div>
                   <div class="col-md-2">
                       <label for="">Status</label>
                       <select class="form-control select_btn_icon" id="leave_status" name="status"> 
                           <option value="all">All</option>                                     
                           <option value="approved">Approved</option>
                           <option value="not_approved">Not Approved</option>
                           <option value="Pending">Pending</option>
                       </select>
                   </div>
                   <div class="col-md-2 mt-4">
                       <div class="input-group custom-searchfeild">
                           <button type="button" class="btn btn-primary" onclick="daterangeSearch()">Search</button>
                       </div>
                   </div>
                   <div class="col-md-2 mt-4">
                       <div class="input-group custom-searchfeild">
                           <button type="button" class="btn btn-primary" onclick="daterangeSearch('{{date('Y-m-d')}}')">FilterByToday</button>
                       </div>
                   </div>
                   <div class="col-md-2 mt-4">
                       <div class="input-group custom-searchfeild">
                           <button type="submit" class="btn btn-primary">Export</button>
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
                              <th class="th-pad">Employee name</th>
                             
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
                              <td>{{$value->first_name}} {{$value->last_name}} ({{$value->employee_code}})</td>
                          
                              <td>{{$value->start_date}}</td>
                              <td>{{$value->end_date}}</td>
                             
                              <td>@if($value->leave_type_id == 1) Full day @elseif($value->leave_type_id == 2) Half Leave </br> @if($value->half_day_type=='first_half') First Half  @elseif($value->half_day_type=='second_half') Second Half @endif @elseif($value->leave_type_id == 4) Work From Home  @else  Short leave </br>@if(!empty($value->start_time)){{ date('h:i A', strtotime($value->start_time))}} <br>@if(!empty($value->end_time)){{ date('h:i A', strtotime($value->end_time))}}@endif @endif</br>   @endif</td>
                              
                            @if($value->leave_status == 'approved' ) <td style="color: green"><div class="realtimeststus_{{$value->id}}">Approved @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif</div> </td> @elseif($value->leave_status == 'not_approved') <td style="color: red"><div class="realtimeststus_{{$value->id}}">Not Approved @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif</div></td>   @else <td style="color:blue"><div class="realtimeststus_{{$value->id}}">Pending @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif</div> </td>  @endif 
                         
                         <td>  <p class="response_{{$value->id}}"><a href="javascript:void(0);"  onclick="viewdetails('{{$value->id}}')" title="view details">
                                 <i class="fa fa-eye"></i>
                                 </a>                                             
                                &nbsp
                                 <a href="javascript:void(0);" class="<?php if($value->leave_status == 'approved'){ echo"disable";} ?>"  onclick="statusupdate('{{$value->id}}','{{$value->leave_status}}')" title="Approve" disabled>
                                 <i class="fa fa-check"></i>
                                 </a> 
                                  &nbsp
                                 <a  href="javascript:void(0);" class="<?php if($value->leave_status == 'not_approved' ){ echo"disable";} ?>" onclick="reject('{{$value->id}}','{{$value->leave_status}}')" id="Reject" title="reject">
                                 <i class="fa fa-times-circle" style="color:red"></i>
                                 </a></p>
                             
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

<div id="myModal" class="modal fade" tabindex="-1" style="margin-top:100px">
   <input type ="hidden" id= "leaveid" value="">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Leave take action</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            &nbsp
            <textarea  name="description" class="form-control"  id="description" name="description" placeholder="Leave Rejection Reason" length="500" maxlength="500" required></textarea>
            <span class="error"></span>
         </div>

         <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="statusupdate()">update</button>
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
