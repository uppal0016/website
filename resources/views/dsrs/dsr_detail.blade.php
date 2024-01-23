 
           <table class="table input-list">
                  <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                  @php $i = 1;  $count = count($DsrDetail)+1;     @endphp
                     @foreach($DsrDetail as $value)
                     <tr class="table-row">
                    <td width="20%">
                      <input type="hidden"  id="task_0_0" name="desrdetaisid[{{$i}}][{{$i}}]"  value="{{ $value->id }}"/>
                      <select id="addRow[{{$i}}][project_id]" data-id="{{$i}}" class="form-control dsr vaidation select-project{{$i}}" name="project_id[{{$i}}]">
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
                      <textarea id="addRow[{{$i}}][des]"  data-id="{{$i}}" name="des[{{$i}}][{{$i}}]"  rows="1" placeholder="Description"  class=" form-control dsr  vaidation dsr-area{{$i}}">{{$value->description}}</textarea>
                      <span id="des{{$i}}" style="color:red"> </span>
                    </td>
                    <!-- <td width="35%">
                      <input id="addRow[{{$i}}][task]" type="text" name="task[{{$i}}][{{$i}}]"  placeholder="Task" class=" form-control dsr" value="{{$value->task}}" />
                    </td> -->
                     <input type="hidden"  id="task_0_0" dataid ="{{ $value->id }}" />
                    <td width="10%">
                      <input class="form-control start_time timer  updateStart{{$i}}" row="{{ $i}}" id="start_{{ $i}}_0" name="start[{{$i}}][{{$i}}]" type="text" placeholder="Start Time" value="{{$value->start_time}}" required  maxlength="5" >
                    </td>
                    <td width="10%">
                      <input class="form-control end_time timer  updateEnd{{$i}}" row="{{ $i}}" id="end_{{ $i}}_0" name="end[{{$i}}][{{$i}}]" type="text" placeholder="End Time" value="{{$value->end_time}}" required maxlength="5" >
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
                  <tr>
                    <td width="100%" colspan="3">&nbsp</td>
                  </tr>
                   @php $i++ @endphp
                   @endforeach
                   <tr></tr>
                 </table>
                   <table class="table input-lists">
                  <tr class="table-row">
                    <td width="20%">
                   
                      <select id="addRow[{{$count}}][project_id]"  data-id="{{ $count}}" class="form-control select_btn_icon vaidation <?php if($i >1){
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
                      <textarea id="addRow[{{ $count}}][des]" rows="1" data-id="{{ $count}}" name="addRow[{{ $count}}][des]" placeholder="Description"  class=" form-control  vaidation<?php if($i > 1){
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
                    <div class="updatedsr{{$count}}"> <a href="javascript:void(0);" data-row="{{ $count}}" data-sub-row="0" class="btn btn-primary  add-rows">Save</a></div>
                     
                    </td>
                  </tr>
               <!--    
                  <tr>
                    <td colspan="3">
                      <textarea id="addRow[{{ $count}}][des]" name="addRow[{{ $count}}][des]" placeholder="Description" class=" form-control dsr dsr-area"></textarea>
                    </td>
                   
                  </tr> -->
                  <tr>
                    <td width="100%" colspan="3">&nbsp</td>
                  </tr>
                </table>
<script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ URL::asset('js/formValidate.js') }}"></script>
<script type="text/javascript">
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
  });

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
       initiateTimer();
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
});
  </script>
