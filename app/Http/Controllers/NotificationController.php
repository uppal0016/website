<?php

namespace App\Http\Controllers;

use Crypt;
use Auth;
use App\Dsr;
use App\DsrComment;
use App\Traits\Sanitize;
use App\Traits\CommonMethods;
use App\Notification;
use App\NotificationRead;
use App\NotificationType;
use Illuminate\Http\Request;  

class NotificationController extends Controller
{

    use CommonMethods;
    
    use Sanitize;

    public $response;

    public function __construct(){
         $this->middleware('auth');

        $this->response = parent::getResponse();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   23-08-2018 (dd-mm-yyyy)
    * @modified date   :   16-08-2018 (dd-mm-yyyy)
    * @purpose         :   to send notification
    * @params          :   
    * @return          :   response as []
    */
    
    public function index()
    {
        $authId = Auth::user()->id;

        $notification = Notification::where('user_id', '!=', $authId)
        ->whereHas('dsr', function($q) use ($authId){
            $q->where("user_id", $authId)
              ->orWhereRaw("FIND_IN_SET('". $authId ."', to_ids)")
              ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
        })
        ->with([
            'notificationread' => function($q) use ($authId){
                $q->where([
                    'user_id' => $authId,
                    'is_read' => 1
                ]);
            } 
        ])->orderBy('created_at', 'desc')->paginate(10);

        return view('notification.notification',compact('notification'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   23-08-2018 (dd-mm-yyyy)
    * @modified date   :   16-08-2018 (dd-mm-yyyy)
    * @purpose         :   to read the notification
    * @params          :   
    * @return          :   response as []
    */
    public function show($id, $markNotificationRead = 1)
    {
        $auth = Auth::user();
        $id = Crypt::decrypt($id);
        $notifRead = Notification::where('id', $id)->with('dsr')->first();

        if($markNotificationRead) {$this->markNotificationRead($id, $auth->id);}

        $query = [
            'dsrId' => Crypt::encrypt($notifRead->activity_id),
            'redirect' => 1,
            'id' => Crypt::encrypt($notifRead['dsr']['user_id'])
        ];

        if($auth->role_id == 4){

            return redirect()->route('common.emp.dsrdetail', $query);
        }

        if($auth->role_id == 2){
            
            return redirect()->route('common.management.user_dsrs', $query);
        }

        if($auth->role_id == 3){
            
            return redirect()->route('common.pm.user_dsrs', $query);
        }


          
        // return response()->json($notifRead, 200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
}
