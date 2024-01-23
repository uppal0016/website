<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VendorStoreRequest;
use Auth;
use DB;
use Illuminate\Support\Facades\Input;

class VendorController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->url= url(request()->route()->getPrefix());
    $this->prefix= 'admin/inventory/vendor';
    $this->title= 'Vendors';
    $this->perPage= 10;
  }

  /**
  * @purpose         :   Display a listing of the vendor
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  public function index(Request $request)
  {
    $vendor = Vendor::orderBy('id','desc')->paginate($this->perPage);
    if($request->ajax())
    {
        if($request->get('search')){
            $keyword = $request->get('search');
            $vendor = Vendor::where('name', 'LIKE', "%$keyword%")->latest()->paginate(10);
        }
        $vendor->setPath('vendor');
      return view($this->prefix.'/search',['vendor'=>$vendor,'url'=>$this->url]);
    }
    $vendor->setPath('vendor');
    return view($this->prefix.'/index',['vendor'=>$vendor,'url'=>$this->url,'title'=>$this->title]);
  }

  /**
  * @purpose         :   Show the form for creating a new vendor
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  public function create()
  {
    return view($this->prefix.'/add');
  }

  /**
  * @purpose         :   Store a newly created vendor in storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   Request
  * @return          :   view
  */
  public function store(VendorStoreRequest $request)
  {
    try
    {
      $id=null;
      if(!empty($request->name))
      {
        DB::beginTransaction();
        $request->name=trim($request->name);
        $createData = array(
          'name' => $request->name,
          'phone1' => $request->phone1,
          'phone2' => $request->phone2,
          'is_deleted' => $request->status,
          'added_by' => Auth::user()->id,
        );
        if(Vendor::updateOrCreate(['id'=>$id],$createData))
        {
          DB::commit();
          return redirect('admin/vendor')->with('flash_message','Vendor created successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('admin/vendor')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('admin/vendor')->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Display the specified vendor
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @param           : \App\Vendor  $vendor
  * @params          :   Request
  * @return          :   view
  */
  public function show(Vendor $vendor)
  {
    return view($this->prefix.'/show',compact('vendor'));
  }

  /**
  * @purpose         :   Show the form for editing the specified vendor
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @param           :   $id
  * @return          :   view
  */
  public function edit($id)
  {
    $id = \Crypt::decrypt($id);
    $vendor = Vendor::findOrFail($id);
    return view($this->prefix.'/edit',compact('vendor'));
  }

  /**
  * @purpose         :   Update the specified vendor in storage.
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   PUT
  * @params          :   \Illuminate\Http\Request  $request
  * @params          :   App\Category  $inventoryItem
  * @return          :   view
  */
  public function update(VendorStoreRequest $request, Vendor $vendor)
  {
    try
    {
      $id= $vendor->id;
      if(!empty($request->name))
      {
        DB::beginTransaction();
        $request->name=trim($request->name);
        $createData = array(
          'name' => $request->name,
          'phone1' => $request->phone1,
          'phone2' => $request->phone2,
          'is_deleted' => $request->status
        );
        if(Vendor::updateOrCreate(['id'=>$id],$createData))
        {
          DB::commit();
          return redirect('admin/vendor')->with('flash_message','Vendor updated successfully.');
        }
        else
        {
          DB::rollback();
          return redirect('admin/vendor')->with('error','Something went wrong.');
        }
      }
    }
    catch (\Exception $e)
    {
      DB::rollback();
      $errors             = $e->getMessage();
      return redirect('admin/vendor')->with('error',$errors);
    }
  }

  /**
  * @purpose         :   Searching for vendor
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   \Illuminate\Http\Request  $request
  * @return          :   view
  */
  public function vendorSearch(Request $request)
  {
    $search = trim($request->input('search'));
    $entriesperpage = $this->perPage;

    if($search == 'empty' || $search == '')
    {
      $vendor = Vendor::orderBy('id','desc')->paginate($entriesperpage);
    }
    else if($search != '')
    {
      $vendor = Vendor::where(function($q) use ($search){
        $q->where('name','LIKE','%'.$search.'%');
      })
      ->orderBy('id','desc')
      ->paginate($entriesperpage);
    }
    return view($this->prefix.'/search', ['vendor'=>$vendor]);
  }

  /**
  * @purpose         :   Change status of vendor
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :    $id
  * @return          :   view
  */
  public function change_vendor_status($id){
    $id = \Crypt::decrypt($id);
    $vendor = Vendor::findOrFail($id);
    if(!empty($vendor->is_deleted)){
      $status='0';
    }else{
      $status='1';
    }
    $vendor->is_deleted=$status;
    $vendor->update();
    return redirect($this->url.'/vendor')->with('flash_message', 'Vendor status changed successfully!');
  }
}
