<table class="table">
    <thead>
        <tr>
            <th>Emp Code</th>
            <th>Ticket no</th>
            @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                    Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                    Auth::user()->email == 'rohit.gupta@talentelgia.in')
                <th>Employee Email</th>
            @endif
            <th>Category Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $avail = ['1' => 'Assigned', '0' => 'Spare'];
        @endphp
        @if (!$tickets->isEmpty())
            @foreach ($tickets as $index => $value)
                <tr>
                    <td>{{ $value->employee_code }}</td>
                    <td>{{ $value->ticket_id }}</td>
                    @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                            Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                            Auth::user()->email == 'rohit.gupta@talentelgia.in')
                        <td>({{ $value->first_name }}) {{ $value->user_email }}</td>
                    @endif

                    <td>{{$value->category}}</td>

                    @if ($value->status == 'Open')
                        <td class="status-td"><img src="{{ asset('images/red.svg') }}" alt="">{{ $value->status }}
                        </td>
                    @elseif ($value->status == 'InProgress')
                        <td class="status-td"><img src="{{ asset('images/Yellow.svg') }}"
                                alt="">{{ $value->status }}</td>
                    @elseif ($value->status == 'Closed')
                        <td class="status-td"><img src="{{ asset('images/green.svg') }}"
                                alt="">{{ $value->status }}</td>
                    @endif
                    <td>
                        <a class="action_btn"   href="{{ action('TicketsController@details', $value->ticket_id) }}"
                            title="View details">
                            <i class="fas fa-eye"> </i>
                        </a>
                        {{-- @if (Auth::user()->email !== 'manish.chopra@talentelgia.in' &&
                                Auth::user()->email !== 'pallavi.ranjan@talentelgia.in' &&
                                Auth::user()->email !== 'rohit.gupta@talentelgia.in')
                            <a class="action_btn" href="{{ action('TicketsController@details', $value->ticket_id) }}"
                                title="View details">
                                <i class="fas fa-eye"> </i>
                            </a>
                        @else
                            @if ($value->status !== 'Closed')
                                <a class="action_btn" href="{{ action('TicketsController@details', $value->ticket_id) }}"
                                    title="View details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                            
                        @endif --}}
                        {{-- &nbsp
                        @if (Auth::user()->email !== 'manish.chopra@talentelgia.in' &&
                                Auth::user()->email !== 'pallavi.ranjan@talentelgia.in' &&
                                Auth::user()->email !== 'rohit.gupta@talentelgia.in')
                            @if ($value->status == 'Open')
                                <a href="{{ action('TicketsController@delete', $value->ticket_id) }}"
                                    title="Delete ticket">
                                    <i class="fa fa-trash"> </i>
                                </a>
                            @endif
                        @endif
                        &nbsp
                        @if (Auth::user()->email !== 'manish.chopra@talentelgia.in' &&
                                Auth::user()->email !== 'pallavi.ranjan@talentelgia.in' &&
                                Auth::user()->email !== 'rohit.gupta@talentelgia.in')
                            @if ($value->status !== 'Closed')
                                <a href="{{ action('TicketsController@close', $value->ticket_id) }}"
                                    title="Mark ticket as closed">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        @endif --}}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9" class="text-center"><b>No record found</b></td>
            </tr>
        @endif
    </tbody>
</table>
<div class="pagination">
    {{ $tickets->appends(['page' => Request::get('page'), 'status' => Request::get('status'), 'search' => Request::get('search'), 'start_date' => Request::get('start_date'), 'end_date' => Request::get('end_date'), 'category_id' => Request::get('category_id'), '_token' => csrf_token()])->render() }}
</div>
