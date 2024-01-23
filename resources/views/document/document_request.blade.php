@extends('layouts.page')

@section('content')
<style>
    .modal-content {
        width: 50rem;
        position: relative;
        right: 46%;
    }

    .text_center{
        text-align: center;
    }
</style>
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-11 col-7">
                    <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                        class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a
                                    href="{{ url('/document') }}">Documents</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Request Document Details</li>
                        </ol>
                    </nav>
                </div>
                <div class="expBtn text-left">
                    <button type="submit" class="btn btn-primary" id="documentRequestExport">
                        Export
                    </button>
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
                    <h3 class="mb-0">Request Document Details</h3>
                    <div class="row">
                    </div>
                </div>

                <div class="card-body">
                    <div class="panel-body">
                        <div class="canvas-wrapper">
                            <div class="table-responsive item_inv" id="dynamicContent">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text_center">Sr no</th>
                                            <th class="text_center">Document Name</th>
                                            <th class="text_center">User Count</th>
                                            <th class="text_center">Requesting Times</th>
                                            <th class="text_center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>   
                                        @php $counter = ($document_request->currentPage() - 1) * $document_request->perPage() + 1; @endphp
                                        @if ($document_request->isNotEmpty())
                                            @foreach ($document_request as $value)
                                                <tr>
                                                    <td class="text_center">{{ $counter }}</td>
                                                    <td class="text_center">{{ $value->document_name }}</td>
                                                    <td class="text_center"><a href="#" onclick="openMyModal({{ $value->document_id }},{{$value->user_id}})">{{ $value->user_count }}</a></td>
                                                    <td class="text_center"><a href="#" onclick="openRequestModal({{ $value->document_id }},{{$value->user_id}})">{{ $value->request_count }}</a></td>
                                                    <td class="text_center">{{ $value->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
                                                </tr>
                                                @php $counter ++; @endphp
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="9" class="text-center"><b>No record found</b></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="pagination">
                                    {{ $document_request->appends(['page' => Request::get('page'), '_token' => csrf_token()])->render() }}
                                </div>
                            </div>          
                        </div>  
                    </div>  
                </div>  
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" style="margin-top:150px:">
    <input type="hidden" id="leaveid" value="">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Employee Details</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text_center">Sr no</th>
                                <th class="text_center">Employee Name</th>
                                <th class="text_center">Employee Code</th>
                            </tr>
                        </thead>
                        <tbody id="user_detail">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="myModal2" class="modal fade" tabindex="-1" style="margin-top:150px:">
    <input type="hidden" id="leaveid" value="">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Request Document Details</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text_center">Sr no</th>
                                <th class="text_center">Employee Name</th>
                                <th class="text_center">Request Type</th>
                            </tr>
                        </thead>
                        <tbody id="request_document_details">

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

@section('script')
<script>
    function openMyModal(document_id, user_id) {
        $.ajax({
            url: "/get-employee-details/" + document_id + "/" + user_id,
            method: "GET",
            success: function(response) {
                if (response.success) {
                    $('#myModal').modal('show');
                    $('#user_detail').empty();
                    response.data.forEach(function(document, index) {
                        var rowHtml = '<tr>' +
                            '<td class="text_center">' + (index + 1) + '</td>' +
                            '<td class="password-cell text_center">' + document.first_name + ' ' + document.last_name + '</td>' +
                            '<td class="text_center">' + document.employee_code + '</td>' +
                            '</tr>';
                        $('#user_detail').append(rowHtml); // Updated the ID here
                    });
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>

<script>
    function openRequestModal(document_id, user_id) {
        $.ajax({
            url: "/get-request_document-details/" + document_id + "/" + user_id,
            method: "GET",
            success: function(response) {
                if (response.success) {
                    $('#myModal2').modal('show');
                    $('#request_document_details').empty();
                    response.data.forEach(function(document, index) {
                        var rowHtml = '<tr>' +
                            '<td class="text_center">' + (index + 1) + '</td>' +
                            '<td class="password-cell text_center">' + document.first_name + ' ' + document.last_name + '</td>' +
                            '<td class="text_center">' + document.request_type + '</td>' +
                            '</tr>';
                        $('#request_document_details').append(rowHtml); // Updated the ID here
                    });
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>

<script>
$(document).ready(function() { $('#documentRequestExport').click(function() { window.location = "/document_request_export"; }); });
</script>

<script src="{{ URL::asset('js/custom.js') }}"></script>
@endsection
@endsection

