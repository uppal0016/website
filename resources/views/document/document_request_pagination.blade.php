<div class="table-responsive item_inv" id="dynamicContent">
    <table class="table">
        <thead>
            <tr>
                <th class="text_center">Sr no</th>
                <th class="text_center">Document Name</th>
                <th class="text_center">User Count</th>
                <th class="text_center">Requesting Times</th>
                <th class="text_center">Date</th>
            </tr>
        </thead>
        <tbody>   
            @php $counter = ($document_request->currentPage() - 1) * $document_request->perPage() + 1; @endphp
            @if ($document_request->isNotEmpty())
                @foreach ($document_request as $value)
                   <tr>
                        <td class="text_center">{{ $counter }}</td>
                        <td class="text_center">{{ $value->document_name }}</td>
                        <td class="text_center"><a href="#" onclick="openMyModal({{ $value->document_id }},{{$value->user_id}})">{{ $value->user_count }}</a></td>
                        <td class="text_center"><a href="#" onclick="openRequestModal({{ $value->document_id }},{{$value->user_id}})">{{ $value->request_count }}</a></td>
                        <td class="text_center">{{ $value->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                    </tr>
                    @php $counter ++; @endphp
                @endforeach
            @else
                <tr>
                    <td colspan="9" class="text-center"><b>No record found</b></td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $document_request->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
    </div>
</div> 