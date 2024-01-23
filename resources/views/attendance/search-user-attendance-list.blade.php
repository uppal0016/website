<?php 
use Carbon\Carbon;
?>
<table class="table">

  <tbody>
    @if(!$attendance->isEmpty())
    <?php $old_date = ''; ?>
    @foreach($attendance as $index=>$value)
    @php
      $status = 'Absent';
      $color = 'label label-danger';
      $date = Carbon::parse($value->created_at)->format('d-m-Y');
      if(!empty($value->total_working_hour))
      {
          $status = 'Present';
          $color = 'label label-success';
      } else {
        if(!empty($value->time_in)){
          if((Carbon::parse($value->time_in)->format('Y-m-d'))== (Carbon::now()->format('Y-m-d')))
          {
            $status = 'Working';
            $color = 'label label-warning';
          }
        }
      }
      $new_date = Carbon::parse($value->created_at)->format('d-m-Y');
    @endphp
    @if($old_date == $new_date)

    @else
      <?php $old_date = $new_date; ?>
      @if($old_date != '')
        <tr>
          <td colspan="7" style="text-align: center;font-weight: bold;font-size: 18px;background: #555555;color: white">{{ $old_date }}</td>
        </tr>
        <tr>
          <th> S.No.</th>
          <th>Date</th>
          <th>Employee Name</th>
          <th>Time In</th>
          <th>Time Out</th>
          <th>Total Working Hours</th>
          <th>Status</th>
        </tr>
      @endif
    @endif



    <tr>
      <td>{{ $attendance->perPage() * ($attendance->currentPage() -1 ) + $index+1 }}</td>
      <td>{{$date}}</td>
      <td>{{ !empty($value->time_in) ? \Carbon\Carbon::parse($value->time_in)->format('d-m-Y g:i A') : '-' }}</td>
      <td>{{ !empty($value->time_out) ? \Carbon\Carbon::parse($value->time_out)->format('d-m-Y g:i A') : '-' }}</td>
      <td>{{ !empty($value->total_working_hour) ? $value->total_working_hour : '-'}}</td>
      <td><span class="{{ $color }}">{{$status}}</span></td>

    </tr>
    @endforeach
    @else
    <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
    @endif
  </tbody>
</table>
<div class="common pagination">
  {{ $attendance->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'daterange' => Request::get('daterange') ,'_token'=>csrf_token()])->render() }}
</div>
