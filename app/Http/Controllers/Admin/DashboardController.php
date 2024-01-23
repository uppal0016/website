<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\FestivalCard;
use App\Quotes;
use Auth;
use App\Dsr;
use App\User;
use App\Project;
use App\Holiday;
use App\DsrDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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

        $totalDsrs = Dsr::count();

        $totalProjects =  Project::Where([
            ['status','!=',1],
            ['is_deleted',0]
        ])->count();

        $totalUsers = User::where([
            ['role_id', '!=', 1],
            ['is_deleted',0]
        ])->count();

        $today = Carbon::now()->format('Y-m-d');
        $next_date = Carbon::now()->addMonth(3)->format('Y-m-d');
        $date = now();
        // $date_of_birth = User::whereMonth('dob', '>', $date->month)
        //    ->orWhere(function ($query) use ($date) {
        //        $query->whereMonth('dob', '=', $date->month)
        //            ->whereDay('dob', '>=', $date->day);
        //    })
        //    ->orderByRaw("dob",'ASC')
        //    ->take(6)
        //    ->get();
        
       $date_of_birth = User::WhereRaw('MONTH(dob) >= MONTH(CURDATE())')->OrderBy(DB::raw("MONTH(dob),DAYOFMONTH(dob)"),'ASC')->where('is_deleted', '0')->where('status', '1')->take(10)->get();       
        $festival_dates = FestivalCard::whereBetween(DB::raw('DATE_FORMAT(festival_date, "%Y-%m-%d")'),[$today,$next_date])
        ->where('status','=',1)
        ->orderBy(DB::raw('DATE_FORMAT(festival_date, "%Y-%m-%d")'),'asc')->get();
        $upcoming_holiday = Holiday::whereRaw('MONTH(date) = ?', date('m'))->where('status',1)->get(); 
        
        // $upcoming_work_anniversary = User::whereRaw('DATE_FORMAT(joining_date, "%m-%d") = ?', [date('m-d')])->join('designations','designations.id','=','users.designation_id')->paginate(10);
        $upcoming_work_anniversary = User::whereRaw('DATE_FORMAT(DATE_ADD(joining_date, INTERVAL 1 YEAR), "%m-%d") = DATE_FORMAT(NOW(), "%m-%d")')->join('designations','designations.id','=','users.designation_id')->paginate(10);

        $aniversary_quote = Quotes::where('type','2')->whereDate('active_date','=',Carbon::now()->format('Y-m-d'))->first();
        if($aniversary_quote == null){
            $aniversary_quote = Quotes::where('type','2')->inRandomOrder()->first();
            if($aniversary_quote){
                //update created date for this quote
                $aniversary_quote->timestamps = false;
                Quotes::where('type','2')->where('id', '=', $aniversary_quote->id)->update(['active_date' => Carbon::now()->format('Y-m-d')]);
            }
        }
        $default_anniversay_quote = "An year of dedication, growth, and achievements. Happy Work Anniversary! Your contributions make a difference every day, and we're grateful to have you on our team.";
        $aniversary_quote =($aniversary_quote)?$aniversary_quote->quote:$default_anniversay_quote;

        $festival_dates->map(function ($data) {
            $data->dob = Carbon::parse($data->dob)->format('d F');
        });

        $attendance = Attendance::whereUserId(Auth::id())->whereDate('time_in', Carbon::now()->format('Y-m-d'))->first();
        $time_in = ''; $today_attendance = ''; $time_in_date = '';
        if(!empty($attendance)){
            $time_in = ($attendance->time_out =='') ? $attendance->time_in : '' ;
            $today_attendance = ($attendance->total_working_hour) ? $attendance->total_working_hour : '';
            $time_in_date = ($attendance->time_out =='') ? Carbon::parse($attendance->time_in)->format('Y-m-d') : '' ;
        }
         $dashboardCounts = [
            "total_projects" => $totalProjects,
            "dsrs_received" => $totalDsrs,
            "total_users" => $totalUsers
        ];

        //first check if there is any quote with active date today
        $quotes = Quotes::where('type','0')->whereDate('active_date','=',Carbon::now()->format('Y-m-d'))->first();
        if($quotes == null){
            $quotes = Quotes::inRandomOrder()->first();
            if($quotes){
                //update created date for this quote
                $quotes->timestamps = false;
                Quotes::where('id', '=', $quotes->id)->update(['active_date' => Carbon::now()->format('Y-m-d')]);
            }
        }
        $default_quote = "Ability is what you’re capable of doing. Motivation determines what you do. Attitude determines how well you do it."; 
        $quotation = ($quotes)?$quotes->quote:$default_quote;
        return view('dashboard.index', compact('dashboardCounts', 'time_in','today_attendance','time_in_date','date_of_birth', 'quotation', 'festival_dates','upcoming_holiday','upcoming_work_anniversary','aniversary_quote'));
    }

}
