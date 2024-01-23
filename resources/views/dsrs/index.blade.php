@extends('layouts.page')
@section('content')
<style>
    a.btn.rejection-reason-btn {
        background-color: #eb5252;
        color: white;
    }
</style>

    <?php
    
    $role_id = auth()->user()->role_id;
    $user_id = auth()->user()->id;
    $current_uri = Route::getFacadeRoot()
        ->current()
        ->uri();
    $sentCase = in_array($current_uri, ['sent_dsr']) ? 1 : 0;
    $current_url = $_SERVER['REQUEST_URI'];
    $current_url_parts = explode('/', $current_url);
    $last_word = end($current_url_parts);
    ?>
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url(\App\Helpers\Helper::dashboardUrl()) }}"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">DSR</li>
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

        <div class="row" id="dsr_list_view">
            <div class="col-6">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header d-flex justify-content-between align-items-center border-0">
                        <h3 class="mb-0">DSR List</h3>
                        @include('common.dsr_search')
                    </div>
                    <div class="card-body">
                        <!-- Light table -->
                        <div class="canvas-wrapper dsr-details-list">
                            <div class="table-responsive">
                                <div>
                                    <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;"
                                        class="loaderList" src="{{ asset('images/small_loader.gif') }}">
                                </div>
                                <table class="table ">
                                    <tbody id="dsr_tbody">
                                        @foreach ($dsrs as $value)
                                            <?php
                                            $lastid = isset($lastid) ? $lastid : '';
                                            $request = new \Illuminate\Http\Request();
                                            $idToHighlight = $request->get('dsrId');
                                            $idToHighlight = $idToHighlight ? \Crypt::decrypt($idToHighlight) : 0;
                                            $lastdsractive = $value['id'] == $lastid ? 'active-row' : '';
                                            $project_name = 'N-A';
                                            $description = '';
                                            $highlight = $value['read']->count() && $value['read'][0]['is_read'] == 1 ? 0 : 1;
                                            if ($value['details']->count()) {
                                                if ($value['details'][0]['project']) {
                                                    $project_name = $value['details'][0]['project']['name'];
                                                }
                                                $description = substr($value['details'][0]['description'], 0, 40);
                                            }
                                            ?>

                                            <tr class="dsr-point {{ $lastdsractive }}{{ $highlight && !$sentCase ? 'highlight' : '' }} {{ $idToHighlight == $value['id'] ? 'noti' : '' }}"
                                                id="dsr_{{ $value['en_id'] }}">
                                                @if ($sentCase != 1)
                                                    <td width="20%">
                                                        <b>{{ $value->user ? $value->user->full_name : 'N-A' }}</b></td>
                                                @endif
                                                <td width="{{ $sentCase == 1 ? '70' : '60' }}%">
                                                    <b>{{ $project_name }}</b><br />{{-- {!! $sentCase == 1 ? '<br>' : '' !!} --}}{{ $description }}...
                                                </td>
                                                @if ($value->status == 1)
                                                    <td style="color:green">
                                                        <div class="realtimeststus_{{ $value->id }}">Approved </div>
                                                    </td>
                                                @elseif($value->status == 2)
                                                    <td style="color:blue">
                                                        <div class="realtimeststus_{{ $value->id }}">Pending </div>
                                                    </td>
                                                @else
                                                    <td style="color:red">
                                                        <div class="realtimeststus_{{ $value->id }}">Rejected </div>
                                                    </td>
                                                @endif
                                                <td>{{ date('d-m-Y', strtotime($value['created_at'])) }}</td>
                                            </tr>
                                        @endforeach

                                        @if (!$dsrs->count())
                                            <tr>
                                                <td colspan="3" class="no_record">
                                                    <span><b>No records found</b></span>
                                                </td>
                                                <!-- <td class="text-center" style="border: none"><b>No records found</b></td> -->
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <span class="paginate-content">
                                    {{ $dsrs->appends(\Request::except('page'))->render() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if (!session()->has('open_dsr'))
                        <p id="dsr_lastid" lastid="{{ !empty($lastid) ? Crypt::encrypt($lastid) : '' }}">
                    @endif
                    <!-- Card footer -->
                    <!-- <div class="card-footer py-4">
              <div class="common pagination">
              </div>
            </div> -->
                </div>
            </div>
            <div class="col-6" id="dsr_detail_view">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header d-flex justify-content-between align-items-center border-0">
                        <h3 class="mb-0">DSR Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="canvas-wrapper dsr-details-content">
                            {{-- <div>
              <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loader" src="{{asset('images/small_loader.gif')}}">
            </div> --}}
                        </div>
                    </div>
                    <!-- Card footer -->

                    <div class="card-footer py-3 px-3" id="dsrStatus">
                        <p class="m-0 text">
                            <a href="javascript:void(0);" class="btn rejection-reason-btn disable" data-rejection-reason="" data-dsr-id="">
                                Rejection Reason
                            </a>
                        </p>
                    </div>
                    <div class="card-footer py-3 px-3">
                        <div class="row">
                            <div id="sent-details-data" class="col-sm-6" style="cursor: pointer">See Details
                                <div id="sent-details" style="display: block;"></div>
                            </div>
                            <div class="col-sm-6 text-right" id="add-dsr-comment" style="cursor: pointer"
                                data="dsr_{{ !empty($lastid) ? Crypt::encrypt($lastid) : '' }}">Add Comment</div>
                        </div>
                        &nbsp

                    </div>


                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectionReasonModal" tabindex="-1" role="dialog" aria-labelledby="rejectionReasonModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectionReasonModalLabel">DSR Rejection Reason</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="rejectionReasonContent"></p>
                    </div>
                </div>
            </div>
        </div>

        @include('common.attachment_viewer')
        @include('common.chat_box')



    @section('script')
        @if (session()->has('open_dsr'))
            <script type="text/javascript">
                $(document).ready(function() {
                    setTimeout(function() {
                        $('.dsr-point:first').trigger('click');
                    }, 10);
                });
            </script>
        @endif
        <script type="text/javascript">
            var role_id = {{ $role_id }}
            var user_id = {{ $user_id }}
            var app_url = "<?php echo url('/'); ?>";
        </script>
        <script type="text/javascript" src="{{ URL::asset('js/dsrjs.js') }}"></script>
    @endsection
@endsection
