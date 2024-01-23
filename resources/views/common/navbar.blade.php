<!-- Topnav -->
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="alert alert-success ajax-success-alert" style="display: none">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="ajax-success-alert-message"></span>
            </div>
            <div class="alert alert-danger ajax-danger-alert" style="display: none">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="ajax-danger-alert-message"></span>
            </div>
            @if(session()->has('flash_message'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('flash_message') }}
            </div>
            @endif
            @if($errors->any())
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ $errors->first() }}
            </div>
            @endif
            @if(session()->has('error'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session()->get('error') }}
            </div>
            @endif
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center  mr-md-auto">
                @if( auth()->check())
                <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin"
                        data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
            <div class="media-body ml-2 d-none d-lg-block">
                <div class="mb-0 text-sm font-weight-bold" style="color: white;"> Date : <?php echo date('d-m-Y'); ?></div>
            </div>
                <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="media align-items-center">
                            <span class="avatar avatar-sm rounded-circle">
                                @if(!empty(Auth::user()->image))
                                <img class="header-image"
                                    src="{{URL::asset('images/profile_picture/'.Auth::user()->image)}}" alt="preview">
                                @else
                                <img class="header-image" src="{{URL::asset('images/sidebar_image.png')}}"
                                    alt="preview">
                                @endif
                            </span>
                            <div class="media-body  ml-2  d-none d-lg-block">
                                <span class="mb-0 text-sm  font-weight-bold">{{Auth::user()->full_name}}</span>
                            </div>
                        </div>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right ">
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>
                        <a href="{{ URL('/change_password') }}" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>Edit profile</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item logout">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </li>
            </ul>
            @endif
        </div>
    </div>
</nav>
<script>
    setTimeout(function(){
        $(".alert").fadeOut();
    }, 5000)
</script>