<div class="table-responsive" id="dynamicContent">
    <table class="table">
        <thead>
        @php
        $counter = 1;
        @endphp
        @if(Request::get('page') && ! empty(Request::get('page')))
            @php
            $page = Request::get('page') - 1;
            $counter = 10 * $page + 1;
            @endphp
        @endif
        <tr>
            <th class="th-pad">S.No</th>
            <th class="th-pad">Designation Name</th>
            <th class="th-pad">Action</th>
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
        @foreach($designations as $value)
            <tr>
                <td>{{ $counter }}</td>
                <td>{{$value->name}}</td>
                <td>
                    @php $encryptId = Crypt::encrypt($value->id); @endphp
                    <a href="{{ action('Admin\DesignationController@edit',$encryptId) }}" title="Edit Designation">
                        <i class="fa fa-edit"> </i>
                    </a> &nbsp
                    @php
                    $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                    $icon = ($value->status == 1) ? 'fa fa-check' : 'fa fa-times';
                    @endphp
                    <a href="{{ url('/admin/designation_status',$encryptId) }}" title="<?php echo $statusText; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                    </a> &nbsp
                    {{--<a href="{{ url('/admin/technologies',$encryptId) }}" title="Delete Technology">--}}
                    {{--<i class="fa fa-trash-o"></i>--}}
                    {{--</a>--}}
                    <a href="javascript:void(0);" onclick="$(this).find('form').submit();" title="Delete Technology">
                        <i class="fa fa-trash-o"></i>
                        <form action="{{ url('/admin/designations',$encryptId) }}" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </a>
                </td>
            </tr>
            @php $counter++ @endphp
        @endforeach
        @if(!$designations->count())
            <tr>
                <td colspan="3" class="text-center"><b>No records found</b></td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $designations->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
    </div>
</div>
