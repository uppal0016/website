<?php

namespace App\Http\Controllers\Api;

use Crypt;
use App\Designation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;


class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try{           
        $designations = Designation::latest()->paginate(10);
         return response()->json($designations, 200);       
        } catch(\Exception $e){
              return response()->json(['errors' => 'There is something wrong'], 500);
        }   
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

       try{
            $data = $request->only('name', 'status');
            $message = ['required' => 'The designation name field is required'];
            $validator = Validator::make($data, [
                'name' => 'required|unique:designations',
                'status' => 'required'
            ],$message);

           if($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => $validator->messages()
            ]);
        }

            $desg = Designation::create($data);
            if($desg){
                  return response()->json($desg, 200);   
            }else{
                  return response()->json(['errors' => true], 500);
            }
        }catch(\Exception $e){
               return response()->json(['errors' => 'There is something wrong'], 500);
          
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
       try{
          $data = $request->only('name');
          $message = ['required' => 'The designation name field is required'];
          $validator = Validator::make($data, ['name' => 'required|unique:designations,name,'.$id],$message);
           if($validator->fails()) {
            return response()->json([
                'status' => 'error', 
                'message' => $validator->messages()
            ]);
            }
            $desg = Designation::whereId($id)->update(['name'=>$data['name']]);

            if($desg){
                   return response()->json(Designation::whereId($id)->first(), 200);   
                   
                 
            }else{
              return response()->json(['errors' => true], 500); 
            }
        }catch(\Exception $e){
              return response()->json(['errors' => 'There is something wrong'], 500);
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
       try{     
        $desg = Designation::findOrFail($id);
        $del = $desg->delete();
        if($del){
               return response()->json([
             'status' => 200,
             'message'=>'Category Deleted Successfully!!'
          ]);
            }else{
                return response()->json(['errors' => 'There is something wrong'], 500);
            }
        }catch(\Exception $e){
          return response()->json(['errors' => 'There is something wrong'], 500);
        }
    }
}


?>