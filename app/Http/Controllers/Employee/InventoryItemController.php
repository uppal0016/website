<?php

namespace App\Http\Controllers\Employee;

use App\User;
use App\InventoryItem;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryItemStoreRequest;
use Auth;
use DB;
class InventoryItemController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
    $this->url= url(request()->route()->getPrefix());
    $this->prefix= 'employee/inventory/inventory_item';
    $this->asprefix= 'employee/inventory/assigned_stock';
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
    $inventoryItem = InventoryItem::where(['avilability_status'=>0])->orderBy('id','desc')->paginate($this->perPage);
    if($request->ajax())
    {
      $searchArray = $request->input('condition');
      if(!empty($searchArray))
      {
        if(!isset($searchArray['name']))
        {
          $inventoryItem = InventoryItem::where($searchArray)->orderBy('id','desc')->paginate($this->perPage);
        }
        else if($searchArray['name'] != '')
        {
          $search = $searchArray['name'];
          unset($searchArray['name']);
          $inventoryItem = InventoryItem::where($searchArray)->where(function($q) use ($search){
            $q->where('name','LIKE','%'.$search.'%');
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
      return view($this->prefix.'/search',['inventoryItem'=>$inventoryItem,'url'=>$this->url]);
    }
    return view($this->prefix.'/index',['inventoryItem'=>$inventoryItem,'url'=>$this->url,'title'=>$this->title]);
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

        if(InventoryItem::updateOrCreate(['id'=>$id],$createData))
        {
          DB::commit();
          return redirect('inventory_item')->with('success','Inventory Item created successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('inventory_item/create')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('inventory_item/create')->with('error',$errors);
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

  public function show(InventoryItem $inventoryItem)
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
    $inventoryItem = InventoryItem::findOrFail($id);
    return view($this->prefix.'/edit',['inventoryItem'=>$inventoryItem,'title'=>$this->title]);
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
          );
        }

        if(InventoryItem::updateOrCreate(['id'=>$id],$createData))
        {
          DB::commit();
          return redirect('inventory_item')->with('success','Inventory Item updated successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('inventory_item/create')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('inventory_item/create')->with('error',$errors);
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

  public function inventoryItemSearch(Request $request)
  {
    $json = file_get_contents('php://input');
    $condition = json_decode($json, TRUE);
    $entriesperpage = $this->perPage;
    $searchArray = [];
    if(!empty($condition))
    $searchArray = $condition;
    else
    $searchArray = $searchArray;
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
        $q->orwhere('company_name','LIKE','%'.$search.'%');
      })
      ->orderBy('id','desc')
      ->paginate($entriesperpage);
    }
    $inventoryItem->withPath('inventory_item');
    return view($this->prefix.'/search', ['inventoryItem'=>$inventoryItem]);
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
          $inventoryItem->assigned_to = null;
          $inventoryItem->avilability_status = 0;
        }
        if($request->assigned_to != '')
        {
          $inventoryItem->assigned_to = $request->assigned_to;
          $inventoryItem->avilability_status = 1;
        }
        if($request->reason != '' && !isset($request->avilability_status))
        {
          $inventoryItem->is_deleted = $request->is_deleted;
          $inventoryItem->reason = $reason;
        }
        if($request->url == 'inventory_item')
        $redirect_url = 'inventory_item';
        else
        $redirect_url = 'assigned_stock';
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
          return redirect($redirect_url)->with('success', $message);
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
      $inventoryItem = InventoryItem::select('is_deleted','reason','assigned_to')->findOrFail($id);
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
}
