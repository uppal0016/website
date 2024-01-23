@extends('layouts.page')

<style>
    .horizontal-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .td_align_center{
        text-align: center;
    }
</style>
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a
                                        href="{{ url('/document') }}">Documents</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Active Document Details</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center border-0">
                        <h3 class="mb-0">Active Document Details</h3>
                        <div class="row">

                            {{-- @if ($check == '')
                            <div class="col-md-4">
                              <div class="pull-left">
                            <a href="#" class="btn btn-primary request_password_genrate " data="request">Request </a><br/>
                              </div>
                            </div>
                            @endif --}}
                            &nbsp
                            &nbsp
                            &nbsp
                            @php
                                $sort_by = [
                                    '' => 'Sort By',
                                    'all' => 'All',
                                    'new' => 'Newest',
                                    'old' => 'Oldest',
                                ];
                            @endphp
                            <div class="horizontal-container">
                                <div class="commdiv ">
                                    {!! Form::select('sort_by', $sort_by, $sort_by = request()->input('sort_by'), [
                                        'class' => 'form-control inventory_item_filter select_btn_icon',
                                        'rel' => 'severity',
                                        'id' => 'doc_sort_by',
                                        'style'=> 'margin: 0 0 0 0.5rem; width: 7rem;'
                                    ]) !!}
                                </div>
                                <div class="commdiv">
                                    <input autocomplete="off" name="search" type="text" style=" width: 13rem; margin-left: -2.5rem; "
                                        class="form-control inventory_item_filter" rel='name'
                                        placeholder="Search by document name" aria-describedby="button-addon6"
                                        id="doc_name_search" value="{{ request()->input('search') }}">
                                </div>
                                <div class="commdiv2 mx-2" style="width: 18rem;">
                                    <input type="text" id="dates" autocomplete="off" placeholder="Date Range"
                                        name="dates" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                <div class="table-responsive item_inv" id="dynamicContent">
                                    @if ($documents->isNotEmpty())
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th class="td_align_center">Sr no</th>
                                                    <th style="width: 16rem;">Document Name</th>
                                                    <th class="td_align_center">User Count</th>
                                                    <th class="td_align_center">Date</th>
                                                    <th class="td_align_center">Most Read Page</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $counter = ($documents->currentPage() - 1) * $documents->perPage() + 1; @endphp
                                                @foreach ($documents as $value)
                                                    <tr>
                                                        <td class="td_align_center">{{ $counter }}</td>
                                                        <td><a target="_blank"
                                                                href="{{ url('display_pdf/' . $value->document_id) }}">{{ $value->document_name }}</a>
                                                        </td>
                                                        <td class="td_align_center"><a
                                                                href="{{ action('DocumentController@document_users_details', $value->document_id) }}">{{ $value->user_count }}</a>
                                                        </td>
                                                        <td class="td_align_center">{{ $value->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                                                        <td class="td_align_center"><a target="_blank"
                                                                href="{{ url('display_pdf/' . $value->document_id) }}?page_no={{ $value->page_no }}">{{ $value->page_no }}</a>
                                                        </td>

                                                    </tr>
                                                    @php $counter ++; @endphp
                                                @endforeach


                                            </tbody>
                                        </table>
                                    @else
                                        <h5 colspan="3" class="text-center" style="font-size:13px">No Record
                                            Found.</h5>
                                    @endif
                                    <div class="pagination">
                                        {{ $documents->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
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

    <div id="myModal" class="modal fade" tabindex="-1" style="margin-top:150px:">
        <input type="hidden" id="leaveid" value="">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="{{ url('documentview') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="password" value="" name="password" class="form-control"
                                            placeholder="Enter Document Password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@section('script')
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('#doc_sort_by option[value=""]').hide();

        const urlParams = new URLSearchParams(window.location.search);
        const startDate = urlParams.get('start_date');
        const endDate = urlParams.get('end_date');

        $('input[name="dates"]').daterangepicker({
            startDate: startDate ?  moment(startDate).format('DD/MM/YYYY') : moment().format('DD/MM/YYYY'),
            endDate: endDate ? moment(endDate).format('DD/MM/YYYY') : moment().format('DD/MM/YYYY'),
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
            var url = 'document_management' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') +
                '&end_date=' +
                picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

        });
    </script>

    <script>
        $("#doc_sort_by").on("change", function () {
            const sort_by = $(this).val();
            var params = new URLSearchParams(window.location.search);
            params.set("sort_by", sort_by);
            var url = "document_management" + "?" + params.toString();
            window.location.href = url;
        });


        $("#doc_name_search").on("keyup", function () {
            $(".loader_body").css("display", "block");
            const search = $(this).val();
            var params = new URLSearchParams(window.location.search);
            params.set("search", search);
            var url = "document_management" + "?" + params.toString();
            window.location.href = url;
        });
    </script>

    <script>
        function openMyModal() {
            $('#myModal').modal('show');
        }
    </script>

    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
