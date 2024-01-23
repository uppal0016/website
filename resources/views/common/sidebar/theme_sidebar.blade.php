<!-- Sidenav -->
@php
App\Helpers\Helper::class;
$current_uri = Route::getFacadeRoot()->current()->uri();
if(!empty(\Illuminate\Support\Facades\Auth::user())){
$data =  Helper::sidebarQuery();
}
@endphp

<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="{{Url('/')}}">
                <img src="{{ URL::asset('images/tt-one.svg') }}" class="navbar-brand-img" alt="...">
            </a>
        </div>
        <div>
            <span class="close_sidebar"><i class="fa fa-times"></i></span>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
           
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav test_class-1">
                     @if(empty(\Illuminate\Support\Facades\Auth::user()))
                     <li class="nav-item {{$current_uri === 'dashboard' ? 'active' : ''}}">
                        <a class="nav-link " href="{{ URL('dashboard') }}">
                            <i class="fas fa-desktop"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $current_uri === 'attendance/list' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ URL('attendance/list') }}" id="ar">
                            <i class="ni ni-single-copy-04">&nbsp;</i>
                            <span class="nav-link-text"> Attendance Report</span>
                        </a>
                    </li>
                  
                
                @elseif((\Illuminate\Support\Facades\Auth::user()->role_id == 1) || (\Illuminate\Support\Facades\Auth::user()->role_id == 2))
                    <li class="nav-item {{$current_uri === 'admin/dashboard' ? 'active' : ''}}">
                        <a class="nav-link " href="{{ URL('admin/dashboard') }}">
                            <i class="fas fa-desktop"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $current_uri === 'attendance/list' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ URL('attendance/list') }}" id="AttendanceReport" >
                            <i class="ni ni-single-copy-04">&nbsp;</i>
                            <span class="nav-link-text"> Attendance Report</span>
                        </a>
                    </li>
                    <li class="nav-item submenu {{ in_array($current_uri, ['admin/projects', 'admin/create_project', 'admin/projects/{project}/edit', 'admin/project/get_assigned_employees/{pid}' ,'admin/dsr','reports','user_dsrs', 'admin/user_dsrs/{id}','admin/summary','reports-list', 'reportdetail/{id?}']) ? 'active' : ''}}" >
                        <a class="nav-link submenu-toggle" href="#">
                            <i class="fas fa-tasks"></i>
                            <span class="nav-link-text">Project Management</span>   
                            <i class="fas fa-caret-right ml-auto"></i>
                        </a>
                        <ul class="{{ in_array($current_uri, ['admin/projects','admin/create_project' , 'admin/dsr','reports','user_dsrs','admin/summary','reports-list']) ? 'in' : '' }}">
                            <li class="{{ in_array($current_uri, ['admin/summary']) ? 'sub-active' : '' }}"><a href="{{ URL('admin/summary') }}" id="Summary"><i class="fas fa-clipboard-list"></i> Summary</a></li>
                            <li class="{{ $current_uri === 'admin/dsr' || $current_uri === 'admin/user_dsrs/{id}' ? 'sub-active' : '' }}" id="DSRs"><a href="{{ URL('admin/dsr') }}"><i class="far fa-list-alt"></i> DSRs</a> </li>
                            <li class="{{ $current_uri === 'reports-list' || $current_uri === 'reportdetail/{id?}' ? 'sub-active' : '' }}"><a href="{{ URL('reports-list') }}" id="WR"><i class="fas fa-file-alt"></i> Weekly Reports</a> </li>
                            <li class="{{ $current_uri === 'admin/projects' || $current_uri === 'admin/create_project' || $current_uri === 'admin/projects/{project}/edit' || $current_uri === 'admin/project/get_assigned_employees/{pid}' ? 'sub-active' : '' }}"><a href="{{ URL('admin/projects') }}"><i class="fas fa-project-diagram" id="Projects"></i> Projects</a> </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ $current_uri === 'admin/team' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ URL('admin/team') }}">
                            <i class='fas fa-trademark'>&nbsp;</i>
                            <span class="nav-link-text"> Team Management</span>
                        </a>
                    </li>

                    <li class="nav-item submenu {{ in_array($current_uri, ['admin/inventory','admin/category','admin/vendor','admin/inventory_item','admin/assigned_stock','admin/qr_code','admin/qr_code/create','admin/inventory_item/create','admin/category/create', 'admin/vendor/create','admin/category/{category}/edit']) ? 'active' : '' }}">
                        <a class="nav-link submenu-toggle" href="#" id="inventory">
                        <i class="fas fa-sitemap"></i>
                            <span class="nav-link-text">Inventory</span>
                            <i class="fas fa-caret-right ml-auto"></i>
                        </a>
                        <ul class="{{ in_array($current_uri, ['admin/inventory','admin/category','admin/vendor','admin/inventory_item','admin/assigned_stock','admin/inventory_item/create']) ? 'in' : '' }}">
                            <li class="{{ $current_uri === 'admin/inventory' ? 'sub-active' : '' }}"><a href="{{ URL('admin/inventory') }}" id="il"><i class="fas fa-sitemap"></i> Inventory List</a> </li>
                            <li class="{{ $current_uri === 'admin/category' || $current_uri === 'admin/category/create' || $current_uri === 'admin/category/{category}/edit' ? 'sub-active' : '' }}"><a href="{{ URL('admin/category') }}" id="mc"><i class="fas fa-th"></i> Manage Categories</a> </li>
                            <li class="{{ $current_uri === 'admin/vendor' || $current_uri === 'admin/vendor/create' || $current_uri === 'admin/vendor/{vendor}/edit' ? 'sub-active' : '' }}"><a href="{{ URL('admin/vendor') }}" id="mv"><i class="fas fa-th">&nbsp;</i> Manage Vendors</a> </li>
                            <li class="{{ $current_uri === 'admin/inventory_item'|| $current_uri === 'admin/inventory_item/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/inventory_item') }}" id="mii"><i class="fas fa-th"></i> Manage Inventory Items</a> </li>
                            <li class="{{ $current_uri === 'admin/assigned_stock' ? 'sub-active' : '' }}"><a href="{{ URL('admin/assigned_stock') }}" id="mas"><i class="fas fa-user-check"></i> Manage Assigned Stock</a> </li>
                            <li class="{{ $current_uri === 'admin/qr_code' || $current_uri === 'admin/qr_code/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/qr_code') }}" id="mv"><i class="fas fa-th">&nbsp;</i> Manage QR code</a> </li>
                           
                        </ul>
                    </li>
                    <li class="nav-item submenu  {{ in_array($current_uri, ['admin/users', 'admin/user/create','admin/department','admin/department/create','admin/festival', 'admin/festival/create','admin/birthday', 'admin/birthday/create','admin/designations','admin/designations/create']) ? 'active' : '' }}">
                        <a class="nav-link submenu-toggle" href="#" id="hrm">
                        <i class="fas fa-users-cog"></i>
                            <span class="nav-link-text">HRM</span>
                            <i class="fas fa-caret-right ml-auto"></i>
                        </a>
                        <ul class="{{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/department/create','admin/user/create', 'admin/birthday', 'admin/birthday/create','admin/festival', 'admin/festival/create', 'admin/designations', 'admin/designations/create' , 'admin/user/create', 'admin/holiday', 'admin/holiday/create']) ? 'in' : '' }}">
                            <li class="{{ $current_uri === 'admin/users' || $current_uri === 'admin/user/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/users') }}" id="employee"><i class="fas fa-users"></i> Employees</a> </li>
                            <li class="{{ $current_uri === 'admin/department' || $current_uri === 'admin/department/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/department') }}" id="department"><i class="fas fa-building"></i> Department</a> </li>
                            <li class="{{ $current_uri === 'admin/designations' || $current_uri === 'admin/designations/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/designations') }}" id="designations"><i class="fas fa-user-tie"></i> Designations</a> </li>
                            <li class="{{ $current_uri === 'admin/birthday' || $current_uri === 'admin/birthday/create'? 'sub-active' : '' }}"><a href="{{ URL('admin/birthday') }}" id="mbc"><i class="fas fa-birthday-cake"></i> Manage Birthday Cards</a> </li>
                            <li class="{{ $current_uri === 'admin/festival' || $current_uri === 'admin/festival/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/festival') }}" id="mfc"><i class="fas fa-holly-berry"></i> Manage Festival Cards</a> </li>
                            <li class="{{ $current_uri === 'admin/holiday' || $current_uri === 'admin/holiday/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/holiday') }}" id="mh"><i class="fas fa-calendar-alt"></i> Manage Holidays</a> </li>
                        </ul>
                    </li>


                @elseif((\Illuminate\Support\Facades\Auth::user()->role_id == 3))
                    <li class="nav-item {{$current_uri === 'dashboard' ? 'active' : ''}}">
                        <a class="nav-link " href="{{ URL('dashboard') }}">
                            <i class="fas fa-desktop"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $current_uri === 'attendance/list' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ URL('attendance/list') }}" id="ar">
                            <i class="ni ni-single-copy-04">&nbsp;</i>
                            <span class="nav-link-text"> Attendance Report</span>
                        </a>
                    </li>
                    <li class="nav-item submenu {{ in_array($current_uri, ['admin/projects', 'admin/projects/{project}/edit','admin/user_dsrs/{id}' , 'admin/project/get_assigned_employees/{pid}'  ,'admin/dsr','reports','user_dsrs','admin/summary','reports-list', 'reportdetail/{id?}']) ? 'active' : ''}}">
                        <a class="nav-link submenu-toggle" href="#">
                            <i class="fas fa-tasks"></i>
                            <span class="nav-link-text">Project Management</span>
                            <i class="fas fa-caret-right ml-auto"></i>
                        </a>
                        <ul class="{{ in_array($current_uri, ['admin/projects','admin/projects/{project}/edit','admin/user_dsrs/{id}', 'admin/project/get_assigned_employees/{pid}', 'admin/dsr','reports','user_dsrs','admin/summary','reportdetail/{id?}']) ? 'in' : '' }}">
                            <li class="{{ in_array($current_uri, ['admin/summary']) ? 'sub-active' : '' }}"><a href="{{ URL('admin/summary') }}"><i class="fa fa-clipboard-list">&nbsp;</i> Summary</a></li>
                            <li class="{{ $current_uri === 'admin/dsr'  || $current_uri === 'admin/user_dsrs/{id}' ? 'sub-active' : '' }}"><a href="{{ URL('admin/dsr') }}"><i class="fa fa-list-alt">&nbsp;</i>DSRs</a> </li>
                            <li class="{{ $current_uri === 'reports-list' || $current_uri === 'reportdetail/{id?}' ? 'sub-active' : '' }}"><a href="{{ URL('reports-list') }}"><i class="fa fa-file-alt">&nbsp;</i>Weekly Reports</a> </li>
                            <li class="{{ $current_uri === 'admin/projects' || $current_uri === 'admin/create_project' || $current_uri === 'admin/projects/{project}/edit' || $current_uri === 'admin/project/get_assigned_employees/{pid}'? 'sub-active' : '' }}"><a href="{{ URL('admin/projects') }}"><i class="fa fa-project-diagram">&nbsp;</i>Projects</a> </li>
                        </ul>
                    </li>
           <li class="nav-item submenu {{ in_array($current_uri, ['add_dsr', 'sent_dsr']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="far fa-list-alt"></i>
                                <span class="nav-link-text">DSR</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                              <li class="{{ $current_uri === 'add_dsr' ? 'sub-active' : '' }}"><a  @if(empty($data['dsr'])) href="{{ URL('/add_dsr') }}" @else class="DSRSet" @endif ><i class="fas fa-plus"></i> Add DSR</a></li>
                                <li class="{{ $current_uri === 'sent_dsr' ? 'sub-active' : '' }}"><a href="{{ URL('/sent_dsr') }}"><i class="fas fa-paper-plane"></i> Sent DSR</a> </li>
                            </ul>
                        </li>
                    <li class="nav-item submenu {{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report', 'reportdetail', 'admin/summary']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="fas fa-file-alt"></i>
                                <span class="nav-link-text">Weekly Report</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                                <li class="{{ $current_uri === 'add_report' ? 'sub-active' : '' }}"><a @if(empty($data['weekly'])) href="{{ URL('/add_report') }}" @else  class="WEEKLYSet" @endif ><i class="fas fa-plus"></i>  Add Report</a></li>
                                <li class="{{ $current_uri === 'sent_report' ? 'sub-active' : '' }}"><a href="{{ URL('/sent_report') }}"><i class="fas fa-paper-plane"></i> Sent Report</a> </li>
                            </ul>
                        </li>
                @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 4)
                        <li class="nav-item">
                            <a class="nav-link {{$current_uri === 'dashboard' ? 'active' : ''}}" href="{{ URL('dashboard') }}">
                                <i class="fas fa-desktop"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                     
                        <li class="nav-item submenu {{ in_array($current_uri, ['attendance/user-attendance-list', 'attendance/team-attendance-list', ]) ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL('attendance/user-attendance-list') }}">
                            <i class="fas fa-chess-board"></i>
                                <span class="nav-link-text">Attendance Report</span>
                            </a>
                        </li>
                        <li class="nav-item submenu {{ in_array($current_uri, ['add_dsr', 'sent_dsr', 'dsrdetail','team_dsr','user_dsrs/{id}']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="far fa-list-alt"></i>
                                <span class="nav-link-text">DSR</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                               <li class="{{ $current_uri === 'add_dsr' ? 'sub-active' : '' }}"><a  @if(empty($data['dsr'])) href="{{ URL('/add_dsr') }}" @else  class="DSRSet" @endif ><i class="fas fa-plus"></i> Add DSR</a></li>
                                <li class="{{ $current_uri === 'sent_dsr' ? 'sub-active' : '' }}"><a href="{{ URL('/sent_dsr') }}"><i class="fas fa-paper-plane"></i> Sent DSR</a> </li>
                                <li class="{{ $current_uri === 'dsrdetail' ? 'sub-active' : '' }}"><a href="{{ URL('/dsrdetail') }}"><i class="fas fa-database"></i>  Received DSR</a> </li>
                               @if(!empty($data['team_dsr']))
                                <li class="{{ $current_uri === 'team_dsr'  || $current_uri === 'user_dsrs/{id}' ? 'sub-active' : '' }} }}"><a href="{{ URL('/team_dsr') }}"><i class="fab fa-microsoft"></i>  Teams DSR</a> </li>
                                @endif
                        </li>
                            </ul>
                        </li>
                        <li class="nav-item submenu {{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report', 'admin/summary']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="fas fa-file-alt"></i>
                                <span class="nav-link-text">Weekly Report</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                                <li class="{{ $current_uri === 'add_report' ? 'sub-active' : '' }}"><a @if(empty($data['weekly'])) href="{{ URL('/add_report') }}" @else  class="WEEKLYSet" @endif ><i class="fas fa-plus"></i>  Add Report</a></li>
                                <li class="{{ $current_uri === 'sent_report' ? 'sub-active' : '' }}"><a href="{{ URL('/sent_report') }}"><i class="fas fa-paper-plane"></i> Sent Report</a> </li>
                                <li class="{{ $current_uri === 'reportdetail' ? 'sub-active' : '' }}"><a href="{{ URL('/reportdetail') }}"><i class="fas fa-database"></i> Received Report</a> </li>
                            </ul>
                        </li>
                @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 5 )
                        <li class="nav-item">
                            <a class="nav-link {{$current_uri === 'dashboard' ? 'active' : ''}}" href="{{ URL('dashboard') }}">
                                <i class="fas fa-desktop"></i>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item {{ $current_uri === 'attendance/list' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL('attendance/list') }}">
                                <i class="ni ni-single-copy-04">&nbsp;</i>
                                <span class="nav-link-text"> Attendance Report</span>
                            </a>
                        </li>
                        
                       
                 
                        <li class="nav-item submenu {{ in_array($current_uri, ['add_dsr', 'sent_dsr', 'dsrdetail','admin/dsr','team_dsr']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="far fa-list-alt"></i>
                                <span class="nav-link-text">DSR</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                                <li class="{{ $current_uri === 'add_dsr' ? 'sub-active' : '' }}"><a  @if(empty($data['dsr'])) href="{{ URL('/add_dsr') }}" @else  class="DSRSet" @endif ><i class="fas fa-plus"></i> Add DSR</a></li>
                                <li class="{{ $current_uri === 'sent_dsr' ? 'sub-active' : '' }}"><a href="{{ URL('/sent_dsr') }}"><i class="fas fa-paper-plane"></i> Sent DSR</a> </li>
                                <li class="{{ $current_uri === 'dsrdetail' ? 'sub-active' : '' }}"><a href="{{ URL('/dsrdetail') }}"><i class="fas fa-database"></i>     Received DSR</a> </li>
                                @if(!empty($data['team_dsr']))
                                <li class="{{ $current_uri === 'team_dsr' ? 'sub-active' : '' }}"><a href="{{ URL('/team_dsr') }}"><i class="fab fa-microsoft"></i>  Teams DSR</a> </li>
                                @endif
                            </ul>
                        </li>
                        <li class="nav-item submenu {{ in_array($current_uri, ['reportdetail', 'sent_report', 'add_report', 'reports']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="fas fa-file-alt"></i>
                                <span class="nav-link-text">Weekly Report</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                                <li class="{{ $current_uri === 'add_report' ? 'sub-active' : '' }}"><a @if(empty($data['weekly'])) href="{{ URL('/add_report') }}" @else  class="WEEKLYSet" @endif ><i class="fas fa-plus"></i>  Add Report</a></li>
                                <li class="{{ $current_uri === 'sent_report' ? 'sub-active' : '' }}"><a href="{{ URL('/sent_report') }}"><i class="fas fa-paper-plane"></i> Sent Report</a> </li>
                                <li class="{{ $current_uri === 'reportdetail' ? 'sub-active' : '' }}"><a href="{{ URL('/reportdetail') }}"><i class="fas fa-database"></i> Received Report</a> </li>
                            </ul>
                        </li>
                        <li class="nav-item submenu  {{ in_array($current_uri, ['admin/users', 'admin/user/create','admin/department','admin/department/create','admin/festival', 'admin/festival/create','admin/birthday', 'admin/birthday/create','admin/designations','admin/designations/create']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle" href="#">
                                <i class="fas fa-users-cog"></i>
                                <span class="nav-link-text">HRM</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul class="{{ in_array($current_uri, ['admin/users', 'admin/department', 'admin/department/create','admin/user/create', 'admin/birthday', 'admin/birthday/create','admin/festival', 'admin/festival/create', 'admin/designations', 'admin/designations/create' , 'admin/user/create', 'admin/holiday', 'admin/holiday/create']) ? 'in' : '' }}">
                                <li class="{{ $current_uri === 'admin/users' || $current_uri === 'admin/user/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/users') }}"><i class="fas fa-users"></i> Employees</a> </li>
                                <li class="{{ $current_uri === 'admin/department' || $current_uri === 'admin/department/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/department') }}"><i class="fas fa-building"></i> Department</a> </li>
                                <li class="{{ $current_uri === 'admin/designations' || $current_uri === 'admin/designations/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/designations') }}"><i class="fas fa-user-tie"></i> Designations</a> </li>
                                <li class="{{ $current_uri === 'admin/birthday' || $current_uri === 'admin/birthday/create'? 'sub-active' : '' }}"><a href="{{ URL('admin/birthday') }}"><i class="fas fa-birthday-cake"></i> Manage Birthday Cards</a> </li>
                                <li class="{{ $current_uri === 'admin/festival' || $current_uri === 'admin/festival/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/festival') }}"><i class="fas fa-holly-berry"></i> Manage Festival Cards</a> </li>
                                <li class="{{ $current_uri === 'admin/holiday' || $current_uri === 'admin/holiday/create' ? 'sub-active' : '' }}"><a href="{{ URL('admin/holiday') }}"><i class="fas fa-calendar-alt"></i> Manage Holidays</a> </li>
                            </ul>
                        </li>
                @endif

                @if(empty(\Illuminate\Support\Facades\Auth::user()))
                    
                    @elseif(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3]))
                    <li class="nav-item  {{$current_uri === 'employees' ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL('employees') }}">
                            <i class="fas fa-users"></i> Team Members
                        </a> 
                    </li>
                @endif

                @if(empty(\Illuminate\Support\Facades\Auth::user()))
                    
                    @elseif(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [1, 2, 3, 5]))
                    <li class="nav-item  {{$current_uri === 'team_member_chart' ? 'active' : ''}}">
                        <a class="nav-link" href="{{ URL('team_member_chart') }}">
                            <i class="fas fa-users"></i> Company Hierarchical Chart
                        </a> 
                    </li>
                @endif
                  @if(!empty(\Illuminate\Support\Facades\Auth::user()))
                <li class="nav-item submenu {{ in_array($current_uri, ['leave/create', 'cancel/leave','leave','my/leave','team-leave']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle" id="ML">
                            <i class="fas fa-snowman" aria-hidden="true" ></i>
                            
                                <span class="nav-link-text">Manage Leaves</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul> 
                            
                            <li class="{{ $current_uri === 'leave/create' ? 'sub-active' : '' }}"><a class="ff" href="{{ URL('/leave/create') }}" id="AL"><i class="fas fa-plus "></i>Apply Leave</a> </li>  <li class="{{ $current_uri === 'cancel/leave' ? 'sub-active' : '' }}"><a class="ff" href="{{ URL('/cancel/leave') }}" id="ACL"><i class="fa fa-times " style="color:red"></i>Cancel Leave </a> </li>                              
                            <li class="{{ $current_uri === 'leave' ? 'sub-active' : '' }}"><a class="ff" href="{{ URL('/leave') }}" style="font-size:12px" id="ELR"><i class="fas fa-list"></i></i> @if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,5,1])) Employee Leave Requests @else My Leave Request @endif </a> </li>
                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,5,1]))
                            <li class="{{ $current_uri === 'my/leave' ? 'sub-active' : '' }}"><a class="ff" href="{{ URL('/my/leave') }}" id="MLR"><i class="fas fa-list"></i></i>My Leave Requests</a> </li>
                            @endif
                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3,4,5]) && !empty($data['team']))
                            <li class="{{ $current_uri === 'team-leave' ? 'sub-active' : '' }}"><a class="ff" href="{{ URL('team-leave') }}" id="TLR"><i class="fas fa-list"></i></i>Team Leave Requests</a> </li>
                            @endif
                        </ul>
                        </li>
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->interviewPanelStatus, [1]))
                        <li class="nav-item">
                            <a class="nav-link"  target="_blank" href="{{url('generate/token')}}">
                            <i class = "fa fa-calendar"></i>  
                                <span class="nav-link-text">Interview Panel</span>
                            </a>
                        </li>
                         @endif
                        @endif
                        @if(Auth::user())
                        <li class="nav-item {{ $current_uri === 'tickets/list' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL('tickets/list') }}" id="ar">
                                <i class="ni ni-single-copy-04">&nbsp;</i>
                                <span class="nav-link-text">Harmony Tickets</span>
                            </a>
                        </li>
                        <li class="nav-item submenu {{ in_array($current_uri, ['document', '/manage/document']) ? 'active' : '' }}">
                            <a class="nav-link submenu-toggle">
                                <i class="fas fa fa-file"></i>
                                <span class="nav-link-text">Document Management</span>
                                <i class="fas fa-caret-right ml-auto"></i>
                            </a>
                            <ul>
                                @if(\Illuminate\Support\Facades\Auth::user()->role_id == 5)  
                                <li class="{{ $current_uri === 'document' ? 'sub-active' : '' }}"><a href="{{ URL('/document') }}"><i class="fa fa-upload"></i> Document upload</a></li>
                                <li class="{{ $current_uri === '/password_history' ? 'sub-active' : '' }}"><a href="{{ URL('/password_history') }}"><i class="fa fa-upload"></i> Password History</a></li>
                                <li class="{{ $current_uri === '/document_management' ? 'sub-active' : '' }}"><a href="{{ URL('/document_management') }}"><i class="fa fa-upload"></i> Active Documents</a></li>
                                <li class="{{ $current_uri === '/request_documents' ? 'sub-active' : '' }}"><a href="{{ URL('/request_documents') }}"><i class="fa fa-upload"></i> Request Documents</a></li>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->role_id !==5)  
                                <li class="{{ $current_uri === '/manage/document' ? 'sub-active' : '' }}"><a href="{{ URL('/manage/document') }}"><i class="fas fa fa-file"></i> View Document </a> </li>
                              @endif
                            </ul>
                        </li>
                        @endif
                      
                      

                        @if(Auth::user())
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->it_ticket_dashboard, [1]))
                        <li class="nav-item submenu {{ in_array($current_uri, ['it-tickets/list', 'it-tickets/dashboard']) ? 'active' : '' }}">
                                    <a class="nav-link submenu-toggle" id="ML">
                                    <i class="fas fa-snowman" aria-hidden="true" ></i>
                                    
                                        <span class="nav-link-text">IT Tickets</span>
                                        <i class="fas fa-caret-right ml-auto"></i>
                                    </a>
                                    <ul>
                                        <li class="nav-item {{ $current_uri === 'it-tickets/list' ? 'sub-active' : '' }}">
                                            <a class="nav-link" href="{{ URL('it-tickets/list') }}" id="ar">
                                                <i class="ni ni-single-copy-04">&nbsp;</i>
                                                <span class="nav-link-text">IT Tickets</span>
                                            </a>
                                        </li>
                                        <li class="nav-item {{ $current_uri === 'it-tickets/dashboard' ? 'sub-active' : '' }}">
                                            <a class="nav-link" href="{{ URL('it-tickets/dashboard') }}" id="ar">
                                                <i class="ni ni-single-copy-04">&nbsp;</i>
                                                <span class="nav-link-text">IT Tickets Dashboard</span>
                                            </a>
                                        </li>
                                    
                                    </ul>
                        @else
                        <li class="nav-item {{ $current_uri === 'it-tickets/list' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL('it-tickets/list') }}" id="ar">
                                <i class="ni ni-single-copy-04">&nbsp;</i>
                                <span class="nav-link-text">IT Tickets</span>
                            </a>
                        </li>
                        @endif
                        @endif
                        <li class="nav-item {{ $current_uri === 'reference/list' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ URL('reference/list') }}" id="reference">
                                <i class="ni ni-single-copy-04">&nbsp;</i>
                                <span class="nav-link-text">Rapper</span>
                            </a>
                        </li>
                </ul>

            </div>
        </div>
    </div>
</nav>