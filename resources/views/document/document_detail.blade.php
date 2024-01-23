@extends('layouts.page')

@section('content')
    <style>
        /* Custom border color for the tabs */
        /* Custom colors for the tabs */
        /* Custom colors for the tabs */
        #tabs .nav-tabs .nav-item.show .nav-link,
        .nav-tabs .nav-link.active {
            background-color: transparent;
            border-color: transparent transparent #007bff;
            border-bottom: 2px solid #007bff !important;
            font-size: 20px;
            font-weight: bold;
        }

        #tabs .nav-tabs .nav-link {
            border: none;
            border-top-left-radius: .25rem;
            border-top-right-radius: .25rem;
            color: #333;
            font-size: 20px;
        }

        #tabs .nav-tabs .nav-link:hover {
            color: #007bff;
            border-color: none;
        }

        #tabs .nav-tabs .nav-link:focus {
            outline: none;
            box-shadow: none;
        }


        td a {
            display: inline-block;
            padding: 2px 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        td a i {
            font-size: 16px;
        }

        td a:hover {
            background-color: transparent;
            color: #007bff;
        }

        .td_align_center {
            text-align: center;
        }
    </style>
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <!-- <h6 class="h2 text-white d-inline-block mb-0">Attendance</h6> -->
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" title="Dashboard"><i
                                            class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">Document
                                    Details</li>
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
                        <h3 class="mb-0"> Document Details </h3>

                        <div style="width:303px">
                            <input autocomplete="off" name="search" type="text"
                                class="form-control inventory_item_filter" rel='name'
                                placeholder="Search by document name" aria-describedby="button-addon6"
                                id="document_details_name" value="{{ request()->input('search') }}">
                        </div>
                    </div>


                    <div class="card-body">

                        <div class="panel-body">

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" id="current_click">
                                    <a class="nav-link active" data-toggle="pill" href="#home">Current Documents</a>
                                </li>
                                <li class="nav-item" id="new_click">
                                    <a class="nav-link" data-toggle="pill" href="#menu1">New Documents</a>
                                </li>
                                <li class="nav-item" id="favorite_click">
                                    <a class="nav-link" data-toggle="pill" href="#menu2">Favorite Documents</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>
                                    <div class="canvas-wrapper">
                                        {{-- @include('document.current_page') --}}
                                        <div class="table-responsive item_inv" id="dynamicContent">
                                            @if ($documentRead->isNotEmpty())
                                                <table class="table align-items-center table-flush">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Document Name</th>
                                                            <th class="td_align_center">Last Page</th>
                                                            <th class="td_align_center">read Pages </th>
                                                            <th class="td_align_center">Max time page <br> (in min) </th>
                                                            <th class="td_align_center">Total Pages </th>
                                                            <th class="td_align_center">Password type</th>
                                                            <th class="td_align_center">Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $counter = ($documentRead->currentPage() - 1) * $documentRead->perPage() + 1; @endphp
                                                        @foreach ($documentRead as $value)
                                                            <tr>
                                                                <td>{{ $counter }}</td>
                                                                <td><i class="fa fa-file-pdf-o"
                                                                        style="font-size:24px;color:red"> </i>
                                                                    {{ preg_replace('/\\.[^.\\s]{3,4}$/', '', $value->documents) }}
                                                                </td>
                                                                <td class="td_align_center">{{ $value->last_page }}</td>

                                                                <td class="td_align_center">{{ $value->pages }}</td>
                                                                <td class="td_align_center">{{ $value->page_no }}</td>

                                                                <td class="td_align_center"><?php
                                                                $path = public_path('images/document/' . $value->documents);
                                                                $pdf = file_get_contents($path);
                                                                $number = preg_match_all('/\/Page\W/', $pdf, $mdumy);
                                                                echo $number;
                                                                ?></td>
                                                                <td class="td_align_center">
                                                                    {{ $value->protected_file }}
                                                                </td>

                                                                @if ($value->protected_file == 'Single')
                                                                    @if ($value->is_password == 'Yes')
                                                                        @php
                                                                            $documentPassword = App\DocumentPassword::where('document_id', $value->id)
                                                                                ->where('user_id', Auth::user()->id)
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($documentPassword->document_id && $documentPassword->enable == 'Yes')
                                                                            <td class="td_align_center"><a href="{{ url('display_pdf/' . $value->id) }}"
                                                                                    target="_blank"
                                                                                    title="Start Reading">
                                                                                    <i class="far fa-eye"></i> </a>
                                                                            </td>
                                                                        @else
                                                                            <td class="td_align_center"><a target="_blank" title="Lock File"
                                                                                    onclick="OpenModal({{ $value->id }})">
                                                                                    <i
                                                                                        class="fa-solid fa-lock fa-xl"></i>
                                                                                </a>
                                                                            </td>
                                                                        @endif
                                                                    @elseif($value->is_password == 'No')
                                                                        <td class="td_align_center">
                                                                            <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                onclick="sameTab(event)"
                                                                                target="_blank"
                                                                                title="Send email for password ">
                                                                                <i class="far fa-envelope fa-xl"></i>
                                                                            </a>
                                                                        </td>
                                                                    @endif
                                                                @elseif($value->protected_file == 'Multiple')
                                                                    @if ($value->multiple_password == 'yes')
                                                                            <td class="td_align_center"> <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                    title="Send email for password">
                                                                                    <i
                                                                                        class="far fa-envelope fa-xl"></i></a>
                                                                            </td>
                                                                    @elseif($value->multiple_password == 'no')
                                                                        <td class="td_align_center"><a target="_blank"
                                                                                onclick="OpenModal({{ $value->id }})"
                                                                                title="Lock File">
                                                                                <i class="fa-solid fa-lock fa-xl"></i>
                                                                            </a>
                                                                        </td>
                                                                    @else
                                                                        <td class="td_align_center"> <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                title="Send email for password">
                                                                                <i
                                                                                    class="far fa-envelope fa-xl"></i></a>
                                                                        </td>
                                                                    @endif
                                                                @else
                                                                    <td class="td_align_center"><a href="{{ url('display_pdf/' . $value->id) }}"
                                                                            target="_blank" title="Start Reading">
                                                                            <i class="far fa-eye"></i> </a>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @php $counter++ @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <h5 colspan="3" class="text-center" style="font-size:13px">No Document
                                                    Found.</h5>
                                            @endif
                                            <div class="pagination">
                                                {{ $documentRead->appends(['page' => Request::get('page'), 'section' => 'current_manage_document', '_token' => csrf_token()])->render() }}
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="menu1" class="container tab-pane fade"><br>
                                    <div class="canvas-wrapper">
                                        {{-- @include('document.new_page') --}}
                                        <div class="table-responsive item_inv" id="dynamicContentNew">
                                            @if ($documents->isNotEmpty())
                                                <table class="table align-items-center table-flush">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Document Name</th>
                                                            <th class="td_align_center">Password type</th>
                                                            <th class="td_align_center">Date</th>
                                                            <th class="td_align_center">Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $counter = ($documents->currentPage() - 1) * $documents->perPage() + 1; @endphp
                                                        @foreach ($documents as $value)
                                                            <tr>
                                                                <td>{{ $counter }}</td>
                                                                <td><i class="fa fa-file-pdf-o"
                                                                        style="font-size:24px;color:red"> </i>
                                                                    {{ preg_replace('/\\.[^.\\s]{3,4}$/', '', $value->documents) }}
                                                                </td>
                                                                <td class="td_align_center">{{ $value->protected_file }}</td>
                                                                <td class="td_align_center">
                                                                    {{ $value->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}
                                                                </td>
                                                                @if ($value->protected_file == 'Single')
                                                                    @if ($value->is_password == 'Yes')
                                                                        @php
                                                                            $documentPassword = App\DocumentPassword::where('document_id', $value->id)
                                                                                ->where('user_id', Auth::user()->id)
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($documentPassword === null)
                                                                            <td class="td_align_center">
                                                                                <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                    onclick="sameTab(event)"
                                                                                    target="_blank"
                                                                                    title="Send email for password ">
                                                                                    <i class="far fa-envelope fa-xl"></i>
                                                                                </a>
                                                                            </td>
                                                                        @elseif ($documentPassword->document_id && $documentPassword->enable == 'Yes')
                                                                            <td class="td_align_center"><a href="{{ url('display_pdf/' . $value->id) }}"
                                                                                target="_blank" title="Start Reading">
                                                                                <i class="far fa-eye"></i> </a>
                                                                            </td>
                                                                        @else
                                                                            <td class="td_align_center"><a target="_blank" title="Lock File"
                                                                                    onclick="OpenModal({{ $value->id }})">
                                                                                    <i
                                                                                        class="fa-solid fa-lock fa-xl"></i>
                                                                                </a>
                                                                            </td>
                                                                            
                                                                        @endif
                                                                    @elseif($value->is_password == 'No')
                                                                        <td class="td_align_center">
                                                                            <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                onclick="sameTab(event)"
                                                                                target="_blank"
                                                                                title="Send email for password ">
                                                                                <i class="far fa-envelope fa-xl"></i>
                                                                            </a>
                                                                        </td>
                                                                    @endif
                                                                @elseif($value->protected_file == 'Multiple')
                                                                    @if ($value->multiple_password == 'yes')
                                                                            <td class="td_align_center"> <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                    title="Send email for password">
                                                                                    <i
                                                                                        class="far fa-envelope fa-xl"></i></a>
                                                                            </td>
                                                                    @elseif($value->multiple_password == 'no')
                                                                        <td class="td_align_center"><a target="_blank"
                                                                                onclick="OpenModal({{ $value->id }})"
                                                                                title="Lock File">
                                                                                <i class="fa-solid fa-lock fa-xl"></i>
                                                                            </a>
                                                                        </td>
                                                                    @else
                                                                        <td class="td_align_center"> <a href="{{ route('generate.document.password', ['id' => $value->id]) }}"
                                                                                title="Send email for password">
                                                                                <i
                                                                                    class="far fa-envelope fa-xl"></i></a>
                                                                        </td>
                                                                    @endif
                                                                @else
                                                                    <td class="td_align_center"><a href="{{ url('display_pdf/' . $value->id) }}"
                                                                            target="_blank" title="Start Reading">
                                                                            <i class="far fa-eye"></i> </a>
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @php $counter++ @endphp
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <h5 colspan="3" class="text-center" style="font-size:13px">No Record
                                                    Found.</h5>
                                            @endif
                                            <div class="pagination">
                                                {{ $documents->appends(['page' => Request::get('page'), 'section' => 'new_manage_document', '_token' => csrf_token()])->render() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="menu2" class="container tab-pane fade"><br>
                                    <div class="canvas-wrapper">
                                        <div class="table-responsive item_inv" id="dynamicContentFavorite">
                                            @if ($favoriteDocuments->isNotEmpty())
                                                <table class="table align-items-center table-flush">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No</th>
                                                            <th>Document Name</th>
                                                            <th class="td_align_center">Password type</th>
                                                            <th class="td_align_center">Date</th>
                                                            <th class="td_align_center">Action </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($favoriteDocuments as $key => $document)
                                                            <tr>
                                                                <td>{{ $key + 1 }}</td>
                                                                <td>{{ $document->documents }}</td>
                                                                <td class="td_align_center">{{ $document->protected_file }}</td>
                                                                <td class="td_align_center">{{ $document->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td> 
                                                                {{-- <td class="td_align_center"></td> --}}

                                                                @if ($document->protected_file == 'Single')
                                                                    @if ($document->is_password == 'Yes')
                                                                        @php
                                                                            $documentPassword = App\DocumentPassword::where('document_id', $document->id)
                                                                                ->where('user_id', Auth::user()->id)
                                                                                ->first();
                                                                        @endphp
                                                                        @if ($documentPassword->document_id && $documentPassword->enable == 'Yes')
                                                                            <td class="td_align_center"><a href="{{ url('display_pdf/' . $document->id) }}"
                                                                                target="_blank" title="Start Reading">
                                                                                <i class="far fa-eye"></i> </a>
                                                                                <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                href="{{ url('favorite/remove', $document->id) }}"
                                                                                title="Remove Favorite">
                                                                                <i class="fas fa-trash"></i> 
                                                                            </a>
                                                                            </td>
                                                                        @else
                                                                            <td class="td_align_center"><a target="_blank" title="Lock File"
                                                                                    onclick="OpenModal({{ $document->id }})">
                                                                                    <i class="fa-solid fa-lock fa-xl"></i>
                                                                                    <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                href="{{ url('favorite/remove', $document->id) }}"
                                                                                title="Remove Favorite">
                                                                                <i class="fas fa-trash"></i> 
                                                                            </a>
                                                                                </a>
                                                                            </td>
                                                                        @endif
                                                                    @elseif($document->is_password == 'No')
                                                                        <td class="td_align_center">
                                                                            <a href="{{ route('generate.document.password', ['id' => $document->id]) }}"
                                                                                onclick="sameTab(event)"
                                                                                target="_blank"
                                                                                title="Send email for password ">
                                                                                <i class="far fa-envelope fa-xl"></i>
                                                                            </a>
                                                                            <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                href="{{ url('favorite/remove', $document->id) }}"
                                                                                title="Remove Favorite">
                                                                                <i class="fas fa-trash"></i> 
                                                                            </a>
                                                                        </td>
                                                                    @else
                                                                        <td class="td_align_center">
                                                                            <a href="{{ route('generate.document.password', ['id' => $document->id]) }}"
                                                                                onclick="sameTab(event)"
                                                                                target="_blank"
                                                                                title="Send email for password ">
                                                                                <i class="far fa-envelope fa-xl"></i>
                                                                            </a>
                                                                            <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                href="{{ url('favorite/remove', $document->id) }}"
                                                                                title="Remove Favorite">
                                                                                <i class="fas fa-trash"></i> 
                                                                            </a>
                                                                        </td>
                                                                    @endif
                                                                @elseif($document->protected_file == 'Multiple')
                                                                    @if ($document->multiple_password == 'yes')
                                                                        <td class="td_align_center"><a href="{{ route('generate.document.password', ['id' => $document->id]) }}"
                                                                                target="_blank"
                                                                                title="Send email for password"
                                                                                onclick="sameTab(event)">
                                                                                <i
                                                                                    class="far fa-envelope fa-xl"></i></a>
                                                                                    <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                        href="{{ url('favorite/remove', $document->id) }}"
                                                                                        title="Remove Favorite">
                                                                                        <i class="fas fa-trash"></i> </a>
                                                                        </td>
                                                                    @elseif($document->multiple_password == 'no')
                                                                        <td class="td_align_center"><a target="_blank"
                                                                                onclick="OpenModal({{ $document->id }})"
                                                                                title="Lock File">
                                                                                <i class="fa-solid fa-lock fa-xl"></i>
                                                                                <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                    href="{{ url('favorite/remove', $document->id) }}"
                                                                                    title="Remove Favorite">
                                                                                    <i class="fas fa-trash"></i> </a>
                                                                            </a>
                                                                        </td>
                                                                    @else
                                                                        <td class="td_align_center"><a href="{{ route('generate.document.password', ['id' => $document->id]) }}"
                                                                                target="_blank"
                                                                                title="Send email for password"
                                                                                onclick="sameTab(event)">
                                                                                <i class="far fa-envelope fa-xl"></i></a>
                                                                                <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                    href="{{ url('favorite/remove', $document->id) }}"
                                                                                    title="Remove Favorite">
                                                                                    <i class="fas fa-trash"></i> </a>
                                                                        </td>
                                                                    @endif
                                                                @else
                                                                    <td class="td_align_center"><a href="{{ url('display_pdf/' . $document->id) }}"
                                                                        target="_blank" title="Start Reading">
                                                                        <i class="far fa-eye"></i> </a>
                                                                            <a onclick="return confirm('Are you sure you want to remove this document?')"
                                                                                href="{{ url('favorite/remove', $document->id) }}"
                                                                                title="Remove Favorite">
                                                                                <i class="fas fa-trash"></i> </a>
                                                                    </td>
                                                                @endif


                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <h5 colspan="3" class="text-center" style="font-size:13px">No Record
                                                    Found.</h5>
                                            @endif
                                            <div class="pagination">
                                                {{ $favoriteDocuments->appends(['page' => Request::get('page'), 'section' => 'favorite_manage_document', '_token' => csrf_token()])->render() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card footer -->
                </div>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade" tabindex="-1" style="margin-top:150px:">
        <input type="hidden" id="leaveid" value="">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"
                        style="position: absolute; top: 8%;">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form action="{{ url('documentview') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="password" value="" name="password" class="form-control"
                                            id="password" placeholder="Enter Document Password">
                                        <input type="hidden" value="" name="document" id="document_id">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('script')
    <script src="{{ URL::asset('js/custom.js') }}"></script>
    <script>
        $('#new_click').click(function() {
            $('.pagination .page-item[aria-current="page"]').addClass('active');
        })
        $('#current_click').click(function() {
            $('.pagination .page-item[aria-current="page"]').addClass('active');
        })
        $('#favorite_click').click(function() {
            $('.pagination .page-item[aria-current="page"]').addClass('active');
        })

        $(document).ready(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var targetTab = $(e.target).attr('href'); // Get the target tab ID
                if (targetTab === '#home') {
                    $('#remove_tab').hide(); // Hide the remove_tab element when the home tab is active
                } else if (targetTab === '#menu1') {
                    $('#remove_tab').show(); // Show the remove_tab element when the menu1 tab is active
                } else if (targetTab === '#menu2') {
                    $('#remove_tab').show();
                }
            });
        });


        function OpenModal(documentId) {
            $('#myModal').modal('show');
            $('#document_id').val(documentId)
        }
    </script>

    <script>
        function sameTab(event) {
            event.preventDefault();
            window.location.href = event.currentTarget.href;
        }
    </script>
@endsection
@endsection
