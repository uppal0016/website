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
                        <div class="card login-signup-card resetSec">
                            <div class="card-header">
                                <h5>Reset Password</h5>
                            </div>
                            <div class="card-body">
                                @if ($errors->has('Flash'))
                                <span class="help-block" style="color:#a94442">
                                    <strong>{{ $errors->first('Flash') }}</strong>
                                </span>
                                @endif
                                <form class="form-horizontal" method="POST" action="{{ url('reset.password.post') }}" id="reset_form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-12 control-label">E-Mail Address</label>
                                        <div class="col-md-12">
                                            <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
                                            @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Password</label>
                                        <div class="col-md-12">
                                            <input id="password" type="password" class="form-control" name="password">
                                            @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div
                                        class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <label for="password-confirm" class="col-md-12 control-label">Confirm
                                            Password</label>
                                        <div class="col-md-12">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                            @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Reset Password
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
            password : {
                required: !0,
            },
            password_confirmation : {
                required: !0,
                equalTo : "#password"
            },
        },
        messages: {
            email: {
                required: 'The email field is required.'
            },
            password: {
                required: 'The password field is required.'
            },
            password_confirmation: {
                required: 'Please enter confirm password.',
                equalTo: 'New password and confirm password do not match.'
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
            // $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
            // $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
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