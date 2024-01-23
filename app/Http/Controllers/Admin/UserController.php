<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Crypt;
use Log;
use App\Dsr;
use App\User;
use App\Role;
use App\Department;
use App\Designation;
use App\Exports\EmployeeExport;
use App\PermissionRole;
use App\Project;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\UserDesignation;
use App\ProjectAssigned;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Technology;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use JWTAuth;
use League\CommonMark\Node\Block\Document;
use Tymon\JWTAuth\Facades\JWTFactory;
class UserController extends Controller
{

  /**
  * @developer       :   Ajmer
  * @modified by     :   Akshay
  * @created date    :   05-07-2018 (dd-mm-yyyy)
  * @modified date   :   06-07-2018 (dd-mm-yyyy)
  * @purpose         :   Display users details
  * @params          :   email, password
  * @return          :   response as []
  */

  public function index(Request $request){

    if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [4])){
      return redirect()->back();
    }
    $auth = Auth::user();
    $status = 'all';
    $projects = Project::where([
      ['status', '!=', '0'],
      ['is_deleted', '!=', 1]
      ])->get();
  
      if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3])){
        $users = User::with('department','designation')->where('is_deleted',0)->where('id', '!=',$auth->id)->where('reporting_manager_id',Auth::user()->id)->with('role');
      }else {
        $users = User::with('department','designation')->where('is_deleted',0)->where('id', '!=',$auth->id)->with('role');
      }

      if($request->isMethod('post')){
        $keyword = $request->get('search');
        $status = in_array($request->get('status'),['all','0','1'])?$request->get('status'):'all';
        if(!empty($keyword)){
          $users = $users->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"]);
                          // ->orWhere('email','LIKE',"%$keyword%");
        }
        
        if($status != 'all'){
          $users = $users->where('status',$status);
        }
        $view = 'users.search';
      }else{
          if($status != 'all'){
              $users = $users->where('status',$status);
          }
          $view = 'users.index';
      }
      $users = $users->orderBy('id', 'desc')->paginate(10)->setPath(url('admin/users'));
      return view($view, compact('users','projects', 'status'));
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   06-07-2018 (dd-mm-yyyy)
    * @purpose         :   create new user
    * @params          :   first_name, last_name, email, password, role_id
    * @return          :   response as []
    */

    public function store(Request $request){
      $data = $request->all();
      $messages['reportingManager.required_if'] = "Reporting manager field is required when role type is employee.";
      $validator = Validator::make($data, User::saveUserVd(),$messages);
      if($validator->fails()){
        // dd($validator->fails(),$validator,$request->input(),$request->all());
        return back()->withErrors($validator)->withInput($request->input());
      }
      $orgPassword = $data['password'];
       if($request->interviewPanelStatus=='on'){
        $interviewPanelStatus = 1;
       }else{
         $interviewPanelStatus = 0;
       }      
       if($request->it_ticket_dashboard=='on'){
        $it_ticket_dashboard = 1;
       }else{
         $it_ticket_dashboard = 0;
       }
       if($request->ipm_panel=='on'){
        $ipm_panel = 1;
       }else{
         $ipm_panel = 0;
       }    
       $emp_tech_string =  isset($data['technologies']) ? implode(',', $data['technologies']) :null;
      $data = [
        'employee_code' => $data['employee_code'],
        'first_name'    => $data['first_name'],
        'last_name'     => $data['last_name'],
        'email'         => $data['email'],
        'phone_number'  => $data['phone_number'],
        'mobile_number' => $data['mobile_number'],
        'pan_number'    => $data['pan_number'],
        'joining_date'  => $data['date_of_joining'],
        'dob'           => $data['date_of_birth'],
        'address'       => $data['address'],
        'permanent_address'       => $data['permanent_address'],
        'role_id'       => $data['role_id'],
        'status'            => $data['status'],
        'department_id'     => $data['department'],
        'designation_id'    => $data['designations'],
        'password'          => Hash::make($data['password']),
        'reporting_manager_id' => isset($data['reportingManager']) ? implode(',', $data['reportingManager']) : $data['reportingManager'],
        'g_meet_link' => $data['g_meet_link'],
        'end_probation' => $data['date_of_exp_probation'] ? $data['date_of_exp_probation'] : null,
         'shift_start_time' => $data['shift_start_time'],
        'added_by' => Auth::user()->id,
        'interviewPanelStatus' =>$interviewPanelStatus,
        'ipm_panel' =>$ipm_panel,
        'it_ticket_dashboard'=>$it_ticket_dashboard,
        'add_interview_questions'=> $request->canAddQuestions ? 1 : 0,
        'emp_technologies'=>$emp_tech_string,
        'work_mode' => $data['work_mode'],
        'canScheduleInterview' =>isset($data['canScheduleInterview']) ? $data['canScheduleInterview'] : 0,
         'user_technologies'=>!empty($data['technologies']) ? implode(",", $data['technologies']) : ''

      ];

      
      if($data['role_id'] != User::ROLE_ADMIN){
          $data['is_admin'] = 0;
      }
      if ($request->hasFile('image')) {
        $photo = $request->file('image');
        $imageName = Auth::user()->id . '_avatar' . time() . '.' . $photo->getClientOriginalExtension();
        $request->file('image')->move(public_path('images/profile_picture/'), $imageName);                    
        $data['image'] = $imageName;
      }
      if (!empty($request->permission) && ($data['role_id'] != 1)) {
        $data['permission_id'] = implode(',',$request->permission);
      }

      $user = User::create($data);
      $user->message = 'Hello!';
      $user->orgPassword = $orgPassword;
      
      if(env('sync_interviewer') == true){   
        Helper::interviewpanel();
      }
      
      try{
        Mail::to($data['email'])->send(new WelcomeEmail($user));
      }catch(\Exception $e){
        // echo $e->getMessage(); die;

      }
      return redirect('/admin/users')->with('flash_message', 'User created successfully!');
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   06-07-2018 (dd-mm-yyyy)
    * @purpose         :   show the form of edit user
    * @params          :   id
    * @return          :   response as []
    */


    public function edit($id){
      $id = Crypt::decrypt($id);
      $user = User::findOrFail($id);
      $technology = DB::table('departments')->where('status',1)->get();
    
      // $data = Role::where('id','!=',1)->get();
      $data = Role::get();
      $empId = Helper::emp_generate_id($id);
      

      if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3])){
        return view('users.view', compact('user', 'data','empId'));
      }else{
        return view('users.edit', compact('user', 'data','empId'));
      }


    }




    /**
    * @developer       :   Ajmer
    * @modified by     :
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   06-07-2018 (dd-mm-yyyy)
    * @purpose         :   Update the user details
    * @params          :   id
    * @return          :   response as []
    */

    public function update(Request $request,$id){

      try {
      
        $id = Crypt::decrypt($id);
      
        $data = $request->all();

        $messages['reportingManager.required_if'] = "Reporting manager field is required when role type is employee.";
        $validator = Validator::make($data,User::updateUserVd($id),$messages);
        if ($validator->fails()) {
          return back()
          ->withErrors($validator)
          ->withInput($request->input());
        }
        if($request->interviewPanelStatus=='on' ){
         $interviewPanelStatus = 1;         
        }else{
          $interviewPanelStatus = 0;
        }

        if($request->it_ticket_dashboard=='on'){
          $it_ticket_dashboard = 1;
         }else{
           $it_ticket_dashboard = 0;
         } 

         if($request->ipm_panel=='on'){
          $ipm_panel = 1;
         }else{
           $ipm_panel = 0;
         } 
        $user = User::findOrFail($id);
        $emp_tech_string =  isset($data['technologies']) ? implode(',', $data['technologies']) :null;
        if(!empty($data['reportingManager'])){
          $reporting_manager_id = isset($data['reportingManager']) ? implode(',', $data['reportingManager']) : $data['reportingManager'];
        } else {
          $reporting_manager_id = 0;
        }
     
        $data = [
          'first_name'    => $data['first_name'],
          'last_name'     => $data['last_name'],
          'email'         => $data['email'],
          'phone_number'  => $data['phone_number'],
          'mobile_number' => $data['mobile_number'],
          'pan_number'    => $data['pan_number'],
          'joining_date'  => $data['date_of_joining'],
          'dob'           => $data['date_of_birth'],
          'address'       => $data['address'],
          'permanent_address'       => $data['permanent_address'],
          'role_id'       => $data['role_id'],
          'status'            => $data['status'],
          'department_id'     => $data['department'],
          'designation_id'    => $data['designations'],
          'reporting_manager_id' => $reporting_manager_id,
          'g_meet_link' => $data['g_meet_link'],
          'shift_start_time' => $data['shift_start_time'],
          'emp_technologies'=>$emp_tech_string,
          'interviewPanelStatus' =>$interviewPanelStatus,
          'end_probation' => $data['date_of_exp_probation'] ? $data['date_of_exp_probation'] : null,
          'it_ticket_dashboard'=>$it_ticket_dashboard,
          'ipm_panel'=>$ipm_panel,
          'canScheduleInterview' =>$request->canScheduleInterview,
          'user_technologies'=>!empty($data['technologies']) ? implode(",", $data['technologies']) : '',
          'work_mode' => $data['work_mode']
        ];


        if ($request->hasFile('image')) {
          $photo = $request->file('image');

          $imageName = $id . '_avatar' . time() . '.' . $photo->getClientOriginalExtension();
          $request->file('image')->move(public_path('images/profile_picture/'), $imageName);            
          $data['image'] = $imageName;
        }
       

        if (!empty($request->permission) && ($data['role_id'] != 1)) {
          $data['permission_id'] = implode(',',$request->permission);
        }
        if(!empty($user)){
            $update = $user->update($data);
        }
      
        if(env('sync_interviewer') == true){   
          Helper::interviewpanel();
        }      
     
      } 
      catch (\Exception $e) {
         echo $e->getMessage(); die;
      }
      
      return redirect('/admin/users')->with('flash_message', 'User updated successfully!');
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   06-07-2018 (dd-mm-yyyy)
    * @purpose         :   Remove user from user_list
    * @params          :   id
    * @return          :   response as []
    */

    public function destroy(Request $request,$id){

      $id = Crypt::decrypt($id);
      $del = User::findOrFail($id);
      $del->update([
        'is_deleted' => 1,
        'status' => 0
      ]);
      if($request->ajax()){
        return response()->json(['status'=>'success', 'message'=>'Record deleted successfully.']);
      }
      return redirect('/admin/users')->with('flash_message', 'User deleted successfully!');
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   06-07-2018 (dd-mm-yyyy)
    * @purpose         :   Show the roles in add_user form
    * @params          :   id
    * @return          :   response as []
    */
    public function roleList(){
      // $roles = Role::where('id','!=',1)->get();
      $roles = Role::get();
      $desg = Designation::whereStatus(1)->get();
      $dept = Department::whereStatus(1)->get();
      // $tech = Technology::whereStatus(1)->get();
      $empId = Helper::emp_generate_id();
      return view('users.add', compact('roles','desg','dept','empId'));
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   20-07-2018 (dd-mm-yyyy)
    * @modified date   :   25-07-2018 (dd-mm-yyyy)
    * @purpose         :   Assign projects to users
    * @params          :   data as []
    * @return          :   response as []
    */
    public function assign_project(Request $request){

      $validator = Validator::make($request->all(),User::assignUserVd());
      if ($validator->fails()) {
        return redirect('/admin/users')
        ->withErrors($validator)
        ->withInput($request->input());
      }
      $Project = new ProjectAssigned;
      $userId = $request->get('user_id');
      $userId = Crypt::decrypt($userId);
      $data = $request['assign'];
      $assign_project = implode(",",$data);
      $Project->project_id = $assign_project;
      $Project->user_id = $userId;
      $Project->save();

      return redirect('/admin/users')->with('flash_message', 'Project assigned successfully!');
    }

    /**
    * @developer       :   Akshay
    * @modified by     :
    * @created date    :   25-07-2018 (dd-mm-yyyy)
    * @modified date   :
    * @purpose         :   to get project time estimates of user
    * @params          :   data as []
    * @return          :   response as []
    */
    public function getTimeEstimates(Request $request){

      $data = $request->only('project_id', 'user_id', 'start_date', 'end_date');

      $validator = Validator::make($data, User::projectTimeEstVd(), User::projectTimeEstMsg());

      if($validator->fails()){

        $this->response['message'] = $validator->errors()->first();
        return response()->json( $this->response, 400);
      }

      $userId = Crypt::decrypt($data['user_id']);

      $totalHours = Dsr::join('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
      ->where('dsrs.user_id', $userId)
      ->where('dd.project_id', $data['project_id'])
      ->whereBetween('dd.created_at', [
        $data['start_date']." 00:00:00",
        $data['end_date']." 23:59:59"
      ])
      ->sum('dd.total_hours');

      $this->response['data'] = ['total_hours' => $totalHours];
      $this->response['success'] = true;
      $this->response['status'] = 200;
      return response()->json($this->response, 200);
    }

    /**
      * @developer       :   Mohit
      * @created date    :   6-01-2022 (dd-mm-yyyy)
      * @modified date   :   N/A
      * @purpose         :   Export Exployees
      * @params          :   users status and user name
      * @return          :   Excel File
    */
    public function exportEmployees(Request $request)
    {
        $status  = trim($request->input('status'));
        $employee_name  = trim($request->input('employee_name'));

        $users = User::where('is_deleted',0);
        if($status!='all'){
          $users = $users->where('status','=',$status);
        }
        if($employee_name){
          $users = $users->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $employee_name . '%');
        }
        $users = $users->get();
        $file_name = 'employee_list_' . time() . '.xlsx';
        $employees_attendance = [];
        foreach($users as $user){
          $status = "Active";
            if($user->status == 0){
              $status = "In-Active";
            }
           $employees_attendance[] = [
                'Employee Name'=> $user->first_name .' ' .$user->last_name,
                'Email' => !empty($user->email) ? $user->email: '-',
                'DOB' => !empty($user->dob) ? date('d-m-Y', strtotime($user->dob)): '-',
                'Joining Date' => !empty($user->joining_date) ? date('d-m-Y', strtotime($user->joining_date)): '-',
                'Department' => !empty($user->department) ? $user->department->name: '-',
                'Designation' => !empty($user->designation) ? $user->designation->name: '-',
                'Employee Code' => !empty($user->employee_code) ? $user->employee_code: '-',
                'Role' => !empty($user->role) ? $user->role->role: '-',
                'Status' => $status,
            ];
        }

        return Excel::download(new EmployeeExport($employees_attendance), $file_name);
    }
     
    public function generate_ipm_token(){
      try {
        if(!empty(Auth::user())){
        $user_ipm = User::where(['email'=>Auth::user()->email,'ipm_panel'=>1])->first();
                $time = 480;            
                JWTAuth::factory()->setTTL($time);       
                $token = JWTAuth::fromUser($user_ipm);                   
                $user_ipm  = User::findOrFail($user_ipm->id);        
                $user_ipm->update(['other_services'=>$token]);  
        
                $platformUrl = DB::table('platform')->where('platform_services','=','ipm_panel')->first();
                return redirect(''.$platformUrl->platform_url.'/ipm_panel?token='.$token); 
        }
        return redirect('login');
      } catch (JWTException $e) {
        return redirect('login');
      }
    }

    public function generate_token(Request $request){
        try {  
          if(!empty(Auth::user())){
            $user = User::where(['email'=>Auth::user()->email,'interviewPanelStatus'=>1])->first();
             $time = 480;            
             JWTAuth::factory()->setTTL($time);       
             $token = JWTAuth::fromUser($user);                   
             $user  = User::findOrFail($user->id);        
             $user->update(['other_services'=>$token]);  
             $platformUrl = DB::table('platform')->where('platform_services','=','interview_panel')->first();
             return redirect(''.$platformUrl->platform_url.'/interviewlist?token='.$token);
                    
        }
        return redirect('login');
        
        }catch (JWTException $e) {  
               
          return redirect('login');
        
        }     
    
    }

    public function generate_biometric_token(Request $request){
      try {  
        if(!empty(Auth::user())){
          $user = User::id('232');
           $time = 480;            
           JWTAuth::factory()->setTTL($time);       
           $token = JWTAuth::fromUser($user);                   
           $user  = User::findOrFail($user->id);        
           $user->update(['biometric_token'=>$token]);  
          //  $platformUrl = DB::table('platform')->where('platform_services','=','interview_panel')->first();
           return redirect(''.$platformUrl->platform_url.'/interviewlist?token='.$token);
                  
      }
      return redirect('login');
      
      }catch (JWTException $e) {  
             
        return redirect('login');
      
      }     
  
  }
    public function logoutinterviewpanel(Request $request)
    {
        Auth::logout();
        return redirect('login/?service=interview_panel');
    }
  
    public function team_members(Request $request){

      if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [4])){
        return redirect()->back();
      }
      $auth = Auth::user();
      $status = 'all';
      $projects = Project::where([
        ['status', '!=', '0'],
        ['is_deleted', '!=', 1]
        ])->get();
    
        if(in_array(\Illuminate\Support\Facades\Auth::user()->role_id, [3])){
          // $users = User::with('department','designation')->where('is_deleted',0)->where('id', '!=',$auth->id)->where('reporting_manager_id',Auth::user()->id)->with('role');
          $users = User::with('department', 'designation')
            ->where('is_deleted', 0)
            ->where('id', '!=', $auth->id)
            ->where(function ($query) {
                $query->whereRaw("FIND_IN_SET(?, reporting_manager_id)", [Auth::user()->id])
                    ->orWhere('reporting_manager_id', Auth::user()->id);
            })
            ->with('role');
        }else {
          $users = User::with('department','designation')->where('is_deleted',0)->where('id', '!=',$auth->id)->with('role');
        }
  
        if($request->isMethod('post')){
          $keyword = $request->get('search');
          $status = in_array($request->get('status'),['all','0','1'])?$request->get('status'):'all';
          if(!empty($keyword)){
            $users = $users->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"]);
                            // ->orWhere('email','LIKE',"%$keyword%");
          }
          
          if($status != 'all'){
            $users = $users->where('status',$status);
          }
          $view = 'users.search';
        }else{
            if($status != 'all'){
                $users = $users->where('status',$status);
            }
            $view = 'users.index';
        }
        $users = $users->orderBy('id', 'desc')->paginate(10)->setPath(url('admin/users'));
        return view($view, compact('users','projects', 'status'));
      }


  }