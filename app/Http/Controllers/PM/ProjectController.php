<?php

namespace App\Http\Controllers\PM;

use Auth;
use App\Dsr;
use App\DsrDetail;
use App\User;
use App\Project;
use Crypt;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

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
            $projects = Project::where('name','like',"%$keyword%")->where('is_deleted', '!=', 1)->orderBy('id', 'desc')->paginate(10);
            $view = 'projects.search';
        }else{
            $projects = Project::where('is_deleted', '!=', 1)->orderBy('id', 'desc')->paginate(10);
            $view = 'projects.index';
        }
      return view($view, compact('projects'));
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
      return view('projects.add');
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
        return redirect('/pm/projects')->with('flash_message', 'Project status changed successfully!');
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
    public function store(Request $request){
        try{
            $data = $request->only('name','start_date','end_date', 'status');
            $message = [
                'name.required' => 'Project name field is required',
                'start_date.required' => 'Start date field is required',
                'end_date.required'  => 'End date field is required',
                'status.required'  => 'Status field is required',
            ];
            $validator = Validator::make($data, [
                'name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'status' => 'required'
            ],$message);
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }
            $project = Project::create($data);
            if($project){
                return redirect('/pm/projects')->with('flash_message', 'Project created successfully!');
            }else{
                return redirect('/pm/projects')->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return redirect('/pm/projects')->with('flash_message', $e);
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
            
        return view('projects.edit', compact('project'));
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
        try{
            $id = Crypt::decrypt($id);
            $data = $request->only('name','start_date','end_date');
            $message = ['required' => 'The project name field is required'];
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'start_date' => 'required|date_format:Y-m-d',
                'end_date' => 'required|date_format:Y-m-d',
            ],$message);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
            $project = Project::findOrFail($id);
            $update = $project->update($data);
            if($update){
                return redirect('/pm/projects')->with('flash_message','Project updated successfully!');
            }else{
                return redirect('/pm/projects')->with('flash_message','There is something wrong. Please try again');
            }
        }catch(\Exception $e){
            return redirect('/pm/projects')->with('flash_message',$e);
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

        // $del->delete($id);
        
        return back()->with('flash_message', 'Project deleted successfully!');
    }



}
