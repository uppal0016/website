@extends('layouts.backend')
@section('content')
@if(session()->has('flash_message'))
<div class="alert alert-success">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{ session()->get('flash_message') }}
</div>
@endif

<div class="login_signup main-site">
  <main class="site-body">
    <section class="left-section">
      <div class="bg-image">
      </div>
    </section>
    <section class="right-section">
      <div class="main-logo">
        <a class="navbar-brand" href="{{ URL('/') }}">
          <img src="{{ URL::asset('images/tt-one.svg') }}">
        </a>
      </div>
      <div class="container no-gutter">
        <div class="row justify-content-center">
          <div class="col-xl-7 col-lg-9 col-md-12 col-sm-12">
            <div class="card login-signup-card">
              <div class="card-header">
                <h5> Login to your account</h5>
                <p>Enter your email address and password to access the panel.</p>
              </div>
              <div class="card-body">
                @if ($errors->has('Flash'))
                <span class="help-block text-center" style="color:#a94442">
                  <strong>{{ $errors->first('Flash') }}</strong>
                </span>
                @endif
                <form role="form" method="POST" action="{{ route('login') }}" id="login_form">
                  {{ csrf_field() }}
                  <div class="form-group mb-3">
                    <label for="">Email</label>
                    <input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" autocomplete="off">
                  </div>
                  @if ($errors->has('email'))
                  <span class="help-block text-left">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                  @endif
                  <div class="form-group">
                    <label for="">Password</label>
                    <input class="form-control" placeholder="Password" name="password" type="password" value="" autocomplete="off">
                  </div>
                  <input type="hidden" name="inteviewpanel" value ="{{$interviewpanel}}">
                  <input type="hidden" name="inv_path" value ="{{$path}}">
                  @if ($errors->has('password'))
                  <span class="help-block text-left">
                    <strong>{{ $errors->first('password') }}</strong>
                  </span>
                  @endif
                  <div class="text-right forgotPass">
                    <a href="{{ url('password/reset') }}" title="Forgot Password">
                      <span>Forgot Password ?</span>
                    </a>
                  </div>
              </div>
              <div class="text-center">
                <button type="submit" id="btnSubmit" class="btn btn-primary my-4">Login</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
</div>
</section>
</main>
</div>

@push('view-scripts')
<script>
 
  $("#login_form").validate({
        validClass: "success",
        errorElement: 'p',
        rules: {          
            email: {
                required: !0,
                email: true,
                validEmailCheck: true,
                maxlength: 100,
                normalizer: function(value) {
                    return $.trim(value)
                }
            },
            password : {
                required: !0,
            },
        },
        messages: {
            email: {
                required: 'The email field is required.'
            },
            password: {
                required: 'The password field is required.'
            },
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
            // $(element).closest('.form-group')  <input type="hidden" name="inteviewpanel" value ="{{$interviewpanel}}">.removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
            // $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        submitHandler: function(form){
        $('#loader-body').fadeIn();
          form.submit();
        }
       
    });
    setTimeout(function() {        
         $("#loader-body").fadeOut();
    
      },700)

    // $('#loader-body').fadeIn();
    setTimeout(function(){
      $('.help-block').fadeOut();
    },5000);
</script>
@endpush
@endsection