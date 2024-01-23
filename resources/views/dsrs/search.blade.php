<div class="table-responsive" id="dynamicContent">
    <table class="table">
        <thead>
        <tr>
            <th class="th-pad">S.No</th>
            <th class="th-pad">First Name</th>
            <th class="th-pad">Last Name</th>
            <th class="th-pad">Email</th>
            <th class="th-pad">Role</th>
            <th class="th-pad">View DSR</th>
            <th class="th-pad">Total DSR</th>
        </tr>
        </thead>
        <tbody>
        @php $counter = 1; @endphp
        @if(Request::get('page') && ! empty(Request::get('page')))
            @php
            $page = Request::get('page') - 1;
            $counter = 10 * $page + 1;
            @endphp
        @endif
        @foreach($dsrUsers as $value)
            <?php
            $total_count = 0;
            $count = 0;
            $display = 0;
            if($value['dsr']){
                foreach ($value['dsr'] as $dsr) {
                    $hasInTo = in_array($authId, explode(',', $dsr['to_ids']));
                    $hasInCC = in_array($authId, explode(',', $dsr['cc_ids']));
                    if($hasInTo || $hasInCC){
                        $display = 1;
                        if(!$dsr['read']){
                            $count++;
                            continue;
                        }
                        $found = 0;
                        foreach ($dsr['read'] as $read) {
                            if($read['user_id'] == $authId && $read['is_read'] == 1){
                                $found = 1;
                            }
                        }
                        if(!$found){
                            $count++;
                        }
                    }
                }
            }
            $total_count += $count;
            ?>
            <tr>
                <td>{{$counter}}</td>
                <td>{{$value->first_name}}</td>
                <td>{{$value->last_name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->role->role}}</td>
                <td>
                    @php $encryptId = Crypt::encrypt($value->id); @endphp
                    <a href="{{url('pm/user_dsrs',$encryptId)}}" title="View DSR">
                        <i class="fa fa-eye"> </i>
                    </a>
                </td>
                <td>  {{$total_count}} </td>
            </tr>
            @php $counter++ @endphp
        @endforeach
        @if(!$dsrUsers->count())
            <tr>
                <td colspan="6" class="text-center"><b>No records found</b></td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $dsrUsers->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
    </div>
</div>
