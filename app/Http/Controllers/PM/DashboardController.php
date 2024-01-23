<?php

namespace App\Http\Controllers\PM;

use Auth;
use App\Dsr;
use App\User;
use App\Project;
use App\DsrDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use App\Attendance;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void 
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth::user();
        $projectsCount = 0;
        $totalDsrs = Dsr::whereRaw("FIND_IN_SET(".$auth->id.", to_ids)")
                        ->orWhereRaw("FIND_IN_SET(".$auth->id.", cc_ids)")->count();
        $totalProjects = Project::Where('status','!=',1)->where('is_deleted',0)->count();
        $totalUsers = User::whereNotIn('role_id', [1])->where('id', '!=', $auth->id)->where('is_deleted',0)->count();
        $today = Carbon::now()->format('m-d');
        $next_date = date('m-d', strtotime("+3 months", strtotime(Carbon::now()->format('Y-m-d'))));

        $ $date = now();
        $date_of_birth = User::whereMonth('dob', '>', $date->month)
           ->orWhere(function ($query) use ($date) {
               $query->whereMonth('dob', '=', $date->month)
                   ->whereDay('dob', '>=', $date->day);
           })
           ->orderByRaw("dob",'ASC')
           ->take(6)
           ->get();
        $attendance = Attendance::whereUserId(Auth::id())->orderBy('id','DESC')->first();
        $today_attendance = Attendance::whereUserId(Auth::id())
        ->where(DB::raw('DATE_FORMAT(time_in, "%Y-%m-%d")') , '=',Carbon::now()->format('Y-m-d'))
        // ->whereNotNull('time_out')
        ->first();

        $time_in = (!empty($today_attendance)) ? ($attendance->time_out =='') ? $attendance->time_in : '' : '';
        $today_attendance = (!empty($today_attendance)) ? $today_attendance->total_working_hour : '';
        $dashboardCounts = [
            "total_projects" => $totalProjects,
            "dsrs_received" => $totalDsrs,
            "total_users" => $totalUsers
        ];

        return view('dashboard.index', compact('dashboardCounts','time_in' , 'today_attendance','date_of_birth'));
    }
}
