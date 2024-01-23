<?php 
use Carbon\Carbon;
?>
<div class="table-responsive" id ="paginationData">
    <table class="table align-items-center table-flush">
    <thead class="thead-light">
        <th scope="col" class="sort" data-sort="budget">Date</th>
        <th scope="col">Time In</th>
        <th scope="col">Time Out</th>
        <th scope="col">Total Working Hours</th>
        <th scope="col">Status</th>
    </thead>
    <tbody class="list">
    @if(!$attendance->isEmpty())
        @foreach($attendance as $index=>$value)
            @php
                $status = 'Absent';
                $color = 'bg-danger';
                $date = Carbon::parse($value->created_at)->format('d-m-Y');
                if(!empty($value->total_working_hour))
                {
                    $status = 'Present';
                    $color = 'bg-success';
                } else {
                    if(!empty($value->time_in)){
                    if((Carbon::parse($value->time_in)->format('Y-m-d'))== (Carbon::now()->format('Y-m-d')))
                    {
                        $status = 'Working';
                        $color = 'bg-warning';
                    }
                    }
                }
            @endphp
            <tr>
                <td>{{ $date }}</td>
                <td>{{ !empty($value->time_in) ? \Carbon\Carbon::parse($value->time_in)->format('d-m-Y g:i A') : '-' }}</td>
                <td>{{ !empty($value->time_out) ? \Carbon\Carbon::parse($value->time_out)->format('d-m-Y g:i A') : '-' }}</td>
                <td>{{ !empty($value->total_working_hour) ? $value->total_working_hour : '-'}}</td>
                <td>
                    <span class="badge badge-dot mr-4">
                        <i class="{{$color}}"></i>
                        <span class="status">{{$status}}</span>
                    </span>
                </td>                    
            </tr>
        @endforeach
    @else
        <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
    @endif
    </tbody>
    </table>
    <div class="card-footer py-4">
        <div class="common pagination">
            {{ $attendance->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
        </div>
    </div>
</div>
