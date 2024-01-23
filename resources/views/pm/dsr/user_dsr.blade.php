@extends('layouts.page')
@section('content')

@include('common.sidebar.sidebar_pm')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="{{ URL('dashboard') }}">
        <em class="fa fa-home"></em>
      </a></li>
      <li class="active">DSRs List</li>
    </ol>
  </div><!--/.row--> 

  <div class="row"> 
    <div class="col-lg-12">
      <h1 class="page-header">DSRs List</h1>
    </div>
  </div><!--/.row-->

  <div class="row">
    <div class="col-md-5">
      <div class="panel panel-default">
        <div class="panel-heading dsr-panel">
           DSRs List
            @include('common.dsr_search')
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper dsr-details-list">
            <div class="table-responsive">
              <table class="table">
                  <tbody id="dsr_tbody">
                      @foreach($dsrs as $value) 
                        <?php 
                          $project_name = 'N-A';
                          $description = '';
                          $highlight = ($value['read']->count() && $value['read'][0]['is_read'] == 1) ? 0:1;

                          if($value['details']->count()){
                            
                            if($value['details'][0]['project']){
                              $project_name = $value['details'][0]['project']['name'];
                            }
                            $description = substr($value['details'][0]['description'], 0, 20);
                          }
                        ?>
                        <tr class="dsr-point {{$highlight ? 'highlight' : ''}}" id="dsr_{{$value['en_id']}}">
                          <td width="20%"><b>{{ $value->user ? $value->user->full_name : 'N-A' }}</b> </td>
                          <td width="60%"><b>{{ $project_name }}</b> {{$description}}...</td>
                          <td>{{date('d-m-Y', strtotime($value['created_at']))}}</td>
                        </tr>
                      @endforeach

                      @if(!$dsrs->count())
                        <tr>
                          <span><b>No records found</b></span>
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
      </div>
    </div>
    <div class="col-md-7">
      <div class="panel panel-default" id="dsr_detail_panel">
        <div class="panel-heading dsr-panel">
          DSR Details
        </div>
        <div class="panel-body">
          <div class="canvas-wrapper dsr-details-content">
            
          </div>
          <div id="sent-details-data" style="display: none;cursor: pointer">See Details</div>
          <div id="sent-details" style="display: none;"></div>
        </div>
      </div>
    </div>
  </div><!--/.row-->

  @include('common.attachment_viewer')


</div>  <!--/.main-->
 

<script> 
$(document).ready(function(){

  $(document).on('click', '#sent-details-data', function(){
    $('#sent-details').toggle();
  });
 
  $(document).on('click', '.dsr-point', function(){
    $('.dsr-point').removeClass('active-row');
    $(this).addClass('active-row');
    $(this).removeClass('highlight');
      $('#sent-details-data').show();
      $('#sent-details').hide();

    var getId = $(this).attr('id');
    getId = getId.split('_')[1];
    getDsrdetails(getId);
  });

  function getDsrdetails(getId){
    
    if (!getId) return;
    let app_url = '{{ url("/") }}';

    var tpl =''
        i = 1,
        j = 1,
        time = 0,
        $dsrContent = $('.dsr-details-content'),
        $dsrSentDetails = $('#sent-details'),
        toEmails = '',
        ccEmails = '';

    $.get(app_url+ '/get_des_details/' + getId, function(success){

      if(success !=''){
    
        $.each(success.details, function(index, project) {

          tpl += '<div><b>' + i +'. '+ ((project.project) ? project.project.name : 'N-A')  + '</b></div>';
          if(!project.details || !project.details.length){
            
            i++;
            return;
          }

          j=1
          $.each(project.details, function(i, v){ 

            time += parseInt(v.total_hours);

            var totalHours = (v.total_hours  > 1) ? v.total_hours + ' Hrs' : v.total_hours  +' Hr' ;
            
            tpl += '<div class="col-md-12"><p><b>Task</b></p><div class="col-md-12"><div class="col-md-8"><p style="text-align:justify;">'+j+'. ' + v.description.replace(/\n/g , '<br />') + '</p></div><div class="col-md-4"><p class="text-right" ><b>Time Estimate:</b> ' + totalHours  + '</p></div></div></div>';
            j++;
          });

          i++;
        });
        var total = (time  > 1) ? time + ' Hrs' : time  +' Hr' ;
        tpl += '<div class="col-md-12" style="padding-left:0px"><p class="text-right" style="width:100%; border:1px dashed #CCC; padding:10px;"><b>Total Time Estimate:</b> ' + total + '</p></div>';
        // tpl += '<div class="col-md-12"><p class="text-right" padding:10px;"><b>Total Time Estimste:</b> ' + time + '</p></div>';

        // tpl += '<div class="col-md-12" style="padding-left:0px"><p class="text-right" style="width:100%; border:1px dashed #CCC; padding:10px;"><b>Total Time Estimste:</b> ' + time + '</p></div>';
          // tpl += '<div class="col-md-12"><p class="text-right" padding:10px;"><b>Total Time Estimste:</b> ' + time + '</p></div>';

        tpl += '<p><b>Attachments: </b>';
        $.each(success.files, function(k, f){
          tpl += '<br><a class="display_attachment" data="'+f.path_name+'" data-name="'+f.original_name+'" href="javascript:void(0)"><i class="fa fa-paperclip" aria-hidden="true"></i> '+f.original_name +'</a>';
        });

        if(!success.files || !success.files.length){
          tpl += 'N-A</p>';
        }
        
        $.each(success.to_users, function(k, user){

          toEmails +=  user.email + ', ';
        });

        $.each(success.cc_users, function(k, user){

          ccEmails +=  user.email + ', ';
        });

        toEmails = toEmails.slice(0, -2)
        ccEmails = ccEmails.slice(0, -2)
        ccEmails = (ccEmails) ? ccEmails : 'N-A'
        $dsrContent.html(tpl);
        $dsrSentDetails.html('To: '+toEmails+'<br />Cc: '+ccEmails);
      } else {
    
        $dsrContent.html('<center>No Records Found.</center>');
      }
    });
  }


  $('#dsr_detail_panel').on('click', '.display_attachment', function(){

      var fileName = $(this).attr('data'),
          href = "<?php echo URL::asset('download'); ?>/"+fileName;
          dispName = $(this).attr('data-name')
          fileParts = fileName.split('.'),
          extensionType = getExtensionType(fileParts[fileParts.length - 1]),
          fileSrc = "<?php echo URL::asset('storage/dsrs'); ?>/"+fileName,
          thisImg = $('#attachment-popup img'),
          thisIframe = $('#attachment-popup iframe'),
          thisHeading = $('#attachment-popup h3#file-name'),
          thisDwnldLink = $('#download-file-attachment');
          

      // $filePublicUrl = "echo public_path('storage/dsrs');"+fileName;

      thisImg.hide();      
      thisIframe.hide();

      if(extensionType == 0){

        console.log("File type not supported");
        return;
      }

      dispName = dispName.length > 30 ? dispName.substring(0,30)+'...' : dispName; 
      thisHeading.html(dispName);

      if(extensionType == 1){

        thisImg.attr('src', fileSrc);
        thisImg.show();
      }

      if(extensionType == 2){

        thisIframe.attr('src', 'https://docs.google.com/viewer?url='+fileSrc+'&embedded=true')
        thisIframe.show();
      }

      thisDwnldLink.attr('href', href);

      $('#attachment-popup').modal('show');

    });


    function getExtensionType(ext){

      var imgExts = ['jpg', 'jpeg', 'png'],
          docExts = ['doc', 'docx', 'pdf', 'xls', 'csv'];

      return imgExts.includes(ext) ? 1 : ( docExts.includes(ext) ? 2 : 0 ); 

    }


});

</script>

@endsection
