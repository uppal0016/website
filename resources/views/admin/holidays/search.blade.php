<!-- Light table -->
<div class="table-responsive" id ="dynamicContent">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <th scope="col">Title</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </thead>
        <tbody class="list">
        @if(isset($holidays))
            @foreach($holidays as $cards)
                <tr>
                    <td>{{ $cards->title }}</td>
                    <td>{{ \Carbon\Carbon::parse($cards->date)->format('d-m-Y') }}</td>
                    @php
                        if($cards->status == 1){
                            $status = 'Active';
                            $change_status = 0;
                            $icon = 'fa fa-times text-danger';
                            $title = 'Block Card';
                            $class = 'text-success';
                        }
                        else{
                            $change_status = 1;
                            $status = 'Block';
                            $icon = 'fa fa-check text-success';
                            $title = 'Activate Card';
                            $class = 'text-danger';
                        }

                    @endphp
                    <td><span class="{{ $class }}">{{ $status }}</span></td>
                    <td>
                        <a href="{{action('Admin\HolidayController@edit',encrypt($cards->id))}}" title="Edit Card"><i class="fa fa-edit"></i>
                        </a> &nbsp
                        <a href="{{ url('/admin/holiday/destroy/'.encrypt($cards->id)) }}" title="Delete Card" onclick="return confirm('Are you sure you want to delete this holiday card?');"><i class="fa fa-trash"></i>
                        </a> &nbsp
                        <a href="{{ url('/admin/holiday/status/'.encrypt($cards->id)).'/'.$change_status }}" title="{{ $title }}" ><i class="{{ $icon }}"></i>
                        </a> &nbsp
                    </td>
                </tr>
            @endforeach
        @endif
        @if(empty($holidays))
            <tr><td colspan="5" class="no_record">No Record Found</td></tr>
        @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $holidays->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
    </div>
</div>
<!-- Card footer -->