@extends('layouts.page')
@section('content')
 
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li>
        <a href="{{ URL('/dashboard') }}">
          <em class="fa fa-home"></em>
        </a>
      </li>
      <li class="active">{{ $title }}</li>
    </ol>
  </div><!--/.row-->
  <div class="row">
    <div class="col-lg-12">
      <h1 class="page-header">{{ $title }}</h1>

      @if ($message = Session::get('success'))
      <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif

      @if ($message = Session::get('error'))
      <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
      </div>
      @endif
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="canvas-wrapper">
            {{-- <div>
              <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
            </div> --}}
            <div class="table-responsive item_inv" id="dynamicContent">
              <table class="table">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Assigned To</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Company Name</th>
                    <th>Serial No</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if(!$inventoryItem->isEmpty())
                  @foreach($inventoryItem as $index=>$value)
                  <tr>
                    <td>{{ $value->generate_id }}</td>
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
                    </td>
                  </tr>
                  @endforeach
                  @else
                  <tr><td colspan="7" class="text-center"><b>No record found</b></td></tr>
                  @endif
                </tbody>
              </table>
              <div class="item_inv">
                {{ $inventoryItem->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div><!--/.row-->
</div>  <!--/.main-->
<div id="myModal" class="modal fade" role="dialog">
</div>  <!--/.main-->

<script>
var searchUrl = 'inventoryItem-search';
jQuery('.stock_drpDwn').val(0);
</script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
