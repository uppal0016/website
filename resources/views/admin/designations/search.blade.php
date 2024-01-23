<div class="table-responsive" id ="dynamicContent">
    <table class="table align-items-center table-flush">
    <thead class="thead-light">
        <th scope="col">Designation Name</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody class="list">
    @if(!$designations->isEmpty())
    @foreach($designations as $value)
        <tr>
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
            <a href="{{ url('/admin/designations/destroy',$encryptId) }}" title="Delete Designation" onclick="return confirm('Are you sure you want to delete this desgination?');"><i class="fa fa-trash-o"></i></a>
        </td>
        </tr>
        @endforeach
        @else
        <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
        @endif
    </tbody>
    </table>
    <div class="pagination">
    {{ $designations->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div> 