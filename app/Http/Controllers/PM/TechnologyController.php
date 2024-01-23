<?php

namespace App\Http\Controllers\PM;
use Crypt;
use App\Technology;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TechnologyController extends Controller
{
    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   list technologies
     * @params          :
     * @return_type     :   view
     */
    public function index(Request $request)
    {
        try{
            if($request->isMethod('post')){
                $keyword = $request->get('search');
                $technologies = Technology::where('name','LIKE',"%$keyword%")->latest()->paginate(10);
                $view = 'pm.technologies.search';
            }else{
                $technologies = Technology::latest()->paginate(10);
                $view = 'pm.technologies.index';
            }
            return view($view,compact('technologies'));
        }catch(\Exception $e){
            return back()->with('flash_message','There is something wrong');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   add technology
     * @params          :
     * @return_type     :   view
     */
    public function create()
    {
        try{
            return view('pm.technologies.add');
        }catch(\Exception $e){
            return back()->with('flash_message','There is something wrong');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   store technology
     * @params          :
     * @return_type     :   redirect
     */
    public function store(Request $request)
    {
        try{
            $data = $request->only('name', 'status');
            $message = ['required' => 'The technology name field is required'];
            $validator = Validator::make($data, [
                'name' => 'required|unique:technologies',
                'status' => 'required'
            ],$message);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }

            $tech = Technology::create($data);
            if($tech){
                return redirect('/pm/technologies')->with('flash_message', 'Technology has been added successfully.');
            }else{
                return redirect('/pm/technologies')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/technologies')->with('flash_message', 'There is something wrong.');
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
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   edit technology
     * @params          :
     * @return_type     :   view
     */
    public function edit($id)
    {
        try{
            $id = Crypt::decrypt($id);
            $technologies = Technology::whereId($id)->firstOrFail();
            if($technologies){
                return view('pm.technologies.edit',compact('technologies'));
            }else{
                return redirect('/pm/technologies')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/technologies')->with('flash_message', 'There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   update technology
     * @params          :
     * @return_type     :   redirect
     */
    public function update(Request $request, $id)
    {
        try{
            $id = Crypt::decrypt($id);
            $data = $request->only('name');
            $message = ['required' => 'The technology name field is required'];
            $validator = Validator::make($data, ['name' => 'required|unique:technologies,name,'.$id],$message);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }

            $tech = Technology::whereId($id)->update(['name'=>$data['name']]);
            if($tech){
                return redirect('/pm/technologies')->with('flash_message', 'Technology has been updated successfully.');
            }else{
                return redirect('/pm/technologies')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/technologies')->with('flash_message', 'There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   delete technology
     * @params          :
     * @return_type     :   redirect
     */
    public function destroy($id)
    {
        try{
            $id = Crypt::decrypt($id);
            $tech = Technology::findOrFail($id);
            $del = $tech->delete();
            if($del){
                return back()->with('flash_message', 'Technology deleted successfully.');
            }else{
                return back()->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return back()->with('flash_message', 'There is something wrong. Please try again.');
        }
    }


    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   status change technology
     * @params          :
     * @return_type     :   redirect
     */
    public function status($id){
        try{
            $id = Crypt::decrypt($id);
            $tech = Technology::findOrFail($id);
            if(!empty($tech->status)){
                $status='0';
            }else{
                $status='1';
            }
            $tech->status=$status;
            $tech->update();
            if($tech){
                return redirect('/pm/technologies')->with('flash_message', $tech->name.' status has been changed successfully.');
            }else{
                return redirect('/pm/technologies')->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return redirect('/pm/technologies')->with('flash_message', 'There is something wrong. Please try again.');
        }

    }
}
