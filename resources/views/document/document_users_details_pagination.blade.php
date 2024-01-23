<div class="table-responsive item_inv" id="dynamicContent">
    @if ($documents->isNotEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th>Sr no</th>
                    <th>Employee name</th>
                    <th>Emp Code</th>
                    <th>Last Page</th>
                    <th>read Pages </th>
                    <th>Max time page </th>
                    <th>Total Pages </th>
                </tr>
            </thead>
            <tbody>
                @php $counter = ($documents->currentPage() - 1) * $documents->perPage() + 1; @endphp
                @foreach ($documents as $value)
                    <tr>
                        <td>{{ $counter }}</td>
                        <td>{{ $value->user_name }}</td>
                        <td>{{ $value->emp_code }}</td>
                        <td>{{ $value->last_page }}</td>
                        <td>{{ $value->pages }}</td>
                        <td>{{ $value->page_no }}</td>
                        <td><?php
                        $path = public_path('images/document/' . $documentName);
                        $pdf = file_get_contents($path);
                        $number = preg_match_all('/\/Page\W/', $pdf, $mdumy);
                        echo $number;
                        ?></td>
                    </tr>
                    @php $counter ++; @endphp
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
