@extends('layouts.page')

<style>
    .table .password-cell {
        max-width: 100px;
        /* Adjust the max-width as needed */
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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
                                <li class="breadcrumb-item active" aria-current="page"><a href="/document">Documents</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page"><a
                                        href="/document_management">Document
                                        Management</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Employee Details</li>
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
                        <h3 class="mb-0">Employee Details</h3>
                        <div class="row">
                            @if (Auth::user()->role_id == 5)
                                <div class="commdiv">
                                    <input autocomplete="off" name="search" type="text" style=" width: 13rem; margin-left: -2.5rem; "
                                        class="form-control inventory_item_filter" rel='name'
                                        placeholder="Search by employee name" aria-describedby="button-addon6"
                                        id="document_details" value="{{ request()->input('search') }}">
                                </div>
                            @endif

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
                            {{-- @if (Auth::user()->role_id == 5)
                                <div class="commdiv2 mx-2">
                                    <input type="text" id="dates" autocomplete="off" placeholder="Date Range"
                                        name="dates" class="form-control">
                                </div>
                            @endif --}}
                        </div>
                        <!--/.row-->
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card-header d-flex justify-content-between align-items-center border-0">
                                <h3 class="mb-0">Total Employees : {{ $documentCount }} </h3>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                <div class="table-responsive item_inv" id="dynamicContent">
                                    @if ($documents->isNotEmpty())
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sr no</th>
                                                    <th>Employee name</th>
                                                    <th>Emp Code</th>
                                                    <th>Last Page</th>
                                                    <th>read Pages </th>
                                                    <th>Max time page </th>
                                                    <th>Total Pages </th>
                                                    <th>Request Document</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $counter = ($documents->currentPage() - 1) * $documents->perPage() + 1; @endphp
                                                @foreach ($documents as $value)
                                                    <tr>
                                                        <td>{{ $counter }}</td>
                                                        <td>{{ $value->user_name }}</td>
                                                        <td>{{ $value->emp_code }}</td>
                                                        
                                                        <td>{{ $value->last_page }}</td>
                                                        <td>{{ $value->pages }}</td>
                                                        <td>{{ $value->page_no }}</td>
                                                        <td><?php
                                                        $path = public_path('images/document/' . $documentName);
                                                        $pdf = file_get_contents($path);
                                                        $number = preg_match_all('/\/Page\W/', $pdf, $mdumy);
                                                        echo $number;
                                                        ?></td>
                                                        <td> 
                                                            @if ($value->request_type !== null)
                                                            {{$value->request_type}}                                                                
                                                            @else
                                                                Not Requested
                                                            @endif
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
                                        {{ $documents->appends(['page' => Request::get('page'), 'section' => 'new_manage_document', '_token' => csrf_token()])->render() }}
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

    {{-- <div id="myModal" class="modal fade" tabindex="-1" style="margin-top:150px:">
        <input type="hidden" id="leaveid" value="">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Password Details</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Password</th>
                                    <th>Created at</th>
                                </tr>
                            </thead>
                            <tbody id="doc_password_detail">

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@section('script')
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

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
            var url = 'password_history' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' +
                picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

        });
    </script>
    <script>
        function openMyModal(document_id, user_id) {

            $.ajax({
                url: "/get-document-password-details/" + document_id + "/" + user_id,
                method: "GET",
                success: function(response) {
                    if (response.success) {
                        // Show the modal
                        $('#myModal').modal('show');

                        // Clear the existing table rows
                        $('#doc_password_detail').empty();

                        // Generate table rows and append them to the table body
                        response.data.forEach(function(document, index) {
                            var createdAt = new Date(document.created_at).toLocaleDateString();
                            var rowHtml = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td class="password-cell">' + document.password + '</td>' +
                                '<td>' + createdAt + '</td>' +
                                '</tr>';
                            $('#doc_password_detail').append(rowHtml);
                        });
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>

    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
