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

  .input-group.input-group-merge.input-group-alternative {
      margin-bottom: 2.5%;
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
                <li class="breadcrumb-item active" aria-current="page">Add Document</li>
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
          <h3 class="mb-0">Add Document</h3>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="editForm">
            <form action="{{ route('document.store') }}" method="POST" enctype="multipart/form-data" id="documentCreateForm">
            @csrf
              <div class="FormRIght">
               
                  <label for="documentAttachment">Please upload document <span class="important">*</span></label>
                </div>
                <div class="preview-container">
                  <img id="previewImage" style="max-width: 200px; max-height: 200px;">
                </div>
                <div class="input-group input-group-merge input-group-alternative">
                  <input type="file" name="document" accept=".docx,.pdf, .doc" id="document" maxlength="2097152">
                </div>
                <a href="#" id="removeImageButton" style="display: none;">Remove Image</a>
                  <a href="#" id="removeDocumentButton" style="display: none;">Remove Document</a>
                <span class="text-danger">{{ $errors->first('document') }}</span> 
                
                <div class="document_dropdown mt-4">
                  <label for="protected_file">Password Protected <span class="important">*</span></label>
                  {!! Form::select(
                      'protected_file',
                      ['' => 'Select File Type', 'Open' => 'Open', 'Single' => 'Single Password', 'Multiple' => 'Multiple Password'],
                      null,
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
                  <button type="submit" class="btn btn-primary  add-user-btn" name="submit" id="submitbBtn">Submit</button>
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



@endsection