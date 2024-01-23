@extends('layouts.page')
@section('content')
    <style>
        span#resume-error {
            color: red !important;
            font-size: 14px;
            font-weight: 600;
        }
        
        @media (max-width: 1190px) and (min-width:576px){
            .form-group.col-sm-4 {
                max-width: 27rem;
            }
        }

        @media (max-width: 1305px) and (min-width:1200px){
            .form-group.col-sm-4 {
                max-width: 27rem;
            }
        }

        @media (max-width: 360px){
            .fa.fa-asterisk.mx-1.vacancy {
                position: relative;
                top: -6px;
                left: -3px;
            }
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
                                <li class="breadcrumb-item active" aria-current="page"><a href="/reference/list">Rapper</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Rapper</li>
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
                        <h3 class="mb-0">Edit Rapper</h3>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="addForm">
                                {!! Form::open([
                                    'action' => 'ReferenceController@update',
                                    'method' => 'POST',
                                    'id' => 'edit_reference',
                                    'enctype' => 'multipart/form-data',
                                ]) !!}
                                <div class="FormRIght">
                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">First Name <i class="fa fa-asterisk mx-1"
                                                style="font-size:6px;color:red"></i></label>
                                        <input type="hidden" name="reference_id" value="{{ $reference->id }}">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="first_name" value="{{ $reference->first_name }}"
                                                id="first_name" placeholder="Enter first name" class="form-control"
                                                autocomplete="off" maxlength="50" />
                                        </div>

                                        <span class="text-danger">
                                            {{ $errors->first('first_name') }}
                                        </span>
                                    </div>


                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Last Name <i class="fa fa-asterisk mx-1"
                                                style="font-size:6px;color:red"></i></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="last_name" value="{{ $reference->last_name }}"
                                                id="last_name" placeholder="Enter last name" class="form-control"
                                                autocomplete="off" maxlength="50" />
                                        </div>
                                        <span class="text-danger">
                                            {{ $errors->first('last_name') }}
                                        </span>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Mobile Number<i class="fa fa-asterisk mx-1"
                                                style="font-size:6px;color:red"></i></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="mobile_number"
                                                value="{{ $reference->mobile_number }}" id="mobile_number"
                                                placeholder="Enter Mobile number" class="form-control" autocomplete="off"
                                                maxlength="10" />
                                        </div>
                                        <span class="text-danger">
                                            {{ $errors->first('mobile_number') }}
                                        </span>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Department<i class="fa fa-asterisk mx-1"
                                                style="font-size:6px;color:red"></i></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <select name="department" class="form-control select_btn_icon">
                                                <option value="" disable="true" style="font-weight: bold;">--Select
                                                    Department--</option>
                                                @if ($dept)
                                                    @foreach ($dept as $key => $value)
                                                        <option value="{{ $value->id }}"
                                                            {{ $reference->department == $value->id ? 'selected' : '' }}>
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <span class="text-danger">
                                            {{ $errors->first('department') }}
                                        </span>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Experience (in years)<i class="fa fa-asterisk mx-1"
                                                style="font-size:6px;color:red"></i></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="experience" value="{{ $reference->experience }}"
                                                id="experience" placeholder="Experience" class="form-control"
                                                autocomplete="off" maxlength="5" />
                                        </div>
                                        <span class="text-danger">
                                            {{ $errors->first('experience') }}
                                        </span>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">Please upload resume<i class="fa fa-asterisk mx-1"
                                                style="font-size:6px;color:red"></i></label>
                                        <div class="add-resume">
                                            @if ($reference->resume)
                                                <input class="form-control-file valImage" id="resumeInput" name="resume" type="file" accept=".pdf, .doc, .docx, .odt" style="display: none">
                                                <div id="resumeContainer" style="position: relative; margin-bottom: 3rem;">
                                                    <a href="{{ Storage::url('resumes/' . $reference->resume) }}" target="blank" download>{{ $reference->resume }}</a>
                                                    <button id="removeResume" type="button" class="btn btn-sm btn-danger ml-2" style="position: absolute; left: -2%; top: 1.75rem;">Remove</button>
                                                </div>
                                            @else
                                                <input class="form-control-file valImage" id="resumeInput" name="resume" type="file" accept=".pdf, .doc, .docx, .odt">
                                            @endif
                                        </div>
                                        <span class="text-danger" id="resume-error">
                                            {{ $errors->first('resume') }}
                                        </span>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="exampleInputEmail1">How did you get to know about the vacancy? <i
                                                class="fa fa-asterisk mx-1" style="font-size:6px;color:red"></i></label>
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input type="text" name="reference_platform"
                                                value="{{ $reference->reference_platform }}" id="reference_platform"
                                                placeholder="Platform Name" class="form-control" autocomplete="off"
                                                maxlength="50" />
                                        </div>
                                        <span class="text-danger">
                                            {{ $errors->first('reference_platform') }}
                                        </span>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary  add-user-btn" name="submit"
                                                id="submitbBtn">Submit</button>
                                        </div>
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
        document.getElementById("resumeInput").addEventListener("change", function() {
            var fileInput = this;
            var allowedExtensions = ["pdf", "doc", "docx", "odt"];
            var file = fileInput.files[0];
            var fileName = file.name;
            var fileExtension = fileName.split(".").pop().toLowerCase();

            // Check if the file extension is allowed
            if (!allowedExtensions.includes(fileExtension)) {
                document.getElementById("resume-error").textContent =
                    "Please upload only PDF, DOC, DOCX or ODT files.";
                fileInput.value = ""; // Clear the file input
            } else {
                document.getElementById("resume-error").textContent = "";
            }
        });

        $(document).ready(function() {
            $('.valImage').on('change', function() {
                var fileSize = this.files[0].size;
                var maxSize = 2 * 1000 * 1000;

                if (fileSize > maxSize) {
                    $(this).val('');
                    alert('The image file size should not exceed 2MB.');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var isResumePresent = {{ $reference->resume ? 'true' : 'false' }};
            if (isResumePresent) {
                $('#resumeInput').hide();
            }
        });

        $('#removeResume').on('click', function() {
            $('#resumeInput').val('').show();
            $('#resumeContainer').remove();
        });
    </script>
@endsection
