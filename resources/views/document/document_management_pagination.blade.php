<div class="table-responsive item_inv" id="dynamicContent">
    @if ($documents->isNotEmpty())
    <table class="table">
        <thead>
            <tr>
                <th>Sr no</th>
                <th>Document Name</th>
                <th>User Count</th>
                <th>Date</th>
                <th>Most Read Page</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = ($documents->currentPage() - 1) * $documents->perPage() + 1; @endphp
            @foreach ($documents as $value)
            <tr>
                <td>{{ $counter }}</td>
                <td><a target="_blank"
                        href="{{ url('display_pdf/' . $value->document_id) }}">{{ $value->document_name }}</a>
                </td>
                <td><a
                        href="{{ action('DocumentController@document_users_details', $value->document_id) }}">{{ $value->user_count }}</a>
                </td>
                <td>{{ $value->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                <td><a target="_blank"
                        href="{{ url('display_pdf/' . $value->document_id) }}?page_no={{ $value->page_no }}">{{ $value->page_no }}</a>
                </td>

            </tr>
            @php $counter ++; @endphp
            @endforeach


        </tbody>
    </table>
    @else
        <h5 colspan="3" class="text-center" style="font-size:13px">No Record
            Found.</h5>
    @endif
    <div class="pagination">
        {{ $documents->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
    </div>
</div>