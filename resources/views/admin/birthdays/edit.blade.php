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
                                <li class="breadcrumb-item"><a href="{{ url('/admin/birthday') }}">Manage Birthday Cards</a></li>
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
                        <h3 class="mb-0">Add Birthday Card</h3>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="editForm">
                                @php $encryptId = Crypt::encrypt($card_details->id); @endphp

                                <form method="post" id="add_department" action="{{ url('/admin/birthday/'.$encryptId) }}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" name="_method" value="patch">
                                    <input type="hidden" name="_method" value="patch">

                                    <div class="row">
                                        <div class="col-sm-2 profilePIC">
                                            <div class="form-group">

                                                <div class="input-file input-file-image">
                                                    <div class="input-file input-file-image">
                                                        <img id="uploaded_image" class="img-upload-preview" src="{{ url('/images/birthday_cards/'.$card_details->birthday_card) }}" width="200" height="200"/>
                                        
                                                        <input type="file" class="birthday_card form-control-file" name="birthday_card" value="" id="birthday_card" >
                                                    </div>
                                                </div>
                                            
                                        
                                                
                                                    
                                            </div>
                                        </div>

                                        <div class="col-sm-10 FormRIght">
                                        <div class=" form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <select class="form-control select_btn_icon" name="employee_id" id="employee_id" required>
                                                    <option value="">Select Employee</option>
                                                    @if(isset($users))
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" @if($card_details->user_id == $user->id) selected @endif>{{ $user->first_name }} {{ $user->last_name }} {{ $user->employee_code }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('employee_id')}}</span>
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="birthday_date" value="{{ \Carbon\Carbon::parse(@$card_details->birthday_date)->format('d-m-Y') }}" id="birthday_date" placeholder="Select Date" />
                                            </div>
                                        </div>
                                        
                                        <span class="text-danger">{{$errors->first('birthday_card')}}</span>

                                        <div class="">
                                            <button type="submit" class="btn btn-primary add-cards-btn" name="submit">Submit</button>
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
            $('#birthday_date').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                startDate: today
            });

            $(document).on('change', '#birthday_card', function () {
                var file = this.files[0];
                var fileType = file["type"];
                var validImageTypes = ["image/gif", "image/jpeg", "image/png"];
                if ($.inArray(fileType, validImageTypes) < 0) {
                    toaster('Error', 'Please upload jpeg,jpg, gif and png files only', 2000);
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
