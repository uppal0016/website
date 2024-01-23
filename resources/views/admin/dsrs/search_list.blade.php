<?php
 $current_uri = Route::getFacadeRoot()->current()->uri();
 $sentCase = in_array($current_uri, ['sent_dsr']) ? 1 : 0; 
?>
<div class="table-responsive">
<div>
    <img style="display: none; margin-left: 35%; margin-right: 30%; width: 10%;" class="loaderList" src="{{asset('images/small_loader.gif')}}">
</div>
<table class="table">
    <tbody id="dsr_tbody">
    @if($dsrs->count() > 0)
    @foreach($dsrs as $value)
        <?php
        $request = new \Illuminate\Http\Request;
        $idToHighlight = $request->get('dsrId');
        $idToHighlight = $idToHighlight ? \Crypt::decrypt($idToHighlight):0;
        $project_name = 'N-A';
        $description = '';
        $highlight = ($value['read']->count() && $value['read'][0]['is_read'] == 1) ? 0 : 1;

        if($value['details']->count()){
        if($value['details'][0]['project']){
            $project_name = $value['details'][0]['project']['name'];
        }
        $description = substr($value['details'][0]['description'], 0, 20);
        }
        ?>
        <tr class="dsr-point {{$highlight ? 'highlight' : ''}}  {{$idToHighlight == $value['id'] ? 'noti' : ''}}"  id="dsr_{{$value['en_id'] }}">
            <td width="20%"><b>{{ $value->user ? $value->user->full_name : 'N-A' }}</b> </td>
            <td width="60%"><b>{{ $project_name }}</b> {{$description}}...</td>
            <td>{{date('d-m-Y', strtotime($value['created_at']))}}</td>
        </tr>
    @endforeach
    @else
    <tr>
        <span><b>No records found</b></span>
    </tr>
    @endif
    </tbody>
</table>
<span class="paginate-content search_pagination">
{{ $dsrs->appends(\Request::except('page'))->render() }}
</span>
</div>
