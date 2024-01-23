@extends('layouts.page')
@section('content') 
<style>
  .btn-sm.btn-circle{
    border-radius: 15px;
  }
  .btn-md.btn-circle{
    border-radius: 18px;
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
              <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Weekly Report</li>
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
        <div class="card-body">
          <div class="canvas-wrapper">
            <div class="table-responsive">
            <form id="add_report" action="{{url('/reports')}}" method="post">
                <table class="table input-lists">
                <tr> 
                    <td colspan="3">
                      <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                      <select id="project_id_0" class="form-control dsr select-project select_btn_icon" name="project_id[0]">
                        <option value="" disable="true">Select Project</option>
                        @foreach($projects as $project)
                          <option data-id="{{$project->project_manager}}" value ="{{($project->id)}}">  
                              {{$project->name}}
                          </option> 
                        @endforeach 
                        <option value="0">Other</option>
                      </select>
                    </td>
                    <td>
                      <a href="javascript:void(0);" data-row="0" data-sub-row="0" class="btn btn-primary btn-md btn-circle add-rows"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    </td>
                  </tr>

                  <tr>
                    <td colspan="3">    
                      <textarea id="des_0_0" name="des[0][0]" placeholder="Description" class=" form-control dsr dsr-area"></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td width="100%" colspan="3">&nbsp</td>
                  </tr>
                </table>
                <br/>

                <br>
                <div id="send_to">  
                  <label><b>Send To:</b></label>  
                  @if(!empty($email_users))
                    @foreach($email_users as $user)
                      <?php
                      $checked = ($user->role_id == 2) ? 'checked' : '';
                      $return = ($user->role_id == 2) ? 'false' : 'true';
                      ?>
                      <input {{ $checked }} type="checkbox" id="check_{{$user->id}}" data-exp="{{$return == 'false' ? 'exp':''}}" name="send_to[]" onclick="return {{$return}} " value="{{ $user->id }}"> {{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} &nbsp;&nbsp;
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
                           <input type="text"  placeholder="Search Employee Name" id="ccusersearch" >
                           <i id="filtersubmit" class="fa fa-search" style="pointer-events:none"></i>
                        </div>
                     </div>
                      <div class="panel-body"> 
                        <div class="table-responsive">
                          <div class="ccbox-list">
                            <table class="table-condensed" id="ccuserTable">
                              <tbody>
                                <?php $tdCount = 0; ?>
                                @foreach($cc_users as $cc_user) 
                                  @if($tdCount == 0)
                                  <tr>
                                  @endif
                                    <td>   
                                      <input type="checkbox" name="add_cc[]" value="{{$cc_user->id}}" "/>&nbsp; {{ucfirst($cc_user->first_name)}} {{ucfirst($cc_user->last_name)}} 
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
                <input type="submit" id="btn" class="btn btn-primary  pull-center" value="Submit Report" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@section('script')
<script>
  $(document).ready(function() {
    $('#ccusersearch').on('input', function() {
      var searchText = $(this).val().toLowerCase();
      $('#ccuserTable tbody tr').each(function() {
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
  $(document).ready(function(){

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


$('.add-rows').click(function(){
  // $('#add_report').removeData('validator');
  // $('#add_report').removeData('unobtrusiveValidation');

  var tpl = '<tr class="row_' + i + '"><td colspan="3"><select id="project_id_'+i+'" class="form-control dsr select-project select_btn_icon" name="project_id['+i+']"><option value="" disable="true">Select Project</option>'+ options +'<option value="0">Other</option></select></td><td><a href="javascript:void(0)" class="btn-circle btn-md delRow btn btn-danger" id="trash_' + i + '"><i class="fa fa-times" ></i></td></tr><tr class="row_' + i + '" id="sub-row_'+i+'_0"><td colspan="3"><textarea id="des_'+i+'_0" name="des['+i+'][0]" placeholder="Description" class=" form-control dsr dsr-area"></textarea></td></tr><tr class="row_' + i + '"><td width="100%" colspan="3"></td></tr>';
  subRow[i] = 0;

  //<td><i class="fa fa-trash delRow btn btn-danger" id="trash_' + i + '"> Delete Row</i></td>
  $('.input-lists').append(tpl);
  i++;
});


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

  /*if(allMIds){

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

});

/* jquery validation start*/
$('#add_report').on('submit', function(event) {
  $('.select-project').each(function() {
    $(this).rules("add", 
    {
      required: true
    });
  });  
  $('.dsr-area').each(function() {
    $(this).rules("add", 
    {
      required: true,
      normalizer: function(value) {
        return $.trim(value)
      }
    });
  });                    
});
$('#add_report').validate({
  submitHandler: function (form) {
      $(form).find(":submit").prop("disabled", true);
      formSubmit(form);
    },
});
});


</script>

<script type="text/javascript">

var options = [];
$( '.user_lists a' ).on( 'click', function( event ) {
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
 
  // console.log( options );
  return false;
});



</script>
@endsection

@endsection
