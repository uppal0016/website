@extends('layouts.page')
@section('content')

 
<?php 
use App\Leave; 
?>
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li>
        <a href="{{ URL('/dashboard') }}">
          <em class="fa fa-home"></em>
        </a>
      </li>
      <li class="active">Leave</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Leave</h1>
      @if(session()->has('flash_message'))
        <div class="alert alert-success">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          {{ session()->get('flash_message') }}
        </div>
      @endif
    </div>
  </div><!--/.row-->
  <!-- <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">Projects</h1>
    </div>
  </div><!-/.row-->
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading cst-panel-heading">
          <div class="row">
            <div class="col-md-6">
              <form id="searchForm" method="get" action="javascript:void(0);" role="search">
                <div class="col-md-3 input-group custom-searchfeild ">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <label for="">Leave Status</label>
                  {!! Form::select('status',App\Leave::getStatusOption(),null,['placeholder'=>'Select Status','class'=>'form-control','id'=>'leave_status_filter']) !!}
                  <input type="hidden" name="action" value="/Leave">
                </div>
                <div class="col-md-3 input-group custom-searchfeild">
                  <input type="text" id="dates" autocomplete="off" placeholder="Date Range" name="dates" class="form-control datepicker">
                </div>
              </form>
            </div>
            <div class="col-md-6">
              <div class="pull-right">
                <a href="{{ URL('leave/create') }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add Leave </a><br/>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            {{-- <div>
              <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
            </div> --}}
            <div class="table-responsive" id="dynamicContent">
              <table class="table">
                <thead>
                  <tr>
                    <th class="th-pad">S.No</th>
                    <th class="th-pad">Title</th>
                    <th class="th-pad">Type</th>
                    <th class="th-pad">Start Date</th>
                    <th class="th-pad">End Date</th>
                    <th class="th-pad">Status</th>
                    <th class="th-pad">Action</th>
                  </tr>
                </thead>
                <tbody>
                @php $counter = 1; @endphp
                  @foreach($leave as $value)
                    <tr>
                      <td>{{ $counter }}</td>
                      <td>{{$value->title}}</td>
                      <td>{{@$value->getTypeIdToValue($value->type)}}</td>
                      <td>{{@$value->start_date}}</td>
                      <td>{{@$value->end_date}}</td>
                      <td>{{@$value->getStatus()}}</td>
                      <td>
                      <a href="{{ action('LeaveController@edit',$value['en_id']) }}" title="Edit Leave">
                          <i class="fa fa-edit"> </i>
                      </a> &nbsp
                      @php
                          $statusText = ($value->status == Leave::STATUS_CANCEL) ? 'Cancelled' : 'Not Cancelled';
                          $icon = ($value->status == Leave::STATUS_CANCEL) ? 'fa fa-times' : 'fa fa-check';
                          $status = ($value->status == Leave::STATUS_CANCEL) ? 0 : 3;
                        @endphp
                        <a href="{{ url('/leave/cancel_status',$value['en_id']).'/'.$status }}" title="<?php echo $statusText; ?>">
                          <i class="<?php echo $icon; ?>"></i>
                        </a> &nbsp

                      </td>
                    </tr>
                    @php $counter++ @endphp
                  @endforeach
                  @if(!$leave->count())
                    <tr>
                      <td colspan="5" class="text-center"><b>No records found</b></td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <div class="pagination">
                {{ $leave->appends(['status' => Request::get('status'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>  <!--/.main-->
<script src="{{ URL::asset('js/custom.js') }}"></script>
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
 
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 <script type="text/javascript"
             src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script>
               
  $('input[name="dates"]').daterangepicker({
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    }
});

  $('#dates').on('apply.daterangepicker', function (ev, picker) {
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    let dates = $(this).val();
    var status = jQuery('#leave_status_filter').val();
    var entriesperpage = jQuery('.entriesperpage :selected').val();

    searchLeave(status, dates ,entriesperpage )
});

$('#dates').on('cancel.daterangepicker', function () {
  $(this).val('');
  var entriesperpage = jQuery('.entriesperpage :selected').val();
  var status = jQuery('#leave_status_filter').val();
  searchLeave(status, '' ,entriesperpage )
});
  </script>
@endsection
