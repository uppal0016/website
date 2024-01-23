@extends('layouts.page')
@section('content')
    @php
        $current_uri = Route::getFacadeRoot()->current()->uri();
    @endphp
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-8 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Birthday Cards</li>
                            </ol>
                        </nav>
                    </div>
                   <div class="col-lg-4 col-5 text-right fieldSearch">
                        <form method="get" action="javascript:void(0);" role="search" id="searchForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <div class="input-group custom-searchfeild ">
                                <div class="col-sm-12 customSearch padd_0">
                                    <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by employee name" aria-describedby="button-addon6">
                                    <button class="btn btn-primary searchButton" type="submit" name="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <input type="hidden" name="action" value="/birthday">
                            </div>
                        </form>
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
                    <div class="card-header border-0 d-flex align-items-center justify-content-between">
                        <h3 class="mb-0">Birthday Card List</h3>
                        <div class="">
                            <a href="{{ URL('admin/birthday/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Birthday Card">+</a><br/>
                        </div>
                    </div>

                    {{-- <div>
                        <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                    </div> --}}
                    <!-- Light table -->
                    <div class="table-responsive" id ="dynamicContent">
                        <table id="birthday-card-table" class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr id="row">
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Birthday Date</th>
                                    <th scope="col">Birthday Card</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($birthday_cards as $cards)
                                <tr>
                                    <td>{{ $cards->user->first_name }} {{ $cards->user->last_name }}</td>
                                    <td>{{ $cards->birthday_date }}</td>
                                    <td>
                                        <img src="{{ url('images/birthday_cards/'.$cards->birthday_card) }}" height="100" width="100"/>
                                    </td>
                                    @php
                                        if($cards->status == 1){
                                            $status = 'Active';
                                            $change_status = 0;
                                            $icon = 'fa fa-times text-danger';
                                            $title = 'Block Card';
                                            $class = 'text-success';
                                        }
                                        else{
                                            $change_status = 1;
                                            $status = 'Block';
                                            $icon = 'fa fa-check text-success';
                                            $title = 'Activate Card';
                                            $class = 'text-danger';
                                        }

                                    @endphp
                                    <td><span class="{{ $class }}">{{ $status }}</span></td>
                                    <td>
                                        <a href="{{action('Admin\BirthdayController@edit',encrypt($cards->id))}}" title="Edit Card"><i class="fa fa-edit"></i>
                                        </a> &nbsp
                                        <a href="{{ url('/admin/birthday/destroy/'.encrypt($cards->id)) }}" title="Delete Card" onclick="return confirm('Are you sure you want to delete this birthday card?');"><i class="fa fa-trash"></i>
                                        </a> &nbsp
                                        <a href="{{ url('/admin/birthday/status/'.encrypt($cards->id)).'/'.$change_status }}" title="{{ $title }}" ><i class="{{ $icon }}"></i>
                                        </a> &nbsp
                                    </td>
                                </tr>
                            @endforeach
                            @if(!$birthday_cards->count())
                                <tr>
                                    <td colspan="5" ><b>No records found</b></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        {{ $birthday_cards->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}

                    </div>
                    <!-- Card footer -->

                </div>
            </div>
        </div>
    </div>
@section('script')
    <script src="{{ URL::asset('js/custom.js') }}"></script>
<!--    <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#birthday-card-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ url('admin/birthday/card-list') }}",
                    "type": "POST",
                },
                "columns": [
                    {
                        "data": "employee_name",
                    },
                    {
                        "data": "birthday_date",
                    },
                    {
                        "data": "birthday_card"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "action"
                    }
                ],
                "columnDefs": [{
                    "targets": [0,1,2,3,4], //first column / numbering column
                    "orderable": false, //set not orderable
                }, ],

            });
        });
    </script>-->
@endsection
@endsection
