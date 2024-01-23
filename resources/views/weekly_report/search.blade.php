<div class="table-responsive" id ="dynamicContent">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">View Report</th>
                <th scope="col">Total Report</th>
            </thead>
            <tbody class="list">
            @if(!$reportUsers->isEmpty())
            @foreach($reportUsers as $value)
              <tr>
                <td>{{$value->first_name}}</td>
                <td>{{$value->last_name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->role->role}}</td>
                <td>
                    @php $encryptId = Crypt::encrypt($value->id); @endphp
                    <a href="{{url('reportdetail',$encryptId)}}" title="View Report">
                        <i class="fa fa-eye"> </i>
                    </a>
                </td>
                <td>  {{$value->weeklyreport_count}} </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
          <div class="pagination">
            {{ $reportUsers->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
        </div> 