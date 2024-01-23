@extends('layouts.page')

@section('content')
    <style>
        .td_align_center {
            text-align: center;
        }

        .card-body {
            width: 70%;
            margin-left: 15%;
        }

        .filter_container{
            display: flex;
            flex-flow: nowrap;
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
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Documents</li>
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
                        <h3 class="mb-0">Documents</h3>
                        <div class="filter_container">

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
                            @if (Auth::user()->role_id == 5)
                            @php
                            $sort_by = [
                                'Sort By' => 'Sort By',
                                'All' => 'All',
                                'Open' => 'Open',
                                'Single' => 'Single',
                                'Multiple' => 'Multiple',
                            ];
                        @endphp
                            <div class="commdiv ">
                                {!! Form::select('sort_by', $sort_by, $sort_by = request()->input('sort_by'), [
                                    'class' => 'form-control inventory_item_filter select_btn_icon',
                                    'rel' => 'severity',
                                    'id' => 'doc_sort_by_type',
                                    'style'=> 'margin: 0 0 0 -8.5rem; width: 8rem;'
                                ]) !!}
                            </div>
                            <div class="commdiv">
                                <input autocomplete="off" name="search" type="text" style="width: 13rem; margin-left: -1.5rem;"
                                    class="form-control inventory_item_filter" rel='name'
                                    placeholder="Search by document name" aria-describedby="button-addon6"
                                    id="doc_name_search_index" value="{{ request()->input('search') }}">
                            </div>
                                <div class="commdiv2 mx-2 col-sm-5">
                                    <input type="text" id="dates" autocomplete="off" placeholder="Date Range"
                                        name="dates" class="form-control">
                                </div>
                                <div class="commdiv2 mx-2">
                                    <div class="pull-right">
                                        <a href="{{ URL('document/create') }}"  id="add_document" class="btn btn-primary" title="Add Document">+ </a><br />
                                    </div>
                                </div>
                            </div>
                            @endif
                        <!--/.row-->
                    </div>
                    <div class="">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                {{-- <div>
                                <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                              </div> --}}
                                <div class="table-responsive item_inv" id="dynamicContent">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="td_align_center">Sr no</th>
                                                <th style=" width: 14rem; ">Documents</th>
                                                <th class="td_align_center">Password Type</th>
                                                <th class="td_align_center">Date</th>
                                                <th class="td_align_center">Employee Details</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = ($document->currentPage() - 1) * $document->perPage() + 1; @endphp
                                            @foreach ($document as $val)
                                                <tr>
                                                    <td class="td_align_center">{{ $counter }}</td>
                                                    <td ><i class="fa fa-file-pdf-o" style="font-size:24px;color:red"> </i>
                                                        {{ $val->documents }}</td>
                                                    <td class="td_align_center">{{ $val->protected_file }}</td>
                                                    <td class="td_align_center">{{ $val->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                                                    <td class="td_align_center"> <a
                                                            href="{{ action('DocumentController@document_list', $val->id) }}"
                                                            target="_blank"> <i class="fa-solid fa-info-circle fa-xl"
                                                                aria-hidden="true"></i></a> </td>
                                                    <td>
                                                        <a href="{{ url('display_pdf/' . $val->id) }}" target="_blank"
                                                            title="view">
                                                            <i class="fas fa-eye"></i> </a>
                                                        @if (Auth::user()->role_id == 5)
                                                            <a href="{{ route('document.edit', $val->id) }}" title="edit">
                                                                <i class="fas fa-edit"></i> </a>
                                                            <a onclick="return confirm('Are you sure you want to delete this document?')"
                                                                href="{{ url('document/delete', $val->id) }}"
                                                                title="delete">
                                                                <i class="fas fa-trash"></i> </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php $counter++ @endphp
                                            @endforeach
                                            @if ($noRecord)
                                                <tr>
                                                    <td colspan="9" class="text-center"><b>No record found</b></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="pagination">
                                        {{ $document->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
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

    <div id="document_add" class="modal fade" tabindex="-1" style="margin-top:150px:">
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
        $('#doc_sort_by_type option:first-child').hide();
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
            var url = 'document' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' + picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

        });
    </script>

    <script>
        $("#doc_name_search_index").on("keyup", function () {
            $(".loader_body").css("display", "block");
            const search = $(this).val();
            var params = new URLSearchParams(window.location.search);
            params.set("search", search);
            var url = "document" + "?" + params.toString();
            window.location.href = url;
        });

        $("#doc_sort_by_type").on("change", function () {
            const sort_by = $(this).val();
            var params = new URLSearchParams(window.location.search);
            params.set("sort_by", sort_by);
            var url = "document" + "?" + params.toString();
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
