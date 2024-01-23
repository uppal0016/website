<!-- Light table -->
    <table id="birthday-card-table" class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr id="row">
            <th scope="col">Employee Name</th>
            <th scope="col">Birthday Date</th>
            <th scope="col">Birthday Card</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        @if($birthday_cards)
            @foreach($birthday_cards as $cards)
                <tr>
                    <td>{{ $cards->user->first_name }} {{ $cards->user->last_name }}</td>
                    <td>{{ $cards->birthday_date }}</td>
                    <td>
                        <img src="{{ url('images/birthday_cards/'.$cards->birthday_card) }}" height="100" width="100"/>
                    </td>
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
                        <a href="{{action('Admin\BirthdayController@edit',encrypt($cards->id))}}" title="Edit Card"><i class="fa fa-edit"></i>
                        </a> &nbsp
                        <a href="{{ url('/admin/birthday/destroy/'.encrypt($cards->id)) }}" title="Delete Card" onclick="return confirm('Are you sure you want to delete this birthday card?');"><i class="fa fa-trash"></i>
                        </a> &nbsp
                        <a href="{{ url('/admin/birthday/status/'.encrypt($cards->id)).'/'.$change_status }}" title="{{ $title }}" ><i class="{{ $icon }}"></i>
                        </a> &nbsp
                    </td>
                </tr>
            @endforeach
        @endif
        @if(empty($birthday_cards))
            <tr><td colspan="5" class="no_record">No Record Found</td></tr>
        @endif
        </tbody>
    </table>
    {{ $birthday_cards->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}

<!-- Card footer -->