@extends('layouts.page')
@section('content')
     
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>

                <li class="active">DSRs</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Employee's DSRs</h1>
                @if(session()->has('flash_message'))
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session()->get('flash_message') }}
                    </div>
                @endif
            </div>
        </div><!--/.row-->
        <!-- <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">Projects</h1>
          </div>
        </div><!-/.row-->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading cst-panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                                <form id="searchForm" method="get" action="javascript:void(0);" role="search">
                                    <div class="input-group custom-searchfeild">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                        <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search..." aria-describedby="button-addon6">
                                        <button class="btn btn-primary searchButton" type="submit" name="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="hidden" name="action" value="/dsr">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            {{-- <div>
                                <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                            </div> --}}
                            <div class="table-responsive" id="dynamicContent">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="th-pad">S.No</th>
                                        <th class="th-pad">First Name</th>
                                        <th class="th-pad">Last Name</th>
                                        <th class="th-pad">Email</th>
                                        <th class="th-pad">Role</th>
                                        <th class="th-pad">View DSR</th>
                                        <th class="th-pad">Total DSR</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $counter = 1; @endphp
                                    @if(Request::get('page') && ! empty(Request::get('page')))
                                        @php
                                        $page = Request::get('page') - 1;
                                        $counter = 10 * $page + 1;
                                        @endphp
                                    @endif
                                    @foreach($dsrUsers as $value)
                                    <?php
                                        $total_count = 0;
                                        $count = 0;
                                        $display = 0;
                                        if($value['dsr']){
                                        foreach ($value['dsr'] as $dsr) {
                                        $hasInTo = in_array($authId, explode(',', $dsr['to_ids']));
                                        $hasInCC = in_array($authId, explode(',', $dsr['cc_ids']));
                                        if($hasInTo || $hasInCC){
                                        $display = 1;
                                        if(!$dsr['read']){
                                        $count++;
                                        continue;
                                        }
                                        $found = 0;
                                        foreach ($dsr['read'] as $read) {
                                        if($read['user_id'] == $authId && $read['is_read'] == 1){
                                        $found = 1;
                                        }
                                        }
                                        if(!$found){
                                        $count++;
                                        }
                                        }
                                        }
                                        }
                                        $total_count += $count;
                                            ?>
                                        <tr>
                                            <td>{{$counter}}</td>
                                            <td>{{$value->first_name}}</td>
                                            <td>{{$value->last_name}}</td>
                                            <td>{{$value->email}}</td>
                                            <td>{{$value->role->role}}</td>
                                            <td>
                                                @php $encryptId = Crypt::encrypt($value->id); @endphp
                                                <a href="{{url('pm/user_dsrs',$encryptId)}}" title="View DSR">
                                                    <i class="fa fa-eye"> </i>
                                                </a>
                                            </td>
                                            <td>  {{$total_count}} </td>
                                        </tr>
                                        @php $counter++ @endphp
                                    @endforeach
                                    @if(!$dsrUsers->count())
                                        <tr>
                                            <td colspan="6" class="text-center"><b>No records found</b></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $dsrUsers->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/.row-->
    </div>  <!--/.main-->
    <script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
