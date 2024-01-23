@extends('layouts.page')
@section('content')
 
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li>
        <a href="{{ URL('/dashboard') }}">
          <em class="fa fa-home"></em>
        </a>
      </li>
      <li class="active">{{ $title }}</li>
    </ol>
  </div><!--/.row-->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">{{ $title }}</h1>

      @if ($message = Session::get('success'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif

      @if ($message = Session::get('error'))
      <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif
    </div>
  </div>
  <!--/.row-->
  <!-- <div class="row">
  <div class="col-lg-12">
  <h1 class="page-header">{{ $title }}</h1>
</div>
</div><!-/.row-->
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading cst-panel-heading">
        <div class="row">
          <div class="col-md-6">
            <!-- <form id="searchForm" method="get" action="javascript:void(0);" role="search"> -->
            <div class="input-group custom-searchfeild">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
              <input autocomplete="off" name="search" type="text" class="form-control searchBox" placeholder="Search..." aria-describedby="button-addon6">
              <button class="btn btn-primary searchButtons" type="submit" name="submit">
                <i class="fa fa-search"></i>
              </button>
            </div>
            <!-- </form> -->
          </div>
          <div class="col-md-6">
            <div class="pull-right">
              <a href="{{ $url.'/category/create' }}" class="btn btn-primary add-user-btn add-topic-btn">+ Add Category </a>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-body">
        <div class="canvas-wrapper">
          {{-- <div>
            <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
          </div> --}}
          <div class="table-responsive" id="paginationData">
            <table class="table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Category Name</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @if(!$category->isEmpty())
                @foreach($category as $index=>$value)
                <tr>
                  <td>{{ $category->perPage() * ($category->currentPage() -1 ) + $index+1 }}</td>
                  <td>{{ $value->name }}</td>
                  <td>
                    <a href="{{ action('PM\CategoryController@edit',Crypt::encrypt($value->id)) }}" title="Edit Category">
                      <i class="fa fa-edit"> </i>
                    </a> &nbsp
                    @php
                    $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
                    $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
                    @endphp
                    <a href="{{ url($url.'/change_category_status/'.Crypt::encrypt($value->id)) }}" title="<?php echo $statusText; ?>">
                      <i class="<?php echo $icon; ?>"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="3" class="text-center"><b>No record found</b></td></tr>
                @endif
              </tbody>
            </table>
            <div class="common pagination">
              {{ $category->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!--/.row-->
</div>  <!--/.main-->
<script>
var searchUrl = 'category-search';
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
