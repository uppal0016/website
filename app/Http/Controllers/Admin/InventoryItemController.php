<?php

namespace App\Http\Controllers\Admin;
session_start();
use DB;
use Auth;
use File;
use Crypt;
use App\User;
use App\Category;
use App\Designation;
use App\InventoryItem;
use App\InventoryDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\QrCode as Qr_code;
use App\Http\Requests\InventoryItemStoreRequest;

class InventoryItemController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
    $this->url= url(request()->route()->getPrefix());
    $this->prefix= 'admin/inventory/inventory_item';
    $this->asprefix= 'admin/inventory/assigned_stock';
    $this->title= 'Inventory Items';
    $this->perPage= 10;
  }

  /**
  * @purpose         :   Display a listing of the inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */

  public function index(Request $request)
  {
    $availability_status = '';
    $inventoryItem = InventoryItem::/*where(['avilability_status'=>0])->*/orderBy('id','desc')->paginate($this->perPage);
     $data = $request->all(); 
     $status = $request->query('status');
     $category_id = $request->input('category_id');
     if (isset($status)) {  
      if($category_id === null){
        $queryParams = array_diff_key($request->query(), ['page' => 1]);
        $queryParams['status'] = $status;
    
        $inventoryItem = InventoryItem::orderBy('id', 'desc')->where('avilability_status', $status)->paginate($this->perPage)->appends($queryParams);
        $inventoryItem->setPath('inventory_item');
      } else {
        $queryParams = array_diff_key($request->query(), ['page' => 1]);
        $queryParams['status'] = $status;
    
        $inventoryItem = InventoryItem::orderBy('id', 'desc')->where('avilability_status', $status)->where('category_id', $category_id)->paginate($this->perPage)->appends($queryParams);
        $inventoryItem->setPath('inventory_item');
      }
      if($request->ajax()){

        return view($this->prefix . '/search', ['inventoryItem' => $inventoryItem, 'url' => $this->url, 'title' => $this->title, 'availability_status' => $availability_status, 'category_id' => $category_id ]);
      }
      return view($this->prefix . '/index', ['inventoryItem' => $inventoryItem, 'url' => $this->url, 'title' => $this->title, 'availability_status' => $availability_status, 'category_id' => $category_id ]);
  }
  
  try{
       if(!$request->ajax())
    {
      // $searchArray = $request->input('condition');
      $request->session()->forget('condition');
       $searchArray = $data;
       if($data){
       $category_id = $searchArray['category_id'];
       }else{
        $category_id = '';
       }
    
      if(!empty($searchArray))
      {
  
        if(isset($searchArray['avilability_status']) || isset($searchArray['category_id'])){
         
        $availability_status = $searchArray['avilability_status'];
        $inventoryItem = InventoryItem::Where('avilability_status',$searchArray['avilability_status'])->orWhere('category_id',$searchArray['category_id'])->orderBy('id','desc')->paginate($this->perPage);
       
        }
      
        if(isset($searchArray['avilability_status']) && isset($searchArray['category_id']))
        {
            $inventoryItem = InventoryItem::where(['category_id' => $searchArray['category_id'],'avilability_status'=>$searchArray['avilability_status']])->orderBy('id','desc')->paginate($this->perPage);
   
        }
        

        if(isset($searchArray['name']))
        {
          $search = $searchArray['name'];
          unset($searchArray['name']);
          unset($searchArray['page']);
          if(!$searchArray['avilability_status']){
            unset($searchArray['avilability_status']);
          }
          if(!$searchArray['category_id']){
       
            unset($searchArray['category_id']);
          }
   
          $inventoryItem = InventoryItem::where($searchArray)->where(function($q) use ($search){
            $q->where('name','LIKE','%'.$search.'%');
            $q->orwhere('serial_no','LIKE','%'.$search.'%');
            $q->orwhere('company_name','LIKE','%'.$search.'%');
          })
          ->orderBy('id','desc')
          ->paginate($this->perPage);
         
        }
      }
      else
      {      
        $inventoryItem = InventoryItem::orderBy('id','desc')->paginate($this->perPage);
      }
      $inventoryItem->setPath('inventory_item');
       return view($this->prefix.'/index',['inventoryItem'=>$inventoryItem,'url'=>$this->url,'title'=>$this->title ,'availability_status' => $availability_status,'category_id'=>$category_id]);
      // return view($this->prefix.'/search',['inventoryItem'=>$inventoryItem,'url'=>$this->url, 'availability_status' => $availability_status]);
    }
  }
   catch (\Exception $e)
    { 
   return redirect('admin/inventory_item')->with('flash_message',$e->getMessage());

    }
    if($request->ajax())
    {   
      // $user_email = "<script>document.write(localStorage.setItem('email',));</script>";
      $searchArray = $request->except(['_token','page']);
      // $searchArray = $request->input('condition');
      // $searchArray = Session::get('condition'); 

      $inventoryItem = new InventoryItem;
 
      if(!empty($searchArray))
      {
        if (isset($searchArray['avilability_status']) && $searchArray['avilability_status'] != null) {
          $inventoryItem = $inventoryItem->where(function($q) use ($searchArray){
          $q->where('avilability_status',$searchArray['avilability_status']);
          });

        } elseif (isset($searchArray['category_id']) && $searchArray['category_id'] != null) {
          
          $inventoryItem = $inventoryItem->where(function($q) use ($searchArray){
          $q->where('category_id',$searchArray['category_id']);
          });
        }
      
        // if((isset($searchArray['category_id']) && $searchArray['category_id'] != null) || (isset($searchArray['avilability_status']) && $searchArray['avilability_status'] != null)){
        //   $inventoryItem = $inventoryItem->where(function($q) use ($searchArray){
        //     if()
        //     $q->orwhere('category_id',$searchArray['category_id'])->orwhere('avilability_status',$searchArray['avilability_status']);
        //   });
        // }
        if(isset($searchArray['category_id']) && $searchArray['category_id'] != null && isset($searchArray['avilability_status']) && $searchArray['avilability_status'] != null){
          $inventoryItem = $inventoryItem->where('category_id',$searchArray['category_id'])->where('avilability_status',$searchArray['avilability_status']);
        }
        
        if(isset($searchArray['name']) && !empty($searchArray['name'])){
          $search = $searchArray['name'];
          unset($searchArray['name']);
          unset($searchArray['page']);
          if(!$searchArray['avilability_status']){
            unset($searchArray['avilability_status']);
          }
          if(!$searchArray['category_id']){
            unset($searchArray['category_id']);
          }
          $inventoryItem = $inventoryItem->where(function($q) use ($search){
            $q->where('name','LIKE','%'.$search.'%');
            $q->orwhere('serial_no','LIKE','%'.$search.'%');
            $q->orwhere('company_name','LIKE','%'.$search.'%');
          });
        }
      }
        $inventoryItem = $inventoryItem->orderBy('id','desc')->paginate($this->perPage);

    //    if($request->category_id || $request->avilability_status){
    //      $inventoryItem = InventoryItem::orderBy('id','desc')->where(function($query) use ($request){
    //         $query->orWhere('category_id',$request->category_id)->orWhere('avilability_status',$request->avilability_status);
    //      })->paginate($this->perPage);
    //   }

    //    if($request->category_id || $request->avilability_status){
    //      $inventoryItem = InventoryItem::orderBy('id','desc')->orWhere('category_id',$request->category_id)->orWhere('avilability_status',$request->avilability_status)->paginate($this->perPage);
    //   }
    // //  if($request->category_id && $request->avilability_status ){
    // //      $inventoryItem = InventoryItem::orderBy('id','desc')->where('category_id',$request->category_id)->where('avilability_status',$request->avilability_status)->paginate($this->perPage);
    // //   }
    //  if($request->category_id && $request->avilability_status ){
    //      $inventoryItem = InventoryItem::orderBy('id','desc')->where('category_id',$request->category_id)->where('avilability_status',$request->avilability_status)->paginate($this->perPage);
    //   }
    
      $inventoryItem->setPath('inventory_item');
    
      return view($this->prefix.'/search',['inventoryItem'=>$inventoryItem,'url'=>$this->url, 'availability_status' => $request->avilability_status,'category_id'=>$request->category_id]);
    }
  
    $inventoryItem->setPath('inventory_item');      
    return view($this->prefix.'/index',['inventoryItem'=>$inventoryItem,'url'=>$this->url,'title'=>$this->title ,'availability_status' => $availability_status]);
  }

  /**
  * @purpose         :   Show the form for creating a new inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */

  public function create()
  {
    return view($this->prefix.'/add',['title'=>$this->title]);
  }

  /**
  * @purpose         :   Store a newly created inventory item in storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   Request
  * @return          :   view
  */

  public function store(InventoryItemStoreRequest $request)
  {
   
    try
    {
      $id=null;
      if(!empty($request->company_name))
      {
        DB::beginTransaction();
        $request->name=trim($request->name);

        if($request->hasfile('invoice_image'))
        {
          $image = $request->file('invoice_image');
          $image_name = time().'_'.$image->getClientOriginalName();
          $img_path = public_path() . '/images/inventory_items/';
          $image->move($img_path, $image_name);
          $image_name = $image_name;
        }else {
          $image_name = '';
        }
        if($request->parameters)
        {
          $parameter_name = $request->parameter_name;
          $parameters = $request->parameters;
          $parameters = array_combine($parameter_name,$parameters);
          $parameters = serialize($parameters);
        }
        else
        {
          $parameters = '';
        }
        $createData = array(
          'name' => $request->name,
          'generate_id' => $request->generate_id,
          'category_id' => $request->category_id,
          'company_name' => $request->company_name,
          'serial_no' => $request->serial_no,
          'd_o_p' => $request->d_o_p,
          'parameters' => $parameters,
          'vendor_id' => $request->vendor_id,
          'purchase_amount' => $request->purchase_amount,
          'invoice_image' => $image_name,
          'is_deleted' => $request->status,
          'added_by' => Auth::user()->id,
        );

          if ($request->status == 0) {
            $createData['avilability_status'] = 2;
          }
          if($data = InventoryItem::updateOrCreate(['id'=>$id],$createData))
        {      
          foreach ($request->add_more_ as $key => $value) {
             
            $datails = new InventoryDetails;
            $datails->inventory_id = $data->id;
            $datails->hardware_name = $value['hardware_name'];
            $datails->hardware_value = $value['hardware_value'];
            $datails->save();
            }   
          DB::commit();
          return redirect('admin/inventory_item')->with('flash_message','Inventory Item created successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('admin/inventory_item/create')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('admin/inventory_item/create')->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Display the specified  inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */

  public function show(InventoryItem $inventoryItem,$id)
  {
    return view($this->prefix.'/show',compact('inventoryItem'));
  }

  /**
  * @purpose         :   Show the form for editing the specified inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  
  public function edit($id)
  {
    $id = \Crypt::decrypt($id);
    $inventoryItem = InventoryItem::where('id', $id)->first();
    $inventoryItemDetils = InventoryDetails::where('inventory_id',$id)->get();
    return view($this->prefix.'/edit',['inventoryItem'=>$inventoryItem, 'inventoryItemDetils'=>$inventoryItemDetils,'title'=>$this->title]);
  }

  /**
  * @purpose         :   Update the specified inventory item in storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   PUT
  * @params          :   \Illuminate\Http\Request  $request
  * @params          :   App\InventoryItem  $inventoryItem
  * @return          :   view
  */

  public function update(Request $request, InventoryItem $inventoryItem)
  { 
    try
    {
      $id= $inventoryItem->id;
      $catid = $request->catid;
      $page = $request->page;
      $name = $request->search_name;
      $catidurl = '';

      // if(!empty($catid) || $request->avilability_status != ''){
      //   $catidurl = '?category_id='.$catid.'&avilability_status='.$request->avilability_status.'&page='.$page.'&name='.$name.'';
      // }elseif(isset($page) && !empty($page)){
      //   $catidurl = '?category_id='.$catid.'&avilability_status='.$request->avilability_status.'&page='.$page.'&name='.$name.'';
      // }elseif(isset($name) && !empty($name)){
      //   $catidurl = '?category_id='.$catid.'&avilability_status='.$request->avilability_status.'&page='.$page.'&name='.$name.'';
      // }else{
      //   $catidurl = '';
      // }
     
      if(!empty($request->company_name))
      {
        DB::beginTransaction();
        $request->name=trim($request->name);

        if($request->parameters)
        {
          $parameter_name = $request->parameter_name;
          $parameters = $request->parameters;
          $parameters = array_combine($parameter_name,$parameters);
          $parameters = serialize($parameters);
        }
        else
        {
          $parameters = '';
        }
        if($request->hasfile('invoice_image'))
        {
          $file_path = public_path().'/images/inventory_items/'.$inventoryItem->invoice_image;
          if($inventoryItem->invoice_image)
          unlink($file_path);

          $image = $request->file('invoice_image');
          $image_name = time().'_'.$image->getClientOriginalName();
          $img_path = public_path() . '/images/inventory_items/';
          $image->move($img_path, $image_name);
          $image_name = $image_name;
          $createData = array(
            'name' => $request->name,
            'generate_id' => $request->generate_id,
            'category_id' => $request->category_id,
            'company_name' => $request->company_name,
            'serial_no' => $request->serial_no,
            'parameters' => $parameters,
            'd_o_p' => $request->d_o_p,
            'vendor_id' => $request->vendor_id,
            'purchase_amount' => $request->purchase_amount,
            'invoice_image' => $image_name,
            'is_deleted' => $request->status,
            'added_by' => Auth::user()->id,
            'avilability_status' => $request->avilability_status,
            'date_of_sold' => $request->date_of_sold,
            'sold_amount' => $request->sold_amount,
          );
        }else {
          $image_name = '';
          $createData = array(
            'name' => $request->name,
            'generate_id' => $request->generate_id,
            'category_id' => $request->category_id,
            'company_name' => $request->company_name,
            'serial_no' => $request->serial_no,
            'parameters' => $parameters,
            'd_o_p' => $request->d_o_p,
            'vendor_id' => $request->vendor_id,
            'purchase_amount' => $request->purchase_amount,
            'is_deleted' => $request->status,
            'added_by' => Auth::user()->id,
            'avilability_status' => $request->avilability_status,
            'date_of_sold' => $request->date_of_sold,
            'sold_amount' => $request->sold_amount,
          );
        }
        if($request->avilability_status == 1 && $request->status == 0){
          return redirect()->back()->with('error','Please unassign the item first.');
        } else if($request->avilability_status == 0 && $request->status == 0){
          $inventoryItem = InventoryItem::findOrFail($id);
          $inventoryItem->avilability_status = 2;
          $inventoryItem->save();
        }
        
        if($request->avilability_status == 2 && $request->status == 2){
          $inventoryItem = InventoryItem::findOrFail($id);
          $inventoryItem->avilability_status = 3;
          $inventoryItem->save();
        }
        if ($request->status == 0) {
          $createData['avilability_status'] = 2;
        } else if ($request->status == 1) {
          $createData['avilability_status'] = 0;
        } else if ($request->status == 2) {
          $createData['avilability_status'] = 3;
        }

        if(InventoryItem::updateOrCreate(['id'=>$id],$createData))
        {                  
           DB::commit();   
        if($request->update_){
          foreach ($request->update_ as $key => $value) {       
          $dsrupdate = [           
             'hardware_name'  =>  $value['hardware_name'],
             'hardware_value' => $value['hardware_value'],
                      ];              
          InventoryDetails::where('id',$value['detailsid'])->update($dsrupdate);      
            }
          }
        
          if($request->add_more_){
          foreach ($request->add_more_ as $key => $value) {             
           $datails = new InventoryDetails;
           $datails->inventory_id = $id;
           $datails->hardware_name = $value['hardware_name'];
           $datails->hardware_value = $value['hardware_value'];
           $datails->save();
           }
         }
          return redirect('admin/inventory_item'.$catidurl.'')->with('flash_message','Inventory Item updated successfully!');
        }
        else
        {
          DB::rollback();
          return redirect('admin/inventory_item/create')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('admin/inventory_item/create')->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Searching and filter for inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */
  
public function view_qr_code(Request $request){
   $id =  \Crypt::decrypt($request->id);
   $InventoryItem =   InventoryItem::where(['id'=>$id])->first();  
   $QrCode  = Qr_code::where(['id'=>$InventoryItem->qr_code_id])->first(); 
   if(!empty($QrCode)){
    $imagepath = env('APP_URL').'/images/qrcode/'. $QrCode->qr_image;
    return response()->json(['imagepath'=> $imagepath,'uniqueid'=>$QrCode->id ]);
   }else{
    return response()->json(['imagepath'=>'' ]);
   } 
 }
 
  public function remove_inventory_details(Request $request){
   $data = $request->all();
   $datails = InventoryDetails::findOrFail($request->id);    
   $datails->delete();
     return response()->json([
        'flash_message' => ' deleted successfully',                       
      ]);
  }
  public function inventory_details(Request $request){
     $InventoryDetails = InventoryDetails::where('inventory_id', '=',  $request->id)->get();

      return response()->json(['InventoryDetails'=>$InventoryDetails]);
     
  }
  public function inventoryItemSearch(Request $request)
  {
    $json = file_get_contents('php://input');
    $condition = json_decode($json, TRUE);  
   session()->put('condition',$condition);  
    $entriesperpage = $this->perPage;
    $searchArray = [];
    $availability_status = '';
    $category_id = '';
    if(!empty($condition)){
        $searchArray = $condition;
        if(isset($condition['avilability_status'])){
            $availability_status = $condition['avilability_status'];
        }

        
    }
    else{
        $searchArray = $searchArray;
    }
     if(isset($condition['category_id'])){
            $category_id = $condition['category_id'];
        }
    if(!isset($condition['name']))
    {
      $inventoryItem = InventoryItem::where($searchArray)->orderBy('id','desc')->paginate($entriesperpage);
    }
    else if($condition['name'] != '')
    {
      unset($searchArray['name']);
      $search = $condition['name'];
      $inventoryItem = InventoryItem::where($searchArray)->where(function($q) use ($search){
        $q->where('name','LIKE','%'.$search.'%');
        $q->orwhere('serial_no','LIKE','%'.$search.'%');
        $q->orwhere('company_name','LIKE','%'.$search.'%');
      })
      ->orderBy('id','desc')
      ->paginate($entriesperpage);
    }
    $inventoryItem->withPath('inventory_item');
    return view($this->prefix.'/search', ['inventoryItem'=>$inventoryItem, 'availability_status' => $availability_status,'category_id'=>$category_id]);
  }

  /**
  * @purpose         :   Change status of inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */

  public function change_item_status(Request $request)
  {
   try
    {
      if($request->assigned_to != '')
      $this->validate($request,[
        'assigned_to' => 'required',
        'id' => 'required'
      ],[
        'assigned_to.required' => 'User is required.',
        'id.required' => 'Id is required.',
      ]);
      if($request->reason != '')
      $this->validate($request,[
        'reason' => 'required',
        'id' => 'required'
      ],[
        'reason.required' => 'Reason is required.',
        'id.required' => 'Id is required.',
      ]);

      $id=null;
      if(!empty($request->id))
      {
        DB::beginTransaction();
        $reason=trim($request->reason);
        $id = \Crypt::decrypt($request->id);
        $inventoryItem = InventoryItem::findOrFail($id);
      
        if(($request->avilability_status != '') && isset($request->reason))
        {
          if($request->avilability_status == 2){
            $inventoryItem->avilability_status = 2;
            $inventoryItem->is_deleted = 0;
          } else {
            $inventoryItem->assigned_to = null;
            $inventoryItem->avilability_status = 0;
          }
        }
        if($request->assigned_to != '')
        {
          $inventoryItem->assigned_to = $request->assigned_to;
          $inventoryItem->avilability_status = 1;
           $inventoryItem->assign_date  = date('Y-m-d');         
        }
        if($request->reason != '' && !isset($request->avilability_status))
        {
          $inventoryItem->reason = $reason;
          if($request->is_deleted == 1){
            $inventoryItem->avilability_status = 0;
            $inventoryItem->is_deleted = 1;
          } else {
            $inventoryItem->avilability_status = 2;
            $inventoryItem->is_deleted = $request->is_deleted;
          }
        }
        if($request->url == 'inventory_item')
        $redirect_url = $this->url.'/inventory_item';
        else
        $redirect_url = $this->url.'/assigned_stock';
        $inventoryItem->update();
        if($inventoryItem)
        {
          DB::commit();
          if($request->type == 'change_availabilty_status')
          $message = 'Inventory Item status changed successfully!';
          else if($request->type == 'change_status')
          $message = 'Inventory Item status changed successfully!';
          else
          $message = 'Inventory Item assigned to user successfully!';
          return redirect($redirect_url)->with('flash_message', $message);
        }
        else
        {
          DB::rollback();
          return redirect($redirect_url)->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      $redirect_url =  $this->url.'inventory_item';
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect($redirect_url)->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Get inventory item data for modal
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */

  public function get_item_details(Request $request){
    if($request->id != '')
    {
      $id = \Crypt::decrypt($request->id);
      $inventoryItem = InventoryItem::select('is_deleted','reason','assigned_to','id')->findOrFail($id);
      return view($this->prefix.'/inventory_item_modal', ['inventoryItem' => $inventoryItem,'type' => $request->type]);
    }
  }

  /**
  * @purpose         :   List Assigned stock of inventory item
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */

  public function assigned_stock(Request $request){

    $inventoryItems = InventoryItem::whereNotNull('assigned_to')->where(['is_deleted'=>'1'])->orderBy('id','desc')->paginate($this->perPage);
    if($request->isMethod('post')){
      $keyword = $request->search;   
      if(!empty($keyword)){
        $users = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"])->get();
        $user_ids = [];
        foreach($users as $val){
          $user_ids[] = $val->id;
        }
        $inventoryItems = InventoryItem::where(['is_deleted'=>'1'])->whereIn('assigned_to',$user_ids)->orderBy('id','desc')->paginate($this->perPage);     
      }
     }    
    $inventoryItems->setPath('assigned_stock');
    if($request->ajax())
    {
      return view($this->asprefix.'/paginate', ['inventoryItem' => $inventoryItems,'url'=>$this->url]);
    }
    
    
    return view($this->asprefix.'/index', ['inventoryItem' => $inventoryItems,'title'=>'Assigned Stock','url'=>$this->url]);
  }

  /**
  * @purpose         :   Get category parameters
  * @developer       :   Sarvjeet Singh
  * @created date    :   29-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */

  public function get_parameters(Request $request){
    if($request->category_id != '')
    {
      $parameters = Category::select('parameter')->findOrFail($request->category_id);
      if($parameters->parameter != null)
      {
        $tags = rtrim(preg_replace('/,+/', ',', $parameters->parameter),',');
        $parameters = explode(',',$tags);
      }else {
        $parameters = '';
      }
    }
    if($request->item_id != '')
    {
      $item_parameters = InventoryItem::select('parameters')->findOrFail($request->item_id);
      if($item_parameters->parameters != '')
      {
        $item_parameters = unserialize($item_parameters->parameters);
      }
    }
    return view($this->prefix.'/get_category_parameters', ['parameters' => isset($parameters)?$parameters:'', 'item_parameters' => isset($item_parameters)?$item_parameters:'','category_id'=>$request->category_id,'selected_cat_id'=>isset($request->selected_cat_id)?$request->selected_cat_id:'']);
  }
  
  public function scrap_details(Request $request){
    $ScrapDetails = InventoryItem::where('id', '=',  $request->id)->select('sold_amount', 'date_of_sold')->get();
     return response()->json(['ScrapDetails'=>$ScrapDetails]);
    
 }
}