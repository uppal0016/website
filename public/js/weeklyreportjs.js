$(document).ready(function(){
    
    $(document).on('click', '#sent-details-data', function(){
      $('#sent-details').toggle();
    });
    
    
    $(document).on('click', '.dsr-point', function(){
        $('.employee_loader').show();

        $('.dsr-point').removeClass('active-row');
      $(this).addClass('active-row');
      $(this).removeClass('highlight');
      $('#sent-details-data').show();
      $('#sent-details').hide();
    
      var getId = $(this).attr('id');
      getId = getId.split('_')[1];
      $('#set_dsr_id').val(getId);
      $('#add-dsr-comment').attr('data', 'dsr_'+getId);
      $('#report_detail_view').show();
      getReportdetails(getId);
    });
    
    
    function getReportdetails(getId){
      //$('.loader').show();
    
      if (!getId) return; 
      var tpl =''
          roleId = role_id,
          i = 1,
          j = 1,
          $dsrContent = $('.dsr-details-content'),
          $dsrSentDetails = $('#sent-details'), 
          toEmails = '',
          ccEmails = '';
    
      $.get(app_url+ '/get_report_details/' + getId, function(success){
    
        if(success !=''){
      
          $.each(success.details, function(index, project) {
    
            tpl += '<div><b>Project: ' +  ((project.project) ? project.project.name : 'N-A')  + '</b></div>';
            if(!project.details || !project.details.length){
              
              i++;
              return;
            }
    
            j=1
            $.each(project.details, function(k, v){   
              tpl += '<div class="col-md-12"><p style="text-align:justify;">'+ v.description.replace(/\n/g , '<br />') + '</p></div></div>';
              j++;
            });
            i++;
          });
    
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
          // $('.employee_loader').hide();

      });
    }


    //search jquery
    var timer;
		$('#searchDsr').keyup(function() {
	    var _this = $(this);
	   	
	    $('.paginate-content').hide();
	    
	    clearTimeout(timer);

	    timer = setTimeout(function(){

	      search(_this.val());
	    },1000);
	    
	  }); 

	  function search(search) {
		$('.loaderList').show();

	    var row = '';

	    $.ajax({
            url:url+"&search="+search,
	        method:'GET',
	        success: function(response){
			$('.loaderList').hide();

	        if(!response.success) return;

	        var data = response.data;

	        $.each(data, function(key, value){

	          row += '<tr class="dsr-point '+(value.highlight == 1 && sentCase != 1 ? 'highlight':'') +'" id="dsr_'+value.en_id+'">'+ (sentCase == 1 ? '' : '<td width="20%"><b>'+value.user_full_name+'</b> </td>') +'<td width="'+(sentCase == 1 ? '70' : '60')+'%"><b>'+value.project_name+' </b>'+(sentCase == 1 ? '<br>' : '')+value.description+'...</td><td>'+ moment(new Date(value.created_at)).format('DD/MM/YYYY') +'</td></tr>';
	        });

	        if(row === '') row = '<center>No Records Found.</center>';

	        $('#dsr_tbody').html(row);

	        $('.dsr-details-content').html('');
            $('#report_detail_view').hide();

	      },
	      error: function(error){
	        console.log('error', error)
	      }
	    });
	  }
});