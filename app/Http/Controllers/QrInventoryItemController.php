<?php

namespace App\Http\Controllers;

use App\User;
use App\InventoryItem;
use App\InventoryDetails;
use App\Category;
use App\QrCode as Qr_code;
use Illuminate\Http\Request;
use App\Http\Requests\InventoryItemStoreRequest;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Designation;
class QrInventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
     
        // $this->middleware('auth');
        $this->url= url(request()->route()->getPrefix());
        $this->prefix= 'admin/inventory/inventory_item';
      
     }
    public function index()
    {
        //
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



    public function check($id)
    {
       try
       {
      
        if($id != '')
        { 
   
        $qrcode =  Qr_code::where(['id'=>$id])->first();
        $showdetails = InventoryItem::where(['qr_code_id'=>$qrcode->id,'is_deleted'=>'1'])->first();
        $inv_id = isset($showdetails)?$showdetails->id:'';
        $inventorydetails = InventoryDetails::where('inventory_id',$inv_id)->get(); 
        if($showdetails){         
            return view('admin/inventory/inventory_item/show',['inventoryItems'=>$showdetails ,'qr_id'=>$id,'inventorydetails'=>$inventorydetails]);
        }else{
            return redirect('/admin/qr_code/'.$id);
        }
     
       }
        }catch (\Exception $e)
        {    
        $message = 'Something went wrong';
        return redirect('admin/inventory/inventory_item/show')->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryItem $inventoryItem,$id)
    {
        if($id != '')
        { $inventoryItems = InventoryItem::where(['id'=>$id,'is_deleted'=>'1'])->first();
        
          $inventorydetails = InventoryDetails::where('inventory_id',$inventoryItems->id)->get();      
           return view('admin/inventory/inventory_item/show',compact('inventoryItems','inventorydetails'));
              }
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
