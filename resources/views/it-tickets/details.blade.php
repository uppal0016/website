@extends('layouts.page')
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="/it-tickets/list">IT
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
                            <label class="m-0">Ticket No:</label>
                            <div class="pl-2">
                                <h3 class="mb-0">{{ $ticket->ticket_id }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="reply-actions mb-3">
                            @if (Auth::user()->email == $ticket->email)
                            <a href="/it-tickets/edit/{{ $ticket->ticket_id }}" id="edit_ticket_link"
                                class="btn btn-primary {{ $ticket->status !== 'Open' ? 'disabled' : '' }}"
                                title="Edit ticket" {{ $ticket->status !== 'Open' ? 'disabled' : '' }}> <i
                                    class="fa fa-edit"> </i></a>

                            <a class="btn btn-primary  {{ $ticket->status !== 'Open' ? 'disabled' : '' }}"
                                id="archive_ticket_link"title="Archive Ticket"
                                onclick="openArchiveModal()"
                                {{ $ticket->status !== 'Open' ? 'disabled' : '' }} class="btn btn-primary" type="submit">
                                <i class="fas fa-archive"></i></a>
                            @endif
                         
                        </div>
                        <div class="tickets_wrap mb-3">
                            <div class="cus_row row">
                                <div class="col-md-3">
                                    <div class="tickets_detail">
                                        <div class=" mb-3">
                                            <label class="tick_label" for="created_at">Created at :</label>
                                            <h4 name="created_at" class="ticket-labels">{{ $ticket->created_at }}</h4>
                                        </div>
                                        <div class=" mb-3">
                                            <label class="tick_label" for="raised_By">Ticket raised by : </label>
                                            <h4 name="raised_By" class="ticket-labels">{{ $ticket->user_name }}</h4>
                                        </div>
                                        <div class=" mb-3">
                                            <label class="tick_label" for="ticket_category">Ticket category :</label>
                                            <h4 name="ticket_category" class="ticket-labels">{{ $ticket->category_name }}
                                            </h4>
                                        </div>
                                        <div class=" mb-3">
                                            <label class="tick_label" for="ticket_category">Ticket severity level :</label>
                                            <h4 name="ticket_category" class="ticket-labels">{{ $ticket->severity }}</h4>
                                        </div>
                                        <div class="mb-0">
                                            <label class="tick_label" for="ticket_status">Ticket status :</label>
                                            <h4 name="ticket_status" class="ticket-labels"> {{ $ticket->status == "InProgress" ? "In Progress" : $ticket->status }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="content">
                                        <ul>
                                            <li>
                                                <label>Description</label>
                                                <span style="word-break: break-all">{{ $ticket->message }}</span>
                                            </li>
                                            @if ($ticket->item_name)
                                                <li>
                                                    <label>Item Name</label>
                                                    <span>{{ $ticket->item_name }}</span>
                                                </li>
                                            @endif
                                            @if (count($ticket->attachment) > 0 )
                                            <li>
                                                <label>Attachments</label>
                                                <div class="thumb_wrap">
                                                        @foreach ($ticket->attachment as $attachment)
                                                            @if ($attachment->extension == 'jpg' || $attachment->extension == 'jpeg' || $attachment->extension == 'png')
                                                                <span class="thumb_img">
                                                                    <span class="img_inner">
                                                                        <a href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}" target="_blank">
                                                                        <img src="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}"
                                                                            alt="">
                                                                        </a>
                                                                    </span>
                                                                    <a class="overlay"
                                                                        href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}"
                                                                        download>
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </span>
                                                            @elseif ($attachment->extension == 'pdf')
                                                            <span class="thumb_img">
                                                            <img class="icon-style" src="{{ asset('images/pdf.png') }}" alt="">
                                                                <a class="overlay"
                                                                    href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}"
                                                                    download>
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </span>
                                                            @elseif ($attachment->extension == 'docx')
                                                            <span class="thumb_img">
                                                                <img class="icon-style" src="{{ asset('images/docs.png') }}" alt="">
                                                                <a class="overlay"
                                                                    href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}"
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
                                                </div>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="full_hr" />
                        @if ($ticket->status !== 'Archive')
                        <div class="canvas-wrapper reply-box">
                            @if (Auth::user()->email == 'gautam.uppal@talenetelgia.in' ||
                                    Auth::user()->email == 'rohit.gupta@talentelgia.in' ||
                                    $ticket->email)
                            
                                {!! Form::open([
                                    'action' => 'TicketRepliesController@store',
                                    'method' => 'POST',
                                    'id' => 'add_ticket_reply_1',
                                    'class' => 'mb-0',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="content">Reply<span style="color:red">*</span></label>
                                            <textarea name="reply" id="reply" class="form-control" rows="2"></textarea>
                                            <input type="hidden" name="ticket_id" value="{{ $ticket->ticket_id }}">
                                            <input type="hidden" name="it_ticket" value="it_ticket">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <input type="hidden" name="ticket_id" id="ticket_id"
                                                value="{{ $ticket->ticket_id }}">
                                            <label for="change_status">Change status</label>
                                            <select class="form-control col-12 select_btn_icon" name="change_status">
                                                <option value="{{ $ticket->status }}">
                                                    {{ $ticket->status ==  "InProgress" ? "In Progress" : $ticket->status }}</option>
                                                @if (Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
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
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="replyTicketAttachment">Please upload comment attachment</label>
                                    <i>(Maximum files : 10)</i>
                                    <input type="file" name="gallery[]" multiple data-max-file-preview="4" />
                                    <p id="attachment_error" style="color: red ; display:none">Please check your
                                        attachments.</p>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" id="replySubmitbBtn" class="btn btn-primary">Add
                                        Reply</button>
                                </div>
                                {!! Form::close() !!}
                            @endif
                        </div>
                        @endif
                        @if (!$ticket_replies->isEmpty())
                            @foreach ($ticket_replies as $index => $value)
                                @if ($value->is_latest)
                                    <input type="hidden" id="edit_reply" value="{{ $value->reply }}">
                                @endif
                                <div class="reply mt-3">
                                    <span class="posted-by">Posted by {{ $value->user_name }} <div class="created-time">
                                            {{ $value->created_at->diffForHumans() }}</div></span>
                                    <p>{{ $value->reply }}</p>

                                    <div
                                    class="status-btn {{ $value->ticket_status == 'Closed' ? 'closed' : ($value->ticket_status == 'InProgress' ? 'inprogress' : ($value->ticket_status == 'Reopen' ? 'reopen' : ($value->ticket_status == 'Open' ? 'open' : ($value->ticket_status == 'Archive' ? 'archive' : '')))) }}">
                                    
                                    {{ $value->ticket_status == "InProgress" ? "In Progress" :$value->ticket_status }}
                                    </div>

                                    <input type="hidden" id="edit_reply_{{ $value->id }}"
                                        value="{{ $value->reply }}">
                                    <input type="hidden" name="attachment_name"
                                        id="attachment_name_{{ $value->id }}" value="{{ $value->attachment }}">
                                    <input type="hidden" name="reply_attach_is_image"
                                        id="reply_attach_is_image{{ $value->id }}" value="{{ $value->is_image }}">
                                    <span class="thumb_wrap">
                                        @if ($value->attachment)
                                            @foreach ($value->attachment as $attachment)
                                                @if ($attachment->extension == 'jpg' || $attachment->extension == 'jpeg' || $attachment->extension == 'png')
                                                    <span class="thumb_img">
                                                        <span class="img_inner">
                                                            <a  href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}" target="_blank">
                                                            <img class="reply-img"
                                                                src="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                                alt="">
                                                            </a>
                                                        </span>
                                                        <a class="overlay"
                                                            href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                            download><i class="fas fa-download"></i></a>
                                                    </span>
                                                    @elseif ($attachment->extension == 'pdf')
                                                    <span class="thumb_img">
                                                    <img class="icon-style" src="{{ asset('images/pdf.png') }}" alt="">
                                                        <a class="overlay"
                                                            href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </span>
                                                    @elseif ($attachment->extension == 'docx')
                                                    <span class="thumb_img">
                                                        <img class="icon-style" src="{{ asset('images/docs.png') }}" alt="">
                                                        <a class="overlay"
                                                            href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                         </a>
                                                    </span>
                                                    @else
                                                        <i class="fa fa-file"></i>
                                                        <a class="overlay"
                                                            href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    </span>
                                    <div class="reply-footer">

                                        @if (Auth::user()->email == $value->user_email)
                                            @if ($ticket->status !== 'Closed')
                                                @if ($value->is_latest)
                                                    <div class="reply-actions">
                                                        @if ($ticket->status !== 'Archive')
                                                        <a href="#" id="edit_reply_link"
                                                            onclick="edit_reply({{ $value->id }})"
                                                            class="btn btn-primary" title="Edit Reply"> <i
                                                                class="fa fa-edit"> </i></a>

                                                        <a id="reply_delete_btn"
                                                            href="{{ url('/ticket/replies/delete/' . encrypt($value->id)) }}"
                                                            title="Delete Reply"
                                                            onclick="return confirm('Are you sure you want to delete this reply?');"
                                                            class="btn btn-primary" type="submit"> <i
                                                                class="fa fa-trash"> </i></a>    
                                                        @endif
                                                        
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <!-- Card footer -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        
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
              <input type="hidden" name="it_ticket" value="it_ticket">
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
    <div class="modal fade" id="replyEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
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
                    'id' => 'edit_ticket_reply_it',
                    'enctype' => 'multipart/form-data',
                ]) !!}
                <div class="modal-body">
                    <input type="hidden" name="reply_id" id="reply_id">
                    <div class="form-group">
                        <textarea class="form-control" name="reply_edit" id="reply_edit" cols="20" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="ticketAttachment">Please upload ticket attachment </label>
                        <div class="thumb_outer">
                            <i>(Maximum files: <span id="max-files">10</span>)</i>
                            <span class="thumb_wrap">
                                @foreach ($ticket_replies as $index => $value)
                                    @if ($value->is_latest)
                                        @if ($value->attachment)
                                            @foreach ($value->attachment as $attachment)
                                                @if ($attachment->extension == 'jpg' || $attachment->extension == 'jpeg' || $attachment->extension == 'png')
                                                    <span class="thumb_img">
                                                        <span class="img_inner">
                                                            <img class="reply-img"
                                                                src="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                                alt="">
                                                        </span>
                                                        <span class="overlay_wrap">
                                                            <a href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                                download=""><i class="fas fa-download"></i></a>
                                                                <a onclick="replyDeleteAttachment({{ $attachment->id }} , {{$attachment->reply_id}})" class="delete"><i class="fas fa-trash"></i></a>
                                                        </span>
                                                    </span>
                                                    @elseif ($attachment->extension == 'pdf')
                                                    <span class="thumb_img">
                                                        <span class="img_inner">
                                                            <img class="icon-style" src="{{ asset('images/pdf.png') }}"
                                                                alt="">
                                                        </span>
                                                        <span class="overlay_wrap">
                                                            <a
                                                                href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}" download>
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            <a href="/tickets/delete-attachment/{{ $attachment->id }}"
                                                                class="delete"><i class="fas fa-trash"
                                                                    onclick="return confirm('Are you sure you want to delete this ticket?');"></i></a>
                                                        </span>
                                                    </span>
                                                    @elseif ($attachment->extension == 'docx')
                                                    <span class="thumb_img">
                                                        <span class="img_inner">
                                                            <img class="icon-style" src="{{ asset('images/docs.png') }}"
                                                                alt="">
                                                        </span>
                                                        <span class="overlay_wrap">
                                                            <a
                                                                href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}" download>
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            <a href="/it-tickets/delete-attachment/{{ $attachment->id }}"
                                                                class="delete"><i class="fas fa-trash"
                                                                    onclick="return confirm('Are you sure you want to delete this ticket?');"></i></a>
                                                        </span>
                                                    </span>
                                                    @else
                                                        <i class="fa fa-file"></i>
                                                        <a class="overlay"
                                                            href="{{ Storage::url('it_tickets_replies_attachments/' . $attachment->basename) }}"
                                                            download>
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    <a href="/tickets/delete-attachment/{{ $attachment->id }}"
                                                        class="delete"><i class="fas fa-trash"
                                                            onclick="return confirm('Are you sure you want to delete this ticket?');"></i></a>
                                                    </a>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                @endforeach
                            </span>
                        </div>
                        <div id="input-container">
                        <input type="file" id="input-field" name="edit_replygallery[]" multiple data-max-file-preview="4" style="display: none;"/>
                        </div>
                        <div class="input-group input-group-merge input-group-alternative1">
                            <label class="ml-3" for="edit_replyTicketAttachment" id="reply_selectedFile"></label>
                        </div>
                        <div class="mt-3">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="replyEditSubmitbBtn" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
       $(document).ready(function() {
          var reply_attachment_deleted = localStorage.getItem('reply_attachment_deleted');
          if (reply_attachment_deleted) {
            var reply_attachment_deleted = JSON.parse(localStorage.getItem('reply_attachment_deleted'));
            var id = reply_attachment_deleted;
              edit_reply(id);
              localStorage.setItem('reply_attachment_deleted', reply_id);
          }
      });

        $('#edit_ticket_link[disabled]').click(function(e) {
            e.preventDefault();
            return false;
        });
    </script>
    <script>
        function openArchiveModal() {
            $('#archiveModal').modal('show');
        }

        function edit_reply(id) {
            const ticketReplies = <?php echo json_encode($ticket_replies); ?>;
            const latestReply = ticketReplies[0];
            var attach_length;
            if (latestReply && latestReply.attachment) {
                attach_length = latestReply.attachment.length;
            } else {
                console.log("No attachment found in latest reply");
            }
            var maxAttachmentCount = attach_length < 10 ? 10 - attach_length : 0;
            $('#max-files').text(maxAttachmentCount.toString());
            $('#replyEditModal').modal('show');
            var reply_text = $('#edit_reply').val();
            $('#reply_edit').val(reply_text);
            $('#reply_id').val(id);
            var attachment_name = $('#attachment_name_' + id).val();
            var attach_is_image = $('#reply_attach_is_image' + id).val();


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
                maxFiles: maxAttachmentCount,
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


            const pond = FilePond.create(document.querySelector('input[name="edit_replygallery[]"]'), {
                chunkUploads: true,
                onaddfilestart: (file) => {
                    isLoadingEditCheck();
                },
                onprocessfile: (files) => {
                    isLoadingEditCheck();
                },
                onremovefile: () => {
                    isLoadingEditCheck();
                }
            });

            const maxFilesElement = document.getElementById("max-files");
            const inputContainer = document.getElementById("input-container");
            const inputField = document.getElementById("input-field");

            const maxFiles = parseInt(maxFilesElement.textContent);

            if (maxFiles === 0) {
            inputContainer.style.display = "none";
            } else {
            inputField.setAttribute("data-max-file-preview", "4");
            };

            if (attach_length >= 10) {
                $('#edit_attachment_error').show();
                $('#edit_attachment_error').text('You can not upload more than 10 attachments.');
                pond.setOptions({
                    allowDrop: false,
                    allowBrowse: false
                });
            }

            function isLoadingEditCheck() {
                const isLoading = pond.getFiles().filter(x => x.status !== 5).length !== 0;
                if (isLoading) {
                    $('#replyEditSubmitbBtn').attr("disabled", "disabled");
                    $('#edit_attachment_error').show();
                } else {
                    $('#replyEditSubmitbBtn').removeAttr("disabled");
                    $('#edit_attachment_error').hide();
                }
            }

            if (attachment_name) {
                if (attach_is_image) {
                    $('#edit_previewImage').attr('src', '/images/replyTicketAttachment/' + attachment_name);
                } else {
                    $('#edit_previewImage').attr('src', '/images/docs.png');
                }
            } else {
                $('#edit_previewImage').hide()
            }


        }
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
            allowFileTypeNotAllowed: true,
            allowFileTypeValidation: true,
            allowFileSizeValidation: true,
        });

        const pond = FilePond.create(document.querySelector('input[name="gallery[]"]'), {
            chunkUploads: true,
            acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            labelFileTypeNotAllowed: 'Only images, PDFs, and Word documents are allowed',
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
