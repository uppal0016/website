<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Traits\Redirections;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Input;
use JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;
    use Redirections;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest')->except('logout');
    }


    /**
    * @developer       :   Akshay
    * @created date    :   05-06-2018 (dd-mm-yyyy)
    * @purpose         :   Authenticate user
    * @params          :   email, password
    * @return          :   response as []
    */
    public function showLoginForm(Request $request){ 
      $txt =  url()->previous();
   
      if($txt){
        $str = preg_replace('/\W\w+\s*(\W*)$/', '$1', $txt);
        $qrcode_url  = substr( $str, strrpos( $str, '/' )+1);   
      
      }    
      if($qrcode_url =='check'){
        $id = substr( $txt, strrpos( $txt, '/' )+1);
        $path  =  env('APP_URL').'/admin/qr_code/'.$id; 
        } else if($qrcode_url =='qr_code'){
           $path  =  url()->previous(); 
          } else{
           $path = '';
          }            
      $interviewpanel = $request->service;    
      return view('auth.login',compact('interviewpanel','path'));
    }


    /**
    * @developer       :   Akshay
    * @created date    :   05-06-2018 (dd-mm-yyyy)
    * @purpose         :   Authenticate user
    * @params          :   email, password
    * @return          :   response as []
    */
    public function login(Request $request){
 
      $data = $request->only('email', 'password', 'remember_me');
      $remember = isset($data['remember_me']) && $data['remember_me'] == 1 ? 1 : 0;
      $rules = [
        'email' => 'required|email',
        'password' => 'required'
      ];
      $validator = Validator::make($data, $rules);

      if($validator->fails()){

        return back()->withErrors($validator->errors())
                                 ->withInput($request->all());
      }

      $check_user = User::where(['email' => $data['email'], 'status' => true])->first();
      if($check_user == null){
          return back()->withErrors(['Flash' => 'Your account is not created on TalentOne portal, kindly contact HR for the same'])
              ->withInput($request->all());
      }

      $authenticated = Auth::attempt($data, $remember);
        
        $user = User::where(['email'=>$request->email, 'password'=>$request->password,'interviewPanelStatus'=>1])->first();
        if(!empty($request->inv_path)){
          return redirect($request->inv_path);
        }
        if(empty($user)){
          return back()->withErrors(['Flash' => 'Invalid username or password'])
          ->withInput($request->all());                 
        }       
      if(!$authenticated){
        return back()->withErrors(['Flash' => 'Invalid username or password'])
                     ->withInput($request->all());
      }
      if (Auth::user()->is_deleted == 1) {
        Auth::logout();
        return back()->withErrors(['Flash' => 'This account is temporarly disabled.'])->withInput($request->all());
      }
      return redirect($this->pathAfterAuthentication());
    }


    /**
    * @developer       :   Akshay
    * @created date    :   05-06-2018 (dd-mm-yyyy)
    * @purpose         :   Log the user out of the application
    * @params          :
    * @return          :   response as []
    */
    public function logout(Request $request)
    {
      if(!empty(Auth::user())){
         User::where('id',Auth::user()->id)->update(['other_services'=>'logout']);
      }       
        Auth::logout();       
        return redirect('/');
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }


}
