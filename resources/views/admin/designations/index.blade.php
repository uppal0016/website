@extends('layouts.page')
@section('content')
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Designation</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right fieldSearch">
        <form id="searchForm" method="get" action="javascript:void(0);" role="search">
          <div class="input-group custom-searchfeild">
          <div class="col-sm-12 customSearch padd_0">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by designation name" aria-describedby="button-addon6">
            <button class="btn btn-primary searchButton" type="submit" name="submit" id="search">
              <i class="fa fa-search"></i>
            </button>
            <input type="hidden" name="action" value="/designations">
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
          <h3 class="mb-0">Designation</h3>
          <div>        
            <a href="{{ URL('admin/designations/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Designation">+</a><br/>
          </div>
        </div>
       
        {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
        </div> --}}
        <!-- Light table -->
        <div class="table-responsive" id ="dynamicContent">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col">Designation Name</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody class="list">
            @if(!$designations->isEmpty())
            @foreach($designations as $value)
              <tr>
                <td>{{$value->name}}</td>
                <td>
                    @php $encryptId = Crypt::encrypt($value->id); @endphp
                    <a href="{{ action('Admin\DesignationController@edit',$encryptId) }}" title="Edit Designation">
                        <i class="fa fa-edit"> </i>
                    </a> &nbsp
                    @php
                        $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                        $icon = ($value->status == 1) ? 'fa fa-check' : 'fa fa-times';
                    @endphp
                    <a href="{{ url('/admin/designation_status',$encryptId) }}" title="<?php echo $statusText; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                    </a> &nbsp
                    <a href="{{ url('/admin/designations/destroy',$encryptId) }}" title="Delete Designation"onclick="return confirm('Are you sure you want to delete this desgination?');"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
          <div class="pagination">
          {{ $designations->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
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
