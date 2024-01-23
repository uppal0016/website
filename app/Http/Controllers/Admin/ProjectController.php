<?php

namespace App\Http\Controllers\Admin;

use App\ProjectAssigned;
use App\Traits\Sanitize;
use Auth;
use App\Dsr;
use App\DsrDetail;
use App\User;
use App\Project;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Jobs\AssignToAllResources;
use App\Jobs\UnassignToAllResources;
use Log;

class ProjectController extends Controller
{

    use Sanitize;

    /**
    * @developer       :   Ajmer
    * @modified by     :   Papinder
    * @created date    :   06-07-2018 (dd-mm-yyyy)
    * @modified date   :   07-08-2019
    * @purpose         :   Display projects list
    * @params          :   project list,edit,delete and status
    * @return_type          :   response as []
    */
    public function index(Request $request){
        if($request->isMethod('post')){
            $keyword = $request->get('search');
            if(Auth::user()->role_id == 1) {
                $projects = Project::select('projects.*', DB::raw("COUNT(project_assigned.id) project_count") )->where('name','like',"%$keyword%")->where('is_deleted', 0)->leftJoin('project_assigned', 'project_assigned.project_id', '=', 'projects.id')->groupBy("projects.id")->orderBy('id', 'desc')->paginate(10);
                
            } else {
                $projects = Project::where('name','like',"%$keyword%")->where('is_deleted', 0)->where('project_type', '=', 0)->orderBy('id', 'desc')->paginate(10);
            }
            $view = 'admin.projects.search';
        }else{
            if(Auth::user()->role_id == 1) {
                $projects = Project::select('projects.*', DB::raw("COUNT(project_assigned.id) project_count") )->where('is_deleted', 0)->leftJoin('project_assigned', 'project_assigned.project_id', '=', 'projects.id')->groupBy("projects.id")->orderBy('id', 'desc')->paginate(10);
                // $projects = Project::with('project_assigned:project_id')->where('is_deleted', 0)->orderBy('id', 'desc')->paginate(10);
            } else {
                $projects = Project::where('is_deleted', 0)->where('project_type', '=', 0)->orderBy('id', 'desc')->paginate(10);
            }
            // print_r($projects);die;
            $view = 'admin.projects.index';
        }
        $projects->setPath('projects');
        $userIds = User::select('id')->where('role_id', '!=' ,1)->where('status', true)->where('is_deleted', false)->count();
        return view($view, compact('projects', 'userIds'));
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   23-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   display add project page
    * @params          :   
    * @return          :   response as []
    */
    public function view(){
      $project_managers = User::where('role_id',User::ROLE_PROJECT_MANAGER)->where(['status' => true, 'is_deleted' => false])->get();
      $employees = User::where('role_id', User::ROLE_EMPLOYEE)->where(['status' => true, 'is_deleted' => false])->get();
      return view('admin.projects.add', compact('project_managers', 'employees'));
    }

     /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   24-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   Using for activate and deactivate project status
    * @params          :   id
    * @return          :   response as []
    */

    public function status($id){
        $id = Crypt::decrypt($id);
        $project = Project::findOrFail($id);
        if(!empty($project->status)){
            $status='0';
        }else{
            $status='1';
        }
        $project->status=$status;
        $project->update();
        return redirect('/admin/projects')->with('flash_message', 'Project status changed successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   Papinder
    * @created date    :   23-07-2018 (dd-mm-yyyy)
    * @modified date   :   09-08-2019(dd-mm-yyyy)
    * @purpose         :   create new project
    * @params          :   name
    * @return          :   response as []
    */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|unique:projects,name,NULL,id,is_deleted,0|max:255',
            'start_date' => 'required',
            // 'end_date' => 'after_or_equal:start_date',
            //'client_name' => 'string',
            //'address' => 'string',
            //'physical_address' => 'string',
            'project_manager' => 'required|integer',
            'team_lead' => 'required|integer',
            'hours_approved_or_spent' => 'required|integer',
            //'project_url' => 'string',
            //'technology' => 'string',
            //'dev_server_url' => 'string',
            //'qa_server_url' => 'string',
            //'git_or_svn' => 'string',
            //'project_document_url' => 'string',
            //'project_management_tool' => 'string',
            'current_status' => 'required'
        ]);

        try{
            $data = $request->all();
            $data['status'] = $request->current_status;
            unset($data['_token']);
            $project = Project::create($data);
            $project = ProjectAssigned::create(['project_id'=>$project->id, 'user_id'=>$project->team_lead, 'created_at'=>Carbon::now(),'updated_at'=>Carbon::now(),]);
            if($project){
                return redirect('/admin/projects')->with('flash_message', 'Project created successfully!');
            }else{
                return redirect('/admin/projects')->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return redirect('/admin/projects')->with('flash_message', $e);
        }

      }

    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   23-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   update project
    * @params          :   name
    * @return          :   response as []
    */      

    public function edit($id){

        $id = Crypt::decrypt($id);  

        $project = Project::findOrFail($id);
        $project_managers = User::where('role_id',User::ROLE_PROJECT_MANAGER)->where(['status' => true, 'is_deleted' => false])->get();
        $employees = User::where('role_id', User::ROLE_EMPLOYEE)->where(['status' => true, 'is_deleted' => false])->get();

        return view('admin.projects.edit', compact('project','project_managers','employees'));
    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   23-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   update project 
    * @params          :   name
    * @return          :   response as []
    */
    public function update(Request $request, $id){
        $id = Crypt::decrypt($id);

        $validator = $request->validate([
            'name' => 'required|unique:projects,name,' . $id.',id,is_deleted,0|max:255',
            'start_date' => 'required',
            'end_date' => 'nullable|after_or_equal:start_date',
            //'client_name' => 'string',
            //'address' => 'string',
            //'physical_address' => 'string',
            'project_manager' => 'required|integer',
            'team_lead' => 'required|integer',
            'hours_approved_or_spent' => 'required|integer',
            //'project_url' => 'string',
            //'technology' => 'string',
            //'dev_server_url' => 'string',
            //'qa_server_url' => 'string',
            //'git_or_svn' => 'string',
            //'project_document_url' => 'string',
            //'project_management_tool' => 'string',
            'current_status' => 'required'
        ]);
        try{
            $data = $request->all();
            unset($data['_token']);
            $data['status'] = $request->current_status;
            $project = Project::findOrFail($id);
            $project_assigned = ProjectAssigned::where('project_id', $project->id)->first();
            if ($project_assigned) {
                $project_assigned->user_id = $request->team_lead;
                $project_assigned->update();
            }
            $update = $project->update($data);
            if($update){
                return redirect('/admin/projects')->with('flash_message','Project updated successfully!');
            }else{
                return redirect('/admin/projects')->with('flash_message','There is something wrong. Please try again');
            }
        }catch(\Exception $e){
            return redirect('/admin/projects')->with('flash_message',$e);
        }

    }

    /**
    * @developer       :   Ajmer
    * @modified by     :   
    * @created date    :   23-07-2018 (dd-mm-yyyy)
    * @modified date   :   (dd-mm-yyyy)
    * @purpose         :   delete project
    * @params          :   name
    * @return          :   response as []
    */

   	public function destroy($id){

        $id = Crypt::decrypt($id);
        $del = Project::findOrFail($id);

        $del->update([
            'is_deleted' => 1
        ]);

        return back()->with('flash_message', 'Project deleted successfully!');
    }

    public function getAssignedEmployees(Request $request, $en_pid){
        try{
            $pid = Crypt::decrypt($en_pid);
            $query = $request->all();
            $search = isset($query['search']) ? $this->sanitize($query['search']) : '';

            if($search){
                $userAssignedList = User::where(['role_id'=>4,'is_deleted' => 0,'status' => 1])->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$search."%"])
                    ->whereHas('project_assign',function($p) use($pid){
                        $p->where('project_id',$pid);
                    })->with(['project_assign'=>function($p) use($pid){
                        $p->where('project_id',$pid);
                    }])->orderBy('created_at', 'desc')->get();

                $userNotAssignedList = User::where(['role_id'=>4,'is_deleted' => 0,'status' => 1])->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$search."%"])
                    ->whereDoesntHave('project_assign',function($p) use($pid){
                        $p->where('project_id',$pid);
                    })->with(['project_assign'=>function($p) use($pid){
                        $p->where('project_id',$pid);
                    }])->orderBy('created_at', 'desc')->get();
            }else{
                $userAssignedList = User::where(['role_id'=>4,'is_deleted' => 0,'status' => 1])
                    ->whereHas('project_assign',function($p) use($pid){
                        $p->where('project_id',$pid);
                    })->with(['project_assign'=>function($p) use($pid){
                        $p->where('project_id',$pid);
                    }])->orderBy('created_at', 'desc')->get();

                $userNotAssignedList = User::where(['role_id'=>4,'is_deleted' => 0,'status' => 1])
                    ->whereDoesntHave('project_assign',function($p) use($pid){
                        $p->where('project_id',$pid);
                    })->with(['project_assign'=>function($p) use($pid){
                        $p->where('project_id',$pid);
                    }])->orderBy('created_at', 'desc')->get();
            }
            $userList = $userAssignedList->merge($userNotAssignedList)->paginate(10)->setPath(url('/admin/project/get_assigned_employees', ['id' => $en_pid]));
            $project = Project::where('id',$pid)->select('name')->first();
            return view('admin.projects.assign_project', compact('userList','en_pid','project'));
        }catch(\Exception $e){
            return redirect('/admin/projects')->with('flash_message',$e->getMessage());
        }
    }


    public function autocomplete(Request $request){

        $search = $request->get('term');
        $results = array();
        $error = "not ";

        if(!$search) return response()->json($results);

        $queries = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", [$search."%"])
            ->where([
                'is_deleted' => 0,
                'role_id' => 4,
                'status' => 1
            ])
            ->take(5)->get();

        foreach ($queries as $query)
        {
            $results[] = [ 'id' => $query->id, 'value' => $query->first_name.' '.$query->last_name];

        }
        return response()->json($results);
    }

    public function updateAssignedEmployees(Request $request)
    {
        try{
            $userId = $request->user_id;
            $projectId = Crypt::decrypt($request->project_id);
            $msg = '';
            if($userId && $projectId){
                $exist = ProjectAssigned::where('user_id',$userId)->where('project_id',$projectId)->first();
                if($exist){
                    $exist->delete();
                    $msg = 'User removed from this project successfully.';
                    $title = 'Not Assigned';
                }else{
                    ProjectAssigned::create([
                        'project_id'=>$projectId,
                        'user_id'=>$userId,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ]);
                    $msg = 'User added to this project successfully.';
                    $title = 'Assigned';
                }
                return response()->json(['status' => 1,'message'=>$msg,'title'=>$title]);
            }else{
                return response()->json(['status' => 0,'message'=>'Something went wrong.']);
            }
        }catch(\Exception $e){
            return response()->json(['status' => 0,'message' => $e->getMessage()]);
        }
    }

    public function assignToAllEmployees(Request $request, $en_pid)
    {
        try{
            $userIds = User::select('id')->where('role_id', '!=' ,1)->where('status', true)->where('is_deleted', false)->get();
            $projectId = Crypt::decrypt($en_pid);
            $msg = '';
            foreach($userIds as $userId){
                // if($userId && $projectId){
                    $exist = ProjectAssigned::where('user_id',$userId->id)->where('project_id',$projectId)->first();
                    if($exist){
                        // $exist->delete();
                        // $msg = 'Users already added to this project successfully';
                        $title = 'Not Assigned';
                    }else{
                        //print_r($userId);
                        \Log::info('job started');
                        // dispatch(new AssignToAllResources($projectId, $userId->id));
                        AssignToAllResources::dispatch($projectId, $userId->id);
                        $msg = 'All resources assigned to the project successfully.';
                        $title = 'Assigned';
                    }
                    
                // }else{
                //     return response()->json(['status' => 0,'message'=>'Something went wrong.']);
                // }
            }
            return back()->with('flash_message', $msg);
            // return response()->json(['status' => 1,'message'=>$msg,'title'=>$title]);
        }catch(\Exception $e){
            return response()->json(['status' => 0,'message' => $e->getMessage()]);
        }
    }

    public function unAssignToAllEmployees(Request $request, $en_pid)
    {
        try{
            $userIds = User::select('id')->where('role_id', '!=' ,1)->where('status', true)->where('is_deleted', false)->get();
            $projectId = Crypt::decrypt($en_pid);
            $msg = '';
            foreach($userIds as $userId){
                if($userId && $projectId){
                    UnassignToAllResources::dispatch($projectId, $userId->id);
                    $msg = 'All resources unassigned to the project successfully.';
                }else{
                    return response()->json(['status' => 0,'message'=>'Something went wrong.']);
                }
            }
            return back()->with('flash_message', $msg);
            // return response()->json(['status' => 1,'message'=>$msg,'title'=>$title]);
        }catch(\Exception $e){
            return response()->json(['status' => 0,'message' => $e->getMessage()]);
        }
    }
}
