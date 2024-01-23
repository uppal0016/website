@extends('layouts.page')
@section('content')
    <style>
        /* .filepond--item {
                        width: calc(25% - 0.5em);
                        height: 0;
                        padding-bottom: calc(25% - 0.5em);
                    }

                    .filepond--item img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    .filepond--image-preview-overlay-success {
                        display: none !important;
                    }

                    .filepond--image-preview-overlay {
                        opacity: 0;
                    } */

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
            -moz-appearance: none;
            /* Firefox */
            -webkit-appearance: none;
            /* Safari and Chrome */
            appearance: none;
        }

        .select_btn_icon {
            background-image: url(https://uploads-ssl.webflow.com/616818d939434d23bf997966/63bc129d04cd88bae2670537_download-ico-1.svg);
            background-repeat: no-repeat;
            background-position: right 5px;
            background-origin: content-box;
            background-size: 12px;
        }
        .important{
            color: red;
        }

        #category_id option:first-child{
            display: none;
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
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="/tickets/list">Harmony
                                        Tickets</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add Harmony Ticket</li>
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
                        <h3 class="mb-0">Add Harmony Ticket</h3>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="editForm">
                                {!! Form::open([
                                    'action' => 'TicketsController@store',
                                    'method' => 'POST',
                                    'id' => 'add_ticket',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}
                                <div class="FormRIght">
                                    <div class="form-group">
                                        <label for="description">Enter Description<span class="important">*</span></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <textarea class="form-control" placeholder="Enter Description" value="{{ old('description') }}" id="description"
                                                name="description" rows="8"></textarea>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                    @php $categories = Helper::getTablaDataForDropDown('harmony_tickets_categories', 'name', 'asc');
                                        $array1 = ['' => 'Select Category'];
                                        $categories = $array1 + $categories;
                                    @endphp
                                    <div class="form-group row col-6">
                                        <label for="category_id">Please select a category <span class="important">*</span></label>
                                        {!! Form::select('category_id', $categories, null, [
                                            'class' => 'form-control inventory_item_filter select_btn_icon',
                                            'rel' => 'category_id',
                                            'id' => 'category_id',
                                        ]) !!}
                                        <div id="other_category_div" style="display: none; width: 100%;">
                                            {!! Form::text('other_category', null, ['class' => 'mt-3 form-control', 'id' => 'other_category', 'maxlength' => "50"]) !!}
                                        </div>
                                    </div>
                                    <label for="ticketAttachment">Please upload ticket attachment </label>
                                    <div class="form-group">
                                        <i>(Maximum files : 10)</i>
                                        <div class="preview-container  mt-2 d-inline-block position-relative mb-3">
                                            <img id="previewImage" style="width: 100px; height: 100px; display:none;">
                                            <button class="delete-btn" style="display: none" id="removeImageButton"><i
                                                    class="fas fa-times"></i></button>
                                            <button class="delete-btn" style="display: none" id="removeDocumentButton"><i
                                                    class="fas fa-times"></i></button>
                                        </div>
                                        <input type="file" name="gallery[]" multiple data-max-file-preview="4" />
                                        {{-- <p style="width: 100%; color: red; box-shadow: none; font-weight: 600; font-size: 13px;" id="error_gallery_add"></p> --}}
                                        <p class="help-block">{{ $errors->first('gallery.*') }}</p>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary  add-user-btn" name="submit"
                                            id="submitbBtn">Submit</button>
                                        <a href="#" style="display: none;">Remove Document</a>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/.row-->
        </div>
    </div>
@endsection

@section('script')
    <script>
        FilePond.registerPlugin(FilePondPluginImagePreview);
        FilePond.registerPlugin(FilePondPluginPdfPreview);
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        FilePond.registerPlugin(FilePondPluginFileValidateSize);

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
            acceptedFileTypes: ['image/*', 'application/pdf', 'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ],
            labelFileTypeNotAllowed: 'Invalid file type. Only images, Excel, and PDF files are allowed',
            allowFileTypeValidation: true,
            allowFileSizeValidation: true,
        });

        const pond = FilePond.create(document.querySelector('input[name="gallery[]"]'), {
            chunkUploads: true,
            pdfPreviewHeight: 140,
            onaddfilestart: (file) => {
                isLoadingCheck();
            },
            onprocessfile: (files) => {
                $('#error_gallery_add').text("");
                isLoadingCheck();
            },
            onremovefile: () => {
                isLoadingCheck();
            },
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
    <script>
        var categorySelect = document.getElementById('category_id');
        var otherCategoryDiv = document.getElementById('other_category_div');
        var otherCategoryInput = document.getElementById('other_category');

        categorySelect.addEventListener('change', function() {
            if (categorySelect.options[categorySelect.selectedIndex].innerText === 'Other') {
                otherCategoryDiv.style.display = 'block';
                otherCategoryInput.required = true;
            } else {
                otherCategoryDiv.style.display = 'none';
                otherCategoryInput.required = false;
            }
        });
    </script>
@endsection
