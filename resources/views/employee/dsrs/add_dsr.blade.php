@extends('layouts.page')
@section('content') 
 
@include('common.sidebar.sidebar_emp')

<style>
  .btn-sm.btn-circle{
    border-radius: 15px;
  }
  .btn-md.btn-circle{
    border-radius: 18px;
  }
</style>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{url('/dashboard')}}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Add DSR</li>
    </ol> 
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Add DSR</h1>
      @if(session()->has('flash_message'))
        <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('flash_message') }}
        </div>
      @endif
      @if(session()->has('error_flash_message'))
        <div class="alert alert-danger">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('error_flash_message') }}
        </div>
      @endif
    </div>
  </div><!--/.row-->

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          Add DSR
          <!-- <span class="pull-right">
            <button class="btn btn-primary add-rows">+ Add More</button>
          </span>  -->
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="table-responsive">
              <form id="add_dsr" action="{{url('/dsrs')}}" method="post" enctype="multipart/form-data" novalidate />
                <table class="table input-lists">
                  
                  <tr> 
                    <td colspan="2">
                      <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                      <select id="project_id_0" class="form-control dsr select_btn_icon" name="project_id[0]" >
                        <option value="" disable="true">Select Project</option>
                        @foreach($projects as $project)
                          <option value ="{{($project->id)}}">  
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
                    <td width="80%">
                      <input id="task_0_0" type="text" name="task[0][0]" placeholder="Task" class=" form-control dsr" />

                    </td>

                    <td width="15%">
                      <input id="timeEstimate_0_0" type="number" min="1" name="timeEstimate[0][0]" placeholder="Time Est." class=" form-control dsr" />
                    </td>

                    <td width="5%">
                      <a href="javascript:void(0);" data-row="0" data-sub-row="0" class="btn btn-warning btn-sm btn-circle add-task"><i class="fa fa-plus" aria-hidden="true"></i></a>
                    </td>
                    
                  </tr>

                  <tr>
                    <td>    
                      <textarea id="des_0_0" name="des[0][0]" placeholder="Description" class=" form-control dsr dsr-area"></textarea>
                    </td>
                  </tr>
                  
                  <tr>
                    <td width="100%" colspan="3">&nbsp</td>
                  </tr>

                </table>
                <br/>
              
                <div >
                  <label><b>Attachments: </b></label>
                  <input type="file" multiple name="documents[]" id="documents">
                </div>
                
                <br>
                
                <div id="send_to">  
                  <label><b>Send To:</b></label> 
                  @if(!empty($users))
                    @foreach($users as $user)  
                      <?php $checked = ($user->role_id == 2) ? 'checked' : ''; ?>
                      <?php $return = ($user->role_id == 2) ? 'false' : 'true'; ?>
                      <input {{ $checked }} type="checkbox" name="send_to[]" onclick="return {{ $return }} " value="{{ $user->id }}"> {{ucfirst($user->first_name)}} {{ucfirst($user->last_name)}} &nbsp;&nbsp;
                    @endforeach  
                  @endif
                </div> </br>
                <div class="button-group ">
                  <label><b>Add Cc:</b></label> &nbsp 
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle"    data-toggle="dropdown">
                      <span>Add Cc</span> 
                      <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu ccbox">
                      @foreach($checkuser as $value)      
                        <li><a href="#">
                             <input type="checkbox" name="add_cc[]" value="{{$value->id}}" "/>&nbsp; {{ucfirst($value->first_name)}} {{ucfirst($value->last_name)}}
                            </a>
                        </li>
                      @endforeach
                    </ul>
                </div> <br/>
                <input type="submit" id="btn" class="btn btn-primary  pull-center" value="Submit DSR" />
              </form>
            </div>
          </div>
        </div> 
      </div>
    </div>

  </div><!--/.row-->

</div>  <!--/.main-->

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
      options +='<option value ="'+project['id']+'">'+project['name']+'</option>';
    });


    $('.add-rows').click(function(){

      $('#add_dsr').removeData('validator');
      $('#add_dsr').removeData('unobtrusiveValidation');
      // $.validator.unobtrusive.parse('add_dsr'); 

      var tpl = '<tr class="row_' + i + '"><td colspan="2"><select id="project_id_'+i+'" class="form-control dsr" name="project_id['+i+']"><option value="" disable="true">Select Project</option>'+ options +'<option value="0">Other</option></select></td><td><a href="javascript:void(0)" class="btn-circle btn-md delRow btn btn-danger" id="trash_' + i + '"><i class="fa fa-times" ></i></td></tr><tr class="row_' + i + '"><td width="80%"><input id="task_'+i+'_0" type="text" name="task['+i+'][0]" placeholder="Task" class="form-control dsr" /></td><td width="15%"><input id="timeEstimate_'+i+'_0" name="timeEstimate['+i+'][0]" type="number" placeholder="Time Estimate" class="form-control dsr" /></td><td width="5%"><a href="javascript:void(0);" data-row="'+i+'" data-sub-row="0" class="btn btn-warning btn-sm btn-circle add-task"><i class="fa fa-plus" aria-hidden="true"></i></a></td></tr><tr class="row_' + i + '" id="sub-row_'+i+'_0"><td><textarea id="des_'+i+'_0" name="des['+i+'][0]" placeholder="Description" class=" form-control dsr dsr-area"></textarea></td></tr><tr class="row_' + i + '"><td width="100%" colspan="3"></td></tr>';
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
          taskRow = '<tr class="row_' + row + '"><td width="80%"><input type="text" id="task_'+row+'_'+sr+'" name="task['+row+']['+sr+']" placeholder="Task" class="form-control dsr"></td><td width="15%"><input id="timeEstimate_'+row+'_'+sr+'" type="number" name="timeEstimate['+row+']['+sr+']" placeholder="Time Est." class=" form-control dsr" /></td><td width="5%"><a href="javascript:void(0);" data-row="'+row+'" data-sub-row="'+sr+'" class="btn btn-danger btn-sm btn-circle remove-task"><i class="fa fa-times" aria-hidden="true"></i></a></td></tr><tr class="row_' + row + '" id="sub-row_'+row+'_'+sr+'"><td><textarea id="des_'+row+'_'+sr+'" name="des['+row+']['+sr+']" placeholder="Description" class=" form-control dsr dsr-area"></textarea></td></tr>',
          id = $('#sub-row_'+row+'_'+subRow[row]).attr('id');

      if(id){
        
        $(taskRow).insertAfter('#sub-row_'+row+'_'+subRow[row]);
      }else{
        $(taskRow).insertAfter(that.closest('tr').next('tr'));
      }

      subRow[row] = sr;

      return;
    });

  });
</script>

<script type="text/javascript">

    var options = [];
    $( '.dropdown-menu a' ).on( 'click', function( event ) {
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
     
      console.log( options );
      return false;
    });
</script>


@endsection
