<div class="table-responsive" id ="dynamicContent">
    <table class="table align-items-center table-flush">
    <thead class="thead-light">
        <th scope="col">Department Name</th>
        <th scope="col">Department Code</th>
        <th scope="col">Action</th>
    </thead>
    <tbody class="list">
    @if(!$department->isEmpty())
    @foreach($department as $value)
        <tr>
        <td>{{$value->name}}</td>
        <td>{{$value->code}}</td>
        <td>
            @php $encryptId = Crypt::encrypt($value->id); @endphp
            <a href="{{ action('Admin\DepartmentController@edit',$encryptId) }}" title="Edit Department">
                <i class="fa fa-edit"> </i>
            </a> &nbsp
            @php
                $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                $icon = ($value->status == 1) ? 'fa fa-check' : 'fa fa-times';
            @endphp
            <a href="{{ url('/admin/department_status',$encryptId) }}" title="<?php echo $statusText; ?>">
                <i class="<?php echo $icon; ?>"></i>
            </a> &nbsp
            <a href="{{ url('/admin/department/destroy',$encryptId) }}" title="Delete Department"onclick="return confirm('Are you sure you want to delete this department?');"><i class="fa fa-trash-"></i></a>

        </td>
        </tr>
        @endforeach
        @else
        <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
        @endif
    </tbody>
    </table>
    <div class="pagination">
    {{ $department->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}        
</div>
</div>