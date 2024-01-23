@extends('layouts.page')
@section('content')

  <?php
    $current_uri = Route::getFacadeRoot()->current()->uri();
  ?>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-4 col-7 secLeft">
          <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Summary</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-8 col-5 text-right formResponsive btnsSearch">
            <form action="{{url($current_uri)}}" method="get">
                <div class="row justify-content-end">
                    <div class="col-md-4 searchBox customSearch paddRight">
                        <div class="input-group custom-searchfeild search-inputs">
                            {{csrf_field()}}
                            <input placeholder="Search by name.." type="text" id="search_user" name="search" class="form-control" value="{{ request('search') }}"/>
                                <i class="fa fa-search"></i>
                        </div>
                    </div>
                    <div class="col-md-4 search-inputs customSearch ">
                        <input id="summary_datepicker" class="form-control summary_datepicker" name="date" type="text" autocomplete="off" value="{{ request('date') }}" placeholder="Search by date.." />

                    </div>
                    <div>
                        <button class="btn btn-primary" type="submit" name="submit" style="height: 100% !important;">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                    <div class="px-3">
                        <a href="{{url($current_uri)}}">
                          <button class="btn btn-danger" type="button" name="submit" style="height: 100% !important;">
                              <i class="fa fa-times"></i>
                          </button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Summary List</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive" id ="paginationData">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col" class="sort" data-sort="budget">User Name</th>
                <th scope="col">Date Worked On</th>
                <th scope="col">Hours Spent</th>
            </thead>
            <tbody class="list">
            @if(!$sumryList->isEmpty())
            @foreach($sumryList as $list)
              <tr>
                <td>{{$list->full_name}}  </td>
                <td>{{$date}}</td>
                @php $hours = $minutes = "0"; @endphp
                @foreach($list->dsr as $k =>$v)
                  @foreach($v->details as $key =>$val)
                      @php
                        $time = explode('.', number_format( (float) $val->total_hours, 2, '.', ''));
                        $hours += $time[0];
                        $minutes += isset($time[1]) ? $time[1] : "0";
                      @endphp
                  @endforeach
                @endforeach
                @php
                  if($minutes > 59){
                    $hours += explode('.', ($minutes/60))[0];
                    $minutes = $minutes%60;
                  }
                @endphp
                <td>{{$hours.($hours > 1 || $hours == 0 ? ' Hrs':' Hr')}} {{$minutes > 0 ? ($minutes.($minutes > 1 || $minutes == 0 ? ' Mins':' Min')):''}} </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
        </div>
        <!-- Card footer -->
        <span class="paginate-content">
          {{ $sumryList->appends(\Request::except('page'))->render() }}
        </span>   
      </div>
    </div>
  </div>
</div>
@section('script')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script>
    $(document).ready(function(){
      $('#summary_datepicker').datepicker({
        format: "yyyy-mm-dd",
        startDate: "2011-01-01",
        endDate: moment(new Date()).format('YYYY/MM/DD'),
        todayBtn: "linked",
        autoclose: true,
        todayHighlight: true,
        container: '#time-est-popup modal-header'
      });

      $('#summary_datepicker').keypress(function(e){
        e.preventDefault();

      });
      var search_user_ajax
      $('#search_user').autocomplete({
          // autoFocus: true,
          // minLength:3,
          // dataType: "json",
          // source:'search/autocomplete',
          // select:function(event, ui){
          // }   
        minLength:3,
        source: function (request, response) {
            if(search_user_ajax){
              search_user_ajax.abort()
            }
            var DTO = { "term": request.term };
            search_user_ajax = $.ajax({
                data: DTO,
                global: false,
                type: 'GET',
                url: 'search/autocomplete',
                success: function (jobNumbers) {
                    return response(jobNumbers);
                }
            });
        }

      });
    });


  </script>      
@endsection

@endsection
