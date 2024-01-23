@extends('layouts.page')
<style>
    select.form-control.col-12.select_btn_icon {
    padding-right: 10px;
}
</style>

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="/tickets/list">Harmony
                                        Tickets</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Ticket Details
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid ticket-detail mt--6">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center border-0">
                            <h3 class="m-0">Ticket:</h3>
                            <div>
                                <span style="padding-left: 10px;">{{ $ticket->ticket_id }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- @if ($ticket->status !== 'Closed' && $ticket->status !== 'InProgress') --}}
                       
                        <div class="reply-actions mb-3">
                            @if (Auth::user()->email == $ticket->email)
                            <a href="{{ url('/tickets/edit/' . $ticket->ticket_id) }}" id="edit_reply_link"
                                class="btn btn-primary {{ $ticket->status !== 'Open' ? 'disabled' : '' }}" title="Edit Ticket"  {{ $ticket->status !== 'Open' ? 'disabled' : '' }}> <i
                                    class="fa fa-edit">
                                </i></a>
                                <a class="btn btn-primary  {{ $ticket->status !== 'Open' ? 'disabled' : '' }}"
                                    id="archive_ticket_link"title="Archive Ticket"
                                    onclick="openArchiveModal()"
                                    {{ $ticket->status !== 'Open' ? 'disabled' : '' }} class="btn btn-primary" type="submit">
                                    <i class="fas fa-archive"></i></a>
                            @endif  
                          
                            </div>
                        {{-- @endif --}}

                        <div class="tickets_wrap mb-3">
                            <div class="cus_row row">
                                <div class="col-md-3">
                                    <div class="tickets_detail">
                                        <div class=" mb-3">
                                            <label for="created_at">Created at :</label>
                                            <h4 name="created_at" class="ticket-labels">{{ $ticket->created_at }}</h4>
                                        </div>
                                        <div class=" mb-3">
                                            <label for="raised_By">Ticket raised by : </label>
                                            <h4 name="raised_By" class="ticket-labels">{{ $ticket->name }}</h4>
                                        </div>
                                        <div class=" mb-3">
                                            <label for="ticket_category">Ticket category :</label>
                                            <h4 name="ticket_category" class="ticket-labels">{{ $ticket->category_name }}
                                            </h4>
                                        </div>
                                        <div class=" mb-3">
                                            <label for="ticket_status">Ticket status :</label>
                                            <h4 name="ticket_status" class="ticket-labels"> {{ $ticket->status == "InProgress" ? "In Progress" : $ticket->status }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="content">
                                        <ul>
                                            <li>
                                                <label>Description</label>
                                                <span>{{ $ticket->message }}</span>
                                            </li>
                                            @if (count($ticket->attachment) > 0 )
                                            <li>
                                                <label>Attachments</label>
                                                <div class="thumb_wrap">
                                                        @foreach ($ticket->attachment as $attachment)
                                                            @if ($attachment->extension == 'jpg' || $attachment->extension == 'jpeg' || $attachment->extension == 'png')
                                                                <span class="thumb_img">
                                                                    <span class="img_inner">
                                                                        <img src="{{ Storage::url('tickets_attachments/' . $attachment->basename) }}"
                                                                            alt="">
                                                                    </span>
                                                                    <a class="overlay"
                                                                        href="{{ Storage::url('tickets_attachments/' . $attachment->basename) }}"
                                                                        download>
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                @elseif ($attachment->extension == 'pdf')
                                                                <span class="thumb_img">
                                                                <img  class="icon-style" src="{{ asset('images/pdf.png') }}" alt="">
                                                                    <a class="overlay"
                                                                        href="{{ Storage::url('tickets_attachments/' . $attachment->basename) }}"
                                                                        download>
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </span>
                                                                @elseif ($attachment->extension == 'docx')
                                                                <span class="thumb_img">
                                                                    <img class="icon-style" src="{{ asset('images/docs.png') }}" alt="">
                                                                    <a class="overlay"
                                                                        href="{{ Storage::url('tickets_attachments/' . $attachment->basename) }}"
                                                                        download>
                                                                        <i class="fas fa-download"></i>
                                                                     </a>
                                                                </span>
                                                                @else
                                                                    <i class="fa fa-file"></i>
                                                                    <a class="overlay"
                                                                        href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}"
                                                                        download>
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                            @endif
                                                        @endforeach
                                                        <!-- <div id="edit_doc_preview" class="input-group-append">
                                                                                          <a href="{{ asset('images/ticketAttachment/' . $ticket->attachment) }}" id="edit_ticketAttachmentLink" target="_blank"><i class="fas fa-download" style="color: #5e72e4;"></i> {{ $ticket->attachment }}</a>
                                                                                      </div>  -->
                                                    @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <hr class="full_hr" />

                            <div class="canvas-wrapper reply-box">
                                @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                                        Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                                        Auth::user()->email == 'rohit.gupta@talentelgia.in' ||
                                        $ticket->email)
                                    @if ($ticket->status !== 'Closed')
                                    @if ($ticket->status !== 'Archive')
                                        
                                        {!! Form::open([
                                            'action' => 'TicketRepliesController@store',
                                            'method' => 'POST',
                                            'id' => 'add_ticket_reply_tickets',
                                            'class' => 'mb-0 row',
                                            'enctype' => 'multipart/form-data',
                                        ]) !!}
                                        <div class="form-group col-sm-6">
                                            <label for="content">Reply <span style="color: red">*</span></label>
                                            <textarea name="reply" id="reply" class="form-control" rows="3"></textarea>
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->ticket_id }}">
                                            <input type="hidden" name="ticket" value="ticket">
                                        </div>

                                        <div class="form-group col-sm-2">
                                            <input type="hidden" name="ticket_id" id="ticket_id"
                                                value="{{ $ticket->ticket_id }}">
                                            <label for="change_status">Change status :</label>
                                            <select class="form-control col-12 select_btn_icon" name="change_status">
                                                <option value="{{ $ticket->status }}">
                                                    {{ $ticket->status ==  "InProgress" ? "In Progress" : $ticket->status }}</option>
                                                    @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                                                    Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                                                    Auth::user()->email == 'rohit.gupta@talentelgia.in')
                                                    
                                                    @if ($ticket->status == 'Open')
                                                        <option value="InProgress">In Progress</option>
                                                        <option value="Closed">Closed</option>
                                                    @elseif ($ticket->status == 'InProgress')
                                                        <option value="Closed">Closed</option>
                                                    @elseif ($ticket->status == 'Closed')
                                                        <option value="Reopen">Reopen</option>
                                                    @elseif ($ticket->status == 'Reopen')
                                                        <option value="InProgress">In Progress</option>
                                                        <option value="Closed">Closed</option>
                                                    @endif
                                                @else
                                                    @if ($ticket->status == 'Open')
                                                        <option value="Closed">Closed</option>
                                                    @elseif ($ticket->status == 'InProgress')
                                                        <option value="Closed">Closed</option>
                                                    @elseif ($ticket->status == 'Closed')
                                                        <option value="Reopen">Reopen</option>
                                                    @elseif ($ticket->status == 'Reopen')
                                                        <option value="Closed">Closed</option>
                                                    @endif
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-sm-12 mt-2"><button type="submit" class="btn btn-primary">Add Reply</button>
                                        </div>
                                   
                                    {!! Form::close() !!}
                                    @endif
                                    @endif
                                @endif

                                @if (!$ticket_replies->isEmpty())
                                    @foreach ($ticket_replies as $index => $value)
                                        <div class="reply mt-3">
                                            <span class="posted-by">Posted by {{ $value->user_name.' '.'('. $value->user_email.')' }}<div
                                                    class="created-time">
                                                    {{ $value->created_at->diffForHumans() }}</div>
                                                </span>
                                            <p>{{ $value->reply }}</p>
                                            <input type="hidden" id="edit_reply_{{ $value->id }}"
                                                value="{{ $value->reply }}">
                                        
                                            <div
                                            class="status-btn {{ $value->ticket_status == 'Closed' ? 'closed' : ($value->ticket_status == 'InProgress' ? 'inprogress' : ($value->ticket_status == 'Reopen' ? 'reopen' : ($value->ticket_status == 'Open' ? 'open' : ($value->ticket_status == 'Archive' ? 'archive' : '')))) }}">
                                            
                                                {{ $value->ticket_status == "InProgress" ? "In Progress" :$value->ticket_status }}
                                            </div>
                                            <input type="hidden" id="edit_reply_{{ $value->id }}"
                                                value="{{ $value->reply }}">
                                            <input type="hidden" name="attachment_name"
                                                id="attachment_name_{{ $value->id }}"
                                                value="{{ $value->attachment }}">
                                            <input type="hidden" name="reply_attach_is_image"
                                                id="reply_attach_is_image{{ $value->id }}"
                                                value="{{ $value->is_image }}">
                                            @if ($value->attachment)
                                                @if ($value->is_image)
                                                    <img class="reply-img"
                                                        src="{{ asset('images/replyTicketAttachment/' . $value->attachment) }}"
                                                        alt="">
                                                @else
                                                    <i class=" fa-file"></i>
                                                @endif
                                                <div id="edit_doc_preview" class="input-group-append">
                                                    <a href="{{ asset('images/replyTicketAttachment/' . $value->attachment) }}"
                                                        id="edit_ticketAttachmentLink" target="_blank"><i
                                                            class="fas fa-download" style="color: #5e72e4;"></i>
                                                        {{ $value->attachment }}</a>
                                                </div>
                                            @endif
                                            <div class="reply-footer">
                                                @if (Auth::user()->email == $value->user_email)
                                                    @if ($ticket->status !== 'Closed')
                                                    @if ($ticket->status !== 'Archive')
                                                        @if ($value->is_latest)
                                                            <div class="reply-actions">
                                                                <a href="#" id="edit_reply_link" title="Edit Reply"
                                                                    onclick="edit_reply({{ $value->id }})"
                                                                    class="btn btn-primary"> <i class="fa fa-edit">
                                                                    </i>
                                                                </a>
                                                                <a id="reply_delete_btn" title="Delete Reply"
                                                                    href="{{ url('/ticket/replies/delete/' . encrypt($value->id)) }}"
                                                                    onclick="return confirm('Are you sure you want to delete this reply?');"
                                                                    class="btn btn-primary" type="submit"> <i
                                                                        class="fa fa-trash">
                                                                    </i>
                                                                </a>
                                                            </div>
                                                        @endif       
                                                    @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            role="dialog" 
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Archive Reason</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                {!! Form::open([
                    'action' => 'TicketRepliesController@store',
                    'method' => 'POST',
                    'id' => 'archive_store',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                <div class="modal-body">
                    <label for="archive_desc">Add a reason</label>
                  <textarea class="form-control" name="reply" id="archive_desc" cols="30" rows="5"></textarea>
                  <input type="hidden" name="change_status" value="Archive">
                  <input type="hidden" name="ticket_id" value="{{ $ticket->ticket_id }}">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                {!! Form::close() !!}
              </div>
            </div>
          </div>
        <!-- Card footer -->
        <div class="modal fade" id="replyEditModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Edit Reply</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    {!! Form::open([
                        'action' => 'TicketRepliesController@edit',
                        'method' => 'POST',
                        'id' => 'edit_ticket_reply_harmony',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="modal-body">
                        <input type="hidden" name="reply_id" id="reply_id">
                        <textarea class="form-control" name="reply_edit" id="reply_edit" cols="30" rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        
                        <button type="submit" class="btn btn-primary" id="replyEditSubmitbBtn">Save changes</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@section('script')
<script>
function openArchiveModal() {
$('#archiveModal').modal('show');
        }
        function edit_reply(id) {
            $('#replyEditModal').modal('show');
            var reply_text = $('#edit_reply_' + id).val();
            $('#reply_edit').val(reply_text);
            $('#reply_id').val(id);
        }
    </script>
    <script>
        $('#edit_ticket_link[disabled]').click(function(e) {
            e.preventDefault();
            return false;
        });


    </script>

    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);

        // Set default FilePond options
        FilePond.setOptions({
            stylePanelLayout: 'square',
            server: {
                url: "{{ config('filepond.server.url') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                }
            },
            maxFiles: 10,
            maxFileSize: '2MB',
            labelMaxFileSizeExceeded: 'File is too large',
            imagePreviewLoad: true,
            acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ],
            labelFileTypeNotAllowed: 'Invalid file type. Only images, Excel, and PDF files are allowed',
            allowFileTypeValidation: true,
            allowFileSizeValidation: true,
        });

        const pond = FilePond.create(document.querySelector('input[name="gallery[]"]'), {
            chunkUploads: true,
            onaddfilestart: (file) => {
                isLoadingCheck();
            },
            onprocessfile: (files) => {
                isLoadingCheck();
            },
            onremovefile: () => {
                isLoadingCheck();
            }
        });

        function isLoadingCheck() {
            const isLoading = pond.getFiles().filter(x => x.status !== 5).length !== 0;
            if (isLoading) {
                $('#replySubmitbBtn').attr("disabled", "disabled");
            } else {
                $('#replySubmitbBtn').removeAttr("disabled");
            }
        }
    </script>
    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
