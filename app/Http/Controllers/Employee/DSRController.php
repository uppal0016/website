<?php

namespace App\Http\Controllers\Employee;

use DB;
use Auth;
use Crypt;
use App\Dsr;
use App\User;
use App\Team;
use App\DsrComment;
use App\DsrRead;
use App\DsrFile;
use App\Project; 
use App\DsrDetail;
use Carbon\Carbon;
use App\Notification;
use App\Mail\DsrEmail;
use App\ProjectAssigned;
use App\Traits\Sanitize;
use App\Jobs\NewDsrEmail;
use Illuminate\Http\Request;
use App\Traits\CommonMethods;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Attendance;
use App\WeeklyReport;
use App\Http\Controllers\AttendanceController;
use App\Helpers\Helper;
class DSRController extends Controller
{

    use CommonMethods;
    public $response;

    public $result;

    public function __construct(){
        $this->response = parent::getResponse();
    }
    use Sanitize;

    public function index(){

    }
    public function teamsDsr(Request $request)
    {

       $authId = Auth::id();
       $teams = Team::where('team_lead_id','=',$authId)->where('teams.dsr_approve',1)->first();
       
        if((Auth::user()->role_id == 4 || Auth::user()->role_id == 5)){
         if(!empty($teams)){
          $data =   $teams->employee_id;
       
            $res = str_replace( array( 
                ',' ), ' ',  $data);
         
          $employeeid =  explode(" ", $res);
          $dsrUsers =  User::where('is_deleted',0)->whereIn('id',$employeeid)
          ->withCount('dsr');
        
         }else{
          return redirect('/dashboard');
         }
        
                         
        } else {
          $dsrUsers =  User::where('is_deleted',0)->whereNotIn('role_id', [1,2])
                            ->withCount(['dsr' =>function($q) use ($authId){
                              $q->whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                              ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
                            }]);
        }
        
        if ($request->isMethod('post')) {
            $keyword = $request->get('search');
            $dsrUsers = $dsrUsers->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"]);
            $view = 'admin.dsrs.search';
        } else {
            $view = 'admin.dsrs.home';
        }

        $dsrUsers = $dsrUsers->latest()->paginate(10);      
        $dsrUsers->setPath('team_dsr');   
        return view($view, compact('dsrUsers','authId'));
}


    /**
    * @developer       :   Akshay
    * @modified by     :   Akshay
    * @created date    :   09-07-2018 (dd-mm-yyyy)
    * @modified date   :   03-08-2018 
    * @purpose         :   Display Dsrs
    * @params          :   
    * @return          :   view
    */
    public function dsr(Request $request){
      $auth = Auth::user();
      $authId = $auth->id;
      $enId = Crypt::encrypt($authId);
      $idToHighlight = $request->get('dsrId');
      $redirect = $request->get('redirect');

      $dsrs = Dsr::orWhereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                  ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");

      $dsrs = $dsrs->with([
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
          $dsrs = $dsrs->orWhere('user_id', $authId);
        }

        $dsrs = $dsrs->paginate(10)->setPath('dsrdetail');
       
        return view('dsrs.index',compact('dsrs','enId'));
      }

      /*--- Page number logic ---*/
      $idToHighlight = Crypt::decrypt($idToHighlight);
      
      DB::transaction(function() use ($authId, $idToHighlight){
        DB::select('SET @row_number = 0;');
        $this->result = DB::select("SELECT row_no, id FROM (
            SELECT (@row_number:=@row_number + 1) as row_no, id FROM dsrs 
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
        return view('dsrs.index',compact('dsrs','enId'));
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
    * @developer       :   Akshay
    * @modified by     :   Akshay
    * @created date    :   09-07-2018 (dd-mm-yyyy)
    * @modified date   :   31-07-2018
    * @purpose         :   Display Dsr details list
    * @params          :   id
    * @return          :   response as []
    */

    public function showdsr(Request $request, $id)
    {
     
        $enId = $id;
        $id = Crypt::decrypt($id);

        $authId = Auth::user()->id; 
        $idToHighlight = $request->get('dsrId');
        $redirect = $request->get('redirect');
        $dsrs = Dsr::where('user_id', $id)->where('to_ids','!=','');
        if (in_array(Auth::user()->role_id, [4,5])){
          $dsrs = $dsrs->where(function($query) use($authId) {
              // return $query->whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
              //            ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
          });
        }
        
        $dsrs = $dsrs->with([
            'details.project', 
            'user', 
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
        ])->orderBy('created_at', 'desc')->paginate(10);
       
        $dsrs->lastid = Dsr::where('user_id', $id)->where('to_ids','!=','')->latest('id')->first();        
        $lastid =  !empty($dsrs->lastid->id) ? $dsrs->lastid->id:'';               
        if(!$redirect || !$idToHighlight){
          return view('admin.dsrs.index',compact('dsrs','enId','lastid'));
        }
        // $totalCount = $totalCount->count();
        /*--- Page number logic ---*/
        $idToHighlight = Crypt::decrypt($idToHighlight);
        DB::transaction(function() use ($authId, $idToHighlight, $id){
          DB::select('SET @row_number = 0;');
          $this->result = DB::select("SELECT row_no, id FROM (
              SELECT (@row_number:=@row_number + 1) as row_no, id FROM dsrs 
              WHERE 
                user_id = ".$id."
              AND
                (
                  FIND_IN_SET('".$authId."',to_ids) 
                OR 
                  FIND_IN_SET('".$authId."',cc_ids)
                ) ORDER BY id DESC 
            ) as t WHERE t.id = ".$idToHighlight.";");
        });
        $dsrs->setPath('user_dsrs');
        if(!$this->result){
          return view('admin.dsrs.index',compact('dsrs','enId'));
        }
        $row_no = $this->result[0]->row_no;
        $pageNo = ceil($row_no/10);
        /*--- Page number logic ---*/

        $query = [
          'page' => $pageNo,
          'id' => $enId,
          'dsr_id' => Crypt::encrypt($idToHighlight)
        ];
        return redirect()->route('admin.user_dsrs', $query);
    }



    public function DsrDetails($id, $markRead = 1 ){
      $id = Crypt::decrypt($id);
        $auth = Auth::user();
        $dsr = Dsr::where('id', $id)->with(['details' => function($q) use ($id){
        $q->with(['project']);
      }, 'user', 'files'])->first();
    
      if($dsr){
        $dsr = $dsr->toArray();
      }

      if( $dsr && $dsr['details'] ) {
        $dsrDetails = [];
          $comments = DsrComment::where('dsr_id', $id)->with('user')->orderBy('created_at','ASC')->get();
          foreach($dsr['details'] as $k => $detail) {
            $te = explode('.', number_format( (float) $detail['total_hours'], 2, '.', ''));
            $detail['hours'] = $te[0];
            $detail['minutes'] = isset($te[1]) ? $te[1] : "0";
            if(!$dsrDetails){
              $dsrDetails[] = [
                "project"    => $detail['project'] ? $detail['project'] : '',
                "project_id" => $detail['project_id'],
                "details"    => [$detail],
                "comments"    => [$comments],
                "reportmangerid"   =>$dsr['user']['reporting_manager_id'],
                "loginid"=>Auth::user()->id
              ];
              continue;
            }
            $key = array_search($detail['project_id'], array_column($dsrDetails,'project_id'));
            if($key === 0 || $key != null){
              $dsrDetails[$key]['details'][] = $detail;
              continue; 
            }
            $dsrDetails[] = [
              "project"    => $detail['project'] ? $detail['project'] : '',
              "project_id" => $detail['project_id'],
              "details"    => [$detail],
              "comments"   => [$comments],
              "reportmangerid"   =>$dsr['user']['reporting_manager_id'],
              "loginid"=>Auth::user()->id
            ];
        }
      
        $dsr['details'] = $dsrDetails;
       
      }
      $dsr = Dsr::toAndCCUsers($dsr, 1);
    
      if($markRead) {$this->markDsrRead($id, $auth->id);}
      return response()->json($dsr, 200);
    }

    /**
    * @developer       :   Akshay
    * @modified by     :
    * @created date    :   09-07-2018 (dd-mm-yyyy)
    * @modified date   :
    * @purpose         :   Display Add Dsrs Page
    * @params          :   Project Name, Description and Hours
    * @return          :   response as []
    */
    public function Dsrsentrs(Request $request){
      $auth=Auth::user();
      $users = User::whereIn('role_id', [2, 3])->where('is_deleted',0)->get();
      $checkuser = User::where([
        ['id', '!=', $auth->id],
        ['role_id', '=', 4],
        ['is_deleted', '=', 0]
      ])->get();
      $projectAssigned = ProjectAssigned::where('user_id', $auth->id)->first();
      $projects = [];      
      if($projectAssigned){
        // try{
          $projectIds = explode(',', $projectAssigned->project_id);
          $projects = Project::whereIn('id', $projectIds)
                              ->where([
                                ['status', '!=', '0'],
                                ['is_deleted', '!=', 1]
                              ])->get();
          if($projects->count()){
            $managers = User::where('role_id', 3)->whereHas('project_assign', function($q) use ($projectIds){
              $q->where(function($sq) use ($projectIds){
                foreach ($projectIds as $pid) {
                  $sq->orWhereRaw('FIND_IN_SET(?, project_id)', $pid);
                }
              });
            })->with('project_assign')->get()->toArray();
            $managers = array_map(function($m){
              $m['project_id'] = $m['project_assign'][0]['project_id'];
              unset($m['project_assign']);
              return $m;
            }, $managers);
            foreach ($projects as $p) {
              $p->manager_ids = '';
              foreach ($managers as $m) {
                if(in_array($p->id, explode(',', $m['project_id']))){
                  $p->manager_ids .= $m['id'].",";
                }
              }
              $p->manager_ids = rtrim($p->manager_ids, ',');
            }

          }

        // } catch(\Exception $error){
        //   //return common error view with error
        // }
      }
    
      return view('dsrs.add', compact('users', 'checkuser', 'projects'));
    }


    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   16-07-2018 (dd-mm-yyyy)
    * @purpose         :   Display user details
    * @params          :   id
    * @return          :   response as []
    */
    public function show($id = null){

      if(!$id){
        throw new \Exception('Not Found', 404);
      }
      die('bb');

      $user = User::where('id', $id)->get();

      return view('layouts.employee.view', compact('user'));
    }
    

    /**
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   31-07-2018 (dd-mm-yyyy)
    * @purpose         :   create new Dsr
    * @params          :   user_id, to_ids, cc_ids
    * @return          :   response as []
    */
    public function store(Request $request){

      $data = $request->all();
      $auth = Auth::user();
      $rules = Dsr::saveDsrVd($data);
     
      $messages = Dsr::saveDsrVdM($rules);
      
      // $validator = Validator::make($data, $rules, $messages);
      // if($validator->fails()){
      //   return back()->with([ 'error_flash_message' => $validator->errors()->first()])
      //                ->withInput($request->input());                  
      //  }

      if(isset($data['documents'])){

        $res = Dsr::validateDsrDocs($data['documents']);
        if(!$res){

          return back()->with([ 
            'error_flash_message' => 'The file extension must be one of .jpg, .jpeg, .png, .doc, .docx, pdf, xls, xlsx or csv.'
          ])->withInput($request->input());                  
        }
       }

    $dsr = dsr::where('user_id', '=',$auth->id)->where('to_ids','=','')
           ->whereDate('created_at', date('Y-m-d'))->first();
     
     if(!isset($dsr)){
      return back()->with([ 
            'error' => 'Please save  at least One Hour.'
          ]);                  
        }     
     if(isset($dsr)){
      $toIds = implode(",", $data['send_to']);
      if (strpos($toIds, '41') !== false) {
            $toIds .= ','.env('projects_talentelgia_in');
      }
      $ccIds = !empty($data['add_cc']) ? implode(",", $data['add_cc']) : '';
      if (strpos($ccIds, '41') !== false) {
        $ccIds .= ','.env('projects_talentelgia_in');
      } 
      $dsrModel = Dsr::where('id',$dsr->id)->update([
        'user_id' => $auth->id,
        'to_ids' => $toIds,
        'cc_ids' => $ccIds
      ]);
      }
     
      if(!$dsrModel){

        return back()->with([ 'error_flash_message' => 'Error sending dsr']);
      }
      
      $response = $this->updateDsrDetailsData($data, $dsr->id);

      if(!$response['success']){

        return back()->with([ 'error_flash_message' => $response['message'] ]);
      }
      $thisDsr=json_decode($this->DsrDetails(Crypt::encrypt($dsr->id),0)->getContent(), true);

      if($thisDsr){
        
        try{

          $users = array_merge($thisDsr['to_users'], $thisDsr['cc_users']);

          foreach ($users as $key => $user) {
            
            $data = [
              'sender' => $thisDsr['user'],
              'receiver' => $user,
              'dsr_details' => $thisDsr['details'],
              'dsr_date' => Carbon::now()->format('d-m-Y')
            ];
            $timeout = new AttendanceController;
            $check_weeky_report = WeeklyReport::whereUserId(Auth::id())->whereDate('created_at', Carbon::now()->format('Y-m-d'))->first();
            if($check_weeky_report){        
            $timeout->timeOutAction($request);
            }elseif(date('l') != 'Friday'){       
            $timeout->timeOutAction($request);
                    }
          //$this->sendDSRMails($user, $data);
            $job = (new NewDsrEmail($user, $data))
                    ->delay(Carbon::now()->addSeconds(3));
            
            dispatch($job);

          }
        }catch(\Exception $e){

           
        }
      }

      // /*--- Add notification ---*/
      // Notification::create([
      //   "user_id" => $auth->id,
      //   "type_id" => 1,
      //   "activity_id" => $dsrModel->id,
      //   "message" => $auth->fullname.' '.'has sent you a dsr'
      // ]);
   
      /*--- Add notification ---*/
      
      return redirect('sent_dsr')->with('flash_message', 'Dsr sent successfully')->with('open_dsr','');
    } 

    /**
    * @developer       :   Swaran
    * @modified by     :   Akshay
    * @created date    :   18-07-2018 (dd-mm-yyyy)
    * @modified date   :   31-07-2018 (dd-mm-yyyy)
    * @purpose         :   save dsr details data
    * @params          :   data as [], lastInsertId as int
    * @return          :   response as []
    */

   public function  updateDsrDetailsData($data, $lastInsertId){     
     $dataResponse = [ "success" => false, 'message' => ''];
      if(empty($data['project_id'])){
        $dataResponse['message'] = 'Failed to send dsr';        
        return $dataResponse;
      }
      $dsrDetails = []; 
      foreach($data['desrdetaisid'] as $k => $v){  

        foreach ($data['des'][$k] as $key => $des) {     
          $tHr = (string) ( isset($data['hours'][$k][$key]) ? $data['hours'][$k][$key] : 0);
          $tM = (string) ( isset($data['minutes'][$k][$key]) ? $data['minutes'][$k][$key] : 0);
          $tM = $tM <= 9 ? "0".$tM : $tM;
          $tt = $tHr.".".$tM;
          $dsrDetails[] = [
            'dsr_id'      => $lastInsertId,
            'project_id'  => $data['project_id'][$k],
            'task'        => '',
            'description' => $des,
            'start_time'  => $data['start'][$k][$key],
            'end_time'  => $data['end'][$k][$key],
            'total_hours' => (float) $tt,
            'id'=>$v[$key]
          ];
        }
       }
   
     foreach($dsrDetails as $value ){
     $dsrupdate = [           
             'project_id'  => $value['project_id'],
            'task'        => '',
            'description' => $value['description'],
            'start_time'  => $value['start_time'],
            'end_time'  =>$value['end_time'],
            'total_hours' =>$value['total_hours'],
            
          ];
     DsrDetail::where('id',$value['id'])->update($dsrupdate);
  

     }
     for ($i=1; $i < 2 ; $i++) { 
       foreach ($data['addRow'] as $key => $value) {
         $tHr = (string) ( isset($value['hours']) ? $value['hours'] : 0);
          $tM = (string) ( isset($value['minutes']) ? $value['minutes'] : 0);
          $tM = $tM <= 9 ? "0".$tM : $tM;
           $tt = $tHr.".".$tM;
            $dsrDetail = [
            'dsr_id'      => $lastInsertId,
            'project_id'  =>  $value['project_id'],
             'task'        => '',
            'description' => $value['des'],
            'start_time'  => $value['start'],
            'end_time'  => $value['end'],
            'total_hours' => (float)$tt,
          ];   
     }
     
     if(isset($value['project_id']) && $value['des'] ){
     DsrDetail::insert($dsrDetail);
      }
        } 
      if(isset($data['documents'])){
        
        $dsrFiles = [];
        foreach ($data['documents'] as $document) {
          
          $orgName = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
          $dsrFiles[] = [
            'dsr_id' => $lastInsertId,
            'path_name'     => explode('/', $document->store('public/dsrs'))[2],
            'original_name' => $orgName.'_'.rand()
          ];
        }

        DsrFile::insert($dsrFiles);
      }

      $dataResponse["success"] = true;
      return $dataResponse;

   }
  public function storeOneHourDsr( Request $request){
       try{
        $data = $request->all();        
        $auth = Auth::user();         
        $dsr = dsr::where('user_id', '=',$auth->id)->where('to_ids','=','')
           ->whereDate('created_at', date('Y-m-d'))->first();
        $tHr = (string) ( isset($data['hours']) ? $data['hours'] : 0);
        $tM = (string) ( isset($data['minutes']) ? $data['minutes'] : 0);
        $tM = $tM <= 9 ? "0".$tM : $tM;
        $tt = $tHr.".".$tM; 
        if(!empty($data['id'])){      
        DsrDetail::where('id',$data['id'])->update([
       'task'        => '',
       "project_id" => $data['project_id'],
        "description" =>$data['description'],
        "start_time" =>$data['start_time'],
        "end_time" => $data['end_time'],
        "total_hours" =>(float) $tt 
         ]);
        return response()->json([
                     'flash_message' => 'Dsr update successfully',                              
                   ]);
       
      }      
        if(empty($dsr)){       
        $dsrModel = Dsr::create([
        'user_id' => $auth->id,
        'to_ids' => '',
        'cc_ids' => ''
      ]);
        if(!$dsrModel){
        return back()->with([ 'error_flash_message' => 'Error sending dsr']);
        }  
         DsrDetail::insert([
        'dsr_id'      => $dsrModel->id,
        "project_id" => $data['project_id'],
         'task'        => '',
        "description" =>$data['description'],
        "start_time" =>$data['start_time'],
        "end_time" => $data['end_time'],
        "total_hours" =>(float) $tt 
         ]); 
           
    } else{
        DsrDetail::insert([
        'dsr_id'      => $dsr->id,
        "project_id" => $data['project_id'],
        'task'        => '',
        "description" =>$data['description'],
        "start_time" =>$data['start_time'],
        "end_time" => $data['end_time'],
        "total_hours" =>(float) $tt 
         ]);

    }  
   
   return response()->json(['status'=>true,'flash_message' => 'Dsr sent successfully',                              
                   ]);

  }catch(\Exception $e){

           
        }
   
    }
 


  public function ajaxDsr($request){
      $auth = Auth::user();       
      $attendance = Attendance::whereUserId(Auth::id())->whereDate('time_in', Carbon::now()->format('Y-m-d'))->first();
     if(empty($attendance)){
      $data['time_in'] =  date('h:i',strtotime('09:30'));
      }else{      
      $data['time_in'] = date('h:i', strtotime($attendance->time_in));
      }   
      $dsr = dsr::where('user_id', '=',$auth->id)->where('to_ids','=','')
           ->whereDate('created_at', date('Y-m-d'))->first();
      $dsrid = !empty($dsr)?$dsr->id:'';
      $data['DsrDetail']  = DsrDetail::where('dsr_id', $dsrid)->get();
     
      $data['projects'] = Project::select('id','name','project_manager','team_lead')->whereHas('project_assigned',function($query) use($auth){
        $query = $query->where('user_id', $auth->id);
      })->where('status','!=','0')->where('is_deleted','!=', 1)->get();

  $html = view('dsrs.dsr_detail')->with($data)->render();

  return response()->json(['html' => $html]);
        
   
  }


    public function addDsrDetailsData($data, $lastInsertId){

      $dataResponse = [ "success" => false, 'message' => ''];

      if(empty($data['project_id'])){

        $dataResponse['message'] = 'Failed to send dsr';        
        return $dataResponse;
      }

      $dsrDetails = [];
      foreach($data['project_id'] as $k => $v){

        foreach ($data['des'][$k] as $key => $des) {

          $tHr = (string) ( isset($data['hours'][$k][$key]) ? $data['hours'][$k][$key] : 0);
          $tM = (string) ( isset($data['minutes'][$k][$key]) ? $data['minutes'][$k][$key] : 0);
          $tM = $tM <= 9 ? "0".$tM : $tM;

          $tt = $tHr.".".$tM;
          $dsrDetails[] = [
            'dsr_id'      => $lastInsertId,
            'project_id'  => $v,
            'task'        => $data['task'][$k][$key],
            'description' => $des,
            'start_time'  => $data['start'][$k][$key],
            'end_time'  => $data['end'][$k][$key],
            'total_hours' => (float) $tt,
          ];
        }
      }

      DsrDetail::insert($dsrDetails);

      if(isset($data['documents'])){
        
        $dsrFiles = [];
        foreach ($data['documents'] as $document) {
          
          $orgName = pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);
          $dsrFiles[] = [
            'dsr_id' => $lastInsertId,
            'path_name'     => explode('/', $document->store('public/dsrs'))[2],
            'original_name' => $orgName.'_'.rand()
          ];
        }

        DsrFile::insert($dsrFiles);
      }

      $dataResponse["success"] = true;
      return $dataResponse;
    } 


    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   30-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   display sent dsrs
    * @params          :   
    * @return          :   response as []
    */
    public function Dsrsent(){
      $auth = Auth::user();
      $authId = $auth->id; 
      $enId = Crypt::encrypt($authId);
      $dsrs = Dsr::where('user_id', $authId)->where('to_ids','!=','')
                    ->with('details.project')
                    ->orderBy('created_at', 'desc')->paginate(10)->setPath('sent_dsr');
      $dsrs->lastid = Dsr::where('user_id',$authId)->where('to_ids','!=','')->latest('id')->first();        
      $lastid =  !empty($dsrs->lastid->id) ? $dsrs->lastid->id:'';
      return view('dsrs.index',compact('dsrs','enId','lastid'));
    }


     /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   06-08-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   to get dsrs by user id and search string
    * @params          :   id and search as query params
    * @return          :   response as []
    */
    public function getDsrs(Request $request){
 
        $search = $request->get('search');
        $view = $request->get('view');
        $id = $request->get('id');
        $auth = Auth::user();
        $enId = Crypt::encrypt($auth->id);

        if(!$id){

            $this->response['message'] = 'User not found';
            return response()->json($this->response, 400);
        }

        $search = $this->sanitize($search);
        $id = Crypt::decrypt($id);

        $dsrs = Dsr::select('dsrs.*')
                    ->leftJoin('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
                    ->leftJoin('projects as p', 'dd.project_id', '=', 'p.id');

        if($view){

          if($view == 'sent_dsr'){
            
            $dsrs = $dsrs->where('dsrs.user_id', $id);  
          }else{

            $dsrs = $dsrs->where(function($query) use($id) {
                return $query->whereRaw("FIND_IN_SET('". $id ."', dsrs.to_ids)")
                             ->orWhereRaw("FIND_IN_SET('". $id ."', dsrs.cc_ids)");
            });  
          }
        }else{

          $dsrs = $dsrs->where(function($query) use($id) {
              return $query->whereRaw("FIND_IN_SET('". $id ."', dsrs.to_ids)")
                           ->orWhereRaw("FIND_IN_SET('". $id ."', dsrs.cc_ids)");
          })
          ->where('dsrs.user_id', $id);
        }

      
        if($search){

            $dsrs = $dsrs->where(function($q) use ($search){

                $q->orWhere('p.name', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.task', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.description', 'LIKE', '%'.$search.'%');
            });
        }
/*
        $dsrs = $dsrs->pluck('dsrs.id')->toArray();
        $keys = array_unique($dsrs);
        $dsrs = Dsr::whereIn('id' , $dsrs);*/

        
        $dsrs = $dsrs->with([
          'details.project', 
          'user',
          'read' =>function($q) use ($auth){
            $q->where('user_id', $auth->id);
          }
        ])->orderBy('dsrs.created_at', 'desc')->paginate(10)->setPath('sent_dsr');

        foreach ($dsrs as $dsr) {

            $project_name = 'N-A';
            $description = '';
            $highlight = ($dsr['read']->count() && $dsr['read'][0]['is_read'] == 1) ? 0:1;

            if($dsr['details']->count()){
            
                if($dsr['details'][0]['project']){

                  $project_name = $dsr['details'][0]['project']['name'];
                }
                $description = substr($dsr['details'][0]['description'], 0, 40);
            }

            $dsr->user_full_name = $dsr->user ? $dsr->user->fullname : 'N-A';
            $dsr->project_name = $project_name;
            $dsr->description = $description;
            $dsr->highlight = $highlight;
            $dsr->en_id = Crypt::encrypt($dsr->id);
        }
        $sentCase = 1;
        return view('dsrs.search_list', compact('dsrs','enId','sentCase'))->render();

       /* $this->response['data'] = $dsrs;
        $this->response['success'] = true;
        $this->response['status'] = 200;

        return response()->json($this->response, 200);   */
    }



    // public function edit($id){

    //   $user = User::findOrFail($id);
     //  return response()->json($user);
    // }


    // public function update(Request $request,$id){

    //   $req = $request->only('first_name','last_name','email','role_id');
    //   $validator = Validator::make($request->all(), [
    //     'first_name' => 'required',
    //     'last_name' => 'required',
    //     'email' => 'required|email|unique:users',
    //     'role_id' => 'required'
    //   ]);

    //   if ($validator->fails()) {

    //       return response()->json(['validation error'], 500);
    //   }

    //   $user = User::findOrFail($id);
    //   $user->update($req);
    //   if (!$user) {

    //    return response()->json(['failed'], 500);
    //   }

    //   return response()->json(['user_updated']);
    // }


    // public function destroy($id){

      // $del = User::findOrFail($id);
      // $del->delete($id);

      // if(!$del) {

    //       return response()->json(['failed'], 500);
     //  }

     //  return response()->json(['data_deleted']);
    // }

     /**
     * @developer       :   Akshay
     * @modified by     :
     * @created date    :   09-07-2018 (dd-mm-yyyy)
     * @modified date   :
     * @purpose         :   Display Add Dsrs Page
     * @params          :   Project Name, Description and Hours
     * @return          :   response as []
     */
    public function add_Dsrs(Request $request){
    if($request->ajax()){
      return $this->ajaxDsr($request);
    
     }
    $teamLead = Helper::team_lead_user(); 
    $auth = Auth::user();
    $email_users = User::whereIn('role_id', [2, 3])->get();
    $cc_users = User::where([
        ['id', '!=', $auth->id],
        ['is_deleted', '=', 0]
    ])->whereIn('role_id',[4,5])->orderBy('email','ASC')->get();
   
     $attendance = Attendance::whereUserId(Auth::id())->whereDate('time_in', Carbon::now()->format('Y-m-d'))->first();
     if(empty($attendance)){
      return redirect('dashboard')->with('error', 'Please Time In First.');
      // $time_in =  date('h:i',strtotime('09:30'));
      }else{      
      $time_in = date('h:i', strtotime($attendance->time_in));
      }   
      $dsr = dsr::where('user_id', '=',$auth->id)->where('to_ids','=','')
           ->whereDate('created_at', date('Y-m-d'))->first();
      $dsrid = !empty($dsr)?$dsr->id:'';
      $DsrDetail  = DsrDetail::where('dsr_id', $dsrid)->get();     
      $projects = Project::select('id','name','project_manager','team_lead')->whereHas('project_assigned',function($query) use($auth){
        $query = $query->where('user_id', $auth->id);
      })->where('status','!=','0')->where('is_deleted','!=', 1)->get();
        
      return view('dsrs.add', compact('email_users', 'cc_users', 'projects','time_in','DsrDetail','teamLead'));
  }
 

}
