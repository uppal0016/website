@extends('layouts.page')
@section('content')
<style>
  .important{
    color: red
  }

  .error{
    font-size: 14px !important;
  }

  label#document-error {
    position: absolute;
    top: 2rem;
  }

  span#error-message {
    font-size: 14px;
    font-weight: 700;
  }

  #error-message {
    position: absolute;
    top: 10.5rem;
    left: 1.5rem;
}

</style>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="/document">Documents</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Document</li>
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
          <h3 class="mb-0">Update Document</h3>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="editForm">
            <form action="{{ route('document.update',$document->id) }}" method="POST" enctype="multipart/form-data" id="documentEditForm">
            @csrf
            @method('PUT')
              <div class="FormRIght">
               
                  <label for="ticketAttachment">Please upload document <span class="important">*</span></label>
                </div>
                <div class="preview-container">
                  <img id="previewImage" style="max-width: 200px; max-height: 200px;">
                </div>
                <div class="input-group input-group-merge input-group-alternative">
                  <input type="file" name="edit_document" accept=".docx,.pdf, .doc" id="document" maxlength="2097152" value="">
                </div>
                <div id="edit_doc_preview" class="input-group-append">
                    <h4><a href="{{route('display_pdf', ['id' => $document->id])}}" target="_blank">{{$document->documents}}</a><h4>
              </div>

              <a href="#" id="removeImageButton" style="display: none; position: relative; top: 1.5rem;">Remove Image</a>
              <a href="#" id="removeDocumentButton" style="display: none; position: relative; top: 1.5rem;">Remove Document</a>
              <span class="text-danger" id="error-message">{{ $errors->first('edit_document') }}</span> 

              <div class="document_dropdown mt-4">
                <label for="protected_file">Password Protected <span class="important">*</span></label>
                {!! Form::select(
                    'protected_file',
                    ['' => 'Select File Type', 'Open' => 'Open', 'Single' => 'Single Password', 'Multiple' => 'Multiple Password'],
                    $document->protected_file,
                    [
                        'class' => 'form-control document_protected_file stock_drpDwn select_btn_icon',
                        'rel' => 'protected_file',
                        'id' => 'protected_file',
                        'style' => 'width:20%'
                    ],
                ) !!}
                 <span class="text-danger">{{ $errors->first('protected_file') }}</span>
            </div>
                <div class="mt-3">
                  <button type="submit" id="submit" class="btn btn-primary  add-user-btn" name="submit">Submit</button>
                </div>
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
  const add_ticket_input = document.getElementById('document');
 
  const removeImageButton = document.getElementById('removeImageButton');
  const removeDocumentButton = document.getElementById('removeDocumentButton');

  add_ticket_input.addEventListener('change', function() {
    const file = add_ticket_input.files[0];

    if (file && file.size > 2097152) {
      alert('The selected file is too large. Please select a file that is smaller than 2MB.');
      add_ticket_input.value = '';
      return;
    }

    const reader = new FileReader();
    reader.addEventListener('load', function() {
      if (file.type.startsWith('image/')) {
        previewImage.setAttribute('src', reader.result);
        removeImageButton.style.display = 'inline-block';
        removeDocumentButton.style.display = 'none';
      } else {
        previewImage.setAttribute('src', '');
        removeImageButton.style.display = 'none';
        removeDocumentButton.style.display = 'inline-block';
      }
    });

    if (file) {
      reader.readAsDataURL(file);
    }
  });

  removeImageButton.addEventListener('click', function() {
    add_ticket_input.value = '';
    previewImage.setAttribute('src', '');
    removeImageButton.style.display = 'none';
  });

  removeDocumentButton.addEventListener('click', function() {
    add_ticket_input.value = '';
    removeDocumentButton.style.display = 'none';
  });
  
</script>

<script>
  $(document).ready(function() {
    function validateDocument() {
      var documentContent = $("#edit_doc_preview h4").text().trim();
      var documentFile = $("#document").val(); 
      if (documentContent === "" && documentFile === "") {
        event.preventDefault();
        $("#error-message").text("Please upload a document.");
      } else if(documentFile !== ""){
        $("#error-message").text("");
        return true;
      } else {
        $("#error-message").text("");
        return true; 
      }
    }

    $("#submit").click(function() {
         validateDocument();
      });
  });
</script>

<script>
  $(document).ready(function() {
    $("#document").click(function() {
      $("#edit_doc_preview h4").empty();
    });
  });
</script>


@endsection