<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Dsr;
use App\User;
use App\Team;
use App\Project; 
use App\UserLeave;
use DB;
use \Crypt;
use App\Jobs\SendLeaveEmailJob;
use Illuminate\Support\Facades\Log;
use App\Helpers\Helper;
use Route;
use Mail;
use App\Jobs\AppliedleaveStatus;
use App\Mail\sendAppliedLeave;
use App\Mail\SendLeaveMail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
class leaveManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index(Request $request)
    {  
        $role_id = Auth::user()->role_id;
        $keyword = $request->text;
        $from = $request->from;
        $to = $request->to; 
        $status = $request->status;
        $today = $request->today;
        $token = $request->token;         
        $calculateleave =   Helper::totalleaveleft();
        $query = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','leave_types.value','users.first_name','users.last_name','users.employee_code','users.role_id'])
            ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
            ->leftJoin('users','users.id','=','leaves.users_id');
      if($request->isMethod('post') && !empty($keyword) ){      
      if($role_id==\App\User::ROLE_PROJECT_MANAGER){
        if (Auth::id() != 103 && Auth::id() != 27) {
            $user_reporting_manager_id = Auth::user()->id;
        
            $query->where(function ($subquery) use ($user_reporting_manager_id) {
                $subquery->orWhere('users.reporting_manager_id', '=', $user_reporting_manager_id)
                    ->orWhere('users.reporting_manager_id', 'LIKE', $user_reporting_manager_id . ',%')
                    ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id . ',%')
                    ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id);
            });
        }   
        $query->whereRaw("CONCAT(users.first_name, ' ',users.last_name) LIKE ?", ["%".$keyword."%"])->latest();   
          }elseif($role_id == \App\User::ROLE_HR|| $role_id == \App\User::ROLE_ADMIN){       
        $query->where('leaves.leave_status','!=','cancelled');
        $query->where('leaves.users_id','!=',Auth::user()->id);

        $query->whereRaw("CONCAT(users.first_name, ' ',users.last_name) LIKE ?", ["%".$keyword."%"]);            

         }
         $leaves =    $query->orderBy('start_date', 'DESC')->paginate(10);   
         $leaves->setPath('leave');
         return view('leave-management.search',compact('leaves','keyword','from','to','status'));
   
    } elseif($request->isMethod('post')) {

            if(!empty( $from ) && !empty($to)){
                if($role_id == \App\User::ROLE_PROJECT_MANAGER){
                    $query->where('leaves.leave_status','!=','cancelled');
                    if (Auth::id() != 103 && Auth::id() != 27) {
                            $user_reporting_manager_id = Auth::user()->id;
                        
                            $query->where(function ($subquery) use ($user_reporting_manager_id) {
                                $subquery->orWhere('users.reporting_manager_id', '=', $user_reporting_manager_id)
                                    ->orWhere('users.reporting_manager_id', 'LIKE', $user_reporting_manager_id . ',%')
                                    ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id . ',%')
                                    ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id);
                            });
                        }        
                    $query->where('leaves.users_id','!=',Auth::user()->id);    
            }
                 elseif($role_id == \App\User::ROLE_HR || $role_id == \App\User::ROLE_ADMIN ){            
                    $query->where('leaves.leave_status','!=','cancelled');
                    $query->where('leaves.users_id','!=',Auth::user()->id);        
                          }
                elseif($role_id == \App\User::ROLE_EMPLOYEE){                
                 $query->where('leaves.users_id','=',Auth::user()->id);                 
                                     } 
                 $query->whereDate('start_date','>=',DATE($from));
                 $query->whereDate('end_date','<=',DATE($to));
                 if($status!='all'){
                   $query->where('leave_status','=',$status);   
                 }
                                  
                }elseif(!empty($today) ){
                if($role_id == \App\User::ROLE_PROJECT_MANAGER){
                // $query->where('users.reporting_manager_id','=',Auth::user()->id);
                // $query->where('leaves.leave_status','!=','cancelled');
                // $query ->where('leaves.users_id','!=',Auth::user()->id);                  
                $query->where('leaves.leave_status','!=','cancelled');
                if (Auth::id() != 103 && Auth::id() != 27) {
                        $user_reporting_manager_id = Auth::user()->id;
                    
                        $query->where(function ($subquery) use ($user_reporting_manager_id) {
                            $subquery->orWhere('users.reporting_manager_id', '=', $user_reporting_manager_id)
                                ->orWhere('users.reporting_manager_id', 'LIKE', $user_reporting_manager_id . ',%')
                                ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id . ',%')
                                ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id);
                        });
                    }        
                $query->where('leaves.users_id','!=',Auth::user()->id);          
                }elseif($role_id == \App\User::ROLE_HR || $role_id == \App\User::ROLE_ADMIN ){            
                    $query->where('leaves.leave_status','!=','cancelled');
                    $query->where('leaves.users_id','!=',Auth::user()->id);        
                          } 
                $query->whereDate('start_date','=',DATE($today));
                $query->where('leave_status','=',$status);
                }elseif($status!='all' && !empty($status)){
                    if($role_id == \App\User::ROLE_PROJECT_MANAGER){
                        $query->where('leaves.leave_status','!=','cancelled');
                        if (Auth::id() != 103 && Auth::id() != 27) {
                                $user_reporting_manager_id = Auth::user()->id;
                            
                                $query->where(function ($subquery) use ($user_reporting_manager_id) {
                                    $subquery->orWhere('users.reporting_manager_id', '=', $user_reporting_manager_id)
                                        ->orWhere('users.reporting_manager_id', 'LIKE', $user_reporting_manager_id . ',%')
                                        ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id . ',%')
                                        ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id);
                                });
                            }   
                        $query->where('leave_status','=',$status);      
                        $query->where('leaves.users_id','!=',Auth::user()->id);                
                     }            
                   elseif($role_id == \App\User::ROLE_HR || $role_id == \App\User::ROLE_ADMIN){ 
                                   
                        $query->where('leaves.leave_status','!=','cancelled');
                        $query->where('leave_status','=',$status); 
                        $query->where('leaves.users_id','!=',Auth::user()->id);        
                              }
                    elseif($role_id == \App\User::ROLE_EMPLOYEE){                
                    $query->where('leaves.users_id','=',Auth::user()->id); 
                     $query->where('leave_status','=',$status);                
                                         } 
              }else{
                if($role_id == \App\User::ROLE_PROJECT_MANAGER){
                    $query->where('leaves.leave_status','!=','cancelled');
                    if (Auth::id() != 103 && Auth::id() != 27) {
                            $user_reporting_manager_id = Auth::user()->id;
                        
                            $query->where(function ($subquery) use ($user_reporting_manager_id) {
                                $subquery->orWhere('users.reporting_manager_id', '=', $user_reporting_manager_id)
                                    ->orWhere('users.reporting_manager_id', 'LIKE', $user_reporting_manager_id . ',%')
                                    ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id . ',%')
                                    ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id);
                            });
                        }   
                    $query->where('leaves.users_id','!=',Auth::user()->id);                
                 }            
                   elseif($role_id == \App\User::ROLE_HR || $role_id == \App\User::ROLE_ADMIN){ 
                                   
                        $query->where('leaves.leave_status','!=','cancelled');
                        
                        $query->where('leaves.users_id','!=',Auth::user()->id);        
                              }
                    elseif($role_id == \App\User::ROLE_EMPLOYEE){                
                    $query->where('leaves.users_id','=',Auth::user()->id); 
                                    
                                         } 
              }
               
                $leaves =    $query->orderBy('start_date', 'DESC')->paginate(10);
                $leaves->setPath('leave');
         return view('leave-management.search',compact('leaves','keyword','from','to','status'));
        }    
   
        if($role_id== \App\User::ROLE_PROJECT_MANAGER){ //reporting manager            
           $query->where('leaves.leave_status','!=','cancelled');
           if (Auth::id() != 103 && Auth::id() != 27) {
                $user_reporting_manager_id = Auth::user()->id;
            
                $query->where(function ($subquery) use ($user_reporting_manager_id) {
                    $subquery->orWhere('users.reporting_manager_id', '=', $user_reporting_manager_id)
                        ->orWhere('users.reporting_manager_id', 'LIKE', $user_reporting_manager_id . ',%')
                        ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id . ',%')
                        ->orWhere('users.reporting_manager_id', 'LIKE', '%,' . $user_reporting_manager_id);
                });
            }        
           $query->where('leaves.users_id','!=',Auth::user()->id);    

         }elseif ($role_id== \App\User::ROLE_EMPLOYEE) {//employee       
            $query->where('leaves.users_id','=',Auth::user()->id);
            $leaves =    $query->orderBy('start_date', 'DESC')->paginate(10);
            $leaves->setPath('leave');
          return view('leave-management.index',compact('leaves','calculateleave'));
           
        }elseif ($role_id == \App\User::ROLE_HR ||$role_id == \App\User::ROLE_ADMIN) {  //hr
           
            $query->where('leaves.leave_status','!=','cancelled');
            $query->where('leaves.users_id','!=',Auth::user()->id);
             
        }
        elseif ($role_id == \App\User::ROLE_MGMT) {            
            $query->where('leaves.leave_status','!=','cancelled');
            $query->whereIn('users.role_id',[3,5]);  
        
        }
        $leaves =    $query->orderBy('start_date', 'DESC')->paginate(10);
        $leaves->setPath('leave');
        return view('leave-management.reportingmanager',compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

     

public function teamLeaves(Request $request){
try{
$role_id = Auth::user()->role_id;
$keyword = $request->text;
$token = $request->token;
$from = $request->from;
$status = $request->status;
$to = $request->to;
$today = $request->today;   
$teams = Team::where('team_lead_id','=',Auth::user()->id)->where('teams.leave_approve','=',1)->first();
$query = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','leave_types.value','users.first_name','users.last_name','users.employee_code','users.role_id'])
->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
->leftJoin('users','users.id','=','leaves.users_id');   

if(!empty($teams)){ 
$data =   $teams->employee_id;     
$res = str_replace( array( 
',' ), ' ',  $data);
$employeeid =  explode(" ", $res);
if($request->isMethod('post') && !empty($keyword) ){ 
$query->where('leaves.users_id','!=',Auth::user()->id);
$query->whereRaw("CONCAT(users.first_name, ' ',users.last_name) LIKE ?", ["%".$keyword."%"])->latest();
$query->whereIn('users.id',$employeeid);           
$leaves =    $query->orderBy('id', 'DESC')->paginate(10); 
$leaves->setPath('team-leave');    
return view('leave-management.search',compact('leaves','keyword','from','to','status','teams'));
}else if($request->isMethod('post')){            
if(!empty( $from ) && !empty($to)){
$query->where('leaves.leave_status','!=','cancelled');  
$query->where('leaves.users_id','!=',Auth::user()->id);   
$query->whereDate('start_date','>=',DATE($from));
$query->whereDate('end_date','<=',DATE($to));
$query->whereIn('users.id',$employeeid);
if($status  != 'all') {
$query->where('leave_status','=',$status); 
}
}elseif(!empty($today)){              
$query->where('leaves.leave_status','!=','cancelled');  
$query->where('leaves.users_id','!=',Auth::user()->id);         
$query->whereIn('users.id',$employeeid);      
$query->whereDate('start_date','=',DATE($today)); 
$query->where('leave_status','=',$status);             
}else{
$query->where('leaves.leave_status','!=','cancelled');  
$query->where('leaves.users_id','!=',Auth::user()->id); 
$query->whereIn('users.id',$employeeid);     
if($status =='approved' ||$status =='not_approved'||$status =='Pending' ) {
$query->where('leave_status','=',$status); 
}
}
$leaves =    $query->orderBy('id', 'DESC')->paginate(10);
$leaves->setPath('team-leave');   
return view('leave-management.search',compact('leaves','keyword','from','to','status','teams'));   
} 
else{
$query->where('leaves.leave_status','!=','cancelled');  
$query->where('leaves.users_id','!=',Auth::user()->id); 
$query->whereIn('users.id',$employeeid);           
$leaves =    $query->orderBy('id', 'DESC')->paginate(10);  
}       
}else{
$leaves = Team::where('team_lead_id','=',Auth::user()->id)->where('teams.leave_approve','=',1)->paginate(10); 
if($request->isMethod('post')){
$leaves->setPath('team-leave');        
return view('leave-management.search',compact('leaves','keyword','from','to','status','teams'));
}
}
$leaves->setPath('team-leave');   
return view('leave-management.reportingmanager',compact('leaves'));   
}catch(\Exception $e){
   
}       
}


public function create()
    {  
        $teamLead = Helper::team_lead_user(); 
        $auth = Auth::user();
        $user = User::where('id',$auth->id)->first();
        $email_users = User::whereIn('role_id', [2,3])->get();      
        $cc_users = User::where([
          ['id', '!=', $auth->id],
          ['is_deleted', '=', 0],  ['status', '=',1]
      ])->whereIn('role_id',[4,5])->orderBy('email','ASC')->get();
       $leaveTypes = DB::table('leave_types')->get();  
        return view('leave-management.add', compact('email_users', 'cc_users','leaveTypes','teamLead'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function store(Request $request)
    {

        /**/
          $this->validate($request,[
         
         'add_more_.*.type' => 'required',
         'add_more_.*.description' => 'required',
         'add_more_.*.start_date' => 'required_if:add_more_.*.type,full_day',
         'add_more_.*.end_date' => 'required_if:add_more_.*.type,full_day',
         'add_more_.*.start_date' => 'required_if:add_more_.*.type,WFH',
         'add_more_.*.end_date' => 'required_if:add_more_.*.type,WFH',
         'add_more_.*.shift' => 'required_if:add_more_.*.type,half_day',
         'add_more_.*.start_time' => 'required_if:add_more_.*.type,short_leave',        
         'add_more_.*.current_date' => 'required_if:add_more_.*.type,short_leave,half_day',
         'add_cc.*.add_cc.required_if' => __(' add cc is required.'),
                  
        ],[  
         
         'add_more_.*.type.required' => __('Leave type is required.'), 
         'add_more_.*.description.required' => __('Leave reason is required.'), 
         'add_more_.*.start_date.required_if' => __('Start date is required.'), 
         'add_more_.*.end_date.required_if' => __('End date is required.'), 
         'add_more_.*.shift.required_if' => __('Half day shift is required.'), 
         'add_more_.*.start_time.required_if' => __('Start time is required.'),       
         'add_more_.*.current_date.required_if' => __('Date is required.'), 
         'add_cc.*.add_cc.required_if' => __(' add cc is required.'), 
         ]
        );
        
        $data = $request->all();    
        $auth = Auth::user();
        $ccIds = !empty($data['add_cc']) ? implode(",", $data['add_cc']) : NULL;
       if($ccIds ==  NULL){
        $ccIds =  NULL;
        $ccdata =  [];
        }else{
        $ccdata = $data['add_cc'];
        } 

        if($data['request_type'] == 'Apply'){
        $request_type = 'leave_request';
        }else{       
        return  $this->cancel_leave_request($data);
        }
       foreach ($data['add_more_'] as $key => $value) {
        $start_time=NULL;
        $end_time=NULL;
        $half_day_type=NULL;
       
        $stattime = date("H:i:s", strtotime($value['start_time']));            
        $endtime = date('H:i',strtotime('+2 hour ',strtotime($stattime)));
        $query = DB::table('leaves')->where('users_id',$auth->id)->where([['leave_status','!=','cancelled'],['leave_status','!=','not_approved'],['request_type','=',$request_type]]);
        if ($value['start_date']) {
            $query->whereDate('end_date', '>=',$value['start_date']);
            $query->whereDate('start_date', '<=',$value['end_date']);
        }
        else if ($value['current_date'] && $value['type']=='half_day') {                                                    
            $query->whereDate('start_date', '<=',$value['current_date']);              
            $query->whereDate('end_date', '>=',$value['current_date']); 
            $query->whereIn('leave_type_id',[1,2]);             
        }else if($value['current_date'] && $value['type']=='short_leave' ){           
            $query->where('start_date', '=',DATE($value['current_date']));  
            $query->whereIn('leave_type_id',[1,3]); 
          
        }            
        $check = $query->first();   

            if($value['type']=='full_day'){               
             if(empty($check)){
                $type_value= 1;
                $start_date = $value['start_date'];
                $end_date =   $value['end_date'];
              
             }else{
               return response()->json([
                     'error' => 'You are already applied!',
                              
                   ]);
             
             }               
            }elseif($value['type']=='WFH'){               
                if(empty($check)){
                   $type_value= 4;
                   $start_date = $value['start_date'];
                   $end_date =   $value['end_date'];
                 
                }else{
                  return response()->json([
                        'error' => 'You are already applied!',
                                 
                      ]);
                
                }               
            }elseif($value['type']=='half_day'){              
                if(empty($check)){
                    $type_value=2;
                    $start_date = $value['current_date'];
                    $end_date = $value['current_date'];
                    $half_day_type=$value['shift'];
                 }else{
                      return response()->json([
                     'error' => 'You are already applied!',
                              
                   ]);            
                 }
            }else{
             if(empty($check)){
                    $type_value=3;
                    $start_date = $value['current_date'];
                    $end_date = $value['current_date'];
                    $start_time= $stattime;
                    $end_time=  $endtime.':00';
                 }else{                           
                 return response()->json([
                     'error' => 'You are already applied!',                              
                   ]);                 
                     }

            }

            $leave_rejection_reason=NULL ;
            $leaveModel = UserLeave::insert([
                'users_id' => $auth->id,
                'leave_type_id' => $type_value,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' =>$start_time,
                'end_time' => $end_time,
                'half_day_type' => $half_day_type,
                'leave_reason' => isset($value['description'])?$value['description']:'',
                'cc_user_ids' => $ccIds,
                'leave_status' => 'pending',
                'request_type' =>$request_type,                 
                'leave_rejection_reason' => $leave_rejection_reason,
            ]);
            
            if($leaveModel){
                $user = User::whereId(Auth::id())->first();
                $url =  $_SERVER['HTTP_HOST']; 
                $details=[                  
                    "subject" =>  'Leave Request from '.ucfirst($user->first_name).' '.ucfirst($user->last_name),
                    "name"    =>  ucfirst($user->first_name).' '.ucfirst($user->last_name),
                    "type"    =>  'employee',
                    "view"    =>  'mails.leave-email',
                    "emp_code"     =>  $user->employee_code,
                    "urls" =>$url,
                    'leave' =>$value,
                    'receiver' => $user
                 ];
             
              
                $users  =  array_merge($ccdata,$data['send_to']);
                $management = User::whereIn('id',$users)->get();
                if(!empty($management)){
                    foreach($management  as $manage){
                        $details['email'] = $manage->email;
                        $details['type'] = 'manager';
                        $details['receiver'] = $manage;
                        $details['urls'] = $url;                     
                         dispatch(new SendLeaveEmailJob($details));
                        
                    }
                }                
       } 
}
       /****************/
      
        if(!$leaveModel){
            return response()->json([
                'error' => 'Error sending leave',                       
              ]);
          
        }else{
           \Session::flash('flash_message', __('Leave added successfully'));               
                 return response()->json([
                     'success' => true,
                     'role'=>Auth::user()->role_id                   
                   ]);
           
        }   

       
    }


public function cancel_leave_request($data){
     $auth = Auth::user();
      $ccIds = !empty($data['add_cc']) ? implode(",", $data['add_cc']) : NULL;
     
       if($ccIds ==  NULL){
        $ccIds =  NULL;
        $ccdata =  [];
        }else{
        $ccdata = $data['add_cc'];
        } 
     foreach ($data['add_more_'] as $key => $value) {
        $start_time=NULL;
        $end_time=NULL;
        $half_day_type=NULL;       
        $stattime = date("H:i:s", strtotime($value['start_time']));            
        $endtime = date('H:i',strtotime('+2 hour ',strtotime($stattime)));
        $query = DB::table('leaves')->where('users_id',$auth->id)->where([['leave_status','!=','cancelled'],['request_type','=','leave_request'],['leave_status','=','approved']]);
        if ($value['start_date']) {
             $query->whereDate('end_date', '>=',$value['start_date']);
            $query->whereDate('start_date', '<=',$value['end_date']);
        } else if ($value['current_date'] && $value['type']=='half_day') {                                                    
            $query->whereDate('start_date', '<=',$value['current_date']);              
            $query->whereDate('end_date', '>=',$value['current_date']); 
            $query->whereIn('leave_type_id',[1,2]);             
        }else if($value['current_date'] && $value['type']=='short_leave' ){           
            $query->where('start_date', '=',DATE($value['current_date']));  
            $query->whereIn('leave_type_id',[1,3]);           
    } 
     $check = $query->first();  
   $query2 = DB::table('leaves')->where('users_id',$auth->id)->where([['leave_status','!=','cancelled'],['leave_status','!=','not_approved']]);
    if($value['end_date']){
       $query2->whereDate('end_date', '>=',$value['start_date'])->whereDate('start_date', '<=',$value['end_date'])->where('request_type','=','cancel_request');  
    }else{
       $query2->whereDate('start_date', '<=',$value['current_date'])->whereDate('end_date', '>=',$value['current_date'])->where('request_type','=','cancel_request');  
    }           
    $check2 = $query2->first();     
       if($check2){
         return response()->json([
                     'error' => 'You are already applied!',
                              
                   ]); 
       }

    
            if($value['type']=='full_day'){               
             if(!empty($check)){
                $type_value= 1;
                $start_date = $value['start_date'];
                $end_date =   $value['end_date'];
              
             }else{
               return response()->json([
                     'error' => 'Please first approve your applied leave from your manager!',
                              
                   ]);

             }               
            }if($value['type']=='WFH'){               
                if(!empty($check)){
                   $type_value= 4;
                   $start_date = $value['start_date'];
                   $end_date =   $value['end_date'];
                 
                }else{
                  return response()->json([
                        'error' => 'Please first approve your applied leave from your manager!',
                                 
                      ]);
   
                }               
            }elseif($value['type']=='half_day'){              
                if(!empty($check)){
                    $type_value=2;
                    $start_date = $value['current_date'];
                    $end_date = $value['current_date'];
                    $half_day_type=$value['shift'];
                 }else{
                      return response()->json([
                   'error' => 'Please first approve your applied leave from your manager!',
                              
                   ]);            
                 }
            }else{
             if(!empty($check)){
                    $type_value=3;
                    $start_date = $value['current_date'];
                    $end_date = $value['current_date'];
                    $start_time= $stattime;
                    $end_time=  $endtime.':00';
                 }else{                           
                 return response()->json([
                     'error' => 'Please first approve your applied leave from your manager!',                            
                   ]);                 
                     }

            }

            $leave_rejection_reason=NULL ;
            // $leaveModel = UserLeave::insert([
            //     'users_id' => $auth->id,
            //     'leave_type_id' => $type_value,
            //     'start_date' => $start_date,
            //     'end_date' => $end_date,
            //     'start_time' =>$start_time,
            //     'end_time' => $end_time,
            //     'half_day_type' => $half_day_type,
            //     'cancel_reason' => isset($value['description'])?$value['description']:'',
            //     'leave_reason' => '',
            //     'cc_user_ids' => $ccIds,
            //     'leave_status' => 'pending',
            //     'request_type' =>'cancel_request',                 
            //     'leave_rejection_reason' => $leave_rejection_reason,
            // ]);

            $leaveModel = UserLeave::where('users_id', $auth->id)->where('leave_type_id', '4')->where('start_date', $start_date)->update([
                'users_id' => $auth->id,
                'leave_type_id' => $type_value,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'start_time' =>$start_time,
                'end_time' => $end_time,
                'half_day_type' => $half_day_type,
                'cancel_reason' => isset($value['description'])?$value['description']:'',
                'cc_user_ids' => $ccIds,
                'leave_status' => 'pending',
                'request_type' =>'cancel_request',                 
                'leave_rejection_reason' => $leave_rejection_reason,
            ]);
            
            if($leaveModel){
                $user = User::whereId(Auth::id())->first();
                $url =  $_SERVER['HTTP_HOST']; 
                $details=[                  
                    "subject" =>  'Leave Request from '.ucfirst($user->first_name).' '.ucfirst($user->last_name),
                    "name"    =>  ucfirst($user->first_name).' '.ucfirst($user->last_name),
                    "type"    =>  'employee',
                    "view"    =>  'mails.leave-email',
                    "emp_code"     =>  $user->employee_code,
                    "urls" =>$url,
                    'leave' =>$value,
                    'receiver' => $user
                 ];
             
              
                $users  =  array_merge($ccdata,$data['send_to']);
                $management = User::whereIn('id',$users)->get();
                if(!empty($management)){
                    foreach($management  as $manage){
                        $details['email'] = $manage->email;
                        $details['type'] = 'manager';
                        $details['receiver'] = $manage;
                        $details['urls'] = $url;                     
                         dispatch(new SendLeaveEmailJob($details));
                        
                    }
                }                
       } 
}
if(!$leaveModel){
            return response()->json([
                'error' => 'Error sending leave',                       
              ]);
          
        }else{
           \Session::flash('flash_message', __('Cancel leave added successfully'));               
                 return response()->json([
                     'success' => true,
                     'role'=>Auth::user()->role_id                   
                   ]);
           
        }
}
public function cancelStatus($id)
    {
        $id = Crypt::decrypt($id);
        $cancelLeave = UserLeave::findOrFail($id);
        if($cancelLeave->leave_status == 'cancelled' || $cancelLeave->leave_status=='not_approved'){
            if(Auth::user()->role_id == \App\User::ROLE_PROJECT_MANAGER || Auth::user()->role_id== \App\User::ROLE_HR){
                
                return redirect('/my/leave')->with('error', 'You are not applicable status '.$cancelLeave->leave_status.'');             
            }
            return redirect('/leave')->with('error', 'You are not applicable  status '.$cancelLeave->leave_status.'!');
           
        }
        $cancelLeave->update([
            'leave_status' => 'cancelled'
        ]);
        if(Auth::user()->role_id == \App\User::ROLE_PROJECT_MANAGER || Auth::user()->role_id== \App\User::ROLE_HR){
            return redirect('my/leave')->with('flash_message', 'Leave cancelled  successfully');
        }
        return redirect('/leave')->with('flash_message', 'Leave cancelled  successfully!');
    }


public function statusUpdate(request $request)
    {
      
        $employee_id = Auth::user()->id;
        $data = $request->all(); 
         $a = "'";     
         $cancelleave = '';      
        if(!empty($data['acceptid'])){
            $id = $data['acceptid'];
            $statusupdate = UserLeave::findOrFail($id);
            $statusupdate->update([
                'leave_status' => 'approved',
                'employee_id' => $employee_id
            ]);                
            $leave  =    DB::table('leaves')->select('users.email','leaves.request_type','users.id','users.first_name','users.last_name','users.work_mode','leaves.leave_status','leaves.leave_type_id','leaves.cc_user_ids','leaves.users_id', 'leaves.start_date', 'leaves.end_date', 'leaves.employee_id')      
            ->Join('users','users.id','=','leaves.users_id')
            ->where('leaves.id','=',$data['acceptid'])       
            ->first();
            $res = str_replace( array( 
                ',' ), ' ',  $leave->cc_user_ids);
             $ccuserid =  explode(" ",$res); 
             $to = [Auth::user()->id,$leave->users_id,41];
             
             $leavedata = [
                'name' => $leave->first_name,
                'message' =>'Your Leave is Approved',
                'status' =>$leave->leave_status,
              
                'leaveType'=>$leave->leave_type_id,
                'start_date'=>$leave->start_date,
                'end_date'=>$leave->end_date,
                'employee_id' => $employee_id,
               
            ];            

            // if (!empty($leave->leave_type_id) && $leave->leave_type_id == 4) {
            //     User::where('id', $leave->id)
            //         ->update(['work_mode' => 'WFH']);
            
            //     dispatch(function () use ($leave) {
            //         User::where('id', $leave->id)
            //             ->update(['work_mode' => 'WFO']);
            //     })->delay($leave->end_date);
            // }
            
            $ids  =  array_merge($ccuserid, $to);
            $management= User::whereIn('id',$ids)->get();
            
        if(!empty($management)){
                foreach($management  as $manage){
                    $leavedata['email'] = $manage->email;
                    $leavedata['action'] = '';
                    $user = User::where('id', $employee_id)->first();
                    $leavedata['employee_name'] = $user->first_name . ' ' . $user->last_name;
                    // Mail::to($manage->email)->send(new sendAppliedLeave($leavedata));
                    dispatch(new AppliedleaveStatus($leavedata));
                }
            }
            if($leave->request_type=='cancel_request'){
            $cancelleave = '<div style="color:red"> Cancel Leave </div>';
            }
              
               $staus = '<div class="realtimeststus_'.$id.'" style="color:green">Approved '.$cancelleave.' </div>';
               $response = '<a href="javascript:void(0);"  onclick="viewdetails('.$id.')" title="view details">
                                 <i class="fa fa-eye" style="font-size:20px;"></i>                               
                                 </a> &nbsp<a href="javascript:void(0);"  class="disable" onclick="statusupdate('.$id.','.$a.''.$leave->leave_status.''.$a.')" title="Approve" >
                                 <i class="fa fa-check" style="font-size:20px;"></i>
                                 </a> &nbsp
                                 <a  href="javascript:void(0);" onclick="reject('.$id.','.$a.''.$leave->leave_status.''.$a.')"id="Reject" title="reject">
                                 <i class="fa fa-times-circle" style="font-size:20px;color:red"></i>
                                 </a>';
        return response()->json(["status"=>$staus,"id"=>$id,"response"=>$response,
                "flash_message" => "Leave approved  successfully!"
            ]);
          
        }else{
            $rejectid = $data['rejectid'];
            $statusupdate = UserLeave::findOrFail($rejectid);
            $statusupdate->update([
                'leave_status' => 'not_approved',
                'leave_rejection_reason' => $data['description'],
                'employee_id' => $employee_id
            ]);
            $leave  =    DB::table('leaves')->select('users.email','leaves.request_type','users.first_name','leaves.leave_status','leaves.leave_rejection_reason','leaves.leave_type_id','leaves.cc_user_ids','leaves.users_id', 'leaves.start_date', 'leaves.end_date', 'leaves.employee_id')      
            ->Join('users','users.id','=','leaves.users_id')
            ->where('leaves.id','=',$rejectid)       
            ->first();
           
            $res = str_replace( array( 
                ',' ), ' ',  $leave->cc_user_ids);
             $ccuserid =  explode(" ",$res); 
             $to = [Auth::user()->id,$leave->users_id]; 
             
            $leavedata = [
            'name' => $leave->first_name,
            'message' =>$leave->leave_rejection_reason,
            'status' =>$leave->leave_status,          
            'leaveType'=>$leave->leave_type_id,
            'start_date'=>$leave->start_date,
            'end_date'=>$leave->end_date,
           
           ];
           $ids  =  array_merge($ccuserid, $to);
           $management= User::whereIn('id',$ids)->get();
       
        if(!empty($management)){
            foreach($management  as $manage){
                $leavedata['email'] = $manage->email;
                $leavedata['action'] = '';
                $user = User::where('id', $employee_id)->first();
                $leavedata['employee_name'] = $user->first_name . ' ' . $user->last_name;
                dispatch(new AppliedleaveStatus($leavedata));
            }
        }  
      if($leave->request_type=='cancel_request'){
        $cancelleave = '<div style="color:red"> Cancel Leave </div>';
        }  
        $staus = '<div class="realtimeststus_'.$rejectid.'" style="color:red"> Not Approved  '.$cancelleave.'</div>';
        $response = '<a href="javascript:void(0);"  onclick="viewdetails('.$rejectid.')" title="view details">
                                 <i class="fa fa-eye" style="font-size:20px;"></i>
                                 </a>&nbsp <a href="javascript:void(0);" onclick="statusupdate('.$rejectid.','.$a.''.$leave->leave_status.''.$a.')" title="Approve" >
                                 <i class="fa fa-check" style="font-size:20px;"></i>
                                 </a> &nbsp
                                 <a  href="javascript:void(0);" class="disable" onclick="reject('.$rejectid.','.$a.''.$leave->leave_status.''.$a.')"id="Reject" title="reject">
                                 <i class="fa fa-times-circle" style="font-size:20px;color:red"></i>
                                 </a>';
       
         return response()->json([ "status"=>$staus,"id"=>$rejectid,'response'=>$response,
                "flash_message" => "Leave  rejected  successfully!"
            ]);
      
        }
    }
public function export(Request $request){
     $data['team'] = Helper::sidebarQuery();      
        if(!empty($data['team']['team'])){ 
        $data =   $data['team']['team']->employee_id;     
        $res = str_replace( array( 
        ',' ), ' ',  $data);
        $employeeid =  explode(" ", $res);
          
        }
        $role_id = Auth::user()->role_id;
        $from = $request->from;
        $status = $request->status;
        $to = $request->to;
         
         $query = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','leave_types.value','users.first_name','users.last_name','users.employee_code'])
                ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
                ->leftJoin('users','users.id','=','leaves.users_id')
                ->where('leaves.leave_status','!=','cancelled')
                ->where('leaves.users_id','!=',Auth::user()->id);
                if($status != 'all'){       
                              
                $query->where('leave_status','=',$status);
                } 
                if($role_id  ==  \App\User::ROLE_EMPLOYEE){
                 $query->whereIn('users.id',$employeeid);   
                } 
                if(!empty($from) &&!empty($to) ){ 
                 $query->whereDate('start_date','>=',DATE($from));
                 $query->whereDate('end_date','<=',DATE($to)); 
                }                 
                $leaves =  $query->orderBy('id', 'DESC')->get();
                
        $setData = '';  
        $rowData = ''; 
        $columnHeader_summary = "Sr.no" .
        "\t" . "EmployeeName" .
        "\t" . "LeaveType" .
        "\t" . "FromDate" .
        "\t" . "ToDate " .
        "\t" . "Shift" .
        "\t" . "StartTime" .
        "\t" . "EndTime" .       
        "\t" . "Status" .
        "\t" . "Reason" .
        "\t" . "Leave Rejection Reason" .
        $setData_summary = '';
        $value_summary = '';
        $counter = 1;
        foreach($leaves as $key => $val) {
            
        if($val->leave_type_id == 1){
        $type = "Full day";
        
        } else if($val->leave_type_id == 2){ 
        $type = "Half Leave";
       
        }
        else {
        $type = "Short leave"; 
       
        }
        $startdate =  $val->start_date;
        $startdate =  $val->end_date;       
        $shift =   $val->half_day_type;    
        if($val->start_time){
        $startTime =  \Carbon\Carbon::parse($val->start_time)->format('g:i a');
        
        }else{
            $startTime =NULL;
        }  
        if($val->end_time){
            $endTime =  \Carbon\Carbon::parse($val->end_time)->format('g:i a');
            
        }else{
            $endTime = NULL;  
        }
        if($val->leave_status == 'not_approved'){
        $status = 'Not Approved';
        }else{
        $status = $val->leave_status;
        }
        $employeename = $val->first_name .'('. $val->employee_code.')';    
        $value_summary = '"' . $counter. '"' . "\t"
        . '"' . ($employeename) . '"' . "\t"
        . '"' . ($type) . '"' . "\t"
        . '"' . ($startdate) . '"' . "\t"
        . '"' . ($startdate) . '"' . "\t"
        . '"' . ($shift) . '"' . "\t"
        . '"' . ($startTime) . '"' . "\t"
        . '"' . ($endTime) . '"' . "\t"
       
        . '"' . ($status) . '"' . "\t"  
        . '"' . ($val->leave_reason) . '"' . "\t"   
        . '"' . ($val->leave_rejection_reason) . '"' . "\t";
              
        $setData_summary .= trim($value_summary) . "\n";
        $counter++;
        }
        header('Content-Type: text/xml; charset=utf-8');
        header("Content-Disposition: attachment; filename=export.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo ucwords($columnHeader_summary) . "\n" . $setData_summary . "\n";
        }

public function autocomplete(Request $request){
    $keyword = $request->get('search');          
    $leaves =DB::table('users')->select(['users.first_name','users.last_name']) 
    ->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"])
    ->get();
    $response = array();
    foreach($leaves as $autocomplate){
       $response[] = $autocomplate->first_name.' '.$autocomplate->last_name ;
      }
    return response()->json($response);
     
      }
public function myleaveRequest(Request $request)
      {   
         
    $calculateleave =   Helper::totalleaveleft();      
    $from = $request->from;
    $to = $request->to;
    $status = $request->status;
    $keyword  = NULL;  
        
    $query = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','leave_types.value','users.first_name','users.last_name','users.employee_code','users.role_id'])
       ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
       ->leftJoin('users','users.id','=','leaves.users_id');      
    if($request->ajax() &&!empty( $from ) && !empty($to)){
    
        $query->where('leaves.users_id','=',Auth::user()->id);  
        $query->whereDate('start_date','>=',DATE($from));
        $query->whereDate('end_date','<=',DATE($to)); 
        $leaves   =  $query->orderBy('id', 'DESC')->paginate(10);
        $leaves->setPath('leave');

        return view('leave-management.search',compact('leaves','keyword','from','to','status'));         
    }elseif($request->ajax()){
      
        $query->where('leaves.users_id','=',Auth::user()->id);
        $leaves   =  $query->orderBy('id', 'DESC')->paginate(10);
        $leaves->setPath('leave');
        return view('leave-management.search',compact('leaves','keyword','from','to','status')); 
    }else{        
        $query->where('leaves.users_id','=',Auth::user()->id);
        $leaves   =  $query->orderBy('id', 'DESC')->paginate(10);
        $leaves->setPath('leave');
        return view('leave-management.myLeave',compact('leaves','keyword','from','to','status','calculateleave'));    
    }      
      }
    public function show(Request $request)
    {       
        $id = $request->id;
        $leaves = DB::table('leaves')->select(['leaves.*','leave_types.type as leave_type','leave_types.value','users.first_name','users.last_name','users.employee_code','users.email','users.mobile_number','users.role_id'])
        ->leftJoin('leave_types','leave_types.id','=','leaves.leave_type_id')
        ->leftJoin('users','users.id','=','leaves.users_id')
        ->where('leaves.id',$id)->first();       
        $ccid  = explode(',', $leaves->cc_user_ids);
        $ccusers =  User::whereIn('id',$ccid )->get();        
        $data['html'] = $leaves;
        $data['ccusers'] = $ccusers ;
        $leave = view('leave-management.html')->with($data)->render();
        return response()->json(['html' =>$leave]);
  
    }
 
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel_leave(Request $request){
        $teamLead = Helper::team_lead_user(); 
        $auth = Auth::user();
        $user = User::where('id',$auth->id)->first();
        $email_users = User::whereIn('role_id', [2,3])->get();      
        $cc_users = User::where([
          ['id', '!=', $auth->id],
          ['is_deleted', '=', 0],  ['status', '=',1]
      ])->whereIn('role_id',[4,5])->orderBy('email','ASC')->get();
       $leaveTypes = DB::table('leave_types')->get();  
        return view('leave-management.add', compact('email_users', 'cc_users','leaveTypes','teamLead'));
    }

   
    public function edit($id)
    {
 
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

    public function cancel_reason($id){
        $cancelReason = UserLeave::where('request_type', 'cancel_request')->where('id', $id)->first();
        if ($cancelReason) {
            return response()->json(['cancel_reason' => $cancelReason->cancel_reason]);
        } else {
            return response()->json(['error' => 'Leave record not found.'], 404);
        }
    }    
}
