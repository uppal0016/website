@extends('layouts.page')
@section('content')
     
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>

                <li class="active">Department</li>
            </ol>
        </div><!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Department</h1>
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
                                        <input type="hidden" name="action" value="/department">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <div class="pull-right">
                                    <a href="{{ URL('admin/department/create') }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add Department </a>
                                </div>
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
                                        <th class="th-pad">Department Name</th>
                                        <th class="th-pad">Department Code</th>
                                        <th class="th-pad">Action</th>
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
                                    @foreach($department as $value)
                                        <tr>
                                            <td>{{ $counter }}</td>
                                            <td>{{$value->name}}</td>
                                            <td>{{$value->code}}</td>
                                            <td>
                                                @php $encryptId = Crypt::encrypt($value->id); @endphp
                                                <a href="{{ action('Admin\DepartmentController@edit',$encryptId) }}" title="Edit Department">
                                                    <i class="fa fa-edit"> </i>
                                                </a> &nbsp
                                                @php
                                                $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                                                $icon = ($value->status == 1) ? 'fa fa-check' : 'fa fa-times';
                                                @endphp
                                                <a href="{{ url('/admin/department_status',$encryptId) }}" title="<?php echo $statusText; ?>">
                                                    <i class="<?php echo $icon; ?>"></i>
                                                </a> &nbsp
                                                <a href="javascript:void(0);" onclick="$(this).find('form').submit();" title="Delete Designation">
                                                    <i class="fa fa-trash-o"></i>
                                                    <form action="{{ url('/admin/department',$encryptId) }}" method="post">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                    </form>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $counter++ @endphp
                                    @endforeach
                                    @if(!$department->count())
                                        <tr>
                                            <td colspan="4" class="text-center"><b>No records found</b></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $department->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
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
