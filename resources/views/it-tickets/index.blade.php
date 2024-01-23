@extends('layouts.page')
<style>
  .status-td {
  display: flex;
  align-items: center;
}

.status-td img {
  margin-right: 5px;
  width: 20px;
}

</style>
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
                <li class="breadcrumb-item active" aria-current="page">IT Tickets</li>
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
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Tickets</h3>
            @php $categories = Helper::getTablaDataForDropDown('it_ticket_categories','name','asc');
            $array1 = [''=>'Select Category','All' => 'All'];
            $categories = $array1 + $categories;

            
            $severity = [
                '' => 'Select severity', 
                'All' => 'All',
                'High' => 'High',
                'Medium' => 'Medium',
                'Low' => 'Low'
              ];
            @endphp
           
          </div>
          <div class="card-body p-0">
              <div class="formRIght py-3 px-4">
                <div class="commdiv">
                  <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                  @if( Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
                  <input autocomplete="off" name="search" type="text" class="form-control inventory_item_filter"  rel='name' placeholder="Search by employee name" aria-describedby="button-addon6" id="it_ticket_name_search" value="{{ request()->input('search') }}">
                  @endif
                </div>
                  <div class="commdiv">
                    <input type="text" id="dates" autocomplete="off" placeholder="Date Range" name="dates" class="form-control">
                </div>
              
                <div class="commdiv">
                  {!! Form::select('category_id',$categories,$selectedCategory = request()->input('category'),['class'=> 'form-control inventory_item_filter select_btn_icon','rel'=>'category_id' ,'id' => "it_ticket_category" ]) !!}
                </div>
                <div class="commdiv ">
                  {!! Form::select('severity',$severity,$selectedSeverity = request()->input('severity'),['class'=> 'form-control inventory_item_filter select_btn_icon','rel'=>'severity' ,'id' => "it_ticket_severity"]) !!}
                </div>
                <div class="commdiv">
                  <select name="status" class="form-control stock_drpDwn select_btn_icon" rel="avilability_status" id="ticket_status">
                      <option value="" style="display:none">Select Status</option>
                      <option value="All" {{ request()->input('status') === 'All' ? 'selected' : '' }}>All</option>
                      <option value="Open" {{ request()->input('status') === 'Open' ? 'selected' : '' }}>Open</option>
                      <option value="InProgress" {{ request()->input('status') === 'InProgress' ? 'selected' : '' }}>InProgress</option>
                      <option value="Closed" {{ request()->input('status') === 'Closed' ? 'selected' : '' }}>Closed</option>
                      <option value="Reopen" {{ request()->input('status') === 'Reopen' ? 'selected' : '' }}>Reopen</option>
                      <option value="Archive" {{ request()->input('status') === 'Archive' ? 'selected' : '' }}>Archive</option>
                  </select>
              </div>
              
              
              
                <div class="lastBtn">
                  <div class="pull-right">
                    <a href="{{ URL('it-tickets/create') }}" class="btn btn-primary add-user-btn add-topic-btn1">+</a><br/>
                  </div>
                </div>
              </div><!--/.row-->
              <div class="canvas-wrapper">
                {{-- <div>
                  <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
                </div> --}}
                <div class="table-responsive item_inv" id="dynamicContent">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Emp Code</th>
                        <th>Emp Name</th>
                        <th>Ticket no</th>
                        @if( Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
                        <th>Created at</th>
                        @endif 
                        <th>Category</th>
                        <th>Severity</th>
                        @if(Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
                        <th>Turnaround Time</th> 
                        @else
                        <th>Created Date</th> 
                        @endif
                        <th>Status</th> 
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                      $avail = ['1' => 'Assigned','0'=>'Spare']
                      @endphp
                      @if(!$it_tickets->isEmpty())
                      @foreach($it_tickets as $index=>$value)
                      <tr>
                        <td>{{ $value->employee_code }}</td>
                        <td>{{ $value->user_name }}</td>
                        <td>{{ $value->ticket_id }}</td>
                        @if(Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
                        <td>{{ $value->created_at->format('Y-m-d') }}</td>
                        @endif 
                        <td>{{ $value->category }}</td>
                        <td>{{ $value->severity }}</td>
                        @if(Auth::user()->email == 'gautam.uppal@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in')
                        <td class="{{ strtotime($value->turnaround_time) > strtotime('04:00:00') ? 'text-danger' : '' }}">{{ $value->turnaround_time ?: '00:00:00' }}</td>
                        @else
                        <td>{{ $value->created_at->format('Y-m-d') }}</td>
                        @endif
                        @if ($value->status == "Open")
                           <td class="status-td"><img src="{{ asset('images/red.svg') }}" alt="">{{ $value->status }}</td>
                        @elseif ($value->status == "InProgress")
                           <td class="status-td"><img src="{{ asset('images/Yellow.svg') }}" alt="">{{ $value->status == "InProgress" ? "In Progress" :$value->status }}</td>   
                        @elseif ($value->status == "Closed")
                           <td class="status-td"><img src="{{ asset('images/green.svg') }}" alt="">{{ $value->status }}</td>
                        @elseif ($value->status == "Reopen")
                           <td class="status-td"><img src="{{ asset('images/orange.svg') }}" alt="">{{ $value->status }}</td>
                        @elseif ($value->status == "Archive")
                           <td class="status-td"><img style="width: 17px;" src="{{ asset('images/archive.svg') }}" alt="">{{ $value->status }}</td>
                        @endif
                        <td>
                          <a class="action_btn" href="{{ action('ItTicketsController@details', ['user_id' => $value->user_id, 'ticket_id' => $value->ticket_id]) }}" title="View details">
                            <i class="fas fa-eye"> </i>
                          </a> 
                        </td>
                      </tr>
                      @endforeach
                      @else
                      <tr><td colspan="9" class="text-center"><b>No record found</b></td></tr>
                      @endif
                    </tbody>
                  </table>
                  <div class="pagination">
                    {{ $it_tickets->appends(['page' => Request::get('page'), 'status' => Request::get('status'), 'search' => Request::get('search'), 'start_date' => Request::get('start_date'), 'end_date' => Request::get('end_date'), 'severity' => Request::get('severity'), 'category' => Request::get('category'), 'medium_severity' => Request::has('medium_severity') ? 'true' : null, 'high_severity' => Request::has('high_severity') ? 'true' : null, 'low_severity' => Request::has('low_severity') ? 'true' : null, 'turnaround_time' => Request::has('turnaround_time') ? 'true' : null, '_token' => csrf_token()])->setPath(url()->current())->render() }}
                  </div>
                </div>
              </div>
          </div>
          <!-- Card footer -->
        </div>
      </div>
    </div>
  </div>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
  @section('script')
  <script src="https://weareoutman.github.io/clockpicker/dist/jquery-clockpicker.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 <script type="text/javascript"
             src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script>
    $('#it_ticket_severity option[value=""]').hide();
    $('#it_ticket_category option[value=""]').hide();

    const urlParams = new URLSearchParams(window.location.search);

    // Extract the start_date and end_date values
    const startDate = urlParams.get('start_date');
    const endDate = urlParams.get('end_date');


    $('input[name="dates"]').daterangepicker({
        startDate: startDate ?  moment(startDate).format('DD/MM/YYYY') : moment().format('DD/MM/YYYY'),
        endDate: endDate ? moment(endDate).format('DD/MM/YYYY') : moment().format('DD/MM/YYYY'),
        maxDate: moment().format('DD/MM/YYYY'),
        opens: 'left',
        locale: {
            format: 'DD/MM/YYYY',
            cancelLabel: 'Clear'
        },
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });


    $('#dates').on('apply.daterangepicker', function (ev, picker) {
    var url = 'list'+'?start_date='+picker.startDate.format('YYYY/MM/DD')+'&end_date='+picker.endDate.format('YYYY/MM/DD');
    window.location.href = url;
    });

    $('#dates').on('cancel.daterangepicker', function () {
    $(this).val('');

    });
  </script>
  <script>
    $(document).ready(function() {
    $('#ticket_status option:first-child').attr('disabled', 'disabled');
});
  </script>
<script src="{{ URL::asset('js/custom.js') }}"></script>
  @endsection
@endsection
