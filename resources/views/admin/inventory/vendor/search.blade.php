<table class="table">
  <thead>
    <tr>
      <th>Sr.</th>
      <th>Vendor Name</th>
      <th>Phone 1</th>
      <th>Phone 2</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @if(!$vendor->isEmpty())
    @foreach($vendor as $index=>$value)
    <tr>
      <td>{{ $vendor->perPage() * ($vendor->currentPage() -1 ) + $index+1 }}</td>
      <td>{{ $value->name }}</td>
      <td>{{ isset($value->phone1)?$value->phone1:'N/A' }}</td>
      <td>{{ isset($value->phone2)?$value->phone2:'N/A' }}</td>
      <td>
        <a href="{{ action('Admin\VendorController@edit',Crypt::encrypt($value->id)) }}" title="Edit Vendor">
          <i class="fa fa-edit"> </i>
        </a> &nbsp
        @php
        $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
        $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
        @endphp
        <a href="{{ url('/admin/change_vendor_status/'.Crypt::encrypt($value->id)) }}" title="<?php echo $statusText; ?>">
          <i class="<?php echo $icon; ?>"></i>
        </a>
      </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
    @endif
  </tbody>
</table>
<div class="pagination">
  {{ $vendor->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div>
