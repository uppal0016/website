@extends('layouts.page')
@section('content')
@php 
$words = explode(' ', $_SERVER['REQUEST_URI']);
$showword = trim($words[count($words) - 1], '/');
if($showword == 'team_dsr'){
$route = '/team_dsr';
}
else{
$route = '/dsr';
}

@endphp
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-8 col-7">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">DSRs</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-4 col-5 text-right fieldSearch">
        <form id="searchForm" method="get" action="javascript:void(0);" role="search">
          <div class="row">
            <div class="col-md-12">
              <div class="input-group custom-searchfeild customSearch ">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by name" aria-describedby="button-addon6" id="search">
                <button class="btn btn-primary searchButton" type="submit" name="submit">
                    <i class="fa fa-search"></i>
                </button>              
              </div>
            </div>
            <input type="hidden" name="action" value="{{$route}}">
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
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">DSRs List</h3>
        </div>
        {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
        </div> --}}
        <!-- Light table -->
        <div class="table-responsive" id ="dynamicContent">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col">View DSR</th>
                <th scope="col">Total DSR</th>
            </tr>
            </thead>
            <tbody class="list">
            @if(!$dsrUsers->isEmpty())
            @foreach($dsrUsers as $value)
              <tr>
                <td>{{$value->first_name}}</td>
                <td>{{$value->last_name}}</td>
                <td>{{$value->email}}</td>
                <td>{{$value->role->role}}</td>
                <td>
                    @php $encryptId = Crypt::encrypt($value->id); @endphp
                   
                    @if(in_array(Auth::user()->role_id,[4,5]))
                    <a href="{{url('user_dsrs',$encryptId)}}" title="View DSR">
                        <i class="fa fa-eye"> </i>
                    </a> @else
                     <a href="{{url('admin/user_dsrs',$encryptId)}}" title="View DSR">
                        <i class="fa fa-eye"> </i>
                    </a>
                    @endif
                </td>
                <td>  {{$value->dsr_count}} </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
          <div class="pagination">
            {{ $dsrUsers->appends(['page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
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
