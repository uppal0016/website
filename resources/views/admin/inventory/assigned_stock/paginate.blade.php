<table class="table">
  <thead>
  <tr>
    <!-- <th>Id</th> -->
    <th>Sr.No</th>
    <th>Assigned To</th>
    <th>Item Name</th>
    <th>Category</th>
    <th>Company Name</th>
    <th>Serial No</th>
    <th>Action</th>
  </tr>
  </thead>
  <tbody>
  @php  $counter = 1;  @endphp
  @if(Request::get('page') && ! empty(Request::get('page')))
                           @php
                           $page = Request::get('page') - 1;
                           $counter = 10 * $page + 1;
                           @endphp
                           @endif
  @if(!$inventoryItem->isEmpty())
    @foreach($inventoryItem as $index=>$value)
      <tr>
      <td>{{  $counter}}</td>
        <!-- <td>{{ $value->generate_id }}</td> -->
        <td>{{ $value->user->full_name }}</td>
        <td>{{ isset($value->name)?$value->name:'N/A' }}</td>
        <td>{{ $value->category->name }}</td>
        <td>{{ $value->company_name }}</td>
        <td>{{ $value->serial_no }}</td>
        <td>
          @php
            $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
            $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
          @endphp
          <a href="javascript:void(0);" class="change_status" rel="{{ \Crypt::encrypt($value->id) }}" ref="assigned_stock" data-type="assign_item" title="Assign Item">
            <i class="fa fa-tasks"></i>
          </a>
          &nbsp
          <a href="javascript:void(0);" class="change_status" rel="{{ \Crypt::encrypt($value->id) }}" ref="assigned_stock" data-type="change_availabilty_status" title="<?php echo $statusText; ?>">
            <i class="<?php echo $icon; ?>"></i>
          </a>
          &nbsp
          <a href="javascript:void(0);" id="myImg"  @if($value->qr_code_id == 0) onclick ="alert('Qr code Not Available')" @else  class="qr_code"   itemid="{{Crypt::encrypt($value->id)}}" @endif   ref="Download Qr code" data-type=""   title="view qr code" >
                      <i class="fa fa-qrcode" ></i>
                      </a>
        </td>
      </tr>
      @php      $counter++ @endphp
    @endforeach
  @else
    <tr><td colspan="7" class="text-center"><b>No record found</b></td></tr>
  @endif
  </tbody>
</table>
<div class="item_inv">
  {{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div>

