<?php

namespace App\Http\Controllers\PM;

use App\Department;
use App\Helpers\Helper;
use App\UserDesignation;
use Auth;
use Crypt;
use App\Dsr;
use App\User;
use App\Role;
use App\Project;
use Carbon\Carbon;
use App\Designation;
use App\ProjectAssigned;
use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
    * @developer       :   Ajmer
    * @modified_by     :   Papinder
    * @created_date    :   05-07-2018 (dd-mm-yyyy)
    * @modified_date   :   06-07-2019 (dd-mm-yyyy)
    * @purpose         :   Display users list
    * @params          :   email, password
    * @return_type     :   response as []
    */ 
    public function index(Request $request){
        $auth = Auth::user();
        $pro = Project::where([
            ['status', '!=', '0'],
            ['is_deleted', '!=', 1]
        ])->get();
        $query =[
            ['id', '!=', $auth->id],
            ['role_id','!=', 1],
            ['is_deleted', '!=', 1],
        ];
        if($request->isMethod('post')){
            $keyword = $request->get('search');
            $users = User::where('first_name','LIKE',"%$keyword%")->where($query)
                ->orWhere('last_name','LIKE',"%$keyword%")->where($query)
                ->orWhere('email','LIKE',"%$keyword%")
                ->where($query)->with('project_assign')->orderBy('id', 'desc')->paginate(10);
                $view = 'users.search';
        }else{
            $users = User::where($query)->with('project_assign')->orderBy('id', 'desc')->paginate(10);
            $view = 'users.index';
        }
            return view($view, compact('users', 'pro'));
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   05-07-2018 (dd-mm-yyyy)
    * @modified date   :   06-07-2018 (dd-mm-yyyy)
    * @purpose         :   Display user details
    * @params          :   id
    * @return          :   response as []
    */
    public function view($id = null){
      if(!$id){
        throw new \Exception('Not Found', 404);
      }
    	$user = User::where('id', $id);
      return view('layouts.admin.view', compact('user'));
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   Papinder Kumar
    * @created date    :   25-07-2018 (dd-mm-yyyy)
    * @modified date   :   08-08-2019
    * @purpose         :   create new user
    * @params          :   first_name, last_name, email, password, role_id
    * @return          :   response as []
    */
    public function store(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, User::saveUserVd());
        if($validator->fails()){
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
            'is_admin'          => $data['is_admin'],
            'department_id'     => $data['department'],
            'designation_id'    => $data['designations'],
            'password'          => Hash::make($data['password']),
            'reporting_manager_id' => isset($data['reportingManager']) ? implode(',', $data['reportingManager']) : $data['reportingManager'],
            'work_mode' => $data['work_mode'],
        ];
        if (request()->image) {
            $imageName = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/users/'), $imageName);
            $data['image'] = $imageName;
        }
      $user = User::create($data);
      $user->message = 'Hello!';
      $user->orgPassword = $orgPassword;
      try{
          Mail::to($data['email'])->send(new WelcomeEmail($user));
      }catch(\Exception $e){
      }
      return redirect('/pm/users')->with('flash_message', 'User created successfully!');
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   Papinder Kumar
    * @created date    :   27-07-2019 (dd-mm-yyyy)
    * @modified date   :   08-08-2019
    * @purpose         :   Edit user details
    * @params          :   id
    * @return          :   response as []
    */
    public function edit($id){
        try{
            $id = Crypt::decrypt($id);
            $user = User::findOrFail($id);
            $data = Role::where('id', '!=',1)->get();
            $empId = Helper::emp_generate_id($id);
            return view('users.edit', compact('user', 'data','empId'));
        }catch(\Exception $e){
            return redirect()->back()->with('flash_message','There is something wrong. Please try again');
        }
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   Papinder Kumar
    * @created date    :   27-07-2018 (dd-mm-yyyy)
    * @modified date   :   08-08-2019
    * @purpose         :   Edit user details
    * @params          :   id
    * @return          :   response as []
    */

    public function update(Request $request,$id){
        try{
            $id = Crypt::decrypt($id);
            $data = $request->all();
            $validator = Validator::make($data,User::updateUserVd($id));
            if ($validator->fails()) {
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
                'is_admin'          => $data['is_admin'],
                'department_id'     => $data['department'],
                'designation_id'    => $data['designations'],
                'reporting_manager_id' => isset($data['reportingManager']) ? implode(',', $data['reportingManager']) : $data['reportingManager'],
                'work_mode' => $data['work_mode'],
            ];
            if($user){
                if (request()->image) {
                    $imageName = time() . '.' . request()->image->getClientOriginalExtension();
                    request()->image->move(public_path('images/users/'), $imageName);
                    $data['image'] = $imageName;
                }
                $user->update($data);
            }
            return redirect('/pm/users')->with('flash_message', 'User updated successfully!');
        }catch(\Exception $e){
            return redirect('/pm/users')->withErrors($e->getMessage());
        }
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   27-07-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   remove user
    * @params          :   id
    * @return          :   response as []
    */

   	public function destroy($id){
        dd('pm');
      $id = Crypt::decrypt($id);
      $del = User::findOrFail($id);
      $del->update([
          'is_deleted' => 1
      ]);
      return redirect('/pm/users')->with('flash_message', 'User deleted successfully!');
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   Papinder
    * @created date    :   26-07-2018 (dd-mm-yyyy)
    * @modified date   :   08-08-2019
    * @purpose         :   Show the roles in add_user form
    * @params          :   
    * @return          :   response as []
    */
    public function roleList(){
        $role = Role::where('id', '!=', 1)->get();
        $desg = Designation::all();
        $dept = Department::all();
        $empId = Helper::emp_generate_id();
        return view('users.add', compact('role','desg','dept','empId'));
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
            return response()->json($this->response, 400);
        }
        $userId = Crypt::decrypt($data['user_id']);
        $totalHours = Dsr::join('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
                          ->where([
                            'dsrs.user_id' => $userId,
                            'dd.project_id' => $data['project_id']
                          ])
                          ->whereBetween('dd.created_at', [
                            $data['start_date']." 00:00:00",
                            $data['end_date']." 23:59:59"
                          ])
                          ->sum('dd.total_hours');
        $this->response['data'] = ['total_hours' => $totalHours];
        $this->response['success'] = true;
        return response()->json($this->response, 200);
    }

    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   27-07-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   Assign projects to users
    * @params          :   data as []
    * @return          :   response as []
    */
    public function assign_project(Request $request){
        $validator = Validator::make($request->all(),User::assignUserVd());
        if ($validator->fails()) {
            return back()->with('error_message', 'Sorry project not assigned!!');
        }
        $Project = new ProjectAssigned;
        $userId = Crypt::decrypt($request->get('user_id'));
        $where = [
          'user_id' => $userId
        ];
        $setData = [
          "project_id" => $request->get('assign') ? implode(",", $request->get('assign')) : ''
        ];
        ProjectAssigned::updateorCreate($where, $setData);
        return back()->with('flash_message', 'Project assigned successfully!');  
    }

}