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
                                <li class="breadcrumb-item"><a href="{{ url('/admin/birthdays') }}">Manage Festival Cards</a></li>
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
                        <h3 class="mb-0">Edit Festival Card</h3>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="editForm">
                                @php $encryptId = Crypt::encrypt($card_details->id); @endphp

                                <form method="post" id="add_department" action="{{ url('/admin/festival/'.$encryptId) }}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" name="_method" value="patch">
                                    <input type="hidden" name="_method" value="patch">

                                    <div class="row">
                                        <div class="col-sm-2 profilePIC">
                                            <div class="form-group">

                                                <div class="input-file input-file-image">
                                                    <div class="input-file input-file-image">
                                                    <img id="uploaded_image" class="img-upload-preview"  src="{{ url('/images/festival_cards/'.$card_details->festival_card) }}" width="200" height="200"/>
                                        
                                                    <input type="file" class="festival_card form-control-file" name="festival_card" value="" id="festival_card" >
                                                    </div>
                                                </div>
                                            
                                        
                                                
                                                    
                                            </div>
                                        </div>
                                    <div class="FormRIght col-sm-10">
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="title" id="title" placeholder="Enter Title" value="{{ @$card_details->title }}"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="festival_date" value="{{ \Carbon\Carbon::parse(@$card_details->festival_date)->format('d-m-Y') }}" id="festival_date" placeholder="Select Date" />
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('festival_card')}}</span>

                                        <div class="">
                                            <button type="submit" class="btn btn-primary add-cards-btn" name="submit">Update</button>
                                        </div>
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
                console.log(fileType);
                var validImageTypes = ["image/gif", "image/jpeg", "image/png"];

                if ($.inArray(fileType, validImageTypes) < 0) {
                    alert('Please upload jpeg,jpg, gif and png files only');
                    $(this).val('');
                } else {
                    var reader = new FileReader();
                    reader.onload = function () {
                        $('#uploaded_image').attr('src', reader.result);
                    }
                    reader.readAsDataURL(file);
                }
            });



        });
    </script>
@endsection
