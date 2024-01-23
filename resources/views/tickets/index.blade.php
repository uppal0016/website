@extends('layouts.page')
<style>
    .status-td {
        display: flex;
        align-items: center;
    }

    .status-td img {
        margin-right: 5px;
        width: 20px;
    }

    #harmony_ticket_category option:first-child {
        display: none;
    }

    #ticket_status option:first-child {
        display: none;
    }

    #harmony_ticket_category {
        padding-right: 10px !important;
        width: 18rem;
    }

    #ticket_status {
        padding-right: 10px !important;
    }
</style>
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Harmony
                                        Tickets
                                </li>
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
                        <h3 class="mb-0">Tickets</h3>
                    </div>

                    <div class="card-boddy p-0">
                        <div class="formRIght py-3 px-4">
                            <div class="commdiv4">
                                @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                                        Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                                        Auth::user()->email == 'rohit.gupta@talentelgia.in')
                                    <div class="commdiv">
                                        <input autocomplete="off" name="search" type="text"
                                            class="form-control inventory_item_filter" rel='name'
                                            placeholder="Search by employee name" aria-describedby="button-addon6"
                                            id="ticket_name_search" value="{{ request()->input('search') }}">
                                    </div>
                                @endif
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </div>

                            <div class="commdiv2 mx-2">
                                <input type="text" id="dates" autocomplete="off" placeholder="Date Range"
                                    name="dates" class="form-control">
                            </div>
                            
                            

                            @php $categories = Helper::getTablaDataForDropDown('harmony_tickets_categories', 'name', 'asc');
                                $array1 = ['' => 'Select Category', 'All' => 'All'];
                                $categories = $array1 + $categories;
                            @endphp

                            <div class="commdiv3">
                                {!! Form::select('category_id', $categories, $selectedCategory = request()->input('category_id'), [
                                    'class' => 'form-control inventory_category_filter select_btn_icon',
                                    'rel' => 'category_id',
                                    'id' => 'harmony_ticket_category',
                                ]) !!}
                            </div>

                            <div class="commdiv" style="width: 8rem">
                                {!! Form::select(
                                    'status',
                                    ['' => 'Select Status', 'All' => 'All', 'Open' => 'Open', 'InProgress' => 'InProgress', 'Closed' => 'Closed' , 'Archive' => 'Archive'],
                                    $selectedStatus = request()->input('status'),
                                    [
                                        'class' => 'form-control inventory_item_filter stock_drpDwn select_btn_icon',
                                        'rel' => 'avilability_status',
                                        'id' => 'ticket_status',
                                    ],
                                ) !!}
                            </div>
                            <div class="lastBtn">
                                <div class="pull-right">
                                    <a href="{{ URL('tickets/create') }}"
                                        class="btn btn-primary add-user-btn add-topic-btn1">+ </a><br />
                                </div>
                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                    <div class="card-body" style="padding:0 !important">
                        <div class="panel-body">
                            <div class="canvas-wrapper">
                                {{-- <div>
                  <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                </div> --}}
                                <div class="table-responsive item_inv" id="dynamicContent">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Emp Code</th>
                                                <th>Ticket no</th>
                                                @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                                                        Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                                                        Auth::user()->email == 'rohit.gupta@talentelgia.in')
                                                    <th>Employee Email</th>
                                                @endif
                                                <th>Category Name</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $avail = ['1' => 'Assigned', '0' => 'Spare'];
                                            @endphp
                                            @if (!$tickets->isEmpty())
                                                @foreach ($tickets as $index => $value)
                                                    <tr>
                                                        <td>{{ $value->employee_code }}</td>
                                                        <td>{{ $value->ticket_id }}</td>
                                                        @if (Auth::user()->email == 'manish.chopra@talentelgia.in' ||
                                                                Auth::user()->email == 'pallavi.ranjan@talentelgia.in' ||
                                                                Auth::user()->email == 'rohit.gupta@talentelgia.in')
                                                            <td>({{ $value->first_name }}) {{ $value->user_email }}</td>
                                                        @endif

                                                        <td>{{ $value->category }}</td>

                                                        @if ($value->status == 'Open')
                                                            <td class="status-td"><img src="{{ asset('images/red.svg') }}"
                                                                    alt="">{{ $value->status }}</td>
                                                        @elseif ($value->status == 'InProgress')
                                                            <td class="status-td"><img
                                                                    src="{{ asset('images/Yellow.svg') }}"
                                                                    alt="">  {{ $value->status == "InProgress" ? "In Progress" :$value->status }}</td>
                                                        @elseif ($value->status == 'Closed')
                                                            <td class="status-td"><img
                                                                    src="{{ asset('images/green.svg') }}"
                                                                    alt="">{{ $value->status }}</td>
                                                        @elseif ($value->status == 'Archive')
                                                            <td class="status-td"><img style="width: 17px;"
                                                                    src="{{ asset('images/archive.svg') }}"
                                                                    alt="">{{ $value->status }}</td>
                                                        @endif

                                                        <td>
                                                            <a class="action_btn" href="{{ action('TicketsController@details', ['user_id' => $value->user_id, 'ticket_id' => $value->ticket_id]) }}" title="View details"> 
                                                                <i class="fas fa-eye"> </i>
                                                            </a>
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
                                        {{ $tickets->appends(['page' => Request::get('page'), 'status' => Request::get('status'), 'search' => Request::get('search'), 'start_date' => Request::get('start_date'), 'end_date' => Request::get('end_date'), 'category_id' => Request::get('category_id'), '_token' => csrf_token()])->render() }}
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
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@section('script')
    <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script>

        // Get the URL search parameters
        const urlParams = new URLSearchParams(window.location.search);

        // Extract the start_date and end_date values
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
            var url = 'list' + '?start_date=' + picker.startDate.format('YYYY/MM/DD') + '&end_date=' + picker
                .endDate.format('YYYY/MM/DD');
            window.location.href = url;
        });

        $('#dates').on('cancel.daterangepicker', function() {
            $(this).val('');

        });
    </script>

    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
