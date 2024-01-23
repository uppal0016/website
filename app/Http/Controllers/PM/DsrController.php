<?php

namespace App\Http\Controllers\PM;

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

class DsrController extends Controller
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
        $authId = Auth::id();
        if ($request->isMethod('post')) {
            $keyword = $request->get('search');
            $dsrUsers = User::where('is_deleted',0)->whereIn('role_id', [4])->with('dsr.read')->where('first_name', 'LIKE', "%$keyword%")
                ->orWhere('last_name', 'LIKE', "%$keyword%")->where('is_deleted',0)->latest()->paginate(10);
            $view = 'dsrs.search';
        } else {
            $dsrUsers = User::where('is_deleted',0)->whereIn('role_id', [4])->with('dsr.read')->latest()->paginate(10);
            $view = 'dsrs.home';
        }

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

    public function showdsr(Request $request , $id){
      $enId = $id;
        $id = Crypt::decrypt($id);
        $auth = Auth::user();
        $authId = $auth->id; 
        $idToHighlight = $request->get('dsrId');
        $redirect = $request->get('redirect');
        $dsrs = Dsr::where('user_id', $id)->where(function($query) use($authId) {
            return $query->whereRaw("FIND_IN_SET('". $authId ."', to_ids)")
                         ->orWhereRaw("FIND_IN_SET('". $authId ."', cc_ids)");
        })->with([
            'details.project', 
            'user', 
            'read' =>function($q) use ($authId){
              $q->where('user_id', $authId);
            }
        ])->orderBy('created_at', 'desc')->paginate(10);
        if(!$redirect || !$idToHighlight){
          return view('dsrs.index',compact('dsrs','enId'));
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
        if(!$this->result){
          return view('dsrs.index',compact('dsrs','enId'));
        }
        $row_no = $this->result[0]->row_no;
        $pageNo = ceil($row_no/10);
        /*--- Page number logic ---*/

        $query = [
          'page' => $pageNo,
          'id' => $enId,
          'dsr_id' => Crypt::encrypt($idToHighlight)
        ];
        return redirect()->route('common.pm.user_dsrs', $query);
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

        $search = $request->get('search');
        $id = $request->get('id');
        $auth = Auth::user();

        if(!$id){

            $this->response['message'] = 'User not found';
            return response()->json($this->response, 400);
        }

        $search = $this->sanitize($search);
        $id = Crypt::decrypt($id);

        $dsrs = Dsr::select('dsrs.*')
                    ->leftJoin('dsr_details as dd', 'dd.dsr_id', '=', 'dsrs.id')
                    ->leftJoin('projects as p', 'dd.project_id', '=', 'p.id')
                    ->where(function($query) use($auth) {
                        return $query->whereRaw("FIND_IN_SET('". $auth->id ."', dsrs.to_ids)")
                                     ->orWhereRaw("FIND_IN_SET('". $auth->id ."', dsrs.cc_ids)");
                    })
                    ->where('dsrs.user_id', $id);

        if($search){

            $dsrs = $dsrs->where(function($q) use ($search){

                $q->orWhere('p.name', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.task', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.description', 'LIKE', '%'.$search.'%')
                  ->orWhere('dd.total_hours', 'LIKE', '%'.$search.'%');
            });   
        }

        $dsrs = $dsrs->with([
          'details.project', 
          'user', 
          'read' =>function($q) use ($auth){
            $q->where('user_id', $auth->id);
          }
        ])->groupBy('dsrs.id')->orderBy('dsrs.created_at', 'desc')->get();
        
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
        
        $this->response['data'] = $dsrs;
        $this->response['success'] = true;
        $this->response['status'] = 200;

        return response()->json($this->response, 200);
               
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

        $date = isset($query['date']) ? Carbon::parse($this->sanitize($query['date']))->toDateString() : Carbon::yesterday()->toDateString();
      }catch(\Exception $e){
        $date = Carbon::yesterday()->toDateString();
      }

      $sumryList = User::where([
        'role_id'    => 4,
        'is_deleted' => 0
      ]);

      if($search){

        $sumryList = $sumryList->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$search."%"]);
      }

      $sumryList = $sumryList->with([
        'dsr' => (function ($query) use($auth, $date) {
          $query->whereDate('created_at', '=', $date)
                ->with('details');
        })                        
      ])->orderBy('created_at', 'desc')->paginate(10);

      return view('summary.index', compact('sumryList'));
    } 

    public function autocomplete(Request $request){

      $search = $request->get('term');
      $results = array();
      $error = "not ";

      if(!$search) return response()->json($results);
      
      $queries = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search."%"])
                    ->where([
                      'is_deleted' => 0,
                      'role_id' => 4
                    ])
                    ->take(5)->get();

      foreach ($queries as $query)
      {
        $results[] = [ 'id' => $query->id, 'value' => $query->first_name.' '.$query->last_name];
      
      }
      return response()->json($results);
    } 
    

}
