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
            <th class="th-pad">Department Name</th>
            <th class="th-pad">Department Code</th>
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
        @foreach($department as $value)
            <tr>
                <td>{{ $counter }}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->code}}</td>
                <td>
                    @php $encryptId = Crypt::encrypt($value->id); @endphp
                    <a href="{{ action('PM\DepartmentController@edit',$encryptId) }}" title="Edit Department">
                        <i class="fa fa-edit"> </i>
                    </a> &nbsp
                    @php
                    $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                    $icon = ($value->status == 1) ? 'fa fa-check' : 'fa fa-times';
                    @endphp
                    <a href="{{ url('/pm/department_status',$encryptId) }}" title="<?php echo $statusText; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                    </a> &nbsp
                    <a href="javascript:void(0);" onclick="$(this).find('form').submit();" title="Delete Designation">
                        <i class="fa fa-trash-o"></i>
                        <form action="{{ url('/pm/department',$encryptId) }}" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                        </form>
                    </a>
                </td>
            </tr>
            @php $counter++ @endphp
        @endforeach
        @if(!$department->count())
            <tr>
                <td colspan="4" class="text-center"><b>No records found</b></td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $department->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
    </div>
</div>
