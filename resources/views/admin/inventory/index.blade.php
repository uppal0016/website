@extends('layouts.page')

@section('content')
  <div class="header bg-primary pb-6">
    <div class="container-fluid">
      <div class="header-body">
        <div class="row align-items-center py-4">
          <div class="col-lg-6 col-7">
            <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
              <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory</a></li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid dsr-detail-pg mt--6">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- Card header -->
          <div class="card-header d-flex justify-content-between align-items-center border-0">
            <h3 class="mb-0">Inventory List</h3>
          </div>
          <div class="card-body">
                  @php $categories = Helper::getTablaDataForDropDown('categories','name','asc',['status'=>1]);
                    $array1 = [''=>'Select Category'];
                    $categories = $array1 + $categories;
                  @endphp
                  {!! Form::select('category_id',$categories,null,['class'=> 'form-control inventory_dashboard_filter','rel'=>'category_id']) !!}
            <div class="row">
              <div class="col-xs-6 col-md-4 col-lg-4 inventoryList">
                <a id="categoryId2" href="{{ route('admin.inventory_item', ['status' => '0', 'category_id' => $dashboardCounts['category_id']]) }}">
                <div class="inventoryListInner">
                    <em class="fa fa-xl fa-inbox color-blue"></em>
                    <div class="large spare_items">{{ $dashboardCounts['spare_items'] }}</div>
                    <div class="txtBOttom">Inventory Items in Spare</div>
                </div>
                </a>
              </div>
              <div class="col-xs-6 col-md-4 col-lg-4  inventoryList">
                <a id="categoryId1" href="{{ route('admin.inventory_item', ['status' => '1', 'category_id' => $dashboardCounts['category_id']]) }}">
                <div class="inventoryListInner">
                  <em class="fa fa-xl fa- fa-tasks  color-orange"></em>
                    <div class="large assigned_items">{{ $dashboardCounts['assigned_items'] }}</div>
                    <div class="txtBOttom">Assigned Inventory Items</div>
                </div>
                </a>
              </div>
              <div class="col-xs-6 col-md-4 col-lg-4  inventoryList">
                <a id="categoryId3" href="{{ route('admin.inventory_item', ['status' => '2', 'category_id' => $dashboardCounts['category_id']]) }}">
                <div class="inventoryListInner">
                  <em class="fa-solid fa-circle-exclamation"></em>
                    <div class="large damage_items">{{ $dashboardCounts['damage_items'] }}</div>
                    <div class="txtBOttom">Damage Inventory Items</div>
                </div>
                </a>
              </div>
              <div class="col-xs-6 col-md-4 col-lg-4  inventoryList">
                <a id="categoryId4" href="{{ route('admin.inventory_item', ['status' => '3', 'category_id' => $dashboardCounts['category_id']]) }}">
                <div class="inventoryListInner">
                  <em class="fa-solid fa-circle-minus"></em>
                    <div class="large scrap_items">{{ $dashboardCounts['scrap_items'] }}</div>
                    <div class="txtBOttom">Scrap Inventory Items</div>
                </div>
                </a>
              </div>
            </div><!--/.row-->
          </div>
          <!-- Card footer -->
          <!-- <div class="card-footer py-4">
            <div class="common pagination">
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </div>
  @section('script')
<script src="{{ URL::asset('js/custom.js') }}"></script>
<script>
// setInterval(() => {
//   console.log(sessionStorage.getItem('category_id'));
// }, 500);
  
</script>
  @endsection
@endsection
