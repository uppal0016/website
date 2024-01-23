<table class="table">
  <thead>
    <tr>
      <th>S.No</th>
      <th>Category Name</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @if(!$category->isEmpty())
    @foreach($category as $index=>$value)
    <tr class="catId<?php echo $value->id; ?>">
      <td>{{ $category->perPage() * ($category->currentPage() -1 ) + $index+1 }}</td>
      <td>{{ $value->name }}</td>
      <td>
        <a href="{{ action('PM\CategoryController@edit',Crypt::encrypt($value->id)) }}" title="Edit Category">
          <i class="fa fa-edit"> </i>
        </a> &nbsp
        @php
        $statusText = !empty($value->is_deleted) ? 'Deactivate' : 'Activate';
        $icon = !empty($value->is_deleted) ? 'fa fa-check' : 'fa fa-times';
        @endphp
        <a href="{{ url('/pm/change_category_status/'.Crypt::encrypt($value->id)) }}" title="<?php echo $statusText; ?>">
          <i class="<?php echo $icon; ?>"></i>
        </a>
      </td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="3" class="text-center"><b>No record found</b></td></tr>
    @endif
  </tbody>
</table>
<div class="common pagination">
  {{ $category->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div>
