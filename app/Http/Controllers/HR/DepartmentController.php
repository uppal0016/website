<?php

namespace App\Http\Controllers\HR;

use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   index designations
     * @params          :
     * @return_type     :   redirect
     */
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                $keyword = $request->get('search');
                $department = Department::where('name', 'LIKE', "%$keyword%")->latest()->paginate(10);
                $view = 'hr.department.search';
            } else {
                $department = Department::latest()->paginate(10);
                $view = 'hr.department.index';
            }
            return view($view, compact('department'));
        }catch(\Exception $e){
            return back()->withErrors($e);
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   create designations
     * @params          :
     * @return_type     :   redirect
     */
    public function create()
    {
        return view('hr.department.add');
    }

    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   store designations
     * @params          :
     * @return_type     :   redirect
     */
    public function store(Request $request)
    { 
        try{ 
            $data = $request->only('department_name', 'department_code', 'status');
            $input = [
                'name' => $data['department_name'],
                'code' => $data['department_code'],
                'status' => $data['status']
            ];
            $validator = Validator::make($input, Department::saveDepartmentVd());
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }
            $department = Department::create($input);
            if($department){
                return redirect('hr/department')->with('flash_message', 'Department created successfully!');
            }
        }catch(\Exception $e){
            return back()->withErrors($e);
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   show designations
     * @params          :
     * @return_type     :   redirect
     */
    public function show($id)
    {
        //
    }

    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   edit designations
     * @params          :
     * @return_type     :   redirect
     */
    public function edit($id)
    {
        try{
            $id = Crypt::decrypt($id);
            $department = Department::whereId($id)->firstOrFail();
            if($department){
                return view('hr.department.edit',compact('department'));
            }else{
                return redirect('/hr/department')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/hr/department')->with('flash_message', 'There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   update designations
     * @params          :
     * @return_type     :   redirect
     */
    public function update(Request $request, $id)
    {
        try{
            $id = Crypt::decrypt($id);
            $data = $request->only('name', 'code');
            $validator = Validator::make($data, Department::updateDepartmentVd());
            if($validator->fails()){
                return back()->withErrors($validator)->withInput($request->input());
            }

            $dept = Department::whereId($id)->update($data);
            if($dept){
                return redirect('/pm/department')->with('flash_message', 'Department has been updated successfully.');
            }else{
                return redirect('/pm/department')->with('flash_message', 'There is something wrong.');
            }
        }catch(\Exception $e){
            return redirect('/pm/designations')->with('flash_message', 'There is something wrong.');
        }
    }

    /**
     * @developer       :   Papinder Kumar
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   delete designations
     * @params          :
     * @return_type     :   redirect
     */
    public function destroy($id)
    {
        try{
            $id = Crypt::decrypt($id);
            $dept = Department::findOrFail($id);
            $del = $dept->delete();
            if($del){
                return back()->with('flash_message', 'Department deleted successfully.');
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
     * @created date    :   30-08-2019 (dd-mm-yyyy)
     * @modified date   :   (dd-mm-yyyy)
     * @purpose         :   status change designation
     * @params          :
     * @return_type     :   redirect
     */
    public function status($id){
        try{
            $id = Crypt::decrypt($id);
            $dept = Department::findOrFail($id);
            if(!empty($dept->status)){
                $status='0';
            }else{
                $status='1';
            }
            $dept->status=$status;
            $dept->update();
            if($dept){
                return redirect('/hr/department')->with('flash_message', $dept->name.' status has been changed successfully.');
            }else{
                return redirect('/hr/department')->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            return redirect('/hr/department')->with('flash_message', 'There is something wrong. Please try again.');
        }
    }
}
