<div class="row mb-4 dsr-details-list">

<div class="col-xl-6">
   <div class="">
      <p class="m-0 text-left"><strong>
         Employe Name: </strong>{{$html->first_name}}
      </p>
   </div>
   <div class="">
      <p class="m-0 text-left"><strong>Phone No: </strong>{{$html->mobile_number}}</p>
   </div>
   <div class="">
      <p class="m-0 text-left"> @if($ccusers->count() > 0)<strong>CC Users: </strong> @foreach($ccusers  as $key=>$val)
 @if($key !=0),@endif  {{$val->first_name}}   @endforeach    @endif
   </div>
   </br>
   @if($html->leave_type_id == 1)
   <div class="">
      <p class="m-0 text-left"><strong>
         From Date: </strong>{{$html->start_date}}
      </p>
   </div>
   @elseif($html->leave_type_id == 2)
   <div class="">
      <p class="m-0 text-left"><strong>
         Date: </strong>{{$html->end_date}}
      </p>
   </div>
   @elseif($html->leave_type_id == 4)
   <div class="">
      <p class="m-0 text-left"><strong>
         From Date: </strong>{{$html->start_date}}
      </p>
   </div>
   @else

   <div class="">
      <p class="m-0 text-left"><strong>
         Date: </strong>{{$html->end_date}}
      </p>
   </div>
   <div class="">
      <p class="m-0 text-left"><strong>
         Start Time: </strong>{{$html->start_time}}
      </p>
   </div>
   @endif
   <div class="">
      <p class="m-0 text-left"><strong>
         Status: </strong>@if($html->leave_status == 'approved') 
                              Approved                             
                              @elseif($html->leave_status == 'not_approved') 
                            Not Approved
                              @elseif($html->leave_status == 'cancelled') 
                            Cancelled
                              @else
                            Pending
                              @endif
      </p>
   </div>
</div>
<div class="col-xl-6">
   <div class="">
      <p class="m-0 text-left"><strong>
         Employe Id: </strong>{{$html->employee_code}}
      </p>
   </div>
   <div class="">
      <p class="m-0 text-left"><strong>
         Email: </strong>{{$html->email}}
      </p>
   </div>
   <div class="">
      <p class="m-0 text-left"><strong>Leave Type: </strong>@if($html->leave_type_id == 1) Full day @elseif($html->leave_type_id == 2) Half Leave @elseif($html->leave_type_id == 4) Work From Home   @else  Short leave   @endif</p>
   </div>
   @if($html->leave_type_id == 1)
   <div class="">
      <p class="m-0 text-left"><strong>
         To Date: </strong>{{$html->end_date}}
      </p>
   </div>
   @elseif($html->leave_type_id == 2)
   <div class="">
      <p class="m-0 text-left"><strong>
         Shift: </strong>@if($html->half_day_type == 'first_half') First Half @else Second Half @endif
      </p>
   </div>
   @elseif($html->leave_type_id == 4)
   <div class="">
      <p class="m-0 text-left"><strong>
         To Date: </strong>{{$html->end_date}}
      </p>
   </div>
      @else
      </br>
      <div class="">
         <p class="m-0 text-left"><strong>
            End Time: </strong>{{$html->end_time}}
         </p>
      </div>
      @endif
      <div class="">
         <p class="m-0 text-left"><strong>Leave Applied Date: </strong> {{ date('d-m-Y', strtotime($html->created_at))}}</p>
      </div>
   </div>
</div>
<div class="row">
<div class="col-xl-12">
 
      <div class="">
         <p class="m-0 text-left"><strong>  Leave Reason: </strong>{{$html->leave_reason}}
      
   </div>
</div>
@if(!empty($html->leave_rejection_reason) && $html->leave_status == 'not_approved')
<div class="col-xl-12">

<div class="">
   <p class="m-0 text-left"><strong> Leave Rejection Reason: </strong>{{$html->leave_rejection_reason}} </p>
</div>
</div>

@endif
</div>
&nbsp
</div>
@if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,5,1])  && Auth::user()->id != $html->users_id)
@if(Auth::user()->id)
<div class="row">
   <div class="col-md-2"><div class="">
   <p class="m-0 text-left "> <a href="javascript:void(0);"  class="btn btn-primary <?php if($html->leave_status == 'approved'){ echo"disable";} ?>" onclick="statusupdate('{{$html->id}}','{{$html->leave_status}}')" title="Approve" >
      Approve
      </a>
   </p>
</div></div>
  
   <div class="col-md-2" ><div class="">
   <p class="m-0 text-right"><a  href="javascript:void(0);"   class="btn btn-danger <?php if($html->leave_status == 'not_approved' ){ echo"disable";} ?>"onclick="reject('{{$html->id}}','{{$html->leave_status}}')"id="Reject" title="reject">
      Reject
      </a>
      </p>
</div>
   </div>
</div>
</div>

@endif
@endif