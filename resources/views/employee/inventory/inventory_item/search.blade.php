<table class="table">
  <thead>
    <tr>
      <th>Id</th>
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
    $avail = ['1' => 'Assigned','0'=>'Spare']
    @endphp
    @if(!$inventoryItem->isEmpty())
    @foreach($inventoryItem as $index=>$value)
    <tr>
      <td>{{ $value->generate_id }}</td>
      <td>{{ isset($value->name)?$value->name:'N/A' }}</td>
      <td>{{ $value->category->name }}</td>
      <td>{{ $value->company_name }}</td>
      <td>{{ $value->serial_no }}</td>
      <td> @if($value->avilability_status == 1) {{ $value->user->full_name }} @else N/A @endif</td>
      <td>{{ $avail[$value->avilability_status] }}</td>
      <td>
        <a href="{{ action('Admin\InventoryItemController@edit',Crypt::encrypt($value->id)) }}" title="Edit Item">
          <i class="fa fa-edit"> </i>
        </a>
        &nbsp
        @php
        $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
        $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
        @endphp
        <a href="javascript:void(0);" class="@if($value->is_deleted == 1) change_status @endif" ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="assign_item" title="@if($value->is_deleted == 1) Assign Item @else Can't assign Because Item is Deactivated @endif">
          <i class="fa fa-tasks"></i>
        </a>
        &nbsp
        <a href="javascript:void(0);" class="@if($value->avilability_status == 0) change_status @endif" ref="inventory_item" rel="{{ \Crypt::encrypt($value->id) }}" data-type="change_status" title="@if($value->avilability_status == 0) {{ $statusText }} @else Can't Deactivate Because Item is Assigned @endif">
          <i class="<?php echo $icon; ?>"></i>
        </a>
      </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="9" class="text-center"><b>No record found</b></td></tr>
    @endif
  </tbody>
</table>
<div class="item_inv">
  {{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div>
