@php $isEmployeeList = false @endphp
@if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,4]))
    @php $isEmployeeList = true @endphp
@endif
<div class="table-responsive" id="dynamicContent">
<table class="table align-items-center table-flush">
<thead class="thead-light">
    <tr>
        <th>S.No</th>
        <th>Employee Name</th>
        <th>Email</th>
        @if($isEmployeeList)
        <th>DOB</th>
        @endif
        <th>Department</th>
        <th>Designation</th>
            {{-- @if($isEmployeeList) --}}
        <th>Employee Code</th>
        <th>Role</th>
        <th>Status</th>
        <th>Action</th>
        {{-- @endif --}}
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
    @foreach($users as $value)
        <tr>
        <td class="row_counter">{{ $counter }}</td>
        <td>{{$value->first_name}} {{$value->last_name}}</td>
        <td><div class="{{(!$isEmployeeList)?'emTxt':''}}">{{ $value->email }}</div></td>
        @if($isEmployeeList)
            <td>{{(!empty($value->dob))? date('d-M-Y',strtotime($value->dob)):'-'}}</td>
        @endif
        <td>{{($value->department)?$value->department->name:'-'}}</td>
        <td>{{($value->designation)?$value->designation->name:'-'}}</td>
        {{-- @if($isEmployeeList) --}}
            <td >{{$value->employee_code}}</td>
            <td >{{$value->role->role}}</td>
            <td >{{!empty($value->status) ? 'Active' : 'Inactive'}}</td>
            {{-- <td>
            <a href="{{action('Admin\UserController@edit',$value['en_id'])}}" title="Edit User"><i class="fa fa-edit"></i>
            </a> &nbsp
            <a href="{{ url('/admin/destroy',$value['en_id']) }}" title="Delete User"onclick="return confirm('Are you sure you want to delete this employee?');"><i class="fa fa-trash"></i>
                <a href="" class="delete_action" data-name="employee" data-href="{{ url('/admin/destroy',$value['en_id']) }}" title="Delete User"><i class="fa fa-trash"></i>
            </a> &nbsp
            </td> --}}
            <td>
                @if(Auth::user()->role_id == 3)  
                <a href="{{ action('Admin\UserController@edit', $value['en_id']) }}" title="View User"><i class="fa fa-eye"></i></a>
            @else
                <a href="{{ action('Admin\UserController@edit', $value['en_id']) }}" title="Edit User"><i class="fa fa-edit"></i></a>
            @endif
                </a> &nbsp
                @if(Auth::user()->role_id != 3)
                <a href="" class="delete_action" data-name="employee" data-href="{{ url('/admin/destroy',$value['en_id']) }}" title="Delete User"><i class="fa fa-trash"></i>
                </a> &nbsp
                @endif
              </td>
        {{-- @endif --}}
        </tr>
        @php $counter++ @endphp
    @endforeach

    @if(!$users->count())
        <tr>
        <td colspan="7"  class="no_record"><b>No records found</b></td>
        </tr>
    @endif
    </tbody>
    </table>
    {{ $users->appends(['search' => Request::get('search'),'status' => Request::get('status'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div>