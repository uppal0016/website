<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Storage;
use Crypt;
use App\User;
use App\Dsr;
use App\WeeklyReport;
use App\WeeklyReportRead;
use App\Project;
use App\WeeklyReportDetail;
use Carbon\Carbon;
use App\Notification;
use App\ProjectAssigned;
use App\Traits\CommonMethods;
use App\Traits\Sanitize;
use App\Jobs\NewReportEmail;
use App\Http\Controllers\AttendanceController;

class WeeklyReportController extends Controller
{
    use CommonMethods, Sanitize;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authId = Auth::id();

        if((Auth::user()->role_id == 1) || (Auth::user()->role_id == 2) || (Auth::user()->role_id == 3)){
            $reportUsers =  User::where('is_deleted',0)->whereNotIn('role_id', [1,2])
                ->withCount('weeklyreport');
        } else {
            $reportUsers =  User::where('is_deleted',0)->whereNotIn('role_id', [1,2])
                ->withCount(['weeklyreport' =>function($q) use ($authId){
                    $q->whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                        ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
                }]);
        }

        if ($request->isMethod('post')) {
            $keyword = $request->get('search');
            $reportUsers = $reportUsers->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"]);
            $view = 'weekly_report.search';
        } else {
            $view = 'weekly_report.home';
        }

        $reportUsers = $reportUsers->latest()->paginate(10);
        $reportUsers->setPath('reports-list');
        return view($view, compact('reportUsers','authId'));
    }

    public function report(Request $request, $id = null)
    {
        if(!empty($id)){
            $enId = $id;
            $authId = Crypt::decrypt($id);
        } else {
            $authId = Auth::user()->id;
            $enId = Crypt::encrypt($authId);
        }

        $idToHighlight = $request->get('dsrId');
        $redirect = $request->get('redirect');


        if (in_array(Auth::user()->role_id, [4,5])){
            $reports = WeeklyReport::orWhereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
        } else {
            $reports = WeeklyReport::where('user_id', $authId);
        }

        $reports = $reports->with([
            'details.project',
            'user',
            'read' =>function($q) use ($authId){
                $q->where('user_id', $authId);
            }
        ])
            ->orderBy('created_at', 'desc');

        if(!$redirect || !$idToHighlight){

            if($idToHighlight){

                $idToHighlight = Crypt::decrypt($idToHighlight);
                $reports = $reports->orWhere('user_id', $authId);
            }

            $reports = $reports->paginate(10);
            return view('weekly_report.index',compact('reports','enId'));
        }

        /*--- Page number logic ---*/
        $idToHighlight = Crypt::decrypt($idToHighlight);

        DB::transaction(function() use ($authId, $idToHighlight){
            DB::select('SET @row_number = 0;');
            $this->result = DB::select("SELECT row_no, id FROM (
              SELECT (@row_number:=@row_number + 1) as row_no, id FROM weekly_reports 
              WHERE 
                user_id = ".$authId."
              OR
                FIND_IN_SET('".$authId."',to_ids) 
              OR 
                FIND_IN_SET('".$authId."',cc_ids)
              ORDER BY id DESC 
            ) as t WHERE t.id = ".$idToHighlight.";");
        });

        if(!$this->result){
            return view('weekly_report.index',compact('reports','enId'));
        }

        $row_no = $this->result[0]->row_no;

        $pageNo = ceil($row_no/10);
        /*--- Page number logic ---*/

        $query = [
            'page' => $pageNo,
            'dsrId' => Crypt::encrypt($idToHighlight)
        ];

        return redirect()->route('common.emp.dsrdetail', $query);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->all();
        $auth = Auth::user();
        $rules = WeeklyReport::saveReportVd($data);

        $validator = Validator::make($data, $rules);
        if($validator->fails()){

            return back()->with([ 'error_flash_message' => $validator->errors()->first()])
                ->withInput($request->input());
        }

        $toIds = implode(",", $data['send_to']);
        $ccIds = !empty($data['add_cc']) ? implode(",", $data['add_cc']) : '';

        $reportModel = WeeklyReport::create([
            'user_id' => $auth->id,
            'to_ids' => $toIds,
            'cc_ids' => $ccIds
        ]);

        if(!$reportModel){

            return back()->with([ 'error_flash_message' => 'Error sending weekly report']);
        }

        $response = $this->addReportDetailsData($data, $reportModel->id);

        if(!$response['success']){

            return back()->with([ 'error_flash_message' => $response['message'] ]);
        }

        $thisReport=json_decode($this->ReportDetails(Crypt::encrypt($reportModel->id),0)->getContent(), true);

        if($thisReport){

            try{

                $users = array_merge($thisReport['to_users'], $thisReport['cc_users']);

                foreach ($users as $key => $user) {

                    $data = [
                        'sender' => $thisReport['user'],
                        'receiver' => $user,
                        'report_details' => $thisReport['details']
                    ]; 
                    $check_dsr = Dsr::whereUserId(Auth::id())->whereDate('created_at', Carbon::now()->format('Y-m-d'))->where('to_ids','!=','')->first();
                    if($check_dsr) {
                    $timeout = new AttendanceController;
                    $timeout->timeOutAction($request);  
                     }                  
                   
                    //   $job = (new NewReportEmail($user, $data))
                    //           ->delay(Carbon::now()->addSeconds(3));
                    dispatch(new NewReportEmail($user, $data));

                    //   dispatch($job);
                }
            }catch(\Exception $e){


            }
        }
        return redirect('sent_report')->with('flash_message', 'Weekly report sent successfully');
        // return back()->with('flash_message', 'Weekly report sent successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function add_Reports(Request $request)
    {
        $auth=Auth::user();
        $email_users = User::whereIn('role_id', [1,2, 3])->where('status',  1)->where('is_deleted',0)->get();
        $cc_users = User::where([
            ['id', '!=', $auth->id],
            ['is_deleted', '=', 0],
            ['status', '=', 1]
        ])->whereIn('role_id', [4,5])->get();
        $projects = Project::select('id','name','project_manager','team_lead')->whereHas('project_assigned',function($query) use($auth){
            $query = $query->where('user_id', $auth->id);
        })->where('status','!=','0')->where('is_deleted','!=', 1)->get();

        return view('weekly_report.add', compact('email_users', 'cc_users', 'projects'));
    }


    public function addReportDetailsData($data, $lastInsertId){

        $dataResponse = [ "success" => false, 'message' => ''];

        if(empty($data['project_id'])){

            $dataResponse['message'] = 'Failed to send weekly report';
            return $dataResponse;
        }

        $reportDetails = [];
        foreach($data['project_id'] as $k => $v){

            foreach ($data['des'][$k] as $key => $des) {

                $tHr = (string) ( isset($data['hours'][$k][$key]) ? $data['hours'][$k][$key] : 0);
                $tM = (string) ( isset($data['minutes'][$k][$key]) ? $data['minutes'][$k][$key] : 0);
                $tM = $tM <= 9 ? "0".$tM : $tM;

                $tt = $tHr.".".$tM;
                $reportDetails[] = [
                    'report_id'      => $lastInsertId,
                    'project_id'  => $v,
                    'description' => $des,
                ];
            }
        }

        $report_detail = WeeklyReportDetail::insert($reportDetails);

        $dataResponse["success"] = ($report_detail) ? true : false;
        return $dataResponse;
    }


    public function ReportDetails($id, $markRead = 1 )
    {
        $id = Crypt::decrypt($id);
        $auth = Auth::user();
        $report = WeeklyReport::where('id', $id)->with(['details' => function($q) use ($id){
            $q->with(['project']);
        }, 'user'])->first();
        if($report){
            $report = $report->toArray();
        }

        if( $report && $report['details'] ) {
            $reportDetails = [];
            foreach($report['details'] as $k => $detail) {
                if(!$reportDetails){
                    $reportDetails[] = [
                        "project"    => $detail['project'] ? $detail['project'] : '',
                        "project_id" => $detail['project_id'],
                        "details"    => [$detail],
                    ];
                    continue;
                }
                $key = array_search($detail['project_id'], array_column($reportDetails,'project_id'));
                if($key === 0 || $key != null){
                    $reportDetails[$key]['details'][] = $detail;
                    continue;
                }
                $reportDetails[] = [
                    "project"    => $detail['project'] ? $detail['project'] : '',
                    "project_id" => $detail['project_id'],
                    "details"    => [$detail],
                ];
            }
            $report['details'] = $reportDetails;
        }
        $report = WeeklyReport::toAndCCUsers($report, 1);
        if($markRead) {
            $this->markReportRead($id, $auth->id);
        }

        return response()->json($report, 200);
    }



    public function Reportsent()
    {
        $authId = Auth::user()->id;
        $enId = Crypt::encrypt($authId);
        $reports = WeeklyReport::where('user_id', $authId)
            ->with('details.project')
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('weekly_report.index',compact('reports','enId'));
    }

    public function getReports(Request $request)
    {
        $search = $request->get('search');
        $view = $request->get('view');
        $id = $request->get('id');
        $auth = Auth::user();

        if(!$id){

            $this->response['message'] = 'User not found';
            return response()->json($this->response, 400);
        }

        $search = $this->sanitize($search);
        $id = Crypt::decrypt($id);
        $reports = WeeklyReport::select('weekly_reports.*' ,'p.name','dd.description','dd.report_id')
            ->leftJoin('weekly_report_details as dd', 'dd.report_id', '=', 'weekly_reports.id')
            ->leftJoin('projects as p', 'dd.project_id', '=', 'p.id');

        if($view){

            if($view == 'sent_report'){

                $reports = $reports->where('weekly_reports.user_id', $id);
            }else{
                $reports = $reports->where(function($query) use($id) {
                    return $query->whereRaw("FIND_IN_SET('". $id ."', weekly_reports.to_ids)")
                        ->orWhereRaw("FIND_IN_SET('". $id ."', weekly_reports.cc_ids)");
                });
            }
        }else{

            if (in_array(Auth::user()->role_id, [3,4,5])){
                $reports = $reports->where(function($query) use($id) {
                    return $query->whereRaw("FIND_IN_SET('". $id ."', weekly_reports.to_ids)")
                        ->orWhereRaw("FIND_IN_SET('". $id ."', weekly_reports.cc_ids)");
                });
            } else {
                $reports = $reports->where('weekly_reports.user_id', $id);

            }

        }


        if(!empty($search)){

            $reports = $reports->where(function($q) use ($search){
                $q->orWhere('p.name', 'LIKE', '%'.$search.'%')
                    ->orWhere('dd.description', 'LIKE', '%'.$search.'%');
            });
        }

        $reports = $reports->with([
            'details.project',
            'user',
            'read' =>function($q) use ($auth){
                $q->where('user_id', $auth->id);
            }
        ])->orderBy('weekly_reports.created_at', 'desc')->get();

        foreach ($reports as $report) {
            $project_name = 'N-A';
            $description = '';
            $highlight = ($report['read']->count() && $report['read'][0]['is_read'] == 1) ? 0:1;

            if($report['details']->count()){

                if($report['details'][0]['project']){

                    $project_name = $report['details'][0]['project']['name'];
                }
                $description = substr($report['details'][0]['description'], 0, 20);
            }

            $report->user_full_name = $report->user ? $report->user->fullname : 'N-A';
            $report->project_name = $project_name;
            $report->description = $description;
            $report->highlight = $highlight;
            $report->en_id = Crypt::encrypt($report->id);
        }

        $this->response['data'] = $reports;
        $this->response['success'] = true;
        $this->response['status'] = 200;

        return response()->json($this->response, 200);
    }
}