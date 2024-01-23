
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
	<?php 
	 	$current_uri = Route::getFacadeRoot()->current()->uri(); 
	 	$role_id = auth()->user()->role_id; 
	?>
  	<div class="container-fluid">
	    <div class="navbar-header">
		    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span></button>
			<a class="navbar-brand" href="#">
				<img src="{{URL::asset('images/tt-one.svg')}}" class="img-responsive" alt="" style="width: 200px;height: auto;">
			</a>
		   
	        <ul class="nav navbar-top-links navbar-right" style="margin-right: 11px;">
				<li class="dropdown" style="height: 45px;">
					<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" style="    margin-right:  52px; width: 100%; font-size: 17px; text-align: center;">
						Hi, {{Auth::user()->full_name}}
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu dropdown-alerts" style="margin-top: 28px; font-size: 14px;     width: 100%;">
					<li><a href="{{ URL('/change_password') }}"><em class="fa fa-pencil">&nbsp;</em>Edit Profile</a> </li>
					
						<li class="divider"></li>
						<li>
					      	<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><em class="fa fa-power-off">&nbsp;</em>Logout</a>
					      	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
					    </li>
					</ul>
				</li>
			</ul>
	    </div>
 	</div><!-- /.container-fluid -->
</nav>