<?php

namespace App\Http\Controllers;

use App\Department;
use App\Designation;
use Auth;
use Crypt;
use App\Dsr;
use App\User;
use App\Role;
use App\Project; 
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    // /**
    // * @developer       :   Ajmer
    // * @modified by     :   Akshay
    // * @created date    :   05-07-2018 (dd-mm-yyyy)
    // * @modified date   :   06-07-2018 (dd-mm-yyyy)
    // * @purpose         :   Display users list
    // * @params          :   email, password
    // * @return          :   response as []
    // */
    // public function index(){

    //   $auth = Auth::user();
      
    //   $users = User::where([
    //     ['id', '!=', $auth->id],
    //     ['role_id','!=', 1],
    //     ['is_deleted', '!=', 1]
    //   ])->with('project_assign')->orderBy('id', 'desc')->paginate(10);

    //   $pro = Project::where([
    //     ['status', '!=', '0'],
    //     ['is_deleted', '!=', 1]
    //   ])->get();

    //   return view('users.index', compact('users', 'pro'));
    // }

    // /**
    // * @developer       :   Ajmer
    // * @modified by     :   Akshay
    // * @created date    :   05-07-2018 (dd-mm-yyyy)
    // * @modified date   :   06-07-2018 (dd-mm-yyyy)
    // * @purpose         :   Display user details
    // * @params          :   id
    // * @return          :   response as []
    // */
    // public function view($id = null){

    //   if(!$id){
    //     throw new \Exception('Not Found', 404);
    //   }

    // 	$user = User::where('id', $id);

    //   return view('layouts.admin.view', compact('user'));
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   25-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   create new user
    // * @params          :   first_name, last_name, email, password, role_id
    // * @return          :   response as []
    // */
    // public function store(Request $request){

  		// $data = $request->only('first_name', 'last_name', 'email', 'password', 'role_id');

    //   $validator = Validator::make($request->all(), User::saveUserVd());

    //   if($validator->fails()){
    //       return back()->withErrors($validator)->withInput($request->input());
    //   }
      
    //   $orgPassword = $data['password'];
    //   $data['password'] = Hash::make($data['password']);   
    //   $user = User::create($data);
    //   $user->message = 'Hello!';
    //   $user->orgPassword = $orgPassword;

    //   try{

    //       Mail::to($data['email'])->send(new WelcomeEmail($user));
    //   }catch(\Exception $e){
          
    //   }
      
    //   return redirect('/pm/users')->with('flash_message', 'User created successfully!');
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   27-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   Edit user details
    // * @params          :   id
    // * @return          :   response as []
    // */
    // public function edit($id){

    //   $id = Crypt::decrypt($id);

    //   $auth=Auth::user();

    //   $user = User::findOrFail($id);

    //   $data = Role::where('id', '!=',1)->get();
      
    //   return view('users.edit', compact('user', 'data'));
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   27-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   Edit user details
    // * @params          :   id
    // * @return          :   response as []
    // */

    // public function update(Request $request,$id){

    //   $id = Crypt::decrypt($id);
    //   $req = $request->only('first_name','last_name','role_id');
    //   $validator = Validator::make($request->all(),User::updateUserVd()); 
      
    //   if ($validator->fails()) {

    //       return back()
    //               ->withErrors($validator)
    //               ->withInput($request->input());       
    //   }
      
    //   $user = User::findOrFail($id);

    //   $user->update($req);
      
    //   return redirect('/pm/users')->with('flash_message', 'User updated successfully!');
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   27-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   remove user
    // * @params          :   id
    // * @return          :   response as []
    // */

   	// public function destroy($id){
    //   $id = Crypt::decrypt($id);
    //   $del = User::findOrFail($id);

    //   $del->update([
    //       'is_deleted' => 1
    //   ]);
    //   // $del->delete($id);
        
    //   return redirect('/pm/users')->with('flash_message', 'User deleted successfully!');
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   26-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   Show the roles in add_user form
    // * @params          :   
    // * @return          :   response as []
    // */
    // public function roleList(){

    //     $auth = Auth::user();
    //     $role = Role::where('id', '!=', 1)->get();
    //     return view('users.add', compact('role'));
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   25-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   to get project time estimates of user
    // * @params          :   data as []
    // * @return          :   response as []
    // */
    // public function getTimeEstimates(Request $request){
       
    //     $data = $request->only('project_id', 'user_id', 'start_date', 'end_date');

    //     $validator = Validator::make($data, User::projectTimeEstVd(), User::projectTimeEstMsg());

    //     if($validator->fails()){

    //         $this->response['message'] = $validator->errors()->first();
    //         return response()->json($this->response, 400);
    //     }

    //     $userId = Crypt::decrypt($data['user_id']);        

    //     $totalHours = Dsr::join('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
    //                       ->where([
    //                         'dsrs.user_id' => $userId,
    //                         'dd.project_id' => $data['project_id']
    //                       ])
    //                       ->whereBetween('dd.created_at', [
    //                         $data['start_date']." 00:00:00",
    //                         $data['end_date']." 23:59:59"
    //                       ])
    //                       ->sum('dd.total_hours');

    //     $this->response['data'] = ['total_hours' => $totalHours];
    //     $this->response['success'] = true;

    //     return response()->json($this->response, 200);
    // }

    // /**
    // * @developer       :   Akshay
    // * @modified by     :   
    // * @created date    :   27-07-2018 (dd-mm-yyyy)
    // * @modified date   :   
    // * @purpose         :   Assign projects to users
    // * @params          :   data as []
    // * @return          :   response as []
    // */
    // public function assign_project(Request $request){

    //     $validator = Validator::make($request->all(),User::assignUserVd()); 
    //     if ($validator->fails()) {

    //         return redirect('/pm/users')->with('error_message', 'Sorry project not assigned!!');       
    //     }
    //     $Project = new ProjectAssigned;
        
    //     $userId = Crypt::decrypt($request->get('user_id'));
        
    //     $where = [
    //       'user_id' => $userId
    //     ];

    //     $setData = [
    //       "project_id" => Input::get('assign') ? implode(",", Input::get('assign')) : ''
    //     ];
        
    //     ProjectAssigned::updateorCreate($where, $setData);
        
    //     return redirect()->back()->with('flash_message', 'Project assigned successfully!');  
    // }

    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   27-08-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   to show change password view
    * @params          :   data as []
    * @return          :   view
    */
    public function change_password_view(Request $request){
      $user = User::where('id',Auth::id())->firstOrFail();
      $department = Department::where('id', $user->department_id)->first();
      if ($department) {
          $user->department = $department->name; // Assuming 'name' is the department name attribute
      }

      $designation = Designation::where('id', $user->designation_id)->first();
      if ($designation) {
          $user->designation = $designation->name; // Assuming 'name' is the department name attribute
      }
      return view('auth.change_password', compact('user'));
    }

    public function removeProfilePicture()
    {
        $id = \Illuminate\Support\Facades\Auth::user()->id;
        User::where(['id' => $id])->update(['image' => NULL]);
        return redirect('/change_password')->with('flash_message', 'Profile picture removed successfully.');
    }


    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   27-08-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   to change password
    * @params          :   data as []
    * @return          :   response as []
    */
    public function change_password(Request $request){
      $data = $request->all();
      $validator = Validator::make($data, User::changePsswrdVd()); 
      if ($validator->fails()) {
          return back()->withErrors($validator)
                                   ->withInput($request->input());
      }

      $auth = Auth::user();
     
      if(!Hash::check($data['old_password'], $auth->password)){
          return back()->withInput($request->input())->with('error_flash_message', 'Old password doesn\'t matched');

      }

        if(strcmp($request->get('old_password'), $request->get('new_password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error_flash_message","New Password cannot be same as your old password. Please choose a different password.");
        }

      User::where('id', $auth->id)->update([
        'password' => Hash::make($data['new_password'])
      ]);

      Auth::logout();
      
      return redirect('/')->with('flash', 'Password updated successfully!');  
      // return back()->with('flash_message', 'Password updated successfully!');  
    }

    public function changeProfilePicture(Request $request){
        $id = Auth::id();
        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $maxFileSize = 2 * 1000 * 1000; // 2MB in bytes
            if ($photo->getSize() > $maxFileSize) {
              return back()->withErrors(['image' => 'The image file size should not exceed 2MB.'])->withInput();
            }
            $imageName = $id . '_avatar' . time() . '.' . $photo->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/profile_picture/'), $imageName);
            $data['image'] = $imageName;
            User::where('id',$id)->update(['image' => $imageName]);
            return back()->with('flash_message', 'Image updated successfully!');
        }
    }

}


