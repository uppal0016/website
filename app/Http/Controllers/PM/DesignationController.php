<?php

namespace App\Http\Controllers\PM;

use Crypt;
use App\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
{
    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   list designations
     * @params          :
     * @return_type     :   view
     */
    public function index(Request $request)
    {
        try{
            if($request->isMethod('post')){
                $keyword = $request->get('search');
                $designations = Designation::where('name','LIKE',"%$keyword%")->latest()->paginate(10);
                $view = 'pm.designations.search';
            }else{
                $designations = Designation::latest()->paginate(10);
                $view = 'pm.designations.index';
            }
            return view($view,compact('designations'));
        }catch(\Exception $e){
            return back()->with('flash_message','There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   add Designations
     * @params          :
     * @return_type     :   view
     */
    public function create()
    {
        try{
            return view('pm.designations.add');
        }catch(\Exception $e){
            return back()->with('flash_message','There is something wrong');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   store designations
     * @params          :
     * @return_type     :   redirect
     */
    public function store(Request $request)
    {
        try{
            $data = $request->only('name', 'status');
            $message = ['required' => 'The designation name field is required'];
            $validator = Validator::make($data, [
                'name' => 'required|unique:designations',
                'status' => 'required'
            ],$message);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }

            $desg = Designation::create($data);
            if($desg){
                return redirect('/pm/designations')->with('flash_message', 'Designations has been added successfully.');
            }else{
                return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
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
     * @purpose         :   edit designation
     * @params          :
     * @return_type     :   view
     */
    public function edit($id)
    {
        try{
            $id = Crypt::decrypt($id);
            $designation = Designation::whereId($id)->firstOrFail();
            if($designation){
                return view('pm.designations.edit',compact('designation'));
            }else{
                return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   update designation
     * @params          :
     * @return_type     :   redirect
     */
    public function update(Request $request, $id)
    {
        try{
            $id = Crypt::decrypt($id);
            $data = $request->only('name');
            $message = ['required' => 'The designation name field is required'];
            $validator = Validator::make($data, ['name' => 'required|unique:designations,name,'.$id],$message);

            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }

            $desg = Designation::whereId($id)->update(['name'=>$data['name']]);
            if($desg){
                return redirect('/pm/designations')->with('flash_message', 'Designations has been updated successfully.');
            }else{
                return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @modified by     :
     * @created date    :   07-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   delete designations
     * @params          :
     * @return_type     :   redirect
     */
    public function destroy($id)
    {
        try{
            $id = Crypt::decrypt($id);
            $desg = Designation::findOrFail($id);
            $del = $desg->delete();
            if($del){
                return back()->with('flash_message', 'Designations deleted successfully.');
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
     * @purpose         :   status change designation
     * @params          :
     * @return_type     :   redirect
     */
    public function status($id){
        try{
            $id = Crypt::decrypt($id);
            $desg = Designation::findOrFail($id);
            if(!empty($desg->status)){
                $status='0';
            }else{
                $status='1';
            }
            $desg->status=$status;
            $desg->update();
            if($desg){
                return redirect('/pm/designations')->with('flash_message', $desg->name.' status has been changed successfully.');
            }else{
                return redirect('/pm/designations')->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return redirect('/pm/designations')->with('flash_message', 'There is something wrong. Please try again.');
        }

    }
}
