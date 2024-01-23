<table class="table">
  <thead>
    <tr>
      <th>Category Name</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
  @if(!$category->isEmpty())
    @foreach($category as $value)
      <tr>
        <td>{{$value->name}}</td>
        @php
          $statusText = ($value->status == 1) ? 'Activate' : 'Deactivate';
          $icon = ($value->status == 1) ? 'fa fa-times' : 'fa fa-check';
        @endphp
        <td>{{ $statusText }}</td>
        <td>
          <a href="{{ action('Admin\CategoryController@edit',Crypt::encrypt($value->id)) }}" title="Edit Category">
            <i class="fa fa-edit"> </i>
          </a> &nbsp

          <a href="{{ url($url.'/change_category_status/'.Crypt::encrypt($value->id)) }}" title="<?php echo $statusText; ?>">
            <i class="<?php echo $icon; ?>"></i>
          </a>
        </td>
      </tr>
    @endforeach
  @else
    <tr><td colspan="3" class="text-center no_record"><b>No record found</b></td></tr>
    @endif
  </tbody>
</table>
<div class="pagination">
  {{ $category->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
</div>
