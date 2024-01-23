@extends('layouts.page')

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
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('/document') }}">Documents</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Password History</li>
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
                        <h3 class="mb-0">Password history</h3>
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
                            @if (Auth::user()->role_id == 5)
                                <div class="commdiv2 mx-2">
                                    <input type="text" id="dates" autocomplete="off" placeholder="Date Range"
                                        name="dates" class="form-control">
                                </div>
                            @endif
                        </div>
                        <!--/.row-->
                    </div>
                    <div class="card-body">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                <div class="table-responsive item_inv" id="dynamicContent">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Sr no</th>
                                                <th>User Name</th>
                                                <th>Emp Code</th>
                                                <th>Date</th>
                                                <th>Password Genrated</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            @if (count($document_password) >0)
                                           @foreach ($document_password as $value  )
                                           <tr>                                               
                                                <td>{{ $counter}}</td>
                                               <td>{{$value->user_name}}</td>
                                               <td>{{$value->emp_code}}</td>
                                               <td>{{ $value->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}/td>
                                               <td>{{$value->password_count}}</td>
                                               <td> <a href="{{ action('DocumentController@password_details', $value->user_id) }}" > <i class="fa-solid fa-info-circle fa-xl"
                                                aria-hidden="true"></i></a> </td>
                                                <td>
                                            </tr>
                                            @php $counter ++; @endphp
                                           @endforeach
                                           @else
                                           <tr>
                                            <td colspan="9" class="text-center"><b>No record found</b></td>
                                        </tr>
                                           @endif
                                        
                                        </tbody>
                                    </table>
                                    <div class="pagination">
                                        {{ $document_password->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
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
            var url = 'password_history' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' + picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

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
