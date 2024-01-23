<div class="modal-dialog">
  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">@if($type == 'change_status' || $type == 'change_availabilty_status') Change Status @else Assign Item @endif</h4>
    </div>
    <div class="modal-body">
      <div class="row">
        {!! Form::open(array('action' => 'Admin\InventoryItemController@change_item_status','method'=>'POST','id'=>'add_reason')) !!}
        {!! Form::hidden('_token', Session::token()) !!}
        {!! Form::hidden('type','change_status') !!}
        {!! Form::hidden('id',isset($inventoryItem->id)?Crypt::encrypt($inventoryItem->id):null,['class'=>'item_id']) !!}
        {!! Form::hidden('url',null,['class'=>'url_class']) !!}
        @if($type == 'change_availabilty_status')
        <div class="col-md-12">
          <div class="form-group clearfix">
            {!! Form::textarea('reason',isset($inventoryItem->reason)?$inventoryItem->reason:null,['placeholder'=>'Enter Reason','class'=>'form-control reason_cls', 'rows' => 2, 'cols' => 40]) !!}
            <span class="text-danger">{{ $errors->first('reason') }}</span>
          </div>
          <div class="form-group clearfix">
            {!! Form::select('avilability_status', array(''=>'Select Status','0' => 'Spare','2' => 'Damage'),isset($inventoryItem->avilability_status)?$inventoryItem->avilability_status:null,['class'=> 'form-control avl_cls']) !!}
            <span class="text-danger">{{ $errors->first('avilability_status') }}</span>
          </div>
        </div>
        @endif
        @if($type == 'change_status')
        <div class="col-md-12">
          <div class="form-group clearfix">
            {!! Form::textarea('reason',isset($inventoryItem->reason)?$inventoryItem->reason:null,['placeholder'=>'Enter Reason','class'=>'form-control reason_cls', 'rows' => 2, 'cols' => 40]) !!}
            <span class="text-danger">{{ $errors->first('reason') }}</span>
          </div>
          <div class="form-group clearfix">
            {!! Form::select('is_deleted', array('1' => 'Activate', '0' => 'Deactivate'),isset($inventoryItem->is_deleted)?$inventoryItem->is_deleted:null,['class'=> 'form-control']) !!}
            <span class="text-danger">{{ $errors->first('is_deleted') }}</span>
          </div>
        </div>
        @endif
        @if($type == 'assign_item')
        <div class="col-md-12">
          @php
          $users = Helper::getTablaDataOrderBy('users','first_name','asc',['is_deleted'=>0]);
          @endphp
          {!! Form::hidden('type','assign_item') !!}
          <div class="form-group clearfix">
            <select name="assigned_to" class="form-control usr_cls select_btn_icon">
              <option value="">Select User</option>
              @foreach($users as $key=>$user)
              <option @if($inventoryItem->assigned_to == $user->id) selected @endif value="{{ $user->id }}">{{ $user->first_name.' '.$user->last_name }}</option>
              @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('assigned_to') }}</span>
          </div>
        </div>
        @endif
        <div class="col-md-12">

          <div class="form-group clearfix pull-right">
            {!! Form::submit('Submit',['class'=>'btn btn-primary  add-user-btn','name'=> 'submit']) !!}
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
