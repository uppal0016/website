$(document).ready(function(){
    $(document).on('click', '#sent-details-data', function(){
        $('#sent-details').toggle();
    });

    $(document).on('click', '#add-dsr-comment', function(){
        var dsrId = $(this).attr('data'),
            popupBody = $('#chatbox-popup .modal-body'),
            chtml = '';

        dsrId = dsrId.split('_')[1];
        $('#set_dsr_id').val(dsrId);
        //console.log(dsrId);
        $.ajax({
            url:app_url + "/comments/"+dsrId,
            method:'GET',
            success: function(response){
                if(!response.data || !response.data.length){
                    chtml += '<h4 class="text-center">There are no comments present for this DSR.</h4>';
                };

                response.data.forEach(function(comment,k){

                    if(user_id == comment.user_id){
                        //<p><small>'+moment(comment.created_at).format('MMMM Do YYYY, h:mm a')+'</small></p>
                        //receiver
                        chtml += '<div class="text-right receiver"><h5><b>' + (comment.user ? comment.user.first_name+' '+comment.user.last_name:"Unknown User") + '</b></h5><p style="margin-bottom:0px">' + comment.comment + '</p><p><small><i>'+moment(comment.created_at).format('MMMM Do YYYY, h:mm a')+'</i></small></p></div><br>';
                    }else{

                        //sender
                        chtml += '<div class="text-left sender"><h5><b>' + (comment.user ? comment.user.first_name+' '+comment.user.last_name:"Unknown User") + '</b></h5><p style="margin-bottom:0px">' + comment.comment + '</p><p><small><i>'+moment(comment.created_at).format('MMMM Do YYYY, h:mm a')+'</i></small></p></div><br>';
                    }

                });

                popupBody.html(chtml);
            },
            error: function(response){}
        });
        $('#add-comment-form').trigger('reset');
        $('#chatbox-popup').modal('show');
        $('#set_dsr_id').val(dsrId);
    });
$(function(){ 
   $("#dsr_lastid").trigger('click'); 
});
 $(document).on('click', '#dsr_lastid', function(){  
    var getId =  $(this).last().attr("lastid");    
    if(getId){
     $('#dsr_detail_view').show();
     getDsrdetails(getId);
            }    
  });

    $(document).on('click', '.dsr-point', function(){
        // $('.employee_loader').show();
        $('.dsr-point').removeClass('active-row');
        $(this).addClass('active-row');
        $(this).removeClass('highlight');
        $('#sent-details-data,#add-dsr-comment').show();
        $('#sent-details').hide();

        var getId = $(this).attr('id');
        getId = getId.split('_')[1];
        $('#set_dsr_id').val(getId);
        $('#add-dsr-comment').attr('data', 'dsr_'+getId);
        $('#dsr_detail_view').show();
        getDsrdetails(getId);
    });

    function checkMeridian(time){
        if(time !== null){
            var explode_time = time.split(':');
            if(explode_time[0] >= 12){
                return 'pm';
            }else{
                return 'am';
            }
        }
        return '';

    }

    function showTime(time){
        if(time !== null){
            return time;
        }
        return '';

    }
    
    function getDsrdetails(getId){
        //$('.loader').show();
        if (!getId) return;
        var tpl =''
        roleId = role_id,
            i = 1,
            j = 1,
            time = 0,
            $dsrContent = $('.dsr-details-content'),
            $dsrSentDetails = $('#sent-details'),
            toEmails = '',
            ccEmails = '';

        $.get(app_url+ '/get_dsr_details/' + getId, function(success){
     
            if(success !=''){

                $.each(success.details, function(index, project) {

                    tpl += '<div class="row"><div class="col-12"><h4 class="mb-3">Project: ' +  ((project.project) ? project.project.name : 'N-A')  + '</h4></div></div>';
                    if(!project.details || !project.details.length){
                        i++;
                        return;
                    }
                    j=1 ;
                    $.each(project.details, function(k, v){
                        // time += parseFloat(v.total_hours);
                        time += getMinutes(v.hours, v.minutes);
                        var totalHours = '';
                        if(v.hours != 0)
                            totalHours = (v.hours  > 1) ? v.hours + ' Hrs ' : v.hours  +' Hr ' ;

                        if(v.minutes != 0)
                            totalHours += (v.minutes > 1) ? v.minutes + ' Mins' : v.minutes  +' Min' ;


                        tpl += '<div class="row mb-4 dsr-details-list"><div class="col-xl-12">';
                        // tpl += '<h5>'+j+'. '+v.task+'</h5>';
                        tpl += '<div class="time_estimate">';
                        tpl += '<p class="m-0 text-left"><strong>Time: </strong>'+ showTime(v.start_time) +' '+checkMeridian(v.start_time) +' - '+showTime(v.end_time) +' '+ checkMeridian(v.end_time) +'</p>';
                        tpl += '<p class="m-0 text-right"><strong>Time Estimate: </strong>'+ totalHours  +'</p>';
                        tpl += '</div>';
                        tpl += '<p class="m-0">'+ v.description.replace(/\n/g , '<br />') +'</p>';
                        tpl += '</div>';

                        tpl += '</div>';
                        j++;
                    });
                   
                    i++;
                });

                time = getHoursFromMinutes(time);

                var total = '';

                if(time['hours'] == 0 && time['minutes'] == 0){
                    total = "0 Hrs";
                }else{
                    if(time['hours'] != 0)
                        total = (time['hours'] > 1) ? time['hours'] + ' Hrs ' : time['hours']  +' Hr ' ;

                    if(time['minutes'] != 0)
                        total += (time['minutes'] > 1) ? time['minutes'] + ' Mins' : time['minutes']  +' Min' ;
                }
                tpl += '<div class="estimated-total--time"><strong>Total Time Estimate:</strong> '+total+'</div>';
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
                $('#dsrid').val(getId);
                $('.dsr_id').html('<span class="error_'+getId+'" style="color:red"></span>');

                if (success.status == 0 && success.dsr_rejection_reason) {
                    $('.rejection-reason-btn').removeClass('disable')
                        .attr('data-rejection-reason', success.dsr_rejection_reason)
                        .attr('data-dsr-id', getId);

                        $(document).on('click', '.rejection-reason-btn', function () {
                            var rejectionReason = success.dsr_rejection_reason;
                            $('#rejectionReasonContent').text(rejectionReason);
                            $('#rejectionReasonModal').modal('show');
                        });

                } else {
                    $('.rejection-reason-btn').addClass('disable');
                }

                 if(success.status ==1){                             
                 $('.Approve').addClass('disable'); 
                 $('.btn-danger').removeClass('disable');                         
                 }else if(success.status == 0){                                          
                 $('.btn-danger').addClass('disable');
                 $('.Approve').removeClass('disable'); 
                 }
                 else if(success.status == 2){                                          
                 $('.Approve').removeClass('disable');      
                 $('.btn-danger').removeClass('disable');   
                  }
                    
                  $dsrSentDetails.html('To: '+toEmails+'<br />Cc: '+ccEmails);
            } else {

                $dsrContent.html('<center>No Records Found.</center>');
            }
            // $('.employee_loader').hide();
        });
    }

    $(document).on('click','.display_attachment',  function(){
        console.log("clicked");
        var download_link = '<?php echo URL::asset("download"); ?>';
        var fileName = $(this).attr('data'),
            href = app_url+"/download/files/"+fileName;
        dispName = $(this).attr('data-name')
        fileParts = fileName.split('.'),
            extensionType = getExtensionType(fileParts[fileParts.length - 1]),
            fileSrc = app_url+"/download/files/"+fileName,
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
            docExts = ['doc', 'docx', 'pdf', 'xls', 'csv', 'txt'];

        return imgExts.includes(ext) ? 1 : ( docExts.includes(ext) ? 2 : 0 );
    }


    function getMinutes(h, m){
        return (parseInt(h)*60) + parseInt(m)+1;
    }


    function getHoursFromMinutes(m){

        if(!m){
            return {hours:0, minutes:0};
        }

        m = parseInt(m);
        var hours = parseInt(String(m/60).split('.')[0]),
            minutes = m%60;

        return {hours:hours, minutes:minutes};
    }

});


$(document).ready(function(){

    var timer;

    $('#searchDsr').keyup(function() {
        var _this = $(this);
        clearTimeout(timer);

        
        timer = setTimeout(function(){

            search(_this.val());
        },1500);

    });

    $(document).on('click', '.search_pagination a', function(event){
        event.preventDefault();
        var search_url = $(this).attr('href').split('search=')[1];
        search(search_url);
    });

     function search(search) {
        if(search == ''){
            window.location.reload();
        }
        // $('.loaderList').show();
        var row = '';

         $.ajax({
            url:url+"&search="+search,
            method:'GET',
            success: function(response){
                // $('.loaderList').hide();
/*
                if(!response.success) return;
*/
                $('.dsr-details-list').empty();
                $('.dsr-details-list').html(response);
                $('#dsr_detail_view').hide();
                /*var data = response.data;

                $.each(data, function(key, value){

                    row += '<tr class="dsr-point '+(value.highlight == 1 && sentCase != 1 ? 'highlight':'') +'" id="dsr_'+value.en_id+'">'+ (sentCase == 1 ? '' : '<td width="20%"><b>'+value.user_full_name+'</b> </td>') +'<td width="'+(sentCase == 1 ? '70' : '60')+'%"><b>'+value.project_name+' </b>'+(sentCase == 1 ? '<br>' : '')+value.description+'...</td><td>'+ moment(new Date(value.created_at)).format('DD/MM/YYYY') +'</td></tr>';
                });

                if(row === '') row = '<center>No Records Found.</center>';

                $('#dsr-point').html(row);

                $('.dsr-details-content').html('');
                $('#dsr_detail_view').hide();*/

            },
            error: function(error){
                console.log('error', error)
            }
        });
    }
});

function DsrStatusUpdate(status){   
 var id = $("#dsrid").val();
 var dsr_rejection_reason = $("#dsr_rejection_reason").val().trim();
 if(dsr_rejection_reason == '' && status == 0){  
 $('#dsr_rejection_reason_error').html('Dsr rejection is required');
 $('#dsr_rejection_reason').keyup(function() {
 $('#dsr_rejection_reason_error').html('');
 });
 $('#myModal').on('hidden.bs.modal', function (e) {
    $('#dsr_rejection_reason_error').html('');
});
$('.modal-backdrop').on('click', function (e) {
    $('#dsr_rejection_reason_error').html('');
});
 return false;
 }
  $.ajax({
       headers: {
       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       },              
               url: app_url + "/admin/DsrStatusUpdate",
               method: 'post',
               data: {
                  dsrid: id,            
                  status:status,
                  dsr_rejection_reason:dsr_rejection_reason
               },
               success: function(result){               
                   $(window).scrollTop(0);
                    $(".realtimeststus_"+result.dsrid).html(result.status); 
                    if(result.statusvalue ==1){                             
                    $('.btn-primary').addClass('disable'); 
                    $('.btn-danger').removeClass('disable');                                                
                    }else if(result.statusvalue == 0){                                           
                    $('.btn-danger').addClass('disable');
                    $('.btn-primary').removeClass('disable');                                     
                    }
                    else if(result.statusvalue == 2){                                          
                    $('.btn-primary').removeClass('disable');      
                    $('.btn-danger').removeClass('disable');                                  
                     }
                   
                   $('#myModal').modal('hide');

               }});
            }
//chat box js
$('#add-comment-form').on('submit', function(event){

    var thisForm = $(this),
        popupBody = $('#chatbox-popup .modal-body'),
        chtml = '';

    thisForm.validate({
        rules: {
            'comment': {
                required: true
            }
        },
        messages: {
            'comment': {
                required: 'Please enter something'
            }
        }
    });

    event.preventDefault();

    if(!thisForm.validate().form()) {
        console.log('not valid');
        return false;
    }

    var data = {
        'dsr_id': ''
    };
    $.each(thisForm.serializeArray(), function(i, field) {

        data[field.name] = field.value;
    });
    const csrf_token = $('meta[name="csrf-token"]').attr('content');
    data['_token'] = csrf_token;
    //console.log(data);
    $.ajax({
        "url":app_url + "/comments",
        "method":'POST',
        "data":data,
        success: function(res){
            $.ajax({
                url:app_url + "/comments/"+data.dsr_id,
                method:'GET',
                success: function(response){
                    if(!response.data || !response.data.length){
                        chtml += '<h4 class="text-center">There are no comments present for this DSR.</h4>';
                    };

                    response.data.forEach(function(comment,k){

                        if(user_id == comment.user_id){
                            //<p><small>'+moment(comment.created_at).format('MMMM Do YYYY, h:mm a')+'</small></p>
                            //receiver
                            chtml += '<div class="text-right receiver"><h5><b>' + (comment.user ? comment.user.first_name+' '+comment.user.last_name:"Unknown User") + '</b></h5><p style="margin-bottom:0px">' + comment.comment + '</p><p><small><i>'+moment(comment.created_at).format('MMMM Do YYYY, h:mm a')+'</i></small></p></div><br>';
                        }else{

                            //sender
                            chtml += '<div class="text-left sender"><h5><b>' + (comment.user ? comment.user.first_name+' '+comment.user.last_name:"Unknown User") + '</b></h5><p style="margin-bottom:0px">' + comment.comment + '</p><p><small><i>'+moment(comment.created_at).format('MMMM Do YYYY, h:mm a')+'</i></small></p></div><br>';
                        }

                    });

                    popupBody.html(chtml);
                },
                error: function(response){}
            });

            thisForm.trigger('reset');

        },
        error: function(error){
            $.toaster({
                priority : 'danger',
                title : 'Error',
                message : error.responseJSON.message
            });
        }
    });

});