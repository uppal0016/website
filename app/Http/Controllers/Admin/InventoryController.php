<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\InventoryItem;
use Auth;
use Crypt;
class InventoryController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
  * @purpose         :   Display a listing of the inventory
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   GET
  * @params          :   Request
  * @return          :   view
  */
  public function index(){
    $auth = Auth::user();
    $spare_items = InventoryItem::Where(['is_deleted'=>'1','avilability_status'=>'0'])->count();
    $assigned_items = InventoryItem::Where(['is_deleted'=>'1','avilability_status'=>'1'])->count();
    $damage_items = InventoryItem::Where(['is_deleted'=>'0','avilability_status'=>'2'])->count();
    $scrap_items = InventoryItem::Where(['is_deleted'=>'2','avilability_status'=>'3'])->count();
    $dashboardCounts = [
      "spare_items" => $spare_items,
      "assigned_items" => $assigned_items,
      "damage_items" => $damage_items,
      "scrap_items" => $scrap_items,
      "category_id" => '',
    ];
    return view('admin.inventory.index', compact('dashboardCounts'));
  }

  /**
  * @purpose         :   Inventory Filter
  * @developer       :   Sarvjeet Singh
  * @created date    :   22-08-2019 (dd-mm-yyyy)
  * @method          :   POST
  * @params          :   Request
  * @return          :   json []
  */
  public function inventory_filter(Request $request){
    $auth = Auth::user();
    // $category_id = $request->category_id;
    // session()->put('category_id', $category_id);
    // session()->save();

    if($request->category_id != '')
    $catFilter = ['category_id'=>$request->category_id];
    else
    $catFilter = [];
    $spare_items = InventoryItem::where($catFilter)->Where(['is_deleted'=>'1','avilability_status'=>'0'])->count();
    $assigned_items = InventoryItem::where($catFilter)->Where(['is_deleted'=>'1','avilability_status'=>'1'])->count();
    $damage_items = InventoryItem::where($catFilter)->Where(['is_deleted'=>'0','avilability_status'=>'2'])->count();
    $scrap_items = InventoryItem::where($catFilter)->Where(['is_deleted'=>'2','avilability_status'=>'3'])->count();
    $dashboardCounts = [
      "spare_items" => $spare_items,
      "assigned_items" => $assigned_items,
      "damage_items" => $damage_items,
      "scrap_items" => $scrap_items,
      "category_id" => $catFilter['category_id'],
    ];
    echo json_encode($dashboardCounts); die;
  }

public function destroy($id){ 
      try{
             $id = Crypt::decrypt($id);
             $inventory = InventoryItem::find($id);
             $inventory->deleted_by =  Auth::user()->id;
             $inventory->update();
             $inventory = $inventory->delete();
              if( $inventory){       
            
        return response()->json(['status'=>'success', 'message'=>'inventory deleted successfully.']);
      }
  
           
        }catch(\Exception $e){
            return back()->with('flash_message', 'There is something wrong. Please try again.');
        }  
 
  }
}