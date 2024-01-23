<div class="table-responsive" id="dynamicContent">
    <table class="table">
        <thead>
        <tr>
            <th class="th-pad">S.No</th>
            <th class="th-pad">Project Name</th>
            <th class="th-pad">Start Date</th>
            <th class="th-pad">End Date</th>
            <th class="th-pad">Action</th>
        </tr>
        </thead>
        <tbody>
        @php
        $counter = 1;
        @endphp
        @if(Request::get('page') && ! empty(Request::get('page')))
           @php
            $page = Request::get('page') - 1;
            $counter = 10 * $page + 1;
           @endphp
        @endif
        @foreach($projects as $value)
            <tr>
                <td>{{ $counter }}</td>
                <td>{{$value->name}}</td>
                <td>{{$value->start_date}}</td>
                <td>{{$value->end_date}}</td>
                <td>
                    <a href="{{ action('PM\ProjectController@edit',$value['en_id']) }}" title="Edit Project">
                        <i class="fa fa-edit"> </i>
                    </a> &nbsp
                    @php
                    $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                    $icon = !empty($value->status) ? 'fa fa-check' : 'fa fa-times';
                    @endphp
                    <a href="{{ url('/pm/project_status',$value['en_id']) }}" title="<?php echo $statusText; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                    </a> &nbsp
                    <a href="{{ url('/pm/projects/destroy',$value['en_id']) }}" title="Delete Project">
                        <i class="fa fa-trash-o"></i>
                    </a>
                </td>
            </tr>
            @php $counter++ @endphp
        @endforeach
        @if(!$projects->count())
            <tr>
                <td colspan="5" class="text-center"><b>No records found</b></td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="pagination">
        {{ $projects->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
    </div>
</div>