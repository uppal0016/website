@extends('layouts.page')

@section('content')

 

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ url('/pm/inventory') }}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">Inventory</li>
    </ol>
  </div><!--/.row-->

  <div class="row">
    <div class="col-lg-6">
      <h1 class="page-header">Inventory</h1>
    </div>

    <div class="col-lg-3 pull-right">
      @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['is_deleted'=>1]);
      $array1 = [''=>'Select Category'];
      $categories = $array1 + $categories;
      @endphp
      {!! Form::select('category_id',$categories,null,['class'=> 'form-control inventory_dashboard_filter','rel'=>'category_id']) !!}
    </div>
  </div><!--/.row-->

  <div class="panel panel-container">
    <div class="row">

      @if ( auth()->user()->role_id == 3)

      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <a id="categoryId2" href="{{ route('admin.inventory_item', ['status' => '0', 'category_id' => $dashboardCounts['category_id']]) }}">
        <div class="panel panel-teal panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-inbox color-blue"></em>
            <div class="large spare_items">{{ $dashboardCounts['spare_items'] }}</div>
            <div class="text-muted">Inventory Items in Spare</div>
          </div>
        </div>
        </a>
      </div>
      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <a id="categoryId1" href="{{ route('admin.inventory_item', ['status' => '1', 'category_id' => $dashboardCounts['category_id']]) }}">
        <div class="panel panel-blue panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa- fa-tasks  color-orange"></em>
            <div class="large assigned_items">{{ $dashboardCounts['assigned_items'] }}</div>
            <div class="text-muted">Assigned Inventory Items</div>
          </div>
        </div>
        </a>
      </div>
      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <a id="categoryId3" href="{{ route('admin.inventory_item', ['status' => '2', 'category_id' => $dashboardCounts['category_id']]) }}">
        <div class="panel panel-teal panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa-inbox color-blue"></em>
            <div class="large damage_items">{{ $dashboardCounts['damage_items'] }}</div>
            <div class="text-muted">Inventory Items in Spare</div>
          </div>
        </div>
        </a>
      </div>
      <div class="col-xs-6 col-md-4 col-lg-4 no-padding">
        <a id="categoryId4" href="{{ route('admin.inventory_item', ['status' => '3', 'category_id' => $dashboardCounts['category_id']]) }}">
        <div class="panel panel-blue panel-widget border-right">
          <div class="row no-padding"><em class="fa fa-xl fa- fa-tasks  color-orange"></em>
            <div class="large scrap_items">{{ $dashboardCounts['scrap_items'] }}</div>
            <div class="text-muted">Assigned Inventory Items</div>
          </div>
        </div>
        </a>
      </div>

      @endif
    </div><!--/.row-->
  </div>

  <div class="tt-watermark text-center">
    <img src="{{URL::asset('images/watermark_tt.png')}}" class="img-responsive" alt="">
  </div>

</div>	<!--/.main-->
<script src="{{ URL::asset('js/custom.js') }}"></script>

@endsection
