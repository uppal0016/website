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
                                 
                             @if($value->leave_status == 'approved') <td style="color: green"><div class="realtimeststus_{{$value->id}}">Approved  @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif </div></td> @elseif($value->leave_status == 'not_approved')<td style="color: red"> @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif <div class="realtimeststus_{{$value->id}}">Not Approved </div></td>   @elseif($value->leave_status == 'cancelled') 
                              <td style="color:#e60000">Cancelled</td>  @else <td style="color:blue"><div class="realtimeststus_{{$value->id}}">Pending @if($value->request_type=='cancel_request') <div style="color:red; cursor: pointer;" onclick="showCancelModal('{{ $value->id }}')"> Cancel Leave </div> @endif </div> </td>  @endif                       
                                        
                             <td><p class="response_{{$value->id}}"> <a href="javascript:void(0);"  onclick="viewdetails('{{$value->id}}')" title="view details">
                                                <i class="fa fa-eye" style="font-size:20px;"></i>
                                                </a> 
                                                &nbsp
                           @if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,5,1]) && Auth::user()->id != $value->users_id || !empty($teams)  )                   
                           <a href="javascript:void(0);" class="<?php if($value->leave_status == 'approved'){ echo"disable";} ?>" onclick="statusupdate('{{$value->id}}')" title="Approve" >
                           <i class="fa fa-check" style="font-size:20px;"></i>
                           </a> &nbsp
                           <a  href="javascript:void(0);" class="<?php if($value->leave_status == 'not_approved' ){ echo"disable";} ?>"  onclick="reject('{{$value->id}}')"id="Reject" title="reject">
                           <i class="fa fa-times-circle" style="font-size:20px;color:red"></i>
                           </a>
                         
           
            @endif
            </p>
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
        {{ $leaves->appends(['text'=>$keyword,'from'=>$from,'to'=>$to,'status'=>$status,'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
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
                    