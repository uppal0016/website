@extends('layouts.page')

@section('content')
<style>
    .td_align_center {
        text-align: center;
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
                                <li class="breadcrumb-item active" aria-current="page"><a href="/document">Documents</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Document Details</li>
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
                        <div class="row">
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
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="td_align_center">Sr no</th>
                                                <th class="td_align_center">Employee Name</th>
                                                <th class="td_align_center">Last Page Read</th>
                                                <th class="td_align_center">Last Time Document Open</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $counter = 1; @endphp
                                            @foreach ($documentRead as $val)
                                                <tr>
                                                    <td class="td_align_center">{{ $counter }}</td>
                                                    <td class="td_align_center">{{ Auth::user()->where('id', $val->user_id)->value(DB::raw("CONCAT(first_name, ' ', last_name)")) }}
                                                    </td>
                                                    <td class="td_align_center">{{ $val->last_page }}</td>
                                                    <td class="td_align_center">{{ $val->updated_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
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
                                    {{ $documentRead->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
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

    <div id="myModal" class="modal fade" tabindex="-1" style="margin-top:150px:" role="dialog" aria-labelledby="passwordModalLabel" aria-hidden="true">
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
                                        <button type="submit" class="btn btn-primary">Submit</button>
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
            const currentURL = window.location.pathname;
            const urlParts = currentURL.split('/');
            const id = urlParts[urlParts.length - 1]; 
            var url = '/document_list/'+id+'?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' + picker
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
