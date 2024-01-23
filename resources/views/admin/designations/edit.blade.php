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
              <li class="breadcrumb-item active" aria-current="page">Edit Designation</li>
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
          <h3 class="mb-0">Edit Designation</h3>
        </div>
              <div class="panel-body">
                  <div class="canvas-wrapper">
                      <div class="editForm">
                      @php $encryptId = Crypt::encrypt($designation->id); @endphp
                      <form id="add_designation" method="post" action="{{ url('/admin/designations/'.$encryptId) }}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                            <input type="hidden" name="_method" value="patch">
                            <div class="row">
                             <div class="col-sm-12 FormRIght">
                            <div class="form-group">
                                <div class="input-group input-group-merge input-group-alternative">
                                <input maxlength="100" class="form-control " value="{{ $designation->name }}" id="name" name="name" placeholder="Enter Designation Name" type="text">
                                </div>
                              </div>
                                                          
                              <div class="">                             
                              <button type="submit" class="btn btn-primary  add-user-btn" name="submit">Submit
                            </div>
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
