<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Crypt;
use Carbon\Carbon;
use App\Dsr;
use App\DsrDetail;
use App\DsrComment;
use App\User;
use App\Traits\Sanitize;
use Illuminate\Http\Request;
use App\Traits\CommonMethods;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendDsrRejectedJob;
class DSRController extends Controller
{
    use CommonMethods;
    
    public $response;

    public function __construct(){  
      $this->response = parent::getResponse();
    }

    use Sanitize;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

       if(Auth::user()->role_id == 4 || Auth::user()->role_id == 5){
       return redirect('/dashboard');
       }
        $authId = Auth::id();

        if((Auth::user()->role_id == 1) || (Auth::user()->role_id == 2) || (Auth::user()->role_id == 3)){
          $dsrUsers =  User::where('is_deleted',0)->whereNotIn('role_id', [1,2])
                            ->withCount('dsr');
        } else {
          $dsrUsers =  User::where('is_deleted',0)->whereNotIn('role_id', [1,2])
                            ->withCount(['dsr' =>function($q) use ($authId){
                              $q->whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                              ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
                            }]);
        }
        
        if ($request->isMethod('post')) {
            $keyword = $request->search;
            $dsrUsers = $dsrUsers->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"]);
            $view = 'admin.dsrs.search';
        } else {
            $view = 'admin.dsrs.home';
        }

        $dsrUsers = $dsrUsers->latest()->paginate(10);      
        $dsrUsers->setPath('dsr');
        return view($view, compact('dsrUsers','authId'));
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
        //
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){

      
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   10-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   Display user where role is Employee
    * @params          :   Show Employees List in sidebar
    * @return          :   response as []
    */

    public function userview(){

        $auth = Auth::user();

        $users = User::where('role_id','=',4)->where('id','!=',$auth->id)->get();
        return view('common.sidebar.sidebar_pm',compact('users'));
    } 

    /**
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   10-07-2018 (dd-mm-yyyy)
    * @modified date   :   03-08-2018 (dd-mm-yyyy)
    * @purpose         :   View user dsr list
    * @params          :   Employee Name and Time
    * @return          :   view 
    */

    public function showdsr(Request $request , $id)
    {
        $enId = $id;
        $id = Crypt::decrypt($id);
        $authId = Auth::user()->id; 
        $idToHighlight = $request->get('dsrId');
        $redirect = $request->get('redirect');
        $dsrs = Dsr::where('user_id', $id)->where('to_ids','!=','');
        if (in_array(Auth::user()->role_id, [4,5])){
          $dsrs = $dsrs->where(function($query) use($authId) {
              return $query->whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                         ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
          });
        }
        
        $dsrs = $dsrs->with([
            'details.project', 
            'user', 
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
        ])->orderBy('created_at', 'desc')->paginate(10)->setPath(route('admin.user_dsrs', ['id' => $enId])); 
        $dsrs->lastid = Dsr::where('user_id', $id)->where('to_ids','!=','')->latest('id')->first();        
        $lastid =  !empty($dsrs->lastid->id) ? $dsrs->lastid->id:'';     
        if(!$redirect || !$idToHighlight){
          return view('admin.dsrs.index',compact('dsrs','enId','lastid'));
        }
        // $totalCount = $totalCount->count();
        /*--- Page number logic ---*/
        $idToHighlight = Crypt::decrypt($idToHighlight);
        DB::transaction(function() use ($authId, $idToHighlight, $id){
          DB::select('SET @row_number = 0;');
          $this->result = DB::select("SELECT row_no, id FROM (
              SELECT (@row_number:=@row_number + 1) as row_no, id FROM dsrs 
              WHERE 
                user_id = ".$id."
              AND
                (
                  FIND_IN_SET('".$authId."',to_ids) 
                OR 
                  FIND_IN_SET('".$authId."',cc_ids)
                ) ORDER BY id DESC 
            ) as t WHERE t.id = ".$idToHighlight.";");
        });
        // $dsrs->setPath('user_dsrs');
        $dsrs->setPath(route('admin.user_dsrs', ['id' => $enId]));
        if(!$this->result){
          return view('admin.dsrs.index',compact('dsrs','enId'));
        }
        $row_no = $this->result[0]->row_no;
        $pageNo = ceil($row_no/10);
        /*--- Page number logic ---*/

        $query = [
          'page' => $pageNo,
          'id' => $enId,
          'dsr_id' => Crypt::encrypt($idToHighlight)
        ];
        return redirect()->route('admin.user_dsrs', $query);
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
 
    /**
    * @developer       :   Ajmer
    * @modified by     :   Akshay
    * @created date    :   18-07-2018 (dd-mm-yyyy)
    * @modified date   :   01-08-2018
    * @purpose         :   get dsr details
    * @params          :   id 
    * @return          :   response as []
    */
    public function getDsrDetails($id){

      $id = Crypt::decrypt($id);
      $auth = Auth::user();
      
      $dsr = Dsr::where('id', $id)->with(['details' => function($q) use ($id){
        $q->with(['project']);
      }, 'files'])->first();


      if($dsr){
        $dsr = $dsr->toArray();
      } 


      if($dsr && $dsr['details']) {

        $dsrDetails = [];
        foreach($dsr['details'] as $k => $detail) {

            $te = explode('.', number_format( (float) $detail['total_hours'], 2, '.', ''));
            $detail['hours'] = $te[0];
            $detail['minutes'] = isset($te[1]) ? $te[1] : "0";

            // print_r($detail->toArray());
            // die('ok');
            if(!$dsrDetails){
           
              $dsrDetails[] = [
                "project"    => $detail['project'] ? $detail['project'] : '',
                "project_id" => $detail['project_id'],
                "details"    => [$detail]
              ];
              continue;
            }

            $key = array_search($detail['project_id'], array_column($dsrDetails,'project_id'));
            if($key === 0 || $key != null){

              $dsrDetails[$key]['details'][] = $detail;
              continue; 
            }

            $dsrDetails[] = [
              "project"    => $detail['project'] ? $detail['project'] : '',
              "project_id" => $detail['project_id'],
              "details"    => [$detail]
            ];
        }
        $dsr['details'] = $dsrDetails;
      }

      $dsr = Dsr::toAndCCUsers($dsr, 1);

      $this->markDsrRead($id, $auth->id);
      
      return response()->json($dsr, 200);
    }


    /**
    * @developer       :   Akshay
    * @modified by     :   
    * @created date    :   25-07-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   to get dsrs by user id and search string
    * @params          :   id and search as query params
    * @return          :   response as []
    */
    public function getDsrs(Request $request){

        $search = $request('search');
        $id = $request('id');
        $auth = Auth::user();
        $enId = Crypt::encrypt($auth->id);
        if(!$id){
            $this->response['message'] = 'User not found';
            return response()->json($this->response, 400);
        }
        $search = $this->sanitize($search);
        $id = Crypt::decrypt($id);

        $dsrs = Dsr::select('dsrs.*')
                    ->leftJoin('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
                    ->leftJoin('projects as p', 'dd.project_id', '=', 'p.id');
        if (in_array(Auth::user()->role_id, [3,4,5])){
          $dsrs =  $dsrs->where(function($query) use($auth) {
            return $query->whereRaw("FIND_IN_SET('". $auth->id ."', dsrs.to_ids)")
                          ->orWhereRaw("FIND_IN_SET('". $auth->id ."', dsrs.cc_ids)");
          });
        }

        $dsrs = $dsrs->where('dsrs.user_id', $id);
        if($search){
            $dsrs = $dsrs->where(function($q) use ($search){
                $q->orWhere('p.name', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.task', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.description', 'LIKE', '%'.$search.'%');
            });   
        }

        $dsrs = $dsrs->with([
          'details.project', 
          'user', 
          'read' =>function($q) use ($auth){
            $q->where('user_id', $auth->id);
          }
        ])->orderBy('dsrs.created_at', 'desc')->paginate(10);

        
        foreach ($dsrs as $dsr) {
            
            $project_name = 'N-A';
            $description = '';
            $highlight = ($dsr['read']->count() && $dsr['read'][0]['is_read'] == 1) ? 0:1;

            if($dsr['details']->count()){
            
                if($dsr['details'][0]['project']){

                  $project_name = $dsr['details'][0]['project']['name'];
                }
                $description = substr($dsr['details'][0]['description'], 0, 20);
            }

            $dsr->user_full_name = $dsr->user ? $dsr->user->fullname : 'N-A';
            $dsr->project_name = $project_name;
            $dsr->description = $description;
            $dsr->highlight = $highlight;
            $dsr->en_id = Crypt::encrypt($dsr->id);
        }
        
        return view('admin.dsrs.search_list', compact('dsrs','enId'))->render();

        // $this->response['data'] = $dsrs;
        // $this->response['success'] = true;
        // $this->response['status'] = 200;

        // return response()->json($this->response, 200);
               
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   29-08-2018 (dd-mm-yyyy)
    * @modified date   :   
    * @purpose         :   to get users yesterday summary list  and hours Spent
    * @params          :   
    * @return          :   response as []
    */ 

    public function summarylist(Request $request){
      $auth = Auth::user();
      $query = $request->all();

      $search = isset($query['search']) ? $this->sanitize($query['search']) : '';
      try{
        $date = isset($query['date']) ? Carbon::parse($this->sanitize($query['date']))->toDateString() : Carbon::now()->toDateString();
      }catch(\Exception $e){
        $date = Carbon::now()->toDateString();
      }

      $sumryList = User::whereNotIn('role_id',['1','2'])
                        ->where([
                          'is_deleted' => 0
                        ]);


      if($search)
      {
        $sumryList = $sumryList->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$search."%"]);
      }


      $sumryList = $sumryList->with([
        'dsr' => (function ($query) use($auth, $date) {
          $query->where(DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d")') , '=', $date)
                ->with('details');
        })                        
      ])->orderBy('created_at', 'desc')->paginate(10);
       $sumryList->setPath('summary');  
      return view('summary.index', compact('sumryList','date'));
    } 

    public function autocomplete(Request $request){

      $search = $request('term');
      $results = array();
      $error = "not ";

      if(!$search) return response()->json($results);
      
      $queries = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$search."%"])
                      ->whereNotIn('role_id',['1','2'])
                        ->where([
                          'is_deleted' => 0
                        ])
                      ->take(5)->get();

      foreach ($queries as $query)
      {
        $results[] = [ 'id' => $query->id, 'value' => $query->first_name.' '.$query->last_name];
      
      }
      return response()->json($results);
    } 

    Public function DsrStatusUpdate(Request $request){  
    try{       
      $dsrid = Crypt::decrypt($request->dsrid);  
      $dsrs = Dsr::findOrFail($dsrid);  
      if($request->status == 0){
      $dsr = Dsr::where('id', $dsrid)->with(['user'])->first();
      $details['Name'] =$dsr->user->first_name;     
      $details['email'] =$dsr->user->email;
      $details['date'] = date('d-m-Y', strtotime($dsr['created_at']));
      $details['view']= 'mails.send-dsr-rejected';
      $details['dsr_rejection_reason'] = $request->dsr_rejection_reason;                                        
      dispatch(new SendDsrRejectedJob($details));        
      $dsrs->dsr_rejection_reason = $request->dsr_rejection_reason;     
      }
      $dsrs->status=$request->status;
      $dsrs->update();
      if($request->status == 1){
      $staus = '<div class="realtimeststus_'.$dsrid.'" style="color:green">Approved </div>';
      }else{
      $staus = '<div class="realtimeststus_'.$dsrid.'" style="color:red">Rejected </div>';
      }     
      return response()->json(["dsrid"=>$dsrid,"status"=>$staus,'statusvalue'=>$request->status,"message"=>"updated successfully"]);
      }
     catch(\Exception $e){
      
      }
  }
}
