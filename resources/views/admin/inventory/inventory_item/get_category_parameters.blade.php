@if($parameters)
@foreach($parameters as $key => $parameter)
<tr class="parameter_tr">
  <td>
    <div class="col-md-2">
      {!! Form::Label($parameter) !!}
    </div>
    <div class="col-md-10">
      <div class="form-group clearfix">
        {!! Form::hidden('parameter_name[]',$parameter) !!}
        @if($category_id == $selected_cat_id)
        {!! Form::text('parameters[]',$item_parameters[$parameter],['placeholder'=>'Enter Parameter Name','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
        @else
        {!! Form::text('parameters[]',null,['placeholder'=>'Enter Parameter Name','class'=>'form-control', 'rows' => 2, 'cols' => 40]) !!}
        @endif
        <span class="text-danger">{{ $errors->first($parameter) }}</span>
      </div>
    </div>
  </td>
</tr>
@endforeach
@endif
