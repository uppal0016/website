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

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li>
        <a href="{{url('/dashboard')}}">
          <em class="fa fa-home"></em>
        </a>
      </li>
      <li>
        <a href="{{url('/leave')}}">
         Manage Leave
        </a>
      </li>
      <li class="active">Add Leave</li>
    </ol> 
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">

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
  <div class="panel panel-default">
        <div class="panel-heading">
          Add Leave
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <?php
                    ?>
                        @if(isset($data))
                            {{ Form::model($data,array('url'=>url('leave/'.$data->en_id),'id'=>'create-leave-form','autocomplete' => 'off' ,'enctype'=>'multipart/form-data','method' => 'PUT'))}}
                        @else
                            {{ Form::open(array('url'=>url('leave'),'id'=>'create-leave-form','autocomplete' => 'off','enctype'=>'multipart/form-data')) }}
                        @endif

                        <div class="login-form">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="placeholder" for="">Title <span>*</span></label>
                                    <div class="parent-selectbox">
                                    {!! Form::text('title', null,['placeholder'=>"Enter Title",'class'=>'form-control required']) !!}

                                    </div>
                                    <span class="text-danger">
                                        {{$errors->first('title')}}
                                    </span>
                                    {!! Form::hidden('id',isset($data) ? $data['id'] : null) !!}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="placeholder" for="">Description <span>*</span></label>
                                    <div class="parent-selectbox">
                                    {!! Form::textarea('description', null,['placeholder'=>"Enter Leave Description",'class'=>'form-control required']) !!}

                                    </div>
                                    <span class="text-danger">
                                        {{$errors->first('description')}}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="placeholder" for="">Type <span>*</span></label>
                                    <div class="parent-selectbox">
                                    {!! Form::select('type',['full_day' => "Full Day",'half_day' => "Half Day",'short_leave' => "Short Leave"],null,['placeholder'=>'Select Type','class'=>'form-control','id'=>'type']) !!}
                                    </div>
                                    <span class="text-danger">
                                        {{$errors->first('type')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6" id="leave_time_id">
                                <div class="form-group">
                                    <label class="placeholder" for="">Leave Time <span>*</span></label>
                                    <div class="parent-selectbox">
                                    {!! Form::select('leave_time',['10-12' => "10-12",'12-2' => "12-2",'4-6' => "4-6"],null,['placeholder'=>'Select time slot','class'=>'form-control','id'=>'leave_time' ]) !!}
                                    </div>
                                    <span class="text-danger">
                                        {{$errors->first('leave_time')}}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="placeholder" for="">Start Date <span>*</span></label>
                                    <div class="parent-selectbox">
                                    {!! Form::text('start_date', null,['placeholder'=>"From Date",'class'=>'form-control datepicker required' , 'id'=>"fromDate"]) !!}
                                    <span class="text-danger">
                                        {{$errors->first('start_date')}}
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="placeholder" for="">End Date <span>*</span></label>
                                    <div class="parent-selectbox">
                                    {!! Form::text('end_date', null,['placeholder'=>"To Date",'class'=>'form-control datepicker' , 'id'=>"toDate"]) !!}
                                   
                                    </div>
                                    <span class="text-danger">
                                        {{$errors->first('end_date')}}
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label class="placeholder" for="">Attachment <span>*</span></label>
                                    {!! Form::file('attachment',['class'=> 'form-control form-control-file','id'=>'uploadImg2']); !!}
                                      <small><b>*Note:</b> Only .jpg, .jpeg, .png, .doc, .docx, .pdf, .xlsx and .csv formats are allowed.</small>
                                      <span class="text-danger">
                                        {{$errors->first('attachment')}}
                                    </span>
                                  </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary col-md-6" >
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
          </div>
        </div>

</div><!--/.row-->

</div>  <!--/.main-->

<script>

$(document).ready(function(){

  if($('#type').val() !=''){
      if($('#type').val() == 'full_day'){
        $('#leave_time_id').css('display', 'none')
      } else {
        $('#leave_time_id').css('display', 'block')

      }
  } else {
    $('#leave_time_id').css('display', 'none')
  }
  $("#type").change(function () {
    var value = this.value;
    if((value=='half_day') || (value=='short_leave')){
      $('#leave_time_id').css('display', 'block')
    } else {
      $('#leave_time_id').css('display', 'none')

    }
  });
});


</script>

@endsection
