
<?php
$session = Session::get('condition');
 if($session){
    $catid = isset($session['category_id'])? $session['category_id']:'';
     $name = isset($session['name'])? $session['name']:'';
    $avilability_status = isset($session['avilability_status'])? $session['avilability_status']:''; 
   }else{
   $name = '';
   $avilability_status = '';
    $catid = '';
   }
  
            ?>

<table class="table">
<thead>
  <tr>
    <th>Sr.No</th>
  <!--   <th>Id</th> -->
    <th>Item Name</th>
    <th>Category</th>
    <th>Company Name</th>
    <th>Serial No</th>
    <th>Assigned To</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
</thead>
<tbody>
  @php
 $counter = 1;
  $avail = ['1' => 'Assigned','0'=>'Spare','2'=>'Damage','3'=>'Scrap']
  @endphp
   @if(Request::get('page') && ! empty(Request::get('page')))
                         @php
                         $page = Request::get('page') - 1;
                         $counter = 10 * $page + 1;
                         @endphp
                         @endif
  @if(!$inventoryItem->isEmpty())
  @foreach($inventoryItem as $index=>$value)
  <tr>
       <td>{{  $counter }}</td>
  <!--   <td>{{ $value->generate_id }}</td> -->
    <td><a href="javascript:void(0);" title="Item Details"><span class="open_popop" data-url="{{$value->id}}"  style="text-decoration: none;    border-bottom: 1px solid #999999;">{{ isset($value->name)?$value->name:'N/A' }}</span></a></td>
    <td>{{ $value->category->name }}</td>
    <td>{{ $value->company_name }}</td>
    <td>{{ $value->serial_no }}</td>
    <td> @if($value->avilability_status == 1) {{ isset($value->user->full_name)?$value->user->full_name:'N/A' }} @else N/A @endif</td>
    <td>{{ $avail[$value->avilability_status] }}</td>
    @if($value->avilability_status !== 3)
    <td>
      <a href="{{ action('Admin\InventoryItemController@edit',Crypt::encrypt($value->id)) }}?category_id={{$catid}}&name={{$name}}&catval={{$catid}}&avilability_status={{$avilability_status}}&page={{Request::get('page')}}" title="Edit Item">
        <i class="fa fa-edit"> </i>
      </a>
      &nbsp;
      <a href="javascript:void(0);" class="@if($value->is_deleted == 1) change_status @endif" ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="assign_item" title="@if($value->is_deleted == 1) Assign Item @else Can't assign Because Item is Deactivated @endif">
        <i class="fa fa-tasks"></i>
      </a>
      &nbsp;
      <a href="javascript:void(0);" class="@if($value->avilability_status == 0 || $value->avilability_status == 2) change_status @endif" @if($value->avilability_status == 1) onclick="alert('Can\'t Deactivate Because Item is Assigned')" @endif ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="change_status" title="@if($value->avilability_status == 0 || $value->avilability_status == 2) {{ !empty($value->is_deleted) ? 'Deactivate' : 'Activate' }} @else Can't Deactivate Because Item is Assigned @endif">
        <i class="{{!empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times'}}"></i>
      </a>
     
      &nbsp;
      @if($value->avilability_status == 0)
       <a href="" data-href="{{ url('/admin/inventory_item/destroy',Crypt::encrypt($value->id)) }}" class="delete_action" data-name=" Inventory"  title="Delete inventory"><i class="fa fa-trash"></i>
        </a>
        
        @endif
        &nbsp
        @if($value->avilability_status !=2)
        @if(isset($value->qr_code_id))
          <a href="javascript:void(0);" id="myImg"  @if($value->qr_code_id == 0) onclick ="alert('Qr code Not Available')" @else  class="qr_code"   itemid="{{Crypt::encrypt($value->id)}}" @endif   ref="Download Qr code" data-type=""   title="view qr code" >
         <i class="fa fa-qrcode" ></i>
         </a>
         @endif
         @endif
    </td>
    @else 
    <td><a href="javascript:void(0);" title="Scrap Item Details"><i class="fa-solid fa-s" id="scrap_details" data-url="{{$value->id}}" ></i></a></td>
    @endif
  </tr>
@php    $counter++ @endphp
  @endforeach
  @else
  <tr><td colspan="9" class="text-center no_record"><b>No record found</b></td></tr>
  @endif
</tbody>
</table>
<div class="pagination" id="item_inv">
{{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'category_id' =>$category_id,'avilability_status'=>$avilability_status,'name' => $name,'_token'=>csrf_token()])->render() }}
</div>
