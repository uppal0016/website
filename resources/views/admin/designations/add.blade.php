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
              <li class="breadcrumb-item"><a href="{{ url('/admin/designations') }}">Designations</a></li>
              <li class="breadcrumb-item active" aria-current="page">Add Designation</li>
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
          <h3 class="mb-0">Add Designation</h3>
        </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
                      <div class="editForm">
                      <form method="post" id="add_designation" action="{{ url('/admin/designations/create') }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <div class="FormRIght">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                <input maxlength="100" class="form-control " value="{{old('name')}}" id="name" name="name" placeholder="Enter Designation Name" type="text">
                                </div>
                              </div>
                             
                              <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                <select  class="form-control select_btn_icon" value="" name="status" id="status">
                                    <option value="1" checked>Active</option>
                                    <option value="0" >Inactive</option>
                                </select>                                
                            </div>
                              </div>
                            
                             
                              <div class="">                             
                              <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit
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

@endsection
