<?php 
use App\Leave; 
?>
<table class="table">
    <thead>
        <tr>
        <th class="th-pad">S.No</th>
        <th class="th-pad">Title</th>
        <th class="th-pad">Type</th>
        <th class="th-pad">Date</th>
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
            <td>{{$value->start_date}} - {{@$value->end_date}}</td>
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
