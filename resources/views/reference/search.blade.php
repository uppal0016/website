<table class="table">
    <thead>
        <tr>
            <th>Rapper Name</th>
            <th>Department</th>
            <th>Rapper Date</th>
            <th>Interview Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (!$reference->isEmpty())
            @foreach ($reference as $index => $value)
            <tr>
                <td>{{ $value->reference_name }}</td>
                <td>{{ $value->department }}</td>
                <td>{{ \Carbon\Carbon::parse($value->created_at)->tz('Asia/Kolkata')->format('Y-m-d') }}</td>
                @if ($value->rejection_reason === null && $value->rounds === null)
                    <td>{{$value->interview_status}}</td>
                @elseif($value->rejection_reason !== null && $value->rounds === null)     
                    <td style=" color: red; font-weight: 600; ">{{$value->interview_status}}</td>
                @elseif($value->rejection_reason === null && $value->rounds !== null)
                    @if ($value->recommendation === 'No')
                        <td style=" color: red; font-weight: 600; ">{{$value->interview_status}}</td>
                    @elseif($value->recommendation === 'Yes' && $value->rounds !== null) 
                        <td style=" color: green; font-weight: 800; ">(Round - {{$value->rounds}}) is cleared</td>
                    @else 
                        <td style=" color: green; font-weight: 800; ">{{$value->interview_status}} (Round - {{$value->rounds}}) </td>
                    @endif
                @endif
                <td>
                    @if($value->interview_status === "Pending")
                        <a href="{{ url('/reference/edit', ['id' => $value->id]) }}" title="Edit Rapper" id="edit_reference"> 
                            <i class="fa fa-edit"></i>
                        </a>&nbsp
                    @endif

                    {{-- @if($value->interview_status === "Pending")
                        <a class="action_button" href="{{ action('ReferenceController@delete', $value->id) }}" title="Delete Rapper" onclick="return confirm('Are you sure you want to delete this rapper?')">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif --}}

                    @if ($value->interview_status === "Rejected")
                        <a class="action_btn view_rejection_reason" data-reference-id="{{ $value->id }}" title="View Not Suitable Reason" >
                            {{-- <img src="{{asset('images/rapper_icon/edit.png')}}" alt="View Rejection Reason" > --}}
                            <i class="fa-solid fa-eye" style="color: blue; font-size: 14px; position: relative; right: 7px;"></i>
                        </a>
                    @endif
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
    {{ $reference->appends(['page' => Request::get('page'), 'name_search' => Request::get('name_search'), 'technology_search' => Request::get('technology_search'), 'start_date' => Request::get('start_date'), 'end_date' => Request::get('end_date')])->render() }}
</div>

<div id="myModal3" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="modalContent3">
                <div class="modal-header">
                    <h3 class="modal-title">Not Suitable Reason</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="comments mb-4"></div>
                    <div class="employee_id mb-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // display rejected reason
    $(document).ready(function() {
        $('.view_rejection_reason').on('click', function() {
            const referenceId = $(this).data('reference-id');
            $.ajax({
                type: 'GET',
                url: 'rejection_reason/' + referenceId,
                success: function(response) {
                    $('#modalContent3 .comments').html('<h4>Not Suitable Reason : </h4>' + response.rejection_reason);
                    $('#modalContent3 .employee_id').html('<h4>Reason Given By : </h4>' + response.rejected_employee_id);

                    // Show the modal
                    $('#myModal3').modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('AJAX Error:', textStatus, errorThrown);
                }
            });
        });
    });
</script>