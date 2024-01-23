<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Team;
use \Crypt;
use Illuminate\Support\Facades\Validator;
class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $project_managers = User::whereNotIn('role_id', [1,2])->where(['status' => true, 'is_deleted' => false])->orderBy('first_name')->get();
      $employees = User::whereNotIn('role_id', [1,2])->where(['status' => true, 'is_deleted' => false])->orderBy('first_name')->get();
      $teams = Team::paginate(10);
        return view('admin.team.index',compact('project_managers', 'employees','teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $project_managers = User::whereNotIn('role_id', [1,2])->where(['status' => true, 'is_deleted' => false])->orderBy('first_name')->get();
      $employees = User::whereNotIn('role_id', [1,2])->where(['status' => true, 'is_deleted' => false])->orderBy('first_name')->get();
        return view('admin.team.create',compact('project_managers', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
     
           $data = $request->all();
            $validator = Validator::make($data, Team::saveTeam());
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }
            $teamusers = Team::where('team_lead_id',$data['team_lead'])->first();
            if($teamusers){          
              $users = User::where('id',$teamusers->team_lead_id)->first();           
              return redirect('admin/team/create')->with('error', ''.$users->first_name.' '.$users->last_name.' Already Added.');
            }
            
            $team = ['team_lead_id'=>$data['team_lead'],
                      'employee_id'=>!empty($data['employee']) ? implode(",", $data['employee']) : '',
                      'leave_approve'=>!empty($data['leave_approve'])? $data['leave_approve']:0,
                      'dsr_approve'=>!empty($data['dsr_approve'])? $data['dsr_approve']:0,
                      'attendance_approve'=>!empty($data['attendance_approve'])? $data['attendance_approve']:0
                ]; 

            $Teams = Team::create($team);            
            if($Teams){
             return redirect('admin/team')->with('flash_message', 'Added successfully')->with('open_dsr','');
            }
        }catch(\Exception $e){
          return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request)

    {

           
  try{
      $id = Crypt::decrypt($request->id);
   
      $data = $request->all();

      $team = Team::findOrFail($id);
      $team->team_lead_id=$data['team_lead'];
      if(!empty($request->employee)){
      $team->employee_id= implode(",", $request->employee);
      }      
      $team->leave_approve= !empty($data['leave_approve'])? 1:0;
      $team->dsr_approve= !empty($data['dsr_approve'])? 1:0;
      $team->attendance_approve= !empty($data['attendance_approve'])? 1:0;
      $team->update();
      $teams = Team::where('id',$id)->first();

      $res = str_replace( array( 
     ',' ), ' ',  $teams->employee_id);
      $employeeid =  explode(" ", $res); 
        $data = [];
         $user = User::whereIn('id',$employeeid)->get(); 
         if(count($employeeid)>15){
          $Ename = 'All';
       }else{
         foreach($user as $key=>$value){
        $data[] = $value->first_name.' '.$value->last_name.'</br>';
       }
        $Ename = implode($data);
       } 
   
    
     if($team){
     return response()->json(['employee'=> $Ename,'id'=>$id,
        'status' => 200,                       
      ]);
     }
    }catch(\Exception $e){
        return response()->json([
                'status' => 500,                       
              ]);
        }
   

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
