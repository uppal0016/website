<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;
use App\DsrComment;
use App\Notification;
use App\Traits\Sanitize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    use Sanitize;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        //
    }


    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   14-08-2018 (dd-mm-yyyy)
    * @modified date   :   16-08-2018 (dd-mm-yyyy)
    * @purpose         :   to save comment
    * @params          :   
    * @return          :   response as []
    */
    public function store(Request $request){
      $data = $request->only('dsr_id', 'comment');
      $auth = Auth::user();
      $rules = DsrComment::saveVdRules();
      $messages = DsrComment::saveVdMessages();

      $validator = Validator::make($data, $rules, $messages);
      if($validator->fails()){

        $this->response['message'] = $validator->errors()->first();
        return response()->json($this->response, 400);
      }

      $data['comment'] = $this->sanitize($data['comment']);
      $data['dsr_id'] = Crypt::decrypt($data['dsr_id']);
      $data['user_id'] = $auth->id;

      $created = DsrComment::create($data);
      if(!$created){
        $this->response['message'] = "Failed to add comment, please try again.";
        return response()->json($this->response, 400);
      }

      /*-- Add notification --*/
      Notification::create([
        "user_id" => $auth->id,
        "type_id" => 2,
        "activity_id" => $created->dsr_id,
        "message" => $auth->fullname.' '.'has commented on a dsr'
      ]);

      /*-- Add notification --*/
      $count = DsrComment::where('dsr_id', $data['dsr_id'])->count();
      $created['user'] = $created->user;

      $this->response['data'] = [
        "data" => [$created],
        "count" => $count
      ];
        // $usr = DsrComment::where('dsr_id', $data['dsr_id'])->where('user_id','!=',$auth->id)->first();
        // $user['fromUser'] = User::whereId($auth->id)->where('is_deleted',0)->first();
        // $user['toUser'] = User::where('id',$usr->user_id)->where('is_deleted',0)->first();
        // $user['comments'] = DsrComment::where('dsr_id', $data['dsr_id'])->with('user')->latest()->limit(5)->get();
        // Mail::to($user['toUser']->email)->send(new CommentMail($user));

        $this->response['success'] = true;
      return response()->json($this->response, 200);
    }


    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   16-08-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   to fetch dsr's comments
    * @params          :   
    * @return          :   response as []
    */
    public function show(Request $request, $id){
      $id = Crypt::decrypt($id);
      $comments = DsrComment::where('dsr_id', $id)->with('user')->orderBy('created_at','ASC')->get();
      $this->response['data'] = $comments;
      $this->response['success'] = true;
      return response()->json($this->response, 200);
    }


}
