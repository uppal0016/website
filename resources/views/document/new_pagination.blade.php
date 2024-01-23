<div class="table-responsive item_inv" id="dynamicContentNew">
    @if ($documents->isNotEmpty())
    <table class="table align-items-center table-flush">
        <thead>
            <tr>
                <th>Sr No</th>
                <th>Document Name</th>
                <th class="td_align_center">Password type</th>
                <th class="td_align_center">Uploaded on</th>
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
                            @if ($documentPassword == null)
                                <td class="td_align_center"><a href="{{ action('DocumentController@request_password_genrate', $value->id) }}"
                                        onclick="sameTab(event)" target="_blank"
                                        title="Send email for password ">
                                        <i class="far fa-envelope fa-xl"></i>
                                    </a>
                                </td>
                            @else
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
                            @endif
                        @elseif($value->is_password == 'No')
                            @if ($value->is_email == 'Yes')
                                <td class="td_align_center">
                                    <h5>Already an email is sent.</h5>
                                </td>
                            @else
                                <td class="td_align_center"><a href="{{ action('DocumentController@request_password_genrate', $value->id) }}"
                                        onclick="sameTab(event)"
                                        target="_blank"
                                        title="Send email for password ">
                                        <i class="far fa-envelope fa-xl"></i>
                                    </a>
                                </td>
                            @endif
                        @endif
                    @elseif($value->protected_file == 'Multiple')
                        @if ($value->multiple_password == 'yes')
                            @if ($value->is_email == 'Yes')
                                <td class="td_align_center">
                                    <h5>Already an email is sent.</h5>
                                </td>
                            @else
                                <td class="td_align_center"><a href="{{ action('DocumentController@request_password_genrate', $value->id) }}"
                                        title="Send email for password">
                                        <i
                                            class="far fa-envelope fa-xl"></i></a>
                                </td>
                            @endif
                        @elseif($value->multiple_password == 'no')
                            <td class="td_align_center"><a target="_blank"
                                    onclick="OpenModal({{ $value->id }})"
                                    title="Lock File">
                                    <i class="fa-solid fa-lock fa-xl"></i>
                                </a>
                            </td>
                        @else
                            @if ($value->is_email == 'Yes')
                                <td class="td_align_center">
                                    <h5>Already an email is sent.</h5>
                                </td>
                            @else
                                <td class="td_align_center"><a href="{{ action('DocumentController@request_password_genrate', $value->id) }}"
                                        title="Send email for password">
                                        <i
                                            class="far fa-envelope fa-xl"></i></a>
                                </td>
                            @endif
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
