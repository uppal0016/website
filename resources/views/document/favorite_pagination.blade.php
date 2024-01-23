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
                        <td class="td_align_center">
                            {{ $document->created_at->setTimezone('Asia/Kolkata')->format('d-m-Y') }}</td>
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
                                    </td>
                                @else
                                    <td class="td_align_center"><a target="_blank" title="Lock File"
                                            onclick="OpenModal({{ $document->id }})">
                                            <i class="fa-solid fa-lock fa-xl"></i>
                                        </a>
                                    </td>
                                @endif
                            @elseif($document->is_password == 'No')
                                @if ($document->is_email == 'Yes')
                                    <td class="td_align_center">
                                        <h5>Already an email is sent.</h5>
                                    </td>
                                @else
                                    <td class="td_align_center"><a href="{{ url('display_pdf/' . $document->id) }}"
                                            onclick="sameTab(event)" target="_blank" title="Send email for password ">
                                            <i class="far fa-envelope fa-xl"></i>
                                        </a>
                                    </td>
                                @endif
                            @endif
                        @elseif($document->protected_file == 'Multiple')
                            @if ($document->multiple_password == 'yes')
                                @if ($document->is_email == 'Yes')
                                    <td class="td_align_center">
                                        <h5>Already an email is sent.</h5>
                                    </td>
                                @else
                                    <td class="td_align_center"><a
                                            href="{{ action('DocumentController@request_password_genrate', $document->id) }}"
                                            target="_blank" title="Send email for password" onclick="sameTab(event)">
                                            <i class="far fa-envelope fa-xl"></i></a>
                                    </td>
                                @endif
                            @elseif($document->multiple_password == 'no')
                                <td class="td_align_center"><a target="_blank" onclick="OpenModal({{ $document->id }})"
                                        title="Lock File">
                                        <i class="fa-solid fa-lock fa-xl"></i>
                                    </a>
                                </td>
                            @else
                                <td class="td_align_center"><a
                                        href="{{ action('DocumentController@request_password_genrate', $document->id) }}"
                                        target="_blank" title="Send email for password" onclick="sameTab(event)">
                                        <i class="far fa-envelope fa-xl"></i></a>
                                </td>
                            @endif
                        @else
                            <td class="td_align_center"><a href="{{ url('display_pdf/' . $document->id) }}"
                                    target="_blank" title="Start Reading">
                                    <i class="far fa-eye"></i> </a>
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
