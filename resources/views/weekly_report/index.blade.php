@extends('layouts.page')
@section('content')


<?php
  $role_id = auth()->user()->role_id; 
  $user_id = auth()->user()->id;
  $current_uri = Route::getFacadeRoot()->current()->uri();
  $sentCase = in_array($current_uri, ['sent_report']) ? 1 : 0; 
?>
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- <div class="employee_loader" style="display: none;">
  <img style="margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
</div> --}}
<div class="container-fluid dsr-detail-pg mt--6">
  <div class="row">
    <div class="col-6">
      <div class="card">
        <!-- Card header -->
        <div class="card-header d-flex justify-content-between align-items-center border-0">
          <h3 class="mb-0">Weekly Report List</h3>
          @include('common.report_search')
        </div>
        <div class="card-body">
          <!-- Light table -->
           <div class="canvas-wrapper dsr-details-list">
            <!--======= Mgm Dsr Start =======-->
            <div class="table-responsive">
              <div>
                <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loaderList" src="{{asset('images/small_loader.gif')}}">
              </div>
              <table class="table">
                <tbody id="dsr_tbody">
                @if($reports->count() > 0)
                  @foreach($reports as $value)
                  <?php
                      $request = new \Illuminate\Http\Request;
                      $idToHighlight = $request->get('dsrId');
                      $idToHighlight = $idToHighlight ? \Crypt::decrypt($idToHighlight):0;
                      $project_name = 'N-A';
                      $description = '';
                      $highlight = ($value['read']->count() && $value['read'][0]['is_read'] == 1) ? 0:1;
                      if($value['details']->count()){
                        if($value['details'][0]['project']){
                          $project_name = $value['details'][0]['project']['name'];
                        }
                        $description = substr($value['details'][0]['description'], 0, 15);
                      }
                    ?>
                      <tr class="dsr-point {{$highlight && !$sentCase ? 'highlight' : ''}} {{$idToHighlight == $value['id'] ? 'noti' : ''}}" id="dsr_{{$value['en_id']}}">
                      @if(($sentCase != 1))
                      <td width="20%"><b>{{ $value->user ? $value->user->full_name : 'N-A' }}</b></td>
                      @endif
                      <td width="{{ $sentCase == 1 ? '70' : '60'}}%"><b>{{ $project_name }}</b><br>{{$description}}...</td>
                      <td>{{date('d-m-Y', strtotime($value['created_at']))}}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="3" class="no_record">
                      <span><b>No records found</b></span>
                    </td>
                  </tr>
                @endif
                </tbody>
              </table>
              <span class="paginate-content">
              {{ $reports->appends(\Request::except('page'))->render() }}
            </span>
            </div>
            <!--======= Mgm Dsr End =======-->
          </div>
        </div>
        <!-- Card footer -->
        <!-- <div class="card-footer py-4">
          <div class="common pagination">
          </div>
        </div> -->
      </div>
    </div>
    <div class="col-6" id="report_detail_view">
      <div class="card">
        <!-- Card header -->
        <div class="card-header d-flex justify-content-between align-items-center border-0">
          <h3 class="mb-0">Weekly Report Details</h3>
        </div>
        <div class="card-body">
              <div class="canvas-wrapper dsr-details-content">
                {{-- <div>
                  <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                </div> --}}
              </div>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-3 px-3">
          <div class="row">
            <div id="sent-details-data" class="col-sm-6" style="cursor: pointer">See Details
            <div id="sent-details" style="display: block;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@section('script')
<script type="text/javascript">
  var role_id = {{ $role_id}}
  var user_id = {{ $user_id}}
  var app_url = "<?php echo  url('/') ?>"
</script>
<script src="{{ URL::asset('js/weeklyreportjs.js') }}"></script>
@endsection

@endsection
