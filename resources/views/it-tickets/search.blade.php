<style>
  .status-td {
      display: flex;
      align-items: center;
  }

  .status-td img {
      margin-right: 5px;
      width: 20px;
  }
</style>
<table class="table">
<thead>
  <tr>
    <th>Emp Code</th>
    <th>Emp Name</th>
    <th>Ticket no</th>
    @if( Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
    <th>Created at</th>
    @endif 
    <th>Category</th>
    <th>Severity</th>
    @if(Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
    <th>Turnaround Time</th> 
    @else
    <th>Created Date</th> 
    @endif
    <th>Status</th> 
    <th>Action</th>
  </tr>
</thead>
<tbody>
  @php
  $avail = ['1' => 'Assigned','0'=>'Spare']
  @endphp
  @if(!$it_tickets->isEmpty())
  @foreach($it_tickets as $index=>$value)
  <tr>
    <td>{{ $value->employee_code }}</td>
    <td>{{ $value->user_name }}</td>
    <td>{{ $value->ticket_id }}</td>
    @if(Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
    <td>{{ $value->created_at->format('Y-m-d') }}</td>
    @endif 
    <td>{{ $value->category }}</td>
    <td>{{ $value->severity }}</td>
    @if(Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
    <td class="{{ strtotime($value->turnaround_time) > strtotime('04:00:00') ? 'text-danger' : '' }}">{{ $value->turnaround_time ?: '00:00:00' }}</td>
    @else
    <td>{{ $value->created_at->format('Y-m-d') }}</td>
    @endif
    @if ($value->status == "Open")
       <td class="status-td"><img src="{{ asset('images/red.svg') }}" alt="">{{ $value->status }}</td>
    @elseif ($value->status == "InProgress")
       <td class="status-td"><img src="{{ asset('images/Yellow.svg') }}" alt="">{{ $value->status }}</td>   
    @elseif ($value->status == "Closed")
       <td class="status-td"><img src="{{ asset('images/green.svg') }}" alt="">{{ $value->status }}</td>
    @elseif ($value->status == "Reopen")
       <td class="status-td"><img src="{{ asset('images/orange.svg') }}" alt="">{{ $value->status }}</td>
    @elseif ($value->status == "Archive")
       <td class="status-td"><img style="width: 17px;" src="{{ asset('images/archive.svg') }}" alt="">{{ $value->status }}</td>
    @endif
    <td>
      <a class="action_btn" href="{{ action('ItTicketsController@details', ['user_id' => $value->user_id, 'ticket_id' => $value->ticket_id]) }}" title="View details">
        <i class="fas fa-eye"> </i>
      </a> 
    </td>
  </tr>
  @endforeach
  @else
  <tr><td colspan="9" class="text-center"><b>No record found</b></td></tr>
  @endif
</tbody>
</table>
<div class="pagination">
{{ $it_tickets->appends(['page' => Request::get('page'), 'status' => Request::get('status'), 'search' => Request::get('search'), 'start_date' => Request::get('start_date'), 'end_date' => Request::get('end_date'), 'severity' => Request::get('severity'), 'category' => Request::get('category'), 'medium_severity' => Request::has('medium_severity') ? 'true' : null, 'high_severity' => Request::has('high_severity') ? 'true' : null, 'low_severity' => Request::has('low_severity') ? 'true' : null, 'turnaround_time' => Request::has('turnaround_time') ? 'true' : null, '_token' => csrf_token()])->setPath(url()->current())->render() }}
</div>
