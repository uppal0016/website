@extends('layouts.page')
@section('content')
<style>

  button.delete-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    border-radius: 100%;
    cursor: pointer;  
    background: red;
    border: 1px solid red;
    height: 20px;
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #fff;
}
.choose-file {
    border: 1px solid #35b0c4;
    padding: 5px 10px;
    border-radius: 4px;
    color: #35b0c4;
    font-size: 14px;
    font-weight: 600;
}


</style>
<div class="header bg-primary pb-6">
  @php $categories = Helper::getTablaDataForDropDown('it_ticket_categories','name','asc');
  $array1 = [''=>'Select Category'];
  $categories = $array1 + $categories;
  $severity = [
      'High' => 'High',
        'Medium' => 'Medium',
        'Low' => 'Low'
    ];
  @endphp
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="/it-tickets/list">IT Tickets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit ticket</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card minHeight">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Edit Ticket</h3>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            
            <div class="editForm">
              {!! Form::open(array('action' => 'ItTicketsController@update','method'=>'POST','id'=>'edit1_ticket', 'enctype' => "multipart/form-data")) !!}
              <div class="FormRIght">
                <div class="form-group col-4">
                  <label for="category_id">Please select a category<span style="color:red">*</span></label>
                  <div class="commdiv-1">
                      {!! Form::select('category_id',$categories,$ticket->category,['class'=> 'form-control inventory_item_filter select_btn_icon','rel'=>'category_id' ,'id' => 'it_ticket_category']) !!}
                      <span class="text-danger">{{$errors->first('category_id')}}</span>
                  </div>
                  </div>
                <div class="form-group">
                  <label for="edit_description">Enter  description<span style="color:red">*</span></label>
                  <input type="hidden" name="ticket_id" value="{{$ticket->ticket_id}}">
                  <div class="input-group input-group-merge input-group-alternative">
                    <textarea class="form-control" placeholder="Enter Description" id="edit_description" name="edit_description" rows="8" value="{{old('description',isset($description) ? $description: $ticket->message)}}">{{old('description',isset($description) ? $description:$ticket->message)}}</textarea>
                  </div>                
                <span class="text-danger">{{$errors->first('edit_description')}}</span>
                </div>

        
                <div class="row">
                <div class="form-group col-4">
                <label for="severity">Please select severity level</label>
                <div class="commdiv-1">
                    {!! Form::select('severity',$severity,$ticket->severity,['class'=> 'form-control inventory_item_filter select_btn_icon','rel'=>'category_id']) !!}
                </div>
                </div>
                </div>
                {{-- <label for="edit_items_select">Please select an item</label>
                <div class="input-group input-group-merge input-group-alternative">
                  <select class="form-control select_btn_icon" name="edit_items_select" id="edit_items_select">
                    <option value="0" disabled selected>Select item</option>
                    @foreach ($items as $item)
                      <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    <option value="0">Other</option>
                  </select>    
                </div> --}}
                <div class="form-group">
                <div>
                  <label for="ticketAttachment">Please upload ticket attachment </label>
                  <div class="thumb_outer">
                    <i>(Maximum files: <span id="max-files">10</span>)</i>

                      <div class="thumb_wrap">
                          @if ($ticket->attachment)
                          @foreach ($ticket->attachment as $attachment)
                          @if ($attachment->extension == 'jpg' || $attachment->extension == 'jpeg' || $attachment->extension == 'png')
                          <span class="thumb_img">
                          <span class="img_inner">
                            <img src="{{ Storage::url('it_tickets_attachments/'.$attachment->basename) }}" alt="">
                          </span>
                          <span class="overlay_wrap">
                             <a href="{{ Storage::url('it_tickets_attachments/'.$attachment->basename) }}" download> <i class="fas fa-download"></i></a>
                             <a href="/it-tickets/delete-attachment/{{ $attachment->id }}" class="delete"><i class="fas fa-trash" onclick="return confirm('Are you sure you want to delete this ticket?');"></i></a>
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
                                    href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}" download>
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
                                    href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}" download>
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
                                href="{{ Storage::url('it_tickets_attachments/' . $attachment->basename) }}"
                                download>
                                <i class="fas fa-download"></i>
                            </a>
                        <a href="/tickets/delete-attachment/{{ $attachment->id }}"
                            class="delete"><i class="fas fa-trash"
                                onclick="return confirm('Are you sure you want to delete this ticket?');"></i></a>
                        </a>
                          <a href="/it-tickets/delete-attachment/{{ $attachment->id }}" class="delete"><i class="fas fa-trash" onclick="return confirm('Are you sure you want to delete this ticket?');"></i></a>
                          @endif
                          @endforeach
                           <!-- <div id="edit_doc_preview" class="input-group-append">
                            <a href="{{asset('images/ticketAttachment/'.$ticket->attachment)}}" id="edit_ticketAttachmentLink" target="_blank"><i class="fas fa-download" style="color: #5e72e4;"></i> {{$ticket->attachment}}</a>
                        </div>  -->
                    @endif                        
                      </div>
                  </div>
                  <div id="input-container">
                    <input type="file" id="input-field" name="gallery[]" multiple data-max-file-preview="4" style="display: none;"/>
                  </div>
                </div>
          
                {{-- <div class="input-group input-group-merge input-group-alternative">
                  <label class="choose-file">
                    <input class="d-none" type="file" name="edit_ticketAttachment" id="edit_ticketAttachmentInput"  maxlength="10485760" >
                    Choose File
                  </label>
                  <label for="ticketAttachment" class="ml-3" id="selectedFile"></label>
                </div>
                <div id="edit_doc_preview" class="input-group-append">  --}}
                  {{-- <a href="{{asset('images/ticketAttachment/'.$ticket->attachment)}}" id="edit_ticketAttachmentLink" target="_blank">{{$ticket->attachment}}</a> --}}
              {{-- </div> --}}
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary  add-user-btn" name="submit" id="submitbBtn">Submit</button>
                </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>

@endsection
@section('script')
<script>
   $('#it_ticket_category option[value=""]').hide();

  const attachmentCount = <?php echo json_encode(count($ticket->attachment)); ?>;
  var maxAttachmentCount = attachmentCount < 10 ? 10-attachmentCount : 0;
  $('#max-files').text( maxAttachmentCount.toString()); 
  FilePond.registerPlugin(FilePondPluginImagePreview);
  FilePond.registerPlugin(FilePondPluginFileValidateType);
  FilePond.registerPlugin(FilePondPluginFileValidateSize);
  
    // Set default FilePond options
      FilePond.setOptions({
      server: {
        url: "{{ config('filepond.server.url') }}",
        headers: {
          'X-CSRF-TOKEN': "{{ @csrf_token() }}",
        }
      },
      maxFiles: attachmentCount < 10 ? 10-attachmentCount : 0,
      maxFileSize: '2MB',
      labelMaxFileSizeExceeded: 'File is too large',
      labelMaxFilesExceeded: 'You can only upload up to {maxFiles} files',
      imagePreviewMaxHeight: 50,
      imagePreviewMaxWidth: 50,
      imagePreviewLoad: true,
      acceptedFileTypes: ['image/*', 'application/pdf' ,'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
      labelFileTypeNotAllowed: 'Invalid file type. Only images, Excel, and PDF files are allowed',
      allowFileTypeValidation: true,
      allowFileSizeValidation: true,
    });
  
    // Create the FilePond instance
  const pond =   FilePond.create(document.querySelector('input[name="gallery[]"]'), {chunkUploads: true , 
    onaddfilestart: (file) => { isLoadingCheck(); },
    onprocessfile: (files) => { isLoadingCheck(); },
    onremovefile: () => { isLoadingCheck(); }
 });

  const maxFilesElement = document.getElementById("max-files");
  const inputContainer = document.getElementById("input-container");
  const inputField = document.getElementById("input-field");

  const maxFiles = parseInt(maxFilesElement.textContent);

  if (maxFiles === 0) {
    inputContainer.style.display = "none";
  } else {
    inputField.setAttribute("data-max-file-preview", "4");
  }

 function isLoadingCheck() {
  const isLoading = pond.getFiles().filter(x => x.status !== 5).length !== 0;
  if (isLoading) {
    $('#submitbBtn').attr("disabled", "disabled");
  } else {
    $('#submitbBtn').removeAttr("disabled");
  }
}


 if(attachmentCount >= 10){
  pond.setOptions({
        allowDrop: false,
        allowBrowse: false
    });
 }
  </script>


@endsection