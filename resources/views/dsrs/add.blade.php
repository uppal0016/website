@extends('layouts.page')
@section('content') 
<style>

  .btn-sm.btn-circle{
    border-radius: 15px;
  }
  .btn-md.btn-circle{
    border-radius: 18px;

  }
  .table-row{
    display: flex;
  }
span{
  font-size:12px;
}

</style>
<link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">DSR</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid dsr-detail-pg mt--6">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <!-- Card header -->
        <div class="card-header d-flex justify-content-between align-items-center border-0">
          <h3 class="mb-0">Add DSR</h3>
        </div>
        <div class="card-body">
          <div class="canvas-wrapper">
            <div class="table-responsive">
              <form id="add_dsr" action="{{url('/dsrs')}}" method="post"  enctype="multipart/form-data" novalidate>
                  
              
                 <div class="dsr-list">
                
                <table class="table input-list">
                  <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                  @php $i = 1;  $count = count($DsrDetail)+1;     @endphp
                     @foreach($DsrDetail as $value)
                     <tr class="table-row">
                    <td width="20%">
                      <input type="hidden"  id="task_0_0" name="desrdetaisid[{{$i}}][{{$i}}]"  value="{{ $value->id }}"/>
                      <select id="addRow[{{$i}}][project_id]" data-id="{{$i}}"  class="form-control dsr  vaidation select-project{{$i}}" name="project_id[{{$i}}]">
                        <option value="" disable="true">Select Project</option>                     
                        @foreach($projects as $project)
                          <option data-id="{{$project->project_manager}}" <?php if($value['project_id']== $project->id ) echo "selected='selected'"; ?> value ="{{($project->id)}}">
                            {{$project->name}}
                          </option>
                        
                        @endforeach
                         <option   <?php if($value['project_id'] == 0 ) echo "selected='selected'"; ?> value="0">Other</option>
                      </select>

                        <span id="projectid{{$i}}" style="color:red"></span>
                    </td>
                    <td width="50%">
                      <textarea id="addRow[{{$i}}][des]" data-id="{{$i}}" name="des[{{$i}}][{{$i}}]"  rows="1" placeholder="Description"   class=" form-control dsr vaidation  dsr-area{{$i}}">{{$value->description}}</textarea>
                      <span id="des{{$i}}" style="color:red"> </span>
                    </td>
                    <!-- <td width="35%">
                      <input id="addRow[{{$i}}][task]" type="text" name="task[{{$i}}][{{$i}}]"  placeholder="Task" class=" form-control dsr" value="{{$value->task}}" />
                    </td> -->
                     <input type="hidden"  id="task_0_0" dataid ="{{ $value->id }}" />
                    <td width="10%">
                      <input class="form-control start_time timer updateStart{{$i}}" row="{{ $i}}" id="start_{{ $i}}_0" name="start[{{$i}}][{{$i}}]" type="text" placeholder="Start Time" value="{{$value->start_time}}" required maxlength="5">
                    </td>
                    <td width="10%">
                      <input class="form-control end_time timer updateEnd{{$i}}" row="{{ $i}}" id="end_{{ $i}}_0" name="end[{{$i}}][{{$i}}]" type="text" placeholder="End Time" value="{{$value->end_time}}" required maxlength="5">
                    </td>
                      <input id="hours_{{ $i}}_0"  type="hidden" name="hours[{{$i}}][{{$i}}]" placeholder="Hrs" class=" form-control dsr timeEst hours-minutes_{{ $i}}_0" value="{{$value->total_hours}}" value="1" />
                      <input id="minutes_{{ $i}}_0" type="hidden" name=" minutes[{{$i}}][{{$i}}]" placeholder="Mins" class="form-control dsr timeEst hours-minutes_{{$i}}_0" />


                    <!--<td width="10%">
                      <input id="hours_0_0" type="text" name="hours[0][0]" placeholder="Hrs" class=" form-control dsr timeEst hours-minutes_0_0" />
                    </td>
                    <td width="10%">
                      <input id="minutes_0_0" type="text" name="minutes[0][0]" placeholder="Mins" class=" form-control dsr timeEst hours-minutes_0_0" />
                    </td>-->

                  <!--   <td width="5%">
                      <a href="javascript:void(0);" data-row="0" data-sub-row="0" class="btn btn-warning btn-sm btn-circle add-task"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    </td> -->
                     <td>
                      <a href="javascript:void(0);" id="{{ $value->id }}" data-row="{{$i}}" data-sub-row="0" class="btn btn-primary  update-rows">Update</a>
                    </td>
                  </tr>
               <!--    
                  <tr>
                    <td colspan="3">
                      <textarea id="addRow[{{$i}}][des]" name="des[{{$i}}][{{$i}}]" placeholder="Description" class=" form-control dsr dsr-area">{{$value->description}}</textarea>
                    </td>
                   
                  </tr> -->
                  <tr class="table-row">
                    <td width="100%" colspan="5">&nbsp</td>
                  </tr>
                   @php $i++ @endphp
                   @endforeach
                   <tr></tr>
                 </table>
                   <table class="table input-lists">
                  <tr class="table-row">
                    <td width="20%">
                   
                      <select id="addRow[{{$count}}][project_id]" data-id="{{ $count}}" class="form-control vaidation <?php if($i >1){
                      }else{ echo"dsr";}?>  select-project{{$count}}" name="addRow[{{ $count}}][project_id]">
                        <option value="" disable="true">Select Project</option>
                        @foreach($projects as $project)
                          <option data-id="{{$project->project_manager}}" value ="{{($project->id)}}">
                            {{$project->name}}
                          </option>
                          
                        @endforeach
                        <option value="0">Other</option>
                      </select>
                       
                      <span id="projectid{{$count}}" style="color:red"></span>
                    </td>
                    <td width="50%">
                      <textarea id="addRow[{{ $count}}][des]" rows="1"  data-id="{{ $count}}" name="addRow[{{ $count}}][des]" placeholder="Description"  class=" form-control vaidation <?php if($i > 1){
                      }else{ echo"dsr";}?> dsr-area{{$count}}"></textarea>
                       <span id="des{{$count}}" style="color:red"> </span>
                    </td>
                 <!--    <td width="35%">
                      <input id="addRow[{{ $count}}][task]" type="text" name="addRow[{{ $count}}][task]" placeholder="Task" class=" form-control dsr" />
                    </td> -->
                    <td width="10%">
                      <input class="form-control start_time timer  updateStart{{$i}}" dataid="{{ $count}}" row="{{ $count}}" id="start_{{ $count}}_0" name="addRow[{{ $count}}][start]]" type="text" placeholder="Start Time"  required maxlength="5">
                    </td>
                    <td width="10%">
                      <input class="form-control end_time timer  updateEnd{{$i}}" row="{{ $count}}" id="end_{{ $count}}_0"name="addRow[{{ $count}}][end]" type="text" placeholder="End Time"  required maxlength="5">
                    </td>
                      <input id="hours_{{ $count}}_0" type="hidden" name="addRow[{{ $count}}][hours]" placeholder="Hrs" class=" form-control dsr timeEst hours-minutes_{{ $count}}_0" value="1" />
                      <input id="minutes_{{ $count}}_0" type="hidden" name="addRow[{{ $count}}][minutes]" placeholder="Mins" class=" form-control dsr timeEst hours-minutes_{{$count}}_0" />


                    <!--<td width="10%">
                      <input id="hours_0_0" type="text" name="hours[0][0]" placeholder="Hrs" class=" form-control dsr timeEst hours-minutes_0_0" />
                    </td>
                    <td width="10%">
                      <input id="minutes_0_0" type="text" name="minutes[0][0]" placeholder="Mins" class=" form-control dsr timeEst hours-minutes_0_0" />
                    </td>-->

                  <!--   <td width="5%">
                      <a href="javascript:void(0);" data-row="0" data-sub-row="0" class="btn btn-warning btn-sm btn-circle add-task"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    </td> -->
                     <td>
                    <div class="updatedsr{{$count}}"> <a href="javascript:void(0);" data-row="{{ $count}}" data-sub-row="0" class="btn btn-primary add-rows">Save</a></div>
                     
                    </td>
                  </tr>
               <!--    
                  <tr>
                    <td colspan="3">
                      <textarea id="addRow[{{ $count}}][des]" name="addRow[{{ $count}}][des]" placeholder="Description" class=" form-control dsr dsr-area"></textarea>
                    </td>
                   
                  </tr> -->
                  <tr>
                    <td width="100%" colspan="5">&nbsp</td>
                  </tr>
                </table>
                </div>
                <br/>

                <div >
                  <label><b>Attachments: </b></label>
                  <input type="file" multiple name="documents[]" id="documents" onchange="checkFileLimit(this)"><br>
                  <small><b>*Note:</b> Only .jpg, .jpeg, .png, .doc, .docx, .pdf, .xlsx and .csv formats are allowed.</small>
                  <p class="help-block">{{ $errors->first('documents.*') }}</p>
                </div>
                <br>
                <div id="send_to">
                  <label><b>Send To:</b></label> 
                  @if(!empty($email_users))
                    @foreach($email_users as $user)
                      <?php
                      $checked = ($user->role_id == 2) ? 'checked' : '';
                      $return = ($user->role_id == 2) ? 'false' : 'true';
                      ?>
                      <input {{ $checked }} type="checkbox" id="check_{{$user->id}}" data-exp="{{$return == 'false' ? 'exp':''}}" name="send_to[]" onclick="return {{$return}} " value="{{ $user->id }}" autocomplete="off"> {{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} &nbsp;&nbsp;
                    @endforeach
                  @endif
                </div> <br>
                <div class="button-group ">
                  <label><b>Add Cc:</b></label> &nbsp
                  <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span>Add Cc</span>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu user_lists ccbox">
                    <div class="row">
                      <div class="col-md-3"></div>
                      <div class="col-md-3" style="margin: 0 0 1% -12%;">
                         <input type="text"  placeholder="Search Employee" id="ccusersearch" >
                         <i id="filtersubmit" class="fa fa-search" style="pointer-events:none"></i>
                      </div>
                   </div>
                    <div class="panel-body">
                      <div class="table-responsive">
                        <div class="ccbox-list">
                          <table class="table-condensed">
                            <tbody>
                            <?php $tdCount = 0;  ?>
                            @foreach($cc_users as $cc_user)
                            <?php 
                             $checked = ($teamLead == $cc_user->id) ? 'checked' : '';
                              $return = ($teamLead == $cc_user->id) ? 'false' : 'true';
                            ?>
                              @if($tdCount == 0)
                                <tr>
                                  @endif
                                  <td>
                                    <input   class="box" id="id_{{$cc_user->id}}" type="checkbox" name="add_cc[]" value="{{$cc_user->id}}"  autocomplete="off" {{$checked}}   onclick="return {{$return}} "/>&nbsp; {{ucfirst($cc_user->first_name)}} {{ucfirst($cc_user->last_name)}}
                                  </td>
                                  @if($tdCount == 3)
                                </tr>
                                <?php $tdCount = 0; ?>
                              @else
                                <?php $tdCount++; ?>
                              @endif
                            @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </ul>
                </div> <br/>
                <div class="subbtn">
                    <input type="submit" id="btn" class="btn btn-primary  pull-center" value="Submit DSR" />
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@section('script')
<script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/jquery.cookie.min.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script>
  $(document).ready(function() {

    $('.timer').keydown(function (e) {    
      var value = $(this).val();
      
      var charCode = (e.which) ? e.which : event.keyCode;
      
      var  lastval = value.slice(-1);
      //alert(value.slice(2));

      var row = $(this).attr("row");
      var start = $('.updateStart'+row).val();
      var startT = start.split(':');
      var end = $('.updateEnd'+row).val();
      if(startT[0] > 24 || startT[1] > 59){
        // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
        swal("Hours should be less then 24 and minuts should be less then 59");
        $('.updateStart'+row).val('');
        return false ;
      }
      var endT = end.split(':');
      if(endT[0] > 24 || endT[1] > 59){
        // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
        swal("Hours should be less then 24 and minuts should be less then 59");
        $('.updateEnd'+row).val('');
        return false ;
      }
      if(e.keyCode == 8 || e.keyCode == 46) {
        
        var position = e.target.selectionStart;
        if(value.slice((position-1), position) == ':'){
          return false;
        }
        
        return true;
      } else {
          
        var check = e.target.selectionStart;

        if (e.keyCode == 9 || e.keyCode == 37 || e.keyCode == 38 || e.keyCode == 39 || e.keyCode == 40) { 
          if((value.slice(0, 2) > 24) || (value.slice(3, 5) > 59)) {
        
            swal("Hours should be less then 24 and minuts should be less then 59");
            // $('#'+current_end_time_id).val('');
            $(this).val('');
            return false;
          }
          return true;
        }      
        if((value.slice(0, 2) > 24) || (value.slice(3, 5) > 59)) {
          
          swal("Hours should be less then 24 and minuts should be less then 59");
          $(this).val('');
              // $('#'+current_end_time_id).val('');
              return false;
        }
        if(check > 1) {
          if(value.indexOf(':') != -1){
            // var time = value.split(':');

          }  else {
            $(this).val(value+':');
          }
        }
        if (String.fromCharCode(charCode).match(/[^0-9]/g))///[^0-9]/g)
      
      // if(value.include(':')){
      //   // var time = value.split(':');

      // }  else {
      //   $(this).val() = value.insert('2', ':');
      // }
    
    
        // if(value.length == 2){
        //   $(this).val() = value+':';
        // }
          return false;  
      }                      

    });

    $('#ccusersearch').on('input', function() {
      var searchText = $(this).val().toLowerCase();
      $('.ccbox-list table tbody tr').each(function() {
        var employeeName = $(this).find('td').text().toLowerCase();
        if (employeeName.indexOf(searchText) !== -1) {
          $(this).show();
        } else {
          $(this).hide();
        }
      });
    });
  });
</script>

  <script>
  function initiateTimer(){
          $(".timer").clockpicker({
              placement: 'bottom',
              align: 'left',
              autoclose: true,
              default: 'now',
              donetext: "Select",
              init: function() {
                  console.log("colorpicker initiated");
              },
              beforeShow: function() {
                  console.log("before show");
              },
              afterShow: function() {
                  console.log("after show");
              },
              beforeHide: function() {
                  console.log("before hide");
              },
              afterHide: function() {
                  console.log("after hide");
              },
              beforeHourSelect: function() {
                  console.log("before hour selected");
              },
              afterHourSelect: function() {
                  console.log("after hour selected");
              },
              beforeDone: function() {
                  console.log("before done");
              },
              afterDone: function() {
                  console.log("after done");
              }
          });
          
          $('.end_time').on('change', function (){

              console.log('change');

          
              var current_end_time_id = $(this).attr('id');
               
              var explode_id = current_end_time_id.split('end');
              var current_start_time_id = 'start'+explode_id[1];
              var row = $(this).attr("row");
              var end = $('.updateEnd'+row).val();
              var endT = end.split(':');
              if(endT[0] > 24 || endT[1] > 59){
                // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
                swal("Hours should be less then 24 and minuts should be less then 59");
                $('.updateEnd'+row).val('');
                return false ;
              }
              checkTimeDifference(current_start_time_id, current_end_time_id, explode_id[1]);
          });

          $('.start_time').on('change', function (){
             console.log('change');
              var current_start_time_id = $(this).attr('id');

              var explode_id = current_start_time_id.split('start');

              var current_end_time_id = 'end'+explode_id[1];
              var row = $(this).attr("row");
              var start = $('.updateStart'+row).val();
              var startT = start.split(':');
              if(startT[0] > 24 || startT[1] > 59){
                // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
                swal("Hours should be less then 24 and minuts should be less then 59");
                $('.updateStart'+row).val('');
                return false ;
              }
              checkTimeDifference(current_start_time_id, current_end_time_id, explode_id[1]);
          });

          return true;
      }

  function checkTimeDifference(current_start_time_id, current_end_time_id, row){
        console.log("check time");  
      var start_time = $('#'+current_start_time_id).val();

      var end_time = $('#'+current_end_time_id).val();

      //create date format
      if(end_time === ''){
        return false
      }
      if(start_time === ''){
          swal("Please enter start time first");
          $('#'+current_end_time_id).val('');
          return false;
      }
      var timeStart = new Date("01/01/2007 " + start_time);
      var timeEnd = new Date("01/01/2007 " + end_time);
      var seconds = (timeEnd.getTime() - timeStart.getTime()) / 1000;
      var minutes = seconds / 60;
      console.log("minutes ======== " + minutes);
      if(minutes > 60){
          swal("Time of one task should not be greater than 1 hour. You can break the task into two or more tasks.");
          $('#'+current_end_time_id).val('');
          return false;
      }
      if(minutes <= 0){
          swal("End time should not be less than or equal to start time.");
          $('#'+current_end_time_id).val('');
          return false;
      }

      var hrs = Math.floor(minutes / 60);
      // Getting the minutes.
      var min = minutes % 60;
      console.log('#hours'+row);
      $('#hours'+row).val(hrs);
      $('#minutes'+row).val(min);
      console.log(hrs + " Hours and " + min + " Minutes");
  }


  $(document).ready(function(){
      let allCookies = document.cookie.split(';')
      // console.log('allCookies',allCookies)
       allCookies.forEach(value=>{
        if(value.includes('cc_selected')){
            let cookieData = value.split('=');
            cookieData = JSON.parse(decodeURIComponent(cookieData[1]))
            // console.log('cookieData',cookieData);
             $("input.box").each(function(ccUsers){
              cookieData.find((value)=>{                
                if(value.id == $(this).val()){ 
                var id =  $(this).val();             
                $("#id_"+id).prop('checked',true)

                }
              })
                
             })
            // cookieData.forEach(value=>{

            // })

        }
      })
 initiateTimer();
        var i = 1,
        j = 1,
        projects = [],
        options = '', 
        subRow = [0];

    <?php
      if($projects){ ?>
        projects = <?php echo $projects; ?>
    <?php } ?>

    projects.forEach(function(project){
      options +='<option data-id="'+project['project_manager']+'" value ="'+project['id']+'">'+project['name']+'</option>';
    });

 var btnDisable = '<?php echo $DsrDetail->count(); ?>'; 

 if(btnDisable == 0){
  var timein = '<?php echo $time_in;?>';
  $("#start_1_0").val(timein); 
  $("#end_1_0").val(moment(timein, 'HH:mm').add(60, 'minutes').format('HH:mm'));
  $('input[type="submit"]').attr('disabled' , true);
  }
  
 var lastfieldsid = $('.start_time').last().attr('dataid')-parseInt(1);
  var last = $('.start_time').last().attr('dataid'); 
  if(lastfieldsid > 0){ 
  var endtime =  $("#end_"+lastfieldsid+"_0").val();
  var endtime1 = moment(endtime, 'HH:mm').add(1, 'minutes').format('HH:mm');
  var start =  $("#start_"+last+"_0").val(endtime1);
  const endTime = moment(endtime1, 'HH:mm').add(60, 'minutes').format('HH:mm');    
  var endlasttime = $("#end_"+last+"_0").val(endTime);
  }  


 $("input.box").change(function() {
      let cc_cookie=[];   
      $("input.box").each(function (prop){
        if($(this).prop('checked')){
          cc_cookie.push({id: $(this).attr("value")})  
        }
      })      
     $.cookie('cc_selected',JSON.stringify(cc_cookie), {      
        expires: 100
    });
});

//update rows
$("body").on("click", ".update-rows", function () { 
  var id =  $(this).attr("id");
  var row = $(this).attr("data-row");
  var project_id  = $('.select-project'+row).val();
  var des = $('.dsr-area'+row).val();  
  var start = $('.updateStart'+row).val();
  var end = $('.updateEnd'+row).val();
  var hours  = $('#hours_'+row+'_0').val();
  var minutes  = $('#minutes_'+row+'_0').val(); 
  if(project_id == '' ){
   $('#projectid'+row).html("Please Select Project");
   return false ;
  }else{
     $('#projectid'+row).html("");
  }
  if(des == ''){
    $('#des'+row).html("Please Enter Description");
     return false ;
  }else{
      $('#des'+row).html("");
  } 
  if(start == '' || end == ""){
    swal("Please Enter Start Time and End time");
    return false ;
  }
  var startT = start.split(':');
  if(startT[0] > 24 || startT[1] > 59){
    // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
    swal("Hours should be less then 24 and minuts should be less then 59");
    $('.updateStart'+row).val('');
    return false ;
  }
  var endT = end.split(':');
  if(endT[0] > 24 || endT[1] > 59){
    // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
    swal("Hours should be less then 24 and minuts should be less then 59");
    $('.updateEnd'+row).val('');
    return false ;
  }
   var iChars = "!`@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
    if(des == 0  || iChars.indexOf(des.trimStart().charAt()) != -1){ 
      $('#des'+row).html("Space  and  special characters  not allowed.");  

           return false;       
      }else{
         $("#des"+row).html("");  
      }
  $.ajax({
  headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },              
 url: "{{ url('/addOneHourDsr') }}?id="+id,
 method: 'post',
 data:{project_id:project_id,description:des,start_time:start,end_time:end,hours:hours,minutes:minutes},  
success: function(result){
 }
 });
 }); 
  // add Rows
  $("body").on("click", ".add-rows", function () { 
  var row =  $(this).attr("data-row");
  var project_id  = $('.select-project'+row).val();
  var othervalue  =  $('.othervalue'+row).val();

  var des = $('.dsr-area'+row).val();
  var start = $('.updateStart'+row).val();
  var end = $('.updateEnd'+row).val();
  var hours  = $('#hours_'+row+'_0').val();
  var minutes  = $('#minutes_'+row+'_0').val();  
  if(project_id == ''){
   
   $('#projectid'+row).html("Please Select Project");
    return false ;
  }else if(othervalue == ''){
     $('#projectid'+row).html("Please Select Project");
     return false ;
  }else{
     $('#projectid'+row).html("");
  }
  if(des == ''){
    $('#des'+row).html("Please Enter Description");
  return false ;
  }else{
      $('#des'+row).html("");
  }
  if(start == '' || end == ""){
    swal("Please Enter Start Time and End time");
    return false ;
  }
  var startT = start.split(':');
  if(startT[0] > 24 || startT[1] > 59){
    // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
    swal("Hours should be less then 24 and minuts should be less then 59");
    $('.updateStart'+row).val('');
    return false ;
  }
  var endT = end.split(':');
  if(endT[0] > 24 || endT[1] > 59){
    // $('#timepicker'+row).html("Please enter hours less 24 and mints less then 59");
    swal("Hours should be less then 24 and minuts should be less then 59");
    $('.updateEnd'+row).val('');
    return false ;
  }
    var iChars = "!`@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";
    if(des == 0  || iChars.indexOf(des.trimStart().charAt()) != -1){ 
      $('#des'+row).html("Space  and  special characters  not allowed.");  

           return false;       
      }else{
         $("#des"+row).html("");  
      }
  $.ajax({
  headers: {
 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },              
  url: "{{ url('/addOneHourDsr') }}",
  method: 'post',
  data:{project_id:project_id,othervalue:othervalue,description:des,start_time:start,end_time:end,hours:hours,minutes:minutes},
  success: function(result){

  if(result.status == true){    
     $('input[type="submit"]').attr('disabled' , false);
   }
   
    calldsrpage();
 }
  }); 
    });

 function calldsrpage() {  
    $.ajax({
          headers: {
     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }, 
   url: "{{url()->current()}}",
   type: "post",
   dataType: 'json',

   success:function(data){              
       $(".dsr-list").html(data['html']);
         $('#loader-body').fadeOut();
          }
      })
   }

    $(document).on('click', '.delRow', function(){

      var getId = $(this).attr('id');
      getId = getId.split('_')[1];
      var c = confirm('Are you sure want to delete this row?');
      if(true == c){
        $('.row_' + getId).remove();
        return true;
      } else{
        return false;
      }
    });

    $(document).on('change', '.select-project', function(){
      var that = $(this),
          select = $('.select-project:first')
          opts = select.find(':selected'),
          mIds = [],
          allMIds = <?php echo $email_users->where('role_id', 3);?>,
          temp = '';
      /*if(allMIds != undefined){

        allMIds = allMIds.map(o => String(o.id));
        $.each(allMIds, function(k,i){
          $('#check_'+i).prop('checked', false);
        })
      }*/

      $.each(opts, function(){

        temp = $(this).attr('data-id');

        if(temp && !mIds.includes(temp)) mIds.push(temp);
      });

      $.each(mIds, function(k,id){

        $('#check_'+id).prop('checked', true);

      });

    })


    $(document).on('click', '.remove-task', function(){

      var c = confirm('Are you sure want to delete this row?');
      if(true == c){
        $(this).closest('tr').next('tr').remove();
        $(this).closest('tr').remove();
        return true;
      } else{
        return false;
      }
    });


    $('.input-lists').on('click', '.add-task', function(){

      $('#add_dsr').removeData('validator');
      $('#add_dsr').removeData('unobtrusiveValidation');

      var that = $(this),
          row = that.attr('data-row'),
          sr = subRow[row] + 1,
          taskRow = '<tr class="row_' + row + '">' +
              '<td width="10%"><input type="text" id="task_'+row+'_'+sr+'" name="task['+row+']['+sr+']" placeholder="Task" class="form-control dsr"></td>' +
              '<td width="10%"><input id="start_'+row+'_'+sr+'" type="text" name="start['+row+']['+sr+']" placeholder="Start Time" class=" form-control timer dsr  start_time" required readonly/></td>' +
              '<td width="20%"><input id="end_'+row+'_'+sr+'" type="text" name="end['+row+']['+sr+']" placeholder="End Time" class=" form-control timer dsr  end_time" required readonly/></td>' +
              '<input id="hours_'+row+'_'+sr+'" type="hidden" name="hours['+row+']['+sr+']" placeholder="Mins" class=" form-control dsr timeEst hours-minutes_'+row+'_'+sr+'" />'+
              '<input id="minutes_'+row+'_'+sr+'" type="hidden" name="minutes['+row+']['+sr+']" placeholder="Mins" class=" form-control dsr timeEst hours-minutes_'+row+'_'+sr+'" />'+
              '<td width="5%"><a href="javascript:void(0);" data-row="'+row+'" data-sub-row="'+sr+'" class="btn btn-danger btn-sm btn-circle remove-task"><i class="fa fa-times" aria-hidden="true"></i></a></td>' +
              '</tr>' +
              '<tr class="row_' + row + '" id="sub-row_'+row+'_'+sr+'">' +
              '<td colspan="3"><textarea id="des_'+row+'_'+sr+'" name="des['+row+']['+sr+']" placeholder="Description" class=" form-control dsr dsr-area"></textarea></td>' +
              '</tr>',
          id = $('#sub-row_'+row+'_'+subRow[row]).attr('id');

      if(id){

        $(taskRow).insertAfter('#sub-row_'+row+'_'+subRow[row]);
      }else{
        $(taskRow).insertAfter(that.closest('tr').next('tr'));
      }

      subRow[row] = sr;

      // initiateTimer();
      return;
    });


    $(document).on('keydown', '.timeEst', function(e){

      var targetValue = $(this).val();
      if(e.which !== 9 && e.which !== 116){

        if (e.which ===8 || e.which === 13 || e.which === 37 || e.which === 39 || e.which === 46) { return; }

        if (((e.which > 47 &&  e.which < 58) || (e.which > 95 && e.which < 106) || e.which === 116) && targetValue.length < 2) {

          var c = String.fromCharCode(e.which);
          var val = parseInt(c);
          var textVal = parseInt(targetValue || "0");
          var result = textVal + val;
          if (result < 0 || result > 99) {
             e.preventDefault();
          }

          if (targetValue === "0") {
            $(this).val(0);
            e.preventDefault();
          }
        }
        else {
          e.preventDefault();
        }
      }
    });
 
    $(document).on('cut copy paste', '.timeEst', function(e){
      e.preventDefault();
    });
  
 

  });


</script>

<script type="text/javascript">
 
    var options = [];
    $( '.user_lists a' ).on( 'click', function( event ) {
      console.log('jhgjdhfjkdhgkd');
      var $target = $( event.currentTarget ),
         val = $target.attr( 'data-value' ),
         $inp = $target.find( 'input' ),
         idx;
      if ( ( idx = options.indexOf( val ) ) > -1 ) {
        options.splice( idx, 1 );
        setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
      } else {
        options.push( val );
        setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
      }
      $( event.target ).blur();
      return false;
    });



</script>

<script>
  function checkFileLimit(input) {
    if (input.files.length > 5) {
      input.value = ''; // Clear the selected files
      alert('Please select a maximum of 5 files.');
    } else {
      for (var i = 0; i < input.files.length; i++) {
        if (input.files[i].size > 2 * 1000 * 1000) {
          input.value = ''; // Clear the selected files
          alert('Please ensure that each file is less than or equal to 2MB in size.');
          break;
        }
      }
    }
  }

</script>

@endsection

@endsection
