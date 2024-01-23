<?php

namespace App\Http\Controllers\Employee;

use Auth;
use Crypt;
use App\Dsr;
use App\User;
use App\Role;
use App\Department;
use App\Designation;
use App\PermissionRole;
use App\Project;
use App\Helpers\Helper;
use Carbon\Carbon;
use App\UserDesignation;
use App\ProjectAssigned;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
    $auth = Auth::user();
    $projects = Project::where([
      ['status', '!=', '0'],
      ['is_deleted', '!=', 1]
      ])->get();
      $query = [
        ['id', '!=', $auth->id],
        ['is_deleted', '!=', 1]
      ];
      if($request->isMethod('post')){
        $keyword = $request->get('search');
        $users = User::where('first_name','LIKE',"%$keyword%")->where($query)
        ->orWhere('last_name','LIKE',"%$keyword%")->where($query)
        ->orWhere('email','LIKE',"%$keyword%")->where($query)
        ->with('role')->orderBy('id', 'desc')->paginate(10);
        $view = 'users.search';
      }else{
        $users = User::where($query)->with('role')->orderBy('id', 'desc')->paginate(10);
        $view = 'users.index';
      }
      return view($view, compact('users','projects'));
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
      $validator = Validator::make($data, User::saveUserVd());
      if($validator->fails()){
        // dd($validator->fails(),$validator,$request->input(),$request->all());
        return back()->withErrors($validator)->withInput($request->input());
      }
      $orgPassword = $data['password'];
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
        'role_id'       => $data['role_id'],
        'status'            => $data['status'],
        'department_id'     => $data['department'],
        'designation_id'    => $data['designations'],
        'password'          => Hash::make($data['password']),
        'reporting_manager_id' => isset($data['reportingManager']) ? implode(',', $data['reportingManager']) : $data['reportingManager'],
        // 'reporting_manager_id2' => $data['reportingManager2'],
        'work_mode' => $data['work_mode'],
        'added_by' => Auth::user()->id,
      ];
      // dd($data);
      if (request()->image) {
        $imageName = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/users/'), $imageName);
        $data['image'] = $imageName;
      }
      if (!empty($request->permission) && ($data['role_id'] != 1)) {
        $data['permission_id'] = implode(',',$request->permission);
      }

      $user = User::create($data);
      $user->message = 'Hello!';
      $user->orgPassword = $orgPassword;
      try{
        Mail::to($data['email'])->send(new WelcomeEmail($user));
      }catch(\Exception $e){
        // echo $e->getMessage(); die;

      }
      return redirect('/users')->with('flash_message', 'User created successfully!');
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
      $data = Role::where('id','!=',1)->get();
      $empId = Helper::emp_generate_id($id);
      return view('users.edit', compact('user', 'data','empId'));
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
        $validator = Validator::make($data,User::updateUserVd($id));
        if ($validator->fails()) {
          // dd($request->all());
          return back()
          ->withErrors($validator)
          ->withInput($request->input());
        }
        $user = User::findOrFail($id);
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
          'role_id'       => $data['role_id'],
          'status'            => $data['status'],
          'department_id'     => $data['department'],
          'designation_id'    => $data['designations'],
          'reporting_manager_id' => isset($data['reportingManager']) ? implode(',', $data['reportingManager']) : $data['reportingManager'],
          // 'reporting_manager_id2' => $data['reportingManager2'],
          'work_mode' => $data['work_mode'],
        ];
        if($user){
          if (request()->image) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/users/'), $imageName);
            $data['image'] = $imageName;
          }
          if (!empty($request->permission) && ($data['role_id'] != 1)) {
            $data['permission_id'] = implode(',',$request->permission);
          }
          $user->update($data);
        }
      }
      catch (\Exception $e) {
        // echo $e->getMessage(); die;
      }
      return redirect('/employee/users')->with('flash_message', 'User updated successfully!');
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

    public function destroy($id){
      $id = Crypt::decrypt($id);
      $del = User::findOrFail($id);
      $del->update([
        'is_deleted' => 1
      ]);
      return redirect('/employee/users')->with('flash_message', 'User deleted successfully!');
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
      $role = Role::where('id','!=',1)->get();
      $desg = Designation::whereStatus(1)->get();
      $dept = Department::whereStatus(1)->get();
      $empId = Helper::emp_generate_id();
      return view('users.add', compact('role','desg','dept','empId'));
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
        return redirect('/users')
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

      return redirect('/employee/users')->with('flash_message', 'Project assigned successfully!');
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

  }