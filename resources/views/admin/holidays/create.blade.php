@extends('layouts.page')

@section('content')

    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-8 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ url('/admin/holiday') }}">Manage Holiday</a></li>
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
                    <div class="card-header border-0">
                        <h3 class="mb-0">Add Holiday</h3>
                    </div>
               
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="editForm">
                                <form method="post" id="add_holiday" action="{{ url('/admin/holiday/store') }}" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <div class="FormRIght">
                                         &nbsp                                         
                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="title" id="title" placeholder="Enter Holiday Title"autocomplete="off"  />
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('title')}}</span>

                                        <div class="form-group">
                                            <div class="input-group input-group-merge input-group-alternative">
                                                <input type="text" class="form-control" required name="date" id="date" placeholder="Select Date" autocomplete="off" />
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('date')}}</span>                                
                                        <div class="">
                                            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        var today = new Date();
        $(document).ready(function() {
            $('#date').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                startDate: today,
                orientation: "top",
            });
        });

        $("#add_holiday").validate({
            rules: {
                'name': {
                    required: true,
                    normalizer: function(value) {
                        return $.trim(value)
                    }
                },
                'date': {
                    required: true
                }
            },
            errorPlacement: function (error, element) {
                $(element).closest('.form-group').addClass('has-error');
                if ($(element).next().hasClass('help-block')) {
                    $(element).next().remove();
                }
                $(element).closest('.input-group').after(error);
            }
        });
    </script>
@endsection
