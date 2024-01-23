<table class="table">
    <thead>
        <tr>
            <th class="td_align_center">Sr no</th>
            <th style=" width: 14rem; ">Documents</th>
            <th class="td_align_center">Password Type</th>
            <th class="td_align_center">Date</th>
            <th class="td_align_center">Employee Details</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php $counter = ($document->currentPage() - 1) * $document->perPage() + 1; @endphp
        @foreach ($document as $val)
            <tr>
                <td class="td_align_center">{{ $counter }}</td>
                <td><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"> </i>
                    {{ $val->documents }}</td>
                <td class="td_align_center">{{$val->protected_file}}</td>
                <td class="td_align_center">{{ $val->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                <td class="td_align_center"> <a href="{{ action('DocumentController@document_list', $val->id) }}" target="_blank"> <i
                            class="fa-solid fa-info-circle fa-xl" aria-hidden="true"></i></a> </td>
                <td>
                    {{-- @if ($check == 1) --}}
                    <a href="{{ url('display_pdf/' . $val->id) }}" target="_blank" title="view">
                        <i class="fas fa-eye"></i> </a>
                    {{-- @endif --}}
                    @if (Auth::user()->role_id == 5)
                        <a href="{{ route('document.edit', $val->id) }}" title="edit">
                            <i class="fas fa-edit"></i> </a>
                        <a onclick="return confirm('Are you sure you want to delete this document?')"
                            href="{{ url('document/delete', $val->id) }}" title="delete">
                            <i class="fas fa-trash"></i> </a>
                    @endif
                </td>
            </tr>
            @php $counter++ @endphp
        @endforeach
        @if ($noRecord)
            <tr>
                <td colspan="9" class="text-center"><b>No record found</b></td>
            </tr>
        @endif
    </tbody>
</table>
<div class="pagination">
    {{ $document->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
</div>

<div id="myModal" class="modal fade" tabindex="-1" style="margin-top:150px:">
    <input type="hidden" id="leaveid" value="">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="{{ url('documentview') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="password" value="" name="password" class="form-control"
                                        placeholder="Enter Document Password">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
