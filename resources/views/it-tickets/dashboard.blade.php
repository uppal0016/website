@extends('layouts.page')

@section('content')
    <?php date_default_timezone_set('Asia/Kolkata'); ?>
    <style type="text/css">
        .clockpicker-popover {
            z-index: 999999 !important;
        }

        .swal-text {
            font-size: 15px;
        }

        i.fa-solid.fa-bell {
            font-size: 1.5rem;
            position: absolute;
            right: 0;
            color: orange;
        }
    </style>
    <!-- Header -->
    <!-- Header -->
    <link rel="stylesheet" href="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.css">
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-6">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item">Dashboard</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="max-260 ml-auto">
                        <input type="text" id="dates" autocomplete="off" placeholder="Date Range" name="dates"
                            class="form-control">
                    </div>
                </div>

                {{-- Card Heading --}}
                <div class="heading mb-3">
                    <div class="card_heading">
                        <h5 style="font-size: 2rem; color: white;">Tickets Summary </h5>
                    </div>
                </div>

                <!-- Card stats -->
                <div class="row">
                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="All">
                                <a href="{{ url('it-tickets/list?status=All') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-2">Total Tickets</h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                    {{ $allTicketsCount ? $allTicketsCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                    <i class="total_count"style="font-size: 100%;"><span>ALL</span></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Open">
                                <a href="{{ url('it-tickets/list?status=Open') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-2">Open Tickets</h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                    {{ $openCount ? $openCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                    <i class="fa-regular fa-folder-open"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Reopen">
                                <a href="{{ url('it-tickets/list?status=Reopen') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-2">Reopen Tickets</h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                    {{ $reopenCount ? $reopenCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                    <i class="fa-solid fa-arrow-rotate-right"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Open" name="high_severity" class="high_severity">
                                <a href="{{ route('it-tickets.it-tickets.list', 'high_severity') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0">High Severity Tickets
                                                </h5>
                                                <span class="h2 font-weight-bold mb-0">{{ $highCount ? $highCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                    <i class="ni ni-active-40"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Open">
                                    <a href="{{ route('it-tickets.it-tickets.list', 'medium_severity') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Medium Severity
                                                    Tickets</h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                    {{ $mediumCount ? $mediumCount : '0' }} </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                    <i class="ni ni-chart-pie-35"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Open">
                                    <a href="{{ route('it-tickets.it-tickets.list', 'low_severity') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-0">Low Severity Tickets
                                                </h5>
                                                <span class="h2 font-weight-bold mb-0"> {{ $lowCount ? $lowCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                    <i class="ni ni-money-coins"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Closed">
                                <a href="{{ url('it-tickets/list?status=Closed') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-2">Closed Tickets</h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                    {{ $closedCount ? $closedCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                                                    <i class="fa-regular fa-circle-xmark"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <label for="Inprogress">
                                <a href="{{ url('it-tickets/list?status=InProgress') }}">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <h5 class="card-title text-uppercase text-muted mb-2">In Progress Tickets
                                                </h5>
                                                <span class="h2 font-weight-bold mb-0">
                                                    {{ $inProgressCount ? $inProgressCount : '0' }}
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div
                                                    class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                    <i class="fa-solid fa-spinner"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </label>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card card-stats">
                            <!-- Card body -->
                            <a href="{{ url('it-tickets/list?turnaround_time') }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-2">Greater then 4 hours</h5>
                                            <span class="h2 font-weight-bold mb-0">
                                                {{ $turnaroundCount ? $turnaroundCount : '0' }}
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div
                                                class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                <i class="fa-solid fa-greater-than" style="font-size: 85%;">
                                                    <span>4</span></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
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
            var url = 'dashboard' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' + picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

        });
    </script>
@endsection
