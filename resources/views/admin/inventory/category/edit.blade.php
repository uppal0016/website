@extends('layouts.page')
@section('content')
  <div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
                <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
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
            <h3 class="mb-0">Edit Category</h3>
          </div>
          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="editForm">
                <form action="{{ route('admin.category.update',$category->id) }}" method="POST" id="add_category">
                  <div class="FormRIght">
                    {!! Form::hidden('id', $category->id) !!}
                    {!! Form::hidden('_token', Session::token()) !!}
                    {!! Form::hidden('_method', 'PUT') !!}

                    <div class="form-group required">
                      <label for="name">Enter Category Name</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        {!! Form::text('name', $category->name, ['placeholder'=>'Enter Category Name','class'=> 'form-control','name'=>'name']); !!}
                        <span class="text-danger">{{$errors->first('name')}}</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="parameter">Enter Parameter Name</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        {!! Form::text('parameter', $category->parameter, ['placeholder'=>'Enter Parameter Name','class'=> 'form-control','name'=>'parameter']); !!}
                        <span class="text-danger">{{$errors->first('parameter')}}</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="description">Enter Description</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        {!! Form::textarea('description',$category->description,['placeholder'=>'Enter Description','class'=>'form-control', 'rows' => 2, 'cols' => 40 , 'name' => 'description']); !!}                        
                          <span class="text-danger">
                          <span class="text-danger">{{$errors->first('description')}}</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      <div class="input-group input-group-merge input-group-alternative">
                        <select class="form-control select_btn_icon" name="status" style="max-width: 13rem; ">
                          <option value="0" @if($category->status == 0) selected @endif>Deactivate</option>
                          <option value="1" @if($category->status == 1) selected @endif>Active</option>
                        </select>
{{--
                        {!! Form::select('status', array('1' => 'Active', '0' => 'Inactive'),$category->is_deleted,['class'=> 'form-control']) !!}
--}}
                        <span class="text-danger">{{$errors->first('status')}}</span>
                      </div>
                    </div>
                    <span class="text-danger">{{$errors->first('status')}}</span>
                    <div class="">
                      <button type="submit" class="btn btn-primary add-user-btn" name="submit">Update</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div><!--/.row-->
  </div>
</div>

@endsection
