<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use File;
use App\QrCode as Qr_code;
use App\InventoryItem;
use DB;
class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
     {
       $this->middleware('auth');
       $this->url= url(request()->route()->getPrefix());
       $this->prefix= 'admin/inventory/qrCode';      
       $this->perPage= 10;
     }
    public function index( Request $request)
    {   if($request->isMethod('post')) {
        $qrcode =  Qr_code::orderby('id','DESC')->where('status',0)->get();
        return view($this->prefix.'/search',compact('qrcode'));
    }
       $qrcode =  Qr_code::orderby('id','DESC')->where('status',0)->get();
        return view($this->prefix.'/index',compact('qrcode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        
        return view($this->prefix.'/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
      try
       {
        if($request->qr_code > 500){
            return redirect('admin/qr_code/create')->with('error', ' One Time Qr code Genrate maximmum 500');
        }          
        if($request->item_type== 'screen'){
        $size = 120;
        }elseif($request->item_type== 'mouse'){
        $size = 50;
        }
        elseif($request->item_type== 'cpu'){
        $size = 160;    
        }
        elseif($request->item_type== 'keyboard'){
        $size = 100;   
        }
        for($i=1;$i<=$request->qr_code;$i++){          
            $qrid  = Qr_code::create(['status'=>0]);       
            $qr_code_url = env('APP_URL').''. '/qr_code/check/'.  $qrid->id;
            $qr = QrCode::format('svg')->size($size)
                        ->generate($qr_code_url);  
            $updateQr = Qr_code::findOrFail($qrid->id);       
            $folderPath = public_path('images/qrcode/');
            if(!File::isDirectory($folderPath)){
            File::makeDirectory($folderPath, 0777, true, true);
            }
            $image = uniqid(). '.'.'svg';  
            $file = $folderPath .$image;       
            file_put_contents($file, $qr); 
            $updateQr->genrated_id =  'QR-'.$qrid->id;         
            $updateQr->qr_image =  $image;
            $updateQr->update();
        }
        if( $updateQr){
            return redirect('admin/qr_code')->with('flash_message', 'Added successfully');
           }
         } catch (\Exception $e)
           {
               $message = 'Something went wrong';
               return back()->with('error', $message);
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

       try
       {
        if($id != '')
        { 
        $qrcode =  Qr_code::where(['id'=>$id])->first();
        $showdetails = InventoryItem::where(['qr_code_id'=>$qrcode->id,'is_deleted'=>'1'])->first(); 
        $inventoryItems = InventoryItem::get(); 
        $show_spare_btn =  $qrcode->status == 0 ? true : false;            
         return view('admin/inventory/qrCode/show',compact('inventoryItems','qrcode','showdetails','show_spare_btn'));
         }
        }catch (\Exception $e)
        {
        $message = 'Something went wrong';
        return redirect('admin/qr_code/')->with('error', $e->getMessage());
        }
    }

    public function assigned_item(Request $request){
    
       try
        {
            
          if(!empty($request->item_id))
          {
            DB::beginTransaction();   
            $inventoryItem = InventoryItem::findOrFail($request->item_id);
            $checkitem = InventoryItem::where(['qr_code_id'=>$request->qr_code_id,'assigned_to'=>$request->assigned_to])->first();           
            if($request->Assign=='Assign'){
            if(!empty($checkitem)){
                return redirect('admin/qr_code/'.$request->qr_code_id)->with('error', 'Qr code already assigned  this item you can assign another users.');
            } 
                $inventoryItem->assigned_to = $request->assigned_to;
                $inventoryItem->qr_code_id =  $request->qr_code_id;
                if($request->assigned_to){
                    $inventoryItem->avilability_status = 1;  
                    $status = 1;
                }else {
                    $inventoryItem->avilability_status = 0;  
                    $status = 0;
                }           
                $inventoryItem->assign_date  = date('Y-m-d');            
                $inventoryItem->update();  
            }elseif($request->Spare =='Spare'){
                $status = 0;                                    
                $inventoryItem->assigned_to = null;      
                $inventoryItem->qr_code_id = null; 
                $inventoryItem->avilability_status = 0;                  
                $inventoryItem->assign_date  = date('Y-m-d');            
                $inventoryItem->update();  
                    
            }
            $updateQr = Qr_code::findOrFail($request->qr_code_id);         
            $updateQr->status =  $status;
            $updateQr->update();
             if($inventoryItem)
            {           
              DB::commit();
              if($request->Assign=='Assign')
              $message = 'Inventory Item assigned to user successfully!';          
              else
              $message = 'Inventory Item status changed successfully! ';              
              return redirect('admin/qr_code/'.$request->qr_code_id)->with('flash_message', $message);
            }
          
          }else{
            DB::rollback();
            return redirect('admin/qr_code/'.$request->qr_code_id)->with('error','Something went wrong.');
          }
          
        }
        catch (\Exception $e)
        {
            $message = 'Something went wrong';
            return redirect('admin/qr_code/'.$request->qr_code_id)->with('error', $message);
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
    public function destroy()
    { 
    try{
    $qr_code =   Qr_code::where('status',0)->delete();
     if($qr_code){      
     return response()->json(['status'=>'success', 'message'=>' Qr code  deleted successfully.']);
    }      
    }catch(\Exception $e){
       return back()->with('flash_message', 'There is something wrong. Please try again.');
    }  
    }
}
