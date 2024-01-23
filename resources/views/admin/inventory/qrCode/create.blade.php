@extends('layouts.page')
@section('content')
<style>
.form-group.required label:after {
  content:"*";
  color:red;
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
                <li class="breadcrumb-item" aria-current="page"><a href="{{ url('admin/qr_code') }}">Generate QR Code</a></li>        
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
          <div class="card-header border-0 d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Generate QR Code</h3>
            <div class="">
              <a href="{{ URL('admin/qr_code') }}" class="btn btn-primary add-user-btn add-topic-btn">Back</a><br/>
            </div>
          </div>

          <div class="panel-body">
            <div class="canvas-wrapper">
              <div class="editForm">
                <form action ="{{ URL('admin/qrcode/genrate') }}" method="POST" enctype="multipart/form-data" id="add_qrcode">         
                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}"/>
                <div class ="row">
                <div class="col-md-4">
                <div class="form-group required">
                <label for="qr_code"> Qr code Category</label>
                <select class="form-control select_btn_icon" name="item_type" required><option value=""> Select Qr code  Category</option> <option value="screen">Screen</option><option value="mouse">Mouse</option><option value="cpu">CPU</option><option value="keyboard">Keyboard</option></select>
                </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group required">
                    <label for="qr_code">Enter QR code qty to print </label>
                  <input type="text" aut  class="form-control " onkeypress="return qrcodevalidation(event)" name="qr_code" placeholder="Please Enter QR code qty to print" required>
                  
                  <span class="text-danger">
                        {{$errors->first('qr_code')}}
                      </span>
                  </div>
                </div>
                  <div>
</div>
                  <div class="col-md-12">
                    {!! Form::submit('GenrateQrCode',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
</div>
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
<script src="{{ URL::asset('js/custom.js') }}"></script>