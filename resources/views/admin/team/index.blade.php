@extends('layouts.page')
@section('content')
<style type="text/css"> .multiselect-dropdown span.maxselected {
  width: 70%;
} </style>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page"> Team Management</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right fieldSearch">
        <!-- <form id="searchForm" method="get" action="javascript:void(0);" role="search">
          <div class="input-group custom-searchfeild customSearch ">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search..." aria-describedby="button-addon6" id="search">
            <button class="btn btn-primary searchButton" type="submit" name="submit">
              <i class="fa fa-search"></i>
            </button>
            
          </div>
        </form>   -->
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card" >
        <!-- Card header -->
        <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Employees</h3>
            <div class=" p-0">
            <a href="{{ URL('admin/team/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Team">+ </a><br>
            </div>
          </div>

        {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
        </div> --}}
        <!-- Light table -->
        <div class="table-responsive" id ="dynamicContent" >
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="col">S.No</th>
                <th width="20%">Reporting Lead</th>
                <th width="20%">Employee</th>
                 <th width="10%">Selected </br>Employee</th>
                <th width="10%">Leave </br>Approval</th>
                 <th width="10%">DSR </br>Approval</th>
                   <th width="10%">view</br> Attendance</th>
                 <th width="10%">Action</th>
            </tr>
            </tr>
            </thead>
            <tbody class="list">
              @php $counter = 1; @endphp
@if(Request::get('page') && ! empty(Request::get('page')))
                           @php
                           $page = Request::get('page') - 1;
                           $counter = 10 * $page + 1;
                           @endphp
                           @endif


                  @foreach($teams as $value)
              <tr>
              <td>{{$counter}}</td>
              <td>
              <div class="input-group input-group-merge input-group-alternative">
                  <select class="form-control select_btn_icon"  name="team_lead" id="team_lead_{{$value->id}}">
                      <option value="">Select Project Manager</option>
                      @foreach($project_managers as $managers)
                          <option  <?php if($value['team_lead_id']== $managers->id ) echo "selected='selected'"; ?> value="{{ $managers->id }}">{{ $managers->first_name }} {{ $managers->last_name }}</option>                      
                      @endforeach
                  </select>
                </div>                                        
                </td>
                 <td>  
             <?php  $res = str_replace( array( 
                ',' ), ' ', $value->employee_id);
             $employeeid =  explode(" ",$res);

              ?>

                 <select name="employee[]" id="employee_{{$value->id}}" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="2"   onchange="console.log(this.selectedOptions)">
                                                 
                      @foreach($employees as $user)                   
                          <option  <?php if(in_array($user->id,$employeeid ) ) echo "selected='selected'"; ?> value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>   
                      @endforeach
                  </select>
    
              </td>
                <td width="10%"><div class="employee_name{{$value->id}}">  @if(count($employeeid)>15)
                All
                @else @foreach($employees as $key=> $user)
               
                @if(in_array($user->id,$employeeid ))

           {{$user->first_name.' '.$user->last_name}}
           
           </br> 
           
                @endif   
        
            @endforeach
             @endif
      <?php if($value->employee_id == 'all'){ echo"All";} ?> </div></td>  
                <?php  $leave_approve = $value->leave_approve > 0 ? 'checked' : '';
                       $dsr_approve = $value->dsr_approve > 0 ? 'checked' : '';
                        $attendance_approve = $value->attendance_approve > 0 ? 'checked' : '';
                  ?>   
              <td><input type="checkbox" dataid="{{$value->id}}" id="leave_approve_{{$value->id}}" name="leave_approve"  {{$leave_approve}}></td>
              <td><input type="checkbox" dataid="{{$value->id}}" id="dsr_approve_{{$value->id}}" name="dsr_approve" {{$dsr_approve}}></td>
                 <td><input type="checkbox" dataid="{{$value->id}}" id="attendance_approve_{{$value->id}}" name="attendance_approve" {{$attendance_approve}}></td>  
              <td><button type="button" class="btn btn-primary update add-user-btn"  dataid="{{Crypt::encrypt($value->id)}}" row-id="{{$value->id}}" name="submit">Update</button></td>    
                    
              </tr>
             @php $counter++ @endphp
                  @endforeach
                  @if(!$teams->count())
                    <tr>
                      <td colspan="5" class="text-center"><b>No records found</b></td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="pagination">
                {{ $teams->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
              </div>
        </div>
        <!-- Card footer -->
       
      </div>
    </div>
  </div>
</div>
@section('script')   

<script type="text/javascript">
  
$(document).ready(function(){
$('input[type=checkbox]').click(function () {
    $(this).prop("checked") ? $(this).val("1") : $(this).val("0")
});
$('input[type=checkbox]').click(function () {
    $(this).prop("checked") ? $(this).val("1") : $(this).val("0")
});
  $(".update").on("click", function () { 
var id =  $(this).attr("dataid");
var rowid =  $(this).attr("row-id");
var team_lead =  $("#team_lead_"+rowid).val();
var employee =  $("#employee_"+rowid).val();

var leave_approve =  $("#leave_approve_"+rowid).val();
var dsr_approve =  $("#dsr_approve_"+rowid).val();
 var attendance_approve =  $("#attendance_approve_"+rowid).val();
  $.ajax({
  headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },              
 url: "{{ url('/admin/team/update') }}?id="+id,
 method: 'post',
 data:{team_lead:team_lead,employee:employee,leave_approve:leave_approve,dsr_approve:dsr_approve,attendance_approve:attendance_approve},  
success: function(result){
  $(".employee_name"+result.id).html(result.employee);
  if(result.status==200){
   $('.alert-success').show();
   $('.alert-success').html("updated Successfully");
           setTimeout(function(){
        $(".ajax-success-alert").fadeOut();
   
      }, 2000);
   }
 }
 });
 }); 
                               
  });                          
                            
    </script>
    <script src="{{ asset('js/multiselect-dropdown.js') }}"></script>
@endsection
@endsection
