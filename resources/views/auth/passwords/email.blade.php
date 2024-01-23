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
                                <h5>Reset Password</h5>
                                <p>Enter your email address to reset your password.</p>
                              
                            </div>
                            
                            <div class="card-body">
                                 @if(session()->has('message'))

                        <p class="text-success help-block">{{ session()->get('message') }} </p>

                              @endif
                                @if ($errors->first('email'))
                                <span class="help-block" style="color:#a94442">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                                @if (session()->has('status'))
                                <span class="help-block">
                                    <strong class="text-success">{{ session()->get('status') }}</strong>
                                </span>
                                @endif
                                <form class="form-horizontal" method="POST" action="{{ url('forget-password') }}" id="reset_form">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                            </div>
                                            <input class="form-control" placeholder="E-mail" name="email" type="email" value="{{ old('email') }}" autofocus="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Send Password Reset Link
                                            </button>
                                            <div class="loginpage">
                                                <a href="{{ url('login') }}">Login</a>
                                            </div>
                                        </div>
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
  $("#reset_form").validate({
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
        },
        messages: {
            email: {
                required: 'The email field is required.'
            },
        },
        errorPlacement: function ( error, element ) {
            if(element.parent().hasClass('input-group')){
                error.insertAfter( element.parent() );
            }else{
                error.insertAfter( element );
            }
        }
    });
  setTimeout(function() {        
         $("#loader-body").fadeOut();
    
      },200)
    setTimeout(function(){
      $('.help-block').fadeOut();
    },5000);
</script>
@endpush
@endsection