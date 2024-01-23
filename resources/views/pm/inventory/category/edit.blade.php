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
      <li>
        <a href="{{ URL('pm/projects') }}">Categories</a>
      </li>
      <li class="active">Edit Category</li>
    </ol>
  </div><!--/.row-->
  <!-- <div class="row">
  <div class="col-lg-12">
  <h1 class="page-header">Add Category </h1>
</div>
</div><!/.row-->

<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="pull-right">
        <a href="{{ url('pm/category') }}" class="btn btn-primary add-user-btn add-topic-btn">Back </a>
      </div>
      <div class="panel-heading">
        Edit Category
      </div>

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

      <div class="panel-body">
        <div class="canvas-wrapper">
          <div class="table-responsive">
            <form action="{{ route('pm.category.update',$category->id) }}" method="POST" id="add_category">
              <table class="table input-lists">
                <tbody>
                  <tr>
                    <td>
                      {!! Form::hidden('id', $category->id) !!}
                      {!! Form::hidden('_token', Session::token()) !!}
                      {!! Form::hidden('_method', 'PUT') !!}
                      {!! Form::text('name', $category->name, ['placeholder'=>'Enter Category Name','class'=> 'form-control']); !!}
                      <span class="text-danger">
                        {{$errors->first('name')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::text('parameter', $category->parameter, ['placeholder'=>'Enter Parameter Name','class'=> 'form-control']); !!}
                      <span class="text-danger">
                        {{$errors->first('parameter')}}
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      {!! Form::textarea('description',$category->description,['placeholder'=>'Enter Description','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}                        <span class="text-danger">
                        <span class="text-danger">
                          {{$errors->first('description')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::select('status', array('1' => 'Active', '0' => 'Inactive'),$category->is_deleted,['class'=> 'form-control']) !!}
                        <span class="text-danger">
                          {{$errors->first('status')}}
                        </span>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        {!! Form::submit('Submit',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
              {!! Form::close() !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>

@endsection
