<?php

namespace App\Http\Controllers\Api;

use Auth;
use Crypt;
use App\Dsr;
use App\User;
use App\Role;
use App\Project;
use App\ProjectAssigned;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Password;
class UserController extends Controller
{

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
            // return redirect('/admin/user/create')
            //         ->withErrors($validator)
            //         ->withInput($request->input());

            return response()->json([$validator->errors()->first()], 400);
        }

        $userId = Crypt::decrypt($data['user_id']);        

        $totalHours = Dsr::join('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
                           ->where('dsrs.user_id', $userId)
                           ->where('dd.project_id', $data['project_id'])
                           ->sum('dd.total_hours');

        return response()->json(['total_hours' => $totalHours ], 200);
    }

    Public function verifyToken($token){      
        $token =  JWTAuth::getToken();        
        try {  
             $usermail =  JWTAuth::setToken($token)->toUser();
                $user = User::where('email',$usermail->email)->where('interviewPanelStatus','=',1)->where('other_services','!=','logout')->first();                 
               $validuser = JWTAuth::authenticate($token);
                if($validuser && !empty($user) ){ 
                    $token = JWTAuth::fromUser($user);                                                                                             
                    return response()->json([
                        'status' => 200,
                        'data' => $user,
                        'message' => 'success',
                    ], 200);                               
                }else{
                    return response()->json([
                        'status' => 401,
                        'message' => 'Token Invalid',
                    ], 401);
                } 

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'status' => 401,
                'message' => 'token is expired',
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'status' => 401,
                'message' => 'Token Invalid',
            ], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
        
            return response()->json([
                'status' => 401,
                'message' => $e->getMessage(),
            ], 401);
        }

    }

    public function  loginApi(Request $request){

      $credentials = $request->only('email', 'password');
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => $validator->messages()
            ]);
        }
        try {
            // Attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'We can`t find an account with this credentials.'
                ], 401);
            }
        } catch (JWTException $e) {
            // Something went wrong with JWT Auth.
            return response()->json([
                'status' => 'error', 
                'message' => 'Failed to login, please try again.'
            ], 500);
        }
        // All good so return the token
        return response()->json([
            'status' => 'success', 
            'data'=> [
            'token' => $token
                // You can add more details here as per you requirment. 
            ]
        ]);
    }
   
   public function forgot_password(Request $request)
{

    $input = $request->all();

    $rules = array(
        'email' => "required|email",
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array("status" => 400, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject($this->getEmailSubject());
            });

            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return \Response::json(array("status" => 200, "message" => trans($response)));
                case Password::INVALID_USER:
                    return \Response::json(array("status" => 400, "message" => trans($response), "data" => array()));
            }
        } catch (\Swift_TransportException $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        } catch (Exception $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        }
    }
    return \Response::json($arr);
}
  
    
  public function logout(Request $request) 
    {


        $token = $request->header('Authorization');

        // Invalidate the token
        try {

           // $user= JWTAuth::invalidate($token);
         Auth::user()->tokens->each(function($token, $key) {
           $token->delete();
        });

     
            return response()->json([
                'status' => 'success', 
                'message'=> "User successfully logged out."
            ]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json([
              'status' => 'error', 
              'message' => 'Failed to logout, please try again.'
            ], 500);
        }
    }


    public function AllEmployees(){
        try {     
        $users = User::with('department','designation')->with('role'); 
        $users = $users->orderBy('id', 'desc')->paginate(10);
           return response()->json($users, 200);       
        } catch (\Exception $e) {          
         return response()->json(['errors' => true], 500);

        }
    
    }

    public function getHr(){
    try {     
      $users = User::with('department','designation','role')->where('role_id',5);        
      $users = $users->orderBy('id', 'desc')->paginate(10);
      return response()->json($users, 200);         
        } catch (\Exception $e) {           
        return response()->json(['errors' => true], 500);

        }
     
    }

}


?>