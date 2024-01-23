<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <?php
      $total_count = 0;
      $current_uri = Route::getFacadeRoot()->current()->uri();
      $id = app('request')->route()->parameter('id');
      $authId = Auth::user()->id;
      $authRoleId = Auth::user()->role_id;
      $permissionRole = Auth::user()->permission_id;
      $hrPermission = false;
      $invPermission = false;
      if($permissionRole != '' && $authRoleId == 2)
      {
          $permissionRole = explode(',',$permissionRole);
          if(in_array('1',$permissionRole))
          {
            $hrPermission = true;
          }
          if(in_array('2',$permissionRole))
          {
            $invPermission = true;
          }
      }else {
          $hrPermission = false;
          $invPermission = false;
      }
      // echo $hrPermission.$invPermission.$dsrPermission;
  ?>
  <div class="profile-sidebar">
      <div class="profile-userpic">
          <img src="{{URL::asset('images/sidebar_image.png')}}" class="img-responsive" alt="">
      </div>
      <div class="profile-usertitle">
          <div class="profile-usertitle-name">{{Auth::user()->first_name}}</div>
          <div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
      </div>
      <div class="clear"></div>
  </div>
  <div class="divider"></div>
  <ul class="nav menu">
    @if (($authRoleId == 1) || ($authRoleId == 2))
      <li class="{{$current_uri === 'admin/dashboard' ? 'active' : ''}}">
        <a href="{{ URL('admin/dashboard') }}"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a>
      </li>
<!--      <li class="{{ in_array($current_uri, ['admin/summary']) ? 'active' : ''}}">
        <a href="{{ URL('admin/summary') }}"><em class="fa fa-calendar">&nbsp;</em> Summary</a>
      </li>-->
      <li class="{{ $current_uri === 'attendance/list' ? 'active' : '' }}">
        <a href="{{ URL('attendance/list') }}">
          <i class="fa fa-calendar">&nbsp;</i>  Manage Attendance
        </a> 
      </li>
      <li class="parent {{ in_array($current_uri, ['admin/projects','admin/dsr','reports','user_dsrs','admin/summary']) ? 'active' : ''}}">
        <a data-toggle="collapse" class="" href="#submenu-dsr">
          <em class="fa fa-database">&nbsp;</em> Manage Projects <span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="{{ in_array($current_uri, ['admin/projects','admin/dsr','admin/summary']) ? 'fa fa-minus' : 'fa fa-plus' }}"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['admin/projects', 'admin/dsr','reports','user_dsrs','admin/summary']) ? 'in' : '' }}" id="submenu-dsr">
          <li class="{{ $current_uri === 'admin/summary' ? 'active-sub' : ''}}"><a href="{{ URL('admin/summary') }}"><em class="fa fa-calendar">&nbsp;</em> Summary</a></li>
          <li class="{{ $current_uri === 'admin/dsr' ? 'active-sub' : '' }}"><a href="{{ URL('admin/dsr') }}"><em class="fa fa-calendar-o">&nbsp;</em>DSRs</a> </li>
          <li class="{{ $current_uri === 'reports' ? 'active-sub' : '' }}"><a href="{{ URL('reports') }}"><em class="fa fa-calendar-o">&nbsp;</em>Weekly Reports</a> </li>
          <li class="{{ $current_uri === 'admin/projects' ? 'active-sub' : '' }}"><a href="{{ URL('admin/projects') }}"><em class="fa fa-code">&nbsp;</em>Projects</a> </li>
        </ul>
      </li>
      <li class="parent {{ in_array($current_uri, ['admin/category', 'admin/inventory']) ? 'active' : ''}}">
        <a data-toggle="collapse" class="" href="#submenu-inventory">
          <em class="fa fa-database">&nbsp;</em> Inventory<span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="{{ in_array($current_uri, ['admin/inventory','admin/category','admin/vendor','admin/inventory_item','admin/assigned_stock']) ? 'fa fa-minus' : 'fa fa-plus' }}"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['admin/inventory','admin/category','admin/vendor','admin/inventory_item','admin/assigned_stock']) ? 'in' : '' }}" id="submenu-inventory">
          <li class="{{ $current_uri === 'admin/inventory' ? 'active-sub' : '' }}"><a href="{{ URL('admin/inventory') }}"><em>&nbsp;</em>Inventory List</a> </li>
          <li class="{{ $current_uri === 'admin/category' ? 'active-sub' : '' }}"><a href="{{ URL('admin/category') }}"><em>&nbsp;</em>Manage Categories</a> </li>
          <li class="{{ $current_uri === 'admin/vendor' ? 'active-sub' : '' }}"><a href="{{ URL('admin/vendor') }}"><em>&nbsp;</em>Manage Vendors</a> </li>
          <li class="{{ $current_uri === 'admin/inventory_item' ? 'active-sub' : '' }}"><a href="{{ URL('admin/inventory_item') }}"><em>&nbsp;</em>Manage Inventory Items</a> </li>
          <li class="{{ $current_uri === 'admin/assigned_stock' ? 'active-sub' : '' }}"><a href="{{ URL('admin/assigned_stock') }}"><em>&nbsp;</em>Manage Assigned Stock</a> </li>
        </ul>
      </li>
      <li class="parent {{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/designations']) ? 'active' : ''}}">
        <a data-toggle="collapse" class="" href="#submenu-hrm">
          <em class="fa fa-database">&nbsp;</em> HRM<span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="{{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/designations']) ? 'fa fa-minus' : 'fa fa-plus' }}"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/designations', 'admin/user/create']) ? 'in' : '' }}" id="submenu-hrm">
          <li class="{{ $current_uri === 'admin/users' ? 'active-sub' : '' }}"><a href="{{ URL('admin/users') }}"><em class="fa fa-users">&nbsp;</em>Employees</a> </li>
          <li class="{{ $current_uri === 'admin/department' ? 'active-sub' : '' }}"><a href="{{ URL('admin/department') }}"><em class="fa fa-building-o">&nbsp;</em>Department</a> </li>
          <li class="{{ $current_uri === 'admin/designations' ? 'active-sub' : '' }}"><a href="{{ URL('admin/designations') }}"><em class="fa fa-th-list">&nbsp;</em>Designations</a> </li>
        </ul>
      </li>
    @endif


    @if ($authRoleId == 3)
      <li class="{{$current_uri === 'admin/dashboard' ? 'active' : ''}}">
        <a href="{{ URL('dashboard') }}"><em class="fa fa-dashboard">&nbsp;</em>Dashboard</a>
      </li>
<!--      <li class="{{ in_array($current_uri, ['admin/summary']) ? 'active' : ''}}">
        <a href="{{ URL('admin/summary') }}"><em class="fa fa-calendar">&nbsp;</em> Summary</a>
      </li>-->
      <li class="{{ $current_uri === 'attendance/list' ? 'active' : '' }}">
        <a href="{{ URL('attendance/list') }}">
          <i class="fa fa-calendar">&nbsp;</i>Manage Attendance
        </a>
      </li>
      <li class="{{ $current_uri === 'admin/projects' ? 'active' : '' }}">
        <a href="{{ URL('admin/projects') }}">
          <em class="fa fa-calendar">&nbsp;</em>Manage Projects
        </a>
      </li>
      <li class="parent {{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report', 'admin/summary']) ? 'active' : '' }}">
        <a data-toggle="collapse" class="{{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report', 'admin/summary']) ? '' : 'collapsed' }}" href="#submenu-report-1">
          <em class="fa fa-database">&nbsp;</em> Weekly Reports<span data-toggle="collapse" href="#submenu-reports-2" class="icon pull-right"><em class="fa fa-plus"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['reportdetail', 'sent_report' ,'add_report']) ? 'in' : '' }}" id="submenu-report-1">
            <li class="{{ $current_uri === 'admin/summary' ? 'active-sub' : ''}}"><a href="{{ URL('admin/summary') }}"><em class="fa fa-calendar">&nbsp;</em> Summary</a></li>

            <li class="{{ $current_uri === 'add_report' ? 'active-sub' : '' }}"><a href="{{ URL('/add_report') }}"><em class="fa fa-pencil">&nbsp;</em> Add report</a> </li>

          <li class="{{ $current_uri === 'sent_report' ? 'active-sub' : '' }}"><a href="{{ URL('/sent_report') }}"><em class="fa fa-paper-plane">&nbsp;</em> Sent Reports</a> </li>

          <li class="{{ $current_uri === 'reportdetail' ? 'active-sub' : '' }}"><a href="{{ URL('/reportdetail') }}"><em class="fa fa-database">&nbsp;</em><?php
          //Recived DSRs
          $count = 0;

          $tempDsrs = App\Dsr::whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)")
          ->with([
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
            ])->get()->toArray();
            if($tempDsrs){

              foreach ($tempDsrs as $dsr) {

                if(!$dsr['read'] || $dsr['read'][0]['is_read'] == 0){
                  $count++;
                  continue;
                }
              }
            }
            ?>{!! $count ? '<b>' : '' !!} Received Reports{!! $count ? '</b> ('.$count.')' : '' !!}</a></li>
          </ul>
      </li>
      <li class="parent {{ in_array($current_uri, ['admin/dsr', 'sent_dsr', 'add_dsr']) ? 'active' : '' }}">
        <a data-toggle="collapse" class="{{ in_array($current_uri, ['admin/dsr', 'sent_dsr', 'add_dsr']) ? '' : 'collapsed' }}" href="#submenu-dsrs-1">
          <em class="fa fa-database">&nbsp;</em> DSRs<span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="fa fa-plus"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['admin/dsr', 'sent_dsr' ,'add_dsr','dsrdetail']) ? 'in' : '' }}" id="submenu-dsrs-1">
        <li class="{{ $current_uri === 'admin/dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/admin/dsr') }}"><em class="fa fa-paper-plane">&nbsp;</em> Manage DSRs</a> </li>
          <li class="{{ $current_uri === 'add_dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/add_dsr') }}"><em class="fa fa-pencil">&nbsp;</em> Add DSR</a> </li>
          <li class="{{ $current_uri === 'sent_dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/sent_dsr') }}"><em class="fa fa-paper-plane">&nbsp;</em> Sent DSRs</a> </li>
          <li class="{{ $current_uri === 'dsrdetail' ? 'active-sub' : '' }}"><a href="{{ URL('/dsrdetail') }}"><em class="fa fa-database">&nbsp;</em><?php
          //Recived DSRs
          $count = 0;

          $tempDsrs = App\Dsr::whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)")
          ->with([
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
            ])->get()->toArray();
            if($tempDsrs){

              foreach ($tempDsrs as $dsr) {

                if(!$dsr['read'] || $dsr['read'][0]['is_read'] == 0){
                  $count++;
                  continue;
                }
              }
            }
            ?>{!! $count ? '<b>' : '' !!} Received DSRs{!! $count ? '</b> ('.$count.')' : '' !!}</a></li>
          </ul>
      </li>
    @endif


    @if ($authRoleId == 5)
      <li class="{{$current_uri === 'dashboard' ? 'active' : ''}}">
        <a href="{{ URL('dashboard') }}"><em class="fa fa-dashboard">&nbsp;</em>Dashboard</a>
      </li>
      <li class="{{ $current_uri === 'attendance/list' ? 'active' : '' }}">
        <a href="{{ URL('attendance/list') }}">
          <i class="fa fa-calendar">&nbsp;</i>Manage Attendance
        </a>
      </li>
      <li class="parent {{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report']) ? 'active' : '' }}">
        <a data-toggle="collapse" class="{{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report']) ? '' : 'collapsed' }}" href="#submenu-report-1">
          <em class="fa fa-database">&nbsp;</em> Weekly Reports<span data-toggle="collapse" href="#submenu-reports-2" class="icon pull-right"><em class="fa fa-plus"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['reportdetail', 'sent_report' ,'add_report']) ? 'in' : '' }}" id="submenu-report-1">

          <li class="{{ $current_uri === 'add_report' ? 'active-sub' : '' }}"><a href="{{ URL('/add_report') }}"><em class="fa fa-pencil">&nbsp;</em> Add report</a> </li>

          <li class="{{ $current_uri === 'sent_report' ? 'active-sub' : '' }}"><a href="{{ URL('/sent_report') }}"><em class="fa fa-paper-plane">&nbsp;</em> Sent Reports</a> </li>

          <li class="{{ $current_uri === 'reportdetail' ? 'active-sub' : '' }}"><a href="{{ URL('/reportdetail') }}"><em class="fa fa-database">&nbsp;</em><?php
          //Recived DSRs
          $count = 0;

          $tempDsrs = App\Dsr::whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)")
          ->with([
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
            ])->get()->toArray();
            if($tempDsrs){

              foreach ($tempDsrs as $dsr) {

                if(!$dsr['read'] || $dsr['read'][0]['is_read'] == 0){
                  $count++;
                  continue;
                }
              }
            }
            ?>{!! $count ? '<b>' : '' !!} Received Reports{!! $count ? '</b> ('.$count.')' : '' !!}</a></li>
          </ul>
      </li>
      <li class="parent {{ in_array($current_uri, ['admin/dsr', 'sent_dsr', 'add_dsr']) ? 'active' : '' }}">
        <a data-toggle="collapse" class="{{ in_array($current_uri, ['admin/dsr', 'sent_dsr', 'add_dsr']) ? '' : 'collapsed' }}" href="#submenu-dsrs-1">
          <em class="fa fa-database">&nbsp;</em> DSRs<span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="fa fa-plus"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['admin/dsr', 'sent_dsr' ,'add_dsr','dsrdetail']) ? 'in' : '' }}" id="submenu-dsrs-1">
          <li class="{{ $current_uri === 'admin/dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/admin/dsr') }}"><em class="fa fa-paper-plane">&nbsp;</em> Manage DSRs</a> </li>
          <li class="{{ $current_uri === 'add_dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/add_dsr') }}"><em class="fa fa-pencil">&nbsp;</em> Add DSR</a> </li>
          <li class="{{ $current_uri === 'sent_dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/sent_dsr') }}"><em class="fa fa-paper-plane">&nbsp;</em> Sent DSRs</a> </li>
          <li class="{{ $current_uri === 'dsrdetail' ? 'active-sub' : '' }}"><a href="{{ URL('/dsrdetail') }}"><em class="fa fa-database">&nbsp;</em><?php
          //Recived DSRs
          $count = 0;

          $tempDsrs = App\Dsr::whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)")
          ->with([
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
            ])->get()->toArray();
            if($tempDsrs){

              foreach ($tempDsrs as $dsr) {

                if(!$dsr['read'] || $dsr['read'][0]['is_read'] == 0){
                  $count++;
                  continue;
                }
              }
            }
            ?>{!! $count ? '<b>' : '' !!} Received DSRs{!! $count ? '</b> ('.$count.')' : '' !!}</a></li>
        </ul>
      </li>
      <li class="parent {{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/designations']) ? 'active' : ''}}">
        <a data-toggle="collapse" class="" href="#submenu-hrm">
          <em class="fa fa-database">&nbsp;</em>HRM<span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="{{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/designations']) ? 'fa fa-minus' : 'fa fa-plus' }}"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/designations', 'admin/user/create']) ? 'in' : '' }}" id="submenu-hrm">
          <li class="{{ $current_uri === 'admin/users' ? 'active-sub' : '' }}"><a href="{{ URL('admin/users') }}"><em class="fa fa-users">&nbsp;</em>Employees</a> </li>
          <li class="{{ $current_uri === 'admin/department' ? 'active-sub' : '' }}"><a href="{{ URL('admin/department') }}"><em class="fa fa-building-o">&nbsp;</em>Department</a> </li>
          <li class="{{ $current_uri === 'admin/designations' ? 'active-sub' : '' }}"><a href="{{ URL('admin/designations') }}"><em class="fa fa-th-list">&nbsp;</em>Designations</a> </li>
        </ul>
      </li>
    @endif



    @if ($authRoleId == 4)
      <li class="{{$current_uri === 'dashboard' ? 'active' : ''}}">
        <a href="{{ URL('dashboard') }}"><em class="fa fa-dashboard">&nbsp;</em>Dashboard</a>
      </li>
      <li class="{{ $current_uri === 'attendance/user-attendance-list' ? 'active' : '' }}"><a href="{{ URL('attendance/user-attendance-list') }}"><i class="fa fa-calendar">&nbsp;</i>Manage Attendance</a> </li>
      <!-- <li class="{{ $current_uri === 'leave' ? 'active' : '' }}"><a href="{{ URL('leave') }}"><em class="fa fa-calendar">&nbsp;</em>Manage Leave</a> </li> -->
      <li class="parent {{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report']) ? 'active' : '' }}">
        <a data-toggle="collapse" class="{{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report']) ? '' : 'collapsed' }}" href="#submenu-report-1">
          <em class="fa fa-database">&nbsp;</em> Weekly Reports<span data-toggle="collapse" href="#submenu-reports-2" class="icon pull-right"><em class="fa fa-plus"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['reportdetail', 'sent_report' ,'add_report']) ? 'in' : '' }}" id="submenu-report-1">

          <li class="{{ $current_uri === 'add_report' ? 'active-sub' : '' }}"><a href="{{ URL('/add_report') }}"><em class="fa fa-pencil">&nbsp;</em> Add report</a> </li>

          <li class="{{ $current_uri === 'sent_report' ? 'active-sub' : '' }}"><a href="{{ URL('/sent_report') }}"><em class="fa fa-paper-plane">&nbsp;</em> Sent Reports</a> </li>

          <li class="{{ $current_uri === 'reportdetail' ? 'active-sub' : '' }}"><a href="{{ URL('/reportdetail') }}"><em class="fa fa-database">&nbsp;</em><?php
          //Recived DSRs
          $count = 0;

          $tempDsrs = App\Dsr::whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)")
          ->with([
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
            ])->get()->toArray();
            if($tempDsrs){

              foreach ($tempDsrs as $dsr) {

                if(!$dsr['read'] || $dsr['read'][0]['is_read'] == 0){
                  $count++;
                  continue;
                }
              }
            }
            ?>{!! $count ? '<b>' : '' !!} Received Reports{!! $count ? '</b> ('.$count.')' : '' !!}</a></li>
          </ul>
      </li>
      <li class="parent {{ in_array($current_uri, ['dsrdetail', 'sent_dsr', 'add_dsr']) ? 'active' : '' }}">
        <a data-toggle="collapse" class="{{ in_array($current_uri, ['dsrdetail', 'sent_dsr', 'add_dsr']) ? '' : 'collapsed' }}" href="#submenu-dsrs-1">
          <em class="fa fa-database">&nbsp;</em> DSRs<span data-toggle="collapse" href="#submenu-projects-2" class="icon pull-right"><em class="fa fa-plus"> </em> </span>
        </a>
        <ul class="children collapse {{ in_array($current_uri, ['dsrdetail', 'sent_dsr' ,'add_dsr']) ? 'in' : '' }}" id="submenu-dsrs-1">

          <li class="{{ $current_uri === 'add_dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/add_dsr') }}"><em class="fa fa-pencil">&nbsp;</em> Add DSR</a> </li>

          <li class="{{ $current_uri === 'sent_dsr' ? 'active-sub' : '' }}"><a href="{{ URL('/sent_dsr') }}"><em class="fa fa-paper-plane">&nbsp;</em> Sent DSRs</a> </li>

          <li class="{{ $current_uri === 'dsrdetail' ? 'active-sub' : '' }}"><a href="{{ URL('/dsrdetail') }}"><em class="fa fa-database">&nbsp;</em><?php
          //Recived DSRs
          $count = 0;

          $tempDsrs = App\Dsr::whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)")
          ->with([
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
            ])->get()->toArray();
            if($tempDsrs){

              foreach ($tempDsrs as $dsr) {

                if(!$dsr['read'] || $dsr['read'][0]['is_read'] == 0){
                  $count++;
                  continue;
                }
              }
            }
            ?>{!! $count ? '<b>' : '' !!} Recived DSRs{!! $count ? '</b> ('.$count.')' : '' !!}</a></li>
          </ul>
        </li>
          <li class="{{$current_uri === 'notification' ? 'active' : ''}}"><a href="{{URL('/notification') }}"><em class="fa fa-bell">&nbsp;</em> <?php
        //Recived Notification
        $count = App\Notification::where('user_id', '!=', $authId)->whereHas('dsr', function($q) use ($authId){
          $q->where("user_id", $authId)
          ->orWhereRaw("FIND_IN_SET('". $authId ."', to_ids)")
          ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
        })->where(function($cq) use ($authId){
          // $q->whereDoesntHave
          $cq->whereHas('notificationread', function($qhr) use ($authId){
            $qhr->where([
              'user_id' => $authId,
              'is_read' => 0
            ]);
          })
          ->orWhere(function($ccq) use ($authId){
            $ccq->whereDoesntHave('notificationread', function($qdhr) use ($authId){
              $qdhr->where('user_id', $authId);
            });
          });
        })->count();
        ?>
          {!! $count ? '<b>' : '' !!}Notification{!! $count ? '</b> ('.$count.')' : '' !!}</a>
      </li>
    @endif
  </ul>
</div>

<script>
$(document).ready(function(){
  var total_count = <?php echo $total_count; ?>;
  if(total_count) $('#dsr-menu-txt').html('<b>DSRs</b> ('+total_count+')');
});
</script>
