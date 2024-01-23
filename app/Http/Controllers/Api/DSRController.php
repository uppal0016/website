<?php

namespace App\Http\Controllers\Api;

use Crypt;
use App\User;
use App\Dsr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;

class DSRController extends Controller
{

    /**
    * @developer       :   
    * @modified by     :
    * @created date    :   (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   Display employee's Dsrs
    * @params          :   
    * @return          :   response as []
    */
    public function index(){

    }


    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   Display user details
    * @params          :   id
    * @return          :   response as []
    */
    public function show($id = null){

    	// if(!$id){
     //  	    throw new \Exception('Not Found', 404);
     //    }
        die('aa');

    	$id = Crypt::decrypt($id);
    	$user = User::where('id', $id)->get()->toArray();

  		return response()->json($user, 200);
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
    // public function store(Request $request){

  		// $data = $request->only('first_name', 'last_name', 'email', 'password', 'role_id');

    //   $rules = [
    //     'first_name' => 'required',
    //     'last_name' => 'required',
    //     'email' => 'required|email|unique:users',
    //     'password' => 'required|min:6|Regex:/\A(?!.*[:;]-\))[ -~]{3,20}\z/',
    //     'role_id' => 'required|numeric'
    //   ];

  		// $validator = Validator::make($data, $rules);

    //   if($validator->fails()) {

    //     return response()->json(['validation error'], 500);
    //   }

    //   $data['password'] = bcrypt('securedata');

    // 	$resp = User::create($data);
    // 	if(!$resp){

    //     return response()->json(['failed_to_create_new_user'], 500);
    //   }

  		// return response()->json(['user_created']);
    // }


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

    //   	return response()->json(['failed'], 500);
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

}
