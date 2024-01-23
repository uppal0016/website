@extends('layouts.page')

@section('content')

    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-8 col-7">
                        <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ url('/admin/festival') }}">Manage Festival Cards</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add</li>
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
                        <h3 class="mb-0">Add Festival Card</h3>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="editForm">
                                <form method="post" id="add_department" action="{{ url('/admin/festival/store') }}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <div class="FormRIght">
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="title" id="title" placeholder="Enter Title" />
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('title')}}</span>
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="festival_date" id="festival_date" placeholder="Select Date" />
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('festival_date')}}</span>
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="file" class="form-control festival_card" name="festival_card" id="festival_card" required>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('festival_card')}}</span>

                                        <div class="">
                                            <button type="submit" class="btn btn-primary add-cards-btn" name="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
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
        var today = new Date();
        $(document).ready(function() {
            $('#festival_date').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                startDate: today
            });

            $(document).on('change', '#festival_card', function () {
                var file = this.files[0];
                var fileType = file["type"];
                var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
                if ($.inArray(fileType, validImageTypes) < 0) {
                    alert('Please upload jpeg,jpg, gif and png files only');
                    $(this).val('');
                } else {
                    /*var reader = new FileReader();
                    reader.onload = function () {
                        $('.img-upload-preview').attr('src', reader.result);
                    }
                    reader.readAsDataURL(file);*/
                }
            });



        });
    </script>
@endsection
