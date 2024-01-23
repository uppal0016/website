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
 select {
    -moz-appearance:none; /* Firefox */
    -webkit-appearance:none; /* Safari and Chrome */
    appearance:none;
}

.commdiv-1 {
  width:100%
}

.filepond--file-status {
color: black;
font-size: 1.25rem !important;
font-weight: 900;
margin-top: 3%;
}


</style>
@php $categories = Helper::getTablaDataForDropDown('it_ticket_categories','name','asc');
$array1 = [''=>'Select Category'];
$categories = $array1 + $categories;
$severity = [
    'High' => 'High',
    'Medium' => 'Medium',
    'Low' => 'Low'
  ];

$selected_severity = 'Medium'; 
@endphp
<div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="/it-tickets/list">IT Tickets</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add IT Ticket</li>
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
          <h3 class="mb-0">Add IT Ticket</h3>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="editForm">
              {!! Form::open(array('action' => 'ItTicketsController@store','method'=>'POST','id'=>'add_it_ticket', 'enctype' => "multipart/form-data")) !!}
              <div class="FormRIght">
                <div class="form-group row col-4">
                  <label for="category_id">Please select a category<span style="color:red">*</span></label>
                  <div class="commdiv-1 ">
                      {!! Form::select('category_id',$categories,null,['class'=> 'form-control inventory_item_filter select_btn_icon','rel'=>'category_id' ,'id' => "it_ticket_category"]) !!}
                  </div>
                  </div>
                <div class="form-group">
                  <label for="description">Enter  description<span style="color:red">*</span></label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <textarea class="form-control" placeholder="Enter Description" value="{{old('description')}}" id="description" name="description" rows="8"></textarea>
                  </div>                
                <span class="text-danger">{{$errors->first('description')}}</span>
                </div>
           
                <div class="row">
             
                <div class="form-group col-4">
                <label for="severity">Please select severity level</label>
                <div class="commdiv-1">
                  {!! Form::select('severity', $severity, $selected_severity, ['class' => 'form-control inventory_item_filter select_btn_icon', 'rel' => 'severity' ]) !!}
                </div>
                </div>
              </div>
                <div class="form-group">
                <div>
                
                </div>
                </div>
                <label for="ticketAttachment">Please upload ticket attachment</label>
                
                <div class="form-group">
                <i>(Maximum files : 10)</i>
                <div class="preview-container  mt-2 d-inline-block position-relative mb-3">
                  <img id="previewImage" style="width: 100px; height: 100px; display:none;">
                  <button class="delete-btn" style="display: none" id="removeImageButton"><i class="fas fa-times"></i></button>
                  <button class="delete-btn" style="display: none" id="removeDocumentButton"><i class="fas fa-times"></i></button>
                </div>
                <div class="input-group input-group-merge input-group-alternative1">
                </div>
                <input type="file" name="gallery[]" multiple data-max-file-preview="4" />
                <p style="width: 100%; color: red; box-shadow: none; font-weight: 600; font-size: 13px;" id="error_gallery_add"></p>
                <p class="help-block">{{ $errors->first('gallery.*') }}</p>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-primary  add-user-btn" name="submit" id="submitbBtn">Submit</button>
                  <a href="#" style="display: none;">Remove Document</a>
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
FilePond.registerPlugin(FilePondPluginImagePreview);
FilePond.registerPlugin(FilePondPluginPdfPreview);
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
  allowEmpty: false,
  labelMaxFileSizeExceeded: 'File is too large',
  imagePreviewLoad: true,
  acceptedFileTypes: ['image/*', 'application/pdf', 'application/msword'],
  labelFileTypeNotAllowed: 'Invalid file type. Only images, Word, and PDF files are allowed',
  allowFileTypeValidation: true,
  allowFileSizeValidation: true,
});


  // Create the FilePond instance
  const pond = FilePond.create(document.querySelector('input[name="gallery[]"]'), {
  chunkUploads: true,
  pdfPreviewHeight: 140,
  onaddfilestart: (file) => { isLoadingCheck(); },
  onprocessfile: (files) => { 
    $('#error_gallery_add').text("");
    isLoadingCheck(); 
  },
  onremovefile: () => { isLoadingCheck(); } // Add onremovefile event listener
});

function isLoadingCheck() {
  const isLoading = pond.getFiles().filter(x => x.status !== 5).length !== 0;
  if (isLoading) {
    $('#submitbBtn').attr("disabled", "disabled");
  } else {
    $('#error_gallery_add').text("");
    $('#submitbBtn').removeAttr("disabled");
  }
}

</script>
@endsection