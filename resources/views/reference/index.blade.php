@extends('layouts.page')
<style>
    a.action_btn {
        background: white !important;
        padding: 0px !important;
    }

    #successMessage {
        color: #fff;
        background-color: #4fd69c;
        margin-left: 1%;
        position: absolute;
        top: -42%;
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-color: #4fd69c;
        border: 1px solid transparent;
        border-radius: 0.375rem;
    }

    #commentsError {
        font-weight: 600;
        font-size: 14px;
        margin-top: 5px;
    }
</style>
@section('content')
    <div class="header bg-primary pb-6">
        <span id="successMessage" style="display: none;"></span>
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Rapper</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid dsr-detail-pg mt--6">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Rapper</h3>
                    </div>

                    <div class="card-boddy p-0">
                        <div class="formRIght py-3 px-4">
                            @if (Auth::user()->email == 'chandni.rana@talentelgia.in' ||
                                    Auth::user()->email == 'chahat.malhotra@talentelgia.in' ||
                                    Auth::user()->email == 'nisha.kaur@talentelgia.in')
                                <div class="commdiv4">
                                    <div class="commdiv">
                                        <input autocomplete="off" name="name_search" type="text"
                                            class="form-control reference_name_search" rel='name'
                                            placeholder="Search by rapper name" aria-describedby="button-addon6"
                                            id="reference_name_search" value="{{ request()->input('name_search') }}">
                                    </div>
                                </div>

                                <div class="commdiv">
                                    <input autocomplete="off" name="technology_search" type="text"
                                        class="form-control reference_technology_search" rel='name'
                                        placeholder="Search by technology" aria-describedby="button-addon6"
                                        id="reference_technology_search"
                                        value="{{ request()->input('technology_search') }}">
                                </div>

                                <div class="commdiv2 mx-2">
                                    <input type="text" id="dates" autocomplete="off" placeholder="Date Range"
                                        name="dates" class="form-control">
                                </div>
                            @endif
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                           

                            <div class="lastBtn">
                                <div class="pull-right">
                                    <a href="{{ URL('reference/create') }}"
                                        class="btn btn-primary add-user-btn add-topic-btn1">+ </a><br />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:0 !important">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                <div class="table-responsive item_inv" id="dynamicContent">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Rapper Name</th>
                                                <th>Department</th>
                                                <th>Rapper Date</th>
                                                <th>Interview Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!$reference->isEmpty())
                                                @foreach ($reference as $index => $value)
                                                <tr>
                                                    <td>{{ $value->reference_name }}</td>
                                                    <td>{{ $value->department }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($value->created_at)->tz('Asia/Kolkata')->format('Y-m-d') }}</td>
                                                    @if ($value->rejection_reason === null && $value->rounds === null)
                                                        <td>New</td>
                                                    @elseif($value->rejection_reason !== null && $value->rounds === null)     
                                                        <td style=" color: red; font-weight: 600; ">Not Suitable</td>
                                                    @elseif($value->rejection_reason === null && $value->rounds !== null)
                                                        @if ($value->recommendation === 'No')
                                                            <td style=" color: red; font-weight: 600; ">Not Suitable</td>
                                                        @elseif ($value->interview_status === 'Cancelled')
                                                            <td style=" color: red; font-weight: 600; ">{{$value->interview_status}}</td>
                                                        @elseif ($value->recommendation === 'Candidate Not Available')
                                                            <td style=" color: red; font-weight: 600; ">{{$value->recommendation}}</td>
                                                        @else 
                                                            <td style=" color: green; font-weight: 800; ">{{$value->interview_status}} (Round - {{$value->rounds}}) </td>
                                                        @endif
                                                    @elseif($value->rejection_reason !== null && $value->rounds !== null)
                                                        @if ($value->recommendation === 'No')
                                                                <td style=" color: red; font-weight: 600; ">Not Suitable</td>
                                                        @elseif ($value->interview_status === 'Cancelled')
                                                                <td style=" color: red; font-weight: 600; ">{{$value->interview_status}}</td>
                                                        @elseif ($value->recommendation === 'Candidate Not Available')
                                                                <td style=" color: red; font-weight: 600; ">{{$value->recommendation}}</td>
                                                        @endif
                                                    @endif
                                                    <td>
                                                        @if($value->interview_status === "Pending")
                                                            <a href="{{ url('/reference/edit', ['id' => $value->id]) }}" title="Edit Rapper" id="edit_reference"> 
                                                                <i class="fa fa-edit"></i>
                                                            </a>&nbsp
                                                        @endif

                                                        {{-- @if($value->interview_status === "Pending")
                                                            <a class="action_button" href="{{ action('ReferenceController@delete', $value->id) }}" title="Delete Rapper" onclick="return confirm('Are you sure you want to delete this rapper?')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        @endif --}}

                                                        @if ($value->interview_status === "Rejected" || $value->recommendation === 'No')
                                                            <a class="action_btn view_rejection_reason" data-reference-id="{{ $value->id }}" title="View Not Suitable Reason" >
                                                                {{-- <img src="{{asset('images/rapper_icon/edit.png')}}" alt="View Rejection Reason" > --}}
                                                                <i class="fa-solid fa-eye" style="color: blue; font-size: 14px; position: relative; right: 7px;"></i>
                                                            </a>
                                                        @elseif ($value->interview_status === "Cancelled")
                                                            <a class="action_btn view_cancelled_reason" data-reference-id="{{ $value->id }}" title="View Cancelled Reason" >
                                                                <i class="fa-solid fa-eye" style="color: blue; font-size: 14px; position: relative; right: 7px;"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="9" class="text-center"><b>No record found</b></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table> 
                                    <div class="pagination">
                                        {{ $reference->appends(['page' => Request::get('page'), 'name_search' => Request::get('name_search'), 'technology_search' => Request::get('technology_search'), 'start_date' => Request::get('start_date'), 'end_date' => Request::get('end_date')])->render() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card footer -->
                </div>
            </div>
        </div>
    </div>

    <div id="myModal3" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalContent3">
                    <div class="modal-header">
                        <h3 class="modal-title">Not Suitable Reason</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="comments mb-4"></div>
                        <div class="employee_id mb-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div id="myModal4" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="modalContent3">
                    <div class="modal-header">
                        <h3 class="modal-title">Cancel Reason</h3>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="cancel_reason mb-4"></div>
                        <div class="cancel_employee_id mb-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@section('script')
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <script>
        $('input[name="dates"]').daterangepicker({
            startDate: moment().format('DD/MM/YYYY'),
            endDate: moment().format('DD/MM/YYYY'),
            maxDate: moment().format('DD/MM/YYYY'),
            opens: 'left',
            locale: {
                format: 'DD/MM/YYYY',
                cancelLabel: 'Clear'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')]
            }
        });

        $('#dates').on('apply.daterangepicker', function(ev, picker) {
            var url = 'list' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' + picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

        });
    </script>

    <script>
        // display rejected reason
        $(document).ready(function() {
            $('.view_rejection_reason').on('click', function() {
                const referenceId = $(this).data('reference-id');
                $.ajax({
                    type: 'GET',
                    url: 'rejection_reason/' + referenceId,
                    success: function(response) {
                        $('#modalContent3 .comments').html('<h4>Not Suitable Reason : </h4>' + response.rejection_reason);
                        $('#modalContent3 .employee_id').html('<h4>Reason Given By : </h4>' + response.rejected_employee_id);

                        // Show the modal
                        $('#myModal3').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            });
        });
    </script>

    <script>
        // display cancel reason
        $(document).ready(function() {
            $('.view_cancelled_reason').on('click', function() {
                const referenceId = $(this).data('reference-id');
                $.ajax({
                    type: 'GET',
                    url: 'cancel_reason/' + referenceId,
                    success: function(response) {
                        $('#myModal4 .cancel_reason').html('<h4>Cancelled Reason : </h4>' + response.cancel_reason);
                        $('#myModal4 .cancel_employee_id').html('<h4>Reason Given By : </h4>' + response.cancel_employee_id);

                        // Show the modal
                        $('#myModal4').modal('show');
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            });
        });
    </script>

<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection