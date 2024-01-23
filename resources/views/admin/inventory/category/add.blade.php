@extends('layouts.page')
@section('content')
<style>
  .category_star{
    color: red;
  }
</style>

<div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{url("admin/category")}}"> Category</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Category</li>
              </ol>
            </nav>
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
        <div class="card-header border-0">
          <h3 class="mb-0">Add Category</h3>
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="editForm">
              {!! Form::open(array('action' => 'Admin\CategoryController@store','method'=>'POST','id'=>'add_category')) !!}
              <div class="FormRIght">
                <div class="form-group required">
                  <label for="name">Enter Category Name <span class="category_star">*</span></label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <input  type="text" class="form-control" placeholder="Enter Category Name" value="{{old('name')}}" id="name" name="name" maxlength="50 ">
                  </div>
                  <span class="text-danger">{{$errors->first('name')}}</span>
                </div>
             
                <div class="form-group">
                  <label for="parameter">Enter Parameter Name</label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <input  type="text" class="form-control" placeholder="Enter Parameter Name" value="{{old('parameter')}}" id="parameter" name="parameter">
                  </div>
                </div>
                <span class="text-danger">{{$errors->first('parameter')}}</span>
                <div class="form-group">
                  <label for="description">Enter Description</label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <textarea class="form-control" placeholder="Enter Description" value="{{old('description')}}" id="description" name="description"></textarea>
                  </div>
                </div>
                <span class="text-danger">{{$errors->first('description')}}</span>
                <div class="form-group">
                  <label for="status">Status</label>
                  <div class="input-group input-group-merge input-group-alternative">
                    <select class="form-control select_btn_icon" name="status" style="max-width: 13rem; ">
                      <option value="1">Active</option>
                      <option value="0">Deactivate</option>
                    </select>
                  </div>
                </div>
                <span class="text-danger">{{$errors->first('status')}}</span>
                <div class="">
                  <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit</button>
                </div>
              </div>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>

@endsection
