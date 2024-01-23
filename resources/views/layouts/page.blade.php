<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <link rel="icon" href="{!! asset('images/t.jpg') !!}"/>
    <title>TalentOne</title>
    <!-- Favicon -->
    <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('css/nucleo.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}" type="text/css">
    <!-- Page plugins -->
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ asset('css/argon.css?v=1.2.0') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/theme-style.css') }}" type="text/css">
    <link href="{{ URL::asset('css/datepicker/datepicker.css') }}" rel="stylesheet">
    {{-- Loader --}}
    <link rel="stylesheet" href="{{ asset('css/loader.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/toggle.css') }}" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
    <link
    href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
    rel="stylesheet"
/>
<link href="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.css" rel="stylesheet">
    @yield('css')
    <style>
      .bootbox .modal-content {
          position: relative;
          display: flex;
          flex-direction: column;
          width: 100%;
          pointer-events: auto;
          background-color: #fff;
          background-clip: padding-box;
          outline: 0;
          max-width: 321px;
      }
      .bootbox .modal-header {
          display: flex;
          align-items: flex-start;
          justify-content: space-between;
          padding: 7px;
          border-bottom: 1px solid #e9ecef;
          border-top-left-radius: 0.3rem;
          border-top-right-radius: 0.3rem;
      }
      .bootbox .modal-title {
        line-height: 2.1;
      }

      .bootbox .modal-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding: 10px;
        border-top: 1px solid #e9ecef;
      }
      .bootbox .btn-sm {
        font-size: 13px;
      }
      .bootbox .modal-body {
        padding: 14px;
      }

      .choose-file{
        cursor: pointer;
      }

      .ticket-edit-\&-delete {
    position: absolute;
    right: 4%;
    }

    </style>
</head>

<body id="body_tag">
{{--Loader starts--}}
<div id="loader-body">
    <div id="loader"><img src="{{asset('images/loader.gif')}}" ></div>
</div>
{{--Loader Ends--}}
@include('common.sidebar.theme_sidebar')
<!-- Main content -->
<div class="main-content" id="panel">
    @include('common.navbar')
    @yield('content')
    @include('common.footer')
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/js.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset('js/jquery-scrollLock.min.js') }}"></script>
{{-- <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script> --}}
<script src="{{ URL::asset('js/plugin/jquery.validate/jquery.validate.min.js') }}"></script>

<script src="https://unpkg.com/filepond-plugin-pdf-preview/dist/filepond-plugin-pdf-preview.min.js"></script>
  <script src="{{ URL::asset('js/plugin/jquery.validate/additional-methods.min.js') }}"></script>
<script src="{{ URL::asset('js/common.js') }}"></script>
<script src="{{ URL::asset('js/formValidate.js') }}"></script>
{{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
<script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
<script src="{{ URL::asset('js/jquery.toaster.js') }}"></script>

<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/argon.js') }}"></script>
<!-- bootbox min -->
<script src="{{asset('js/bootbox.min.js')}}"  type="text/javascript"></script>

{{-- <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script> --}}
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
{{-- New Custom JS --}}
<script src="{{ URL::asset('js/custom_new.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

<script>
    var ADMIN_URL = '<?php echo URL::to('/admin'); ?>';
    var EMP_URL = '<?php echo URL::to('/'); ?>';
    var TOKEN = '{{csrf_token()}}';
    var PM_URL = '<?php echo URL::to('/pm'); ?>';
    var BASE_URL = '<?php echo URL::to(''); ?>';
    var HR_URL = '<?php echo URL::to('/hr'); ?>';

    $(document).ready(function (){
      $('.DSRSet').click(function(){
       $('.alert-danger').show();
       $('.alert-danger').html("You have already shared the DSR for Today.");
       $('.alert-danger').fadeOut(8000);
      });
    setTimeout(function() {        
         $("#loader-body").fadeOut();
    
      },1000)

       $('.close_sidebar').on("click", function (){
            $('#body_tag').removeClass('nav-open g-sidenav-show g-sidenav-pinned');
            $('#body_tag').addClass('g-sidenav-hidden');
       }) ;
        $('.logout').click(function(){
            $("#loader-body").fadeIn();
        });  
       $('.ff').click(function(){
        $("#loader-body").fadeIn();
      });
      
    });


    $(document).ready(function (){
      $('.WEEKLYSet').click(function(){
       $('.alert-danger').show();
       $('.alert-danger').html("You have already shared the weekly report for today.");
       $('.alert-danger').fadeOut(8000);
      });
    setTimeout(function() {        
         $("#loader-body").fadeOut();
    
      },1000)

       $('.close_sidebar').on("click", function (){
            $('#body_tag').removeClass('nav-open g-sidenav-show g-sidenav-pinned');
            $('#body_tag').addClass('g-sidenav-hidden');
       }) ;
        $('.logout').click(function(){
            $("#loader-body").fadeIn();
        });  
       $('.ff').click(function(){
        $("#loader-body").fadeIn();
      });
      
    });
   
  </script>
  
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

@yield('script')
</body>

</html>
