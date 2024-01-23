<?php

namespace App\Http\Controllers\Employee;

use App\FestivalCard;
use App\Quotes;
use Auth;
use App\Dsr;
use App\Holiday;
use App\ProjectAssigned;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Attendance;
use DB;
use Carbon\Carbon;
use App\User;
use App\Project;
use App\Document;
use App\DocumentPassword ;
use App\DocumentRead ;
use App\Helpers\Helper;
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
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   27-07-2018 (dd-mm-yyyy)
    * @purpose         :   Display dashboard view with project assigned and dsr sent counts 
    * @params          :   
    * @return          :   response as []
    */
    public function index()
    {  
      
        $auth = Auth::user();  
        if((Auth::user()->role_id == 1) || (Auth::user()->role_id == 2)){
            return redirect('admin/dashboard');
        }
        if(Auth::user()->role_id == 4){
            $projectsCount = 0;

            $reciveDsrs = Dsr::whereRaw("FIND_IN_SET(".$auth->id.", to_ids)")
                            ->orWhereRaw("FIND_IN_SET(".$auth->id.", cc_ids)")->count();
            
            $dsrsCount = Dsr::where('user_id', $auth->id)->count();
            $projectsAssigned = ProjectAssigned::where('user_id', $auth->id)->count();

            $dashboardCounts = [
                "projects_assigned" => $projectsAssigned,
                "dsrs_sent" => $dsrsCount,
                "dsrs_received" => $reciveDsrs
            ];
        } elseif((Auth::user()->role_id == 5) || (Auth::user()->role_id == 3)){
            $totalDsrs = Dsr::whereRaw("FIND_IN_SET(".$auth->id.", to_ids)")
            ->orWhereRaw("FIND_IN_SET(".$auth->id.", cc_ids)")->count();

            $totalProjects =  Project::Where([
                ['status','!=',1],
                ['is_deleted',0]
            ])->count();
            $dsrsCount = Dsr::where('user_id', $auth->id)->count();
            $totalUsers = User::whereNotIn('role_id', [1])->where('is_deleted',0)->count();

            $dashboardCounts = [
                "total_projects" => $totalProjects,
                "dsrs_sent" => $dsrsCount,
                "dsrs_received" => $totalDsrs,
                "total_users" => $totalUsers
            ];
        }
        

        
//        $attendance = Attendance::whereUserId(Auth::id())->orderBy('id','DESC')->first();

        $today = Carbon::now()->format('Y-m-d');
        $next_date = Carbon::now()->addMonth(3)->format('Y-m-d');      

        // $date_of_birth = User::whereBetween("dob",[$today,$next_date])
        //     ->orderBy(DB::raw('DATE_FORMAT(dob, "%m")'),'asc')->get();
        // $date_of_birth->map(function ($data) {
        //     $data->dob = Carbon::parse($data->dob)->format('d F');
        // });

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

        $upcoming_work_anniversary = User::whereRaw('DATE_FORMAT(joining_date, "%m-%d") = ?', [date('m-d')])->join('designations','designations.id','=','users.designation_id')->paginate(10);

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

        $festival_dates = FestivalCard::whereBetween(DB::raw('DATE_FORMAT(festival_date, "%Y-%m-%d")'),[$today,$next_date])
            ->where('status','=',1)
            ->orderBy(DB::raw('DATE_FORMAT(festival_date, "%Y-%m-%d")'),'asc')->get();
      $upcoming_holiday = Holiday::whereRaw('MONTH(date) = ?', date('m'))->where('status',1)->get();
        $festival_dates->map(function ($data) {
            $data->dob = Carbon::parse($data->dob)->format('d F');
        });

      
        // echo Carbon::now()->format('Y-m-d');exit;
        $attendance = Attendance::whereUserId(Auth::id())->whereDate('time_in', Carbon::now()->format('Y-m-d'))->first();
        $time_in = ''; $today_attendance = ''; $time_in_date = '';
        if(!empty($attendance)){
            $time_in = ($attendance->time_out =='') ? $attendance->time_in : '' ;
            $today_attendance = ($attendance->total_working_hour) ? $attendance->total_working_hour : '';
            $time_in_date = ($attendance->time_out =='') ? Carbon::parse($attendance->time_in)->format('Y-m-d') : '' ;
        }

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
        $calculateleave =   Helper::totalleaveleft();
        $totaltime =   Helper::documentDetail();
        $documentRead = DocumentRead::join('documents','documents.id','=','document_read.document_id')->with('user')->where(['document_read.user_id'=>Auth::user()->id])->get();
        // dd( $documentRead);       
        $default_quote = "Ability is what you’re capable of doing. Motivation determines what you do. Attitude determines how well you do it."; 
        $quotation = ($quotes)?$quotes->quote:$default_quote;
        return view('dashboard.index', compact('dashboardCounts' , 'time_in' , 'today_attendance','date_of_birth','time_in_date', 'quotation', 'festival_dates','calculateleave','upcoming_holiday','upcoming_work_anniversary','aniversary_quote'));
    }

}
