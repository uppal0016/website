@extends('layouts.page')
@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-8 col-7">
                        <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="/admin/holiday"> Manage Holidays</a></li>
                            </ol>
                        </nav>
                    </div>
                
                    <div class="col-lg-4 col-5 text-right fieldSearch">
                        <form id="searchForm" method="get" action="/admin/holiday-search" role="search">
                            <div class="input-group custom-searchfeild ">
                                <div class="col-sm-12 customSearch padd_0">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                    <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by title" aria-describedby="button-addon6">
                                    <button class="btn btn-primary searchButton" type="submit" name="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="hidden" name="action" value="/holiday">
                                </div>
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
                        <h3 class="mb-0">Holiday List</h3>
                        <div class="">
                            <a href="{{ URL('admin/holiday/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Holiday">+</a><br/>
                        </div>
                    </div>
                    <!-- Light table -->
                    <div class="table-responsive" id ="dynamicContent">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <th scope="col">Title</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </thead>
                            <tbody class="list">
                                @if($holidays->isNotEmpty())
                                    @foreach($holidays as $cards)
                                        <tr>
                                            <td>{{ $cards->title }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cards->date)->format('d-m-Y') }}</td>
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
                                                <a href="{{action('Admin\HolidayController@edit',encrypt($cards->id))}}" title="Edit Card"><i class="fa fa-edit"></i>
                                                </a> &nbsp
                                                <a href="{{ url('/admin/holiday/destroy/'.encrypt($cards->id)) }}" title="Delete Card" onclick="return confirm('Are you sure you want to delete this holiday?');"><i class="fa fa-trash"></i>
                                                </a> &nbsp
                                                <a href="{{ url('/admin/holiday/status/'.encrypt($cards->id)).'/'.$change_status }}" title="{{ $title }}" ><i class="{{ $icon }}"></i>
                                                </a> &nbsp
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="no_record text-center">No Record Found</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="pagination">
                            {{ $holidays->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
                        </div>
                    </div>
                    <!-- Card footer -->
                </div>
            </div>
        </div>
    </div>
@section('script')
    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
