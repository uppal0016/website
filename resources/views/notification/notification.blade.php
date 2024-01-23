@extends('layouts.page')
@section('content')

 

<?php
  $role_id = auth()->user()->role_id; 
  $user_id = auth()->user()->id;
  $current_uri = Route::getFacadeRoot()->current()->uri();
  $sentCase = in_array($current_uri, ['sent_dsr']) ? 1 : 0; 
?>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li>
        <a href="{{ url('/dashboard') }}">
          <em class="fa fa-home"></em> 
        </a>
      </li>
      <li class="active">Notification </li>
    </ol>
  </div><!--/.row--> 
  <div class="row"> 
    <div class="col-lg-12">
      <h1 class="page-header">Notification
      </h1>
    </div>
  </div><!--/.row-->
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading dsr-panel">
          Notification
        </div>

        <table class="table table-striped table-hover table-reflow""  width="80%" cellspacing="2">
          <tbody>

            @foreach($notification as $notify)
            
              <?php
            
                $highlight =  ($notify ['notificationread']->count() && $notify['notificationread'][0]['is_read'] == 1) ? 0 : 1;

              ?>
            
              <tr class="notification {{$highlight ? 'highlight' : ''}} " id="{{$notify
              ['en_id']}}">
                <td height="50">{{$notify->message}}</td>
                <td height="50">{{date('d-m-Y', strtotime($notify['created_at']))}}</td>
              </tr>
            @endforeach

            @if(!$notification->count())
              <tr>
                <td colspan="2"><b>No records found</b></td>
              </tr>
            @endif
          </tbody>
        </table>
          {{ $notification->appends(\Request::except('page'))->render() }}
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  
  $(document).ready(function(){

    $(document).on('click', '.notification', function(){

      $('.notification').removeClass('active-row');
      $(this).addClass('active-row');
      $(this).removeClass('highlight');

      var id = $(this).attr('id'),
          url = "<?php echo url('common/notification'); ?>/"+id;

      window.location = url;
        // $.get(url+'/'+id, function(success){            
        //   if(success != ''){

        //     $(this).removeClass('highlight');
            
        //   }
        // },function(error){
        //   console.log('error');
        // });  
    });
  });    
</script>


@endsection
