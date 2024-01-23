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
                    <a href="{{ URL('pm/designations') }}">Designations</a>
                </li>
                <li class="active">Add Designation</li>
            </ol>
        </div><!--/.row-->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Add Designation </h1>
            </div>
        </div><!--/.row-->

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Add Designation
                    </div>
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="table-responsive">
                                <form method="post" id="add_designation" action="{{ url('/hr/designations/create') }}" enctype="multipart/form-data">
                                    <table class="table input-lists">
                                        <tbody>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                                <input maxlength="100" class="form-control " value="{{old('name')}}" id="name" name="name" placeholder="Enter Designation Name" type="text">
                                                <span class="text-danger">
                                                    {{$errors->first('name')}}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <select  class="form-control select_btn_icon" value="" name="status" id="status">
                                                    <option value="1" checked>Active</option>
                                                    <option value="0" >Inactive</option>
                                                </select>
                                            <span class="text-danger">
                                                {{$errors->first('status')}}
                                            </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--/.row-->
    </div>
@endsection