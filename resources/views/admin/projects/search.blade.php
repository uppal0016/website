<div class="table-responsive" id ="dynamicContent">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <th scope="col">Project Name</th>
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody class="list">
            @if(!$projects->isEmpty())
            @foreach($projects as $value)
              <tr>
              <td>{{$value->name}}</td>
              <td>{{$value->start_date}}</td>
              <td>{{$value->end_date}}</td>
              <td>
                <a href="{{ action('Admin\ProjectController@edit',$value['en_id']) }}" title="Edit Project">
                  <i class="fa fa-edit"> </i>
                </a> &nbsp
                @php
                  $statusText = !empty($value->status) ? 'Deactivate' : 'Activate';
                  $icon = !empty($value->status) ? 'fa fa-check' : 'fa fa-times';
                @endphp
                <a href="{{ url('/admin/project_status',$value['en_id']) }}" title="<?php echo $statusText; ?>">
                  <i class="<?php echo $icon; ?>"></i>
                </a> &nbsp

                <a href="{{ url('/admin/projects/destroy',$value['en_id']) }}" title="Delete Project" onclick="return confirm('Are you sure you want to delete this project?');">
                  <i class="fa fa-trash-o"></i>
                </a> &nbsp
                @if(!empty($value->status))
                <a href="{{ url('/admin/project/get_assigned_employees',$value['en_id']) }}" title="Assign Resource">
                  <i class="fa fa-address-book"></i>
                </a>
                @endif
              </td>
              </tr>
              @endforeach
                @else
                <tr><td colspan="5" class="text-center no_record"><b>No record found</b></td></tr>
                @endif
            </tbody>
          </table>
          <div class="pagination">
          {{ $projects->appends(['search' => Request::get('search'),'page'=>Request::get('page'),'_token'=>csrf_token()])->render() }}
        </div> 