<div class="table-responsive" id ="dynamicContent">
    <table class="table align-items-center table-flush">
    <thead class="thead-light">
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Email</th>
        <th scope="col">Role</th>
        <th scope="col">View DSR</th>
        <th scope="col">Total DSR</th>
    </tr>
    </thead>
    <tbody class="list">
    @if(!$dsrUsers->isEmpty())
    @foreach($dsrUsers as $value)
        <tr>
        <td>{{$value->first_name}}</td>
        <td>{{$value->last_name}}</td>
        <td>{{$value->email}}</td>
        <td>{{$value->role->role}}</td>
        <td>
            @php $encryptId = Crypt::encrypt($value->id); @endphp
             @if(in_array(Auth::user()->role_id,[4,5]))
            <a href="{{url('user_dsrs',$encryptId)}}" title="View DSR">
                <i class="fa fa-eye"> </i>
            </a> @else
             <a href="{{url('admin/user_dsrs',$encryptId)}}" title="View DSR">
                <i class="fa fa-eye"> </i>
            </a>
            @endif
        </td>
        <td>  {{$value->dsr_count}} </td>
        </tr>
        @endforeach
        @else
        <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
        @endif
    </tbody>
    </table>
    <div class="pagination">
        {{ $dsrUsers->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
    </div> 
</div>
