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
              <li class="breadcrumb-item active" aria-current="page"><a href="/tickets/list">Document Password</a></li>
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
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper">
            <div class="editForm">
              <form id="change_password" action="{{ url('document/genrate_password') }}" method="post" autocomplete="off" class="container-fluid" />           
                @csrf    
                <h6 class="heading-small text-muted mb-4">Generate document password</h6>
                <div class="form-row align-items-center">
                  <input type="hidden"  name="user_id" class="form-control" value="{{$user->id}}" >
                  <input type="hidden"  name="document_id" class="form-control" value="{{$documentPassword}}" >
                  <input type="hidden" name="activation_code" id="activation_code" value="{{$activation_code}}">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-control-label" for="email">Email</label>
                      <input type="email" name="email" id="email" value="{{$user->email}}" placeholder="Email" class="required form-control" readonly />
                      <span class="text-danger">
                        {{$errors->first('new_password')}}
                      </span>                      
                    </div>                
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-control-label" for="password">Password</label>
                      <input type="text" name="password"  value="{{old('new_password')}}"placeholder="Password" class="required form-control" />
                      <span class="text-danger">
                        {{$errors->first('new_password')}}
                      </span>                      
                    </div>   
                  </div>             
                </div>
                <button type="submit" class="btn btn-info mt-4 ml-auto add-user-btn" name="submit">Save</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
