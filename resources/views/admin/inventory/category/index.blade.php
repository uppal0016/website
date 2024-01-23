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
                <li class="breadcrumb-item active" aria-current="page">Category</li>
              </ol>
            </nav>
          </div>
          <div class="col-lg-4 col-5 text-right fieldSearch">
            <form id="searchForm" method="get" action="javascript:void(0);" role="search">
              <div class="input-group custom-searchfeild ">
                <div class="col-sm-12 customSearch padd_0">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  <input autocomplete="off" name="search" type="text" class="form-control search-length" placeholder="Search by category name" aria-describedby="button-addon6">
                  <button class="btn btn-primary searchButton" type="submit" name="submit">
                    <i class="fa fa-search"></i>
                  </button>
                  <input type="hidden" name="action" value="/category">
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
            <h3 class="mb-0">Category List</h3>
              <div class="plusBtn">
                <a href="{{ URL('admin/category/create') }}" class="btn btn-primary add-user-btn add-topic-btn" title="Add Category">+</a>
              </div>
          </div>

          {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
          </div> --}}
          <!-- Light table -->
          <div class="table-responsive" id ="dynamicContent">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
              <th scope="col">Sr no</th>                
              <th scope="col">Category Name</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
              </thead>
              <tbody class="list">
                @php
                  $counter = 1;
                @endphp
              @if(!$category->isEmpty())
                @foreach($category as $key=>$value)
                  <tr>
                    <td>{{$counter}}</td>
                    <td>{{$value->name}}</td>
                    @php
                      $statusText = ($value->status == 1) ? 'Activate' : 'Deactivate';
                      $action_label_text = ($value->status == 1) ? 'Deactivate' : 'Activate' ;
                      $icon = ($value->status == 1) ? 'fa fa-times' : 'fa fa-check';
                    @endphp
                    <td>{{ $statusText }}</td>
                    <td>
                      <a href="{{ action('Admin\CategoryController@edit',Crypt::encrypt($value->id)) }}" title="Edit Category">
                        <i class="fa fa-edit"> </i>
                      </a> &nbsp

                      <a href="{{ url($url.'/change_category_status/'.Crypt::encrypt($value->id)) }}" title="<?php echo $action_label_text; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                      </a>
                    </td>
                  </tr>
                @php
                  $counter++;
                @endphp

                @endforeach
              @else
                <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
              @endif
              </tbody>
            </table>
            <div class="pagination">
              {{ $category->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
            </div>
          </div>
          <!-- Card footer -->

        </div>
      </div>
    </div>
  </div>
@section('script')
<script>
var searchUrl = 'category-search';
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection
