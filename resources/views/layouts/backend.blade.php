<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{!! asset('images/t.jpg') !!}"/>
  <title>TalentOne</title>

  <!-- Styles -->
  <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('css/slidebar.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('css/datepicker/datepicker.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('css/jquery-ui.css') }}" rel="stylesheet">
  {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"> --}}
  <link rel="stylesheet" href="{{ URL::asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/bootstrap-select.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ URL::asset('vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ URL::asset('css/argon.css?v=1.2.0') }} " type="text/css">
  {{-- Loader --}}
  <link rel="stylesheet" href="{{ asset('css/loader.css') }}" type="text/css">
    <style>
      a:hover {
        color: #05b1c5;
      }
      a{
        text-decoration: none !important;
      }
    </style>
  </head>
  {{--@if(Route::getFacadeRoot()->current()->uri() !== 'login' && Route::getFacadeRoot()->current()->uri() !== 'password/reset' && Route::getFacadeRoot()->current()->uri() !== '/')--}}
  @if(\Illuminate\Support\Facades\Auth::check())
  <body class="bg-default">
    {{--Loader starts--}}
      <div id="loader-body">
        <div id="loader"></div>
      </div>
    {{--Loader Ends--}}
    @include('common.header')
  @else
  <body class="login-body-class">
    {{--Loader starts--}}
     <div id="loader-body">
      <div id="loader"><img src="{{asset('images/loader.gif')}}"></div>
    </div>
    {{--Loader Ends--}}
  @endif

  @yield('content')
  <!--Script-->
  
  <script src="{{ URL::asset('js/app.js') }}"></script>
  <script src="{{ URL::asset('js/sidebar.js') }}"></script>
  <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
  <script src="{{ URL::asset('js/jquery.toaster.js') }}"></script>
  <script src="{{ URL::asset('js/bootstrap-select.min.js') }}"></script>
  <script src="{{ URL::asset('js/plugin/jquery.validate/jquery.validate.min.js') }}"></script>
  <script src="{{ URL::asset('js/plugin/jquery.validate/additional-methods.min.js') }}"></script>
  <script src="{{ URL::asset('js/common.js') }}"></script>
  <script src="{{ URL::asset('js/formValidate.js') }}"></script>
  <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
  <script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ URL::asset('js/moment.min.js') }}"></script>
  <script src="{{ URL::asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ URL::asset('js/js.cookie.js') }}"></script>
  <script src="{{ URL::asset('js/argon.js?v=1.2.0') }}"></script>
  {{-- New Custom JS --}}
  <script src="{{ URL::asset('js/custom_new.js') }}"></script>
  <script>
    $.toaster({ settings : {timeout:4000} });
  </script>

  <script type="text/javascript">

    var roleId = <?php echo \Auth::check() ? auth()->user()->role_id : '0'; ?>,
        prefix = roleId == 1 ? 'admin/' : (roleId == 2 ? 'management/' : (roleId == 3 ? 'pm/' : ''));

    $(document).ready(function(){
      $('.alert-success').fadeOut(8000);
      $('.alert-danger').fadeOut(8000);
    });
  </script>
  <script>
    var ADMIN_URL = '<?php echo URL::to('/admin'); ?>';
    var EMP_URL = '<?php echo URL::to('/'); ?>';
    var TOKEN = '{{csrf_token()}}';
    var PM_URL = '<?php echo URL::to('/pm'); ?>';
    var BASE_URL = '<?php echo URL::to(''); ?>';
    var HR_URL = '<?php echo URL::to('/hr'); ?>';
  </script>
  @stack('view-scripts')
</body>
</html>
