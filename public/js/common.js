var site_url = jQuery('#site_url').val();
jQuery(document).ready(function(){
  jQuery('[data-toggle="tooltip"]').tooltip();

  // one check box checked
  jQuery(document).on('click', '.oneChecked', function (eve) {
    $('.oneChecked').not(this).prop('checked', false);
  });

  // valid number
  jQuery(document).on('keypress', '.validNumber', function (eve) {
    if (eve.which == 0) {
      return true;
    } else {
      if (eve.which == '.') {
        eve.preventDefault();
      }
      if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57)) {
        if (eve.which != 8)
        {
          eve.preventDefault();
        }
      }
      $('.validNumber').keyup(function (eve) {
        if ($(this).val().indexOf('.') == 0) {
          $(this).val($(this).val().substring(1));
        }
      });
    }
  });
  //valid decimal value
  jQuery(document).on('blur','.toFixed',function () {
    var num = parseFloat($(this).val());
    var cleanNum = num.toFixed(2);
    if (!isNaN(cleanNum)) {
      $(this).val(cleanNum);
    }else{
      // $(this).val(0.00);
    }
  });

  //allow only characters and hyphen in name fields
  jQuery('body').on('keypress', '.hypen', function (e) {
    var regex = new RegExp("^[0-9 \b\t\r\n\-]");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (!regex.test(str)) {
      e.preventDefault();
      return true;
    }
  });

  //allow only characters in name fields
  jQuery('body').on('keypress', '.charonly', function (e) {
    var regex = new RegExp("^[a-zA-Z\s\b ]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (!regex.test(str)) {
      e.preventDefault();
      return true;
    }
  });

  $(document).find('#datepicker1').datepicker({
    format: "yyyy-mm-dd",
    startDate: "2011-01-01",
    autoclose: true,
    todayHighlight: true,
    container: '#time-est-popup modal-header',
    onSelect: function(dateText) {
      console.log(dateText);
    }
  });

  $(document).find('#datepicker2').datepicker({
    format: "yyyy-mm-dd",
    startDate: "2011-01-01",
    autoclose: true,
    todayHighlight: true,
    container: '#time-est-popup modal-header',
    onSelect: function(dateText) {
      console.log(dateText);
    }
  });

  jQuery(".datepicker1").datepicker({
    dateFormat: "yyyy-mm-dd",
    todayBtn: "linked",
    autoclose: true,
    todayHighlight: true,
  }).on("changeDate", function (e) {
    var condition = {};
    var selectedDate = jQuery(this).val();
    jQuery('.inventory_item_filter').each(function() {
      var type =  jQuery(this).attr('rel').trim();
      var id =  jQuery(this).val().trim();
      if(id != '')
      condition[type] = id;
      else
      delete condition[type];
    });
    var dateAr = selectedDate.split('/');
    var selectedDate = dateAr[2] + '-' + dateAr[0].slice(-2)+ '-' + dateAr[1] ;
    condition['d_o_p'] = selectedDate;
    ajaxHit('inventory_item_filter','POST',ADMIN_URL+'/inventoryItem-search',JSON.stringify(condition));
  });

  /*============= pagination===============*/
  jQuery('body').on('click','.common .pagination a', function(){
    console.log("yesssss");
    jQuery('.pagination li.active').removeClass('active');
    jQuery(this).parent('li').addClass('active');
    var value = jQuery(this).val();
    var url = jQuery(this).attr('href');
    //var search = jQuery('.searchBox').val();
    var entriesperpage = jQuery('.entriesperpage :selected').val();
    jQuery.ajax({
      type      : 'GET',
      url       : url,
      cache     : false,
      headers   : {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      dataType  : 'html',
      data      : {value:value,/*search:search,*/entriesperpage:entriesperpage},
      success:function(data){
        jQuery('#paginationData').html(data);
        jQuery('.loader').hide();
        jQuery('.entriesperpage').val(entriesperpage);
      }
    });
    return false;
  });

  // jQuery('body').on('keyup', '.searchBox', function(){
  //   var entriesperpage = jQuery('.entriesperpage :selected').val();
  //   var value = jQuery(this).val();
  //   var daterange = $('#dates').val();
  //   if(value == '')
  //   value = '';
  //   jQuery.ajax({
  //     type    : 'GET',
  //     cache:    false,
  //     headers : {
  //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //     },
  //     url     : searchUrl,
  //     data    : {search:value,entriesperpage:entriesperpage,daterange:daterange},
  //     success:function(data){
  //       jQuery('#paginationData').html(data);
  //       jQuery('.loader').hide();
  //     }
  //   });
  // });

  jQuery('body').on('change', '.entriesperpage', function(){
    var entriesperpage = jQuery('.entriesperpage :selected').val();
    var search = jQuery('.searchBox').val();
    jQuery.ajax({
      type    : 'GET',
      cache:    false,
      headers : {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url     : searchUrl,
      data    : {search:search,entriesperpage:entriesperpage},
      success:function(data){
        jQuery('#paginationData').html(data);
        jQuery('.entriesperpage').val(entriesperpage);
      }
    });
  });
  /*============= pagination===============*/
  jQuery('body').on('click','.item_inv .pagination a', function(e){
    jQuery('.pagination li.active').removeClass('active');
    jQuery(this).parent('li').addClass('active');
    var value = jQuery(this).val();
    var url = jQuery(this).attr('href');
    var urlObject = new URL(url);
    jQuery('.inventory_item_filter').each(function() {
      var type =  jQuery(this).attr('rel').trim();
      var id =  jQuery(this).val().trim();
      if(type == 'd_o_p' && id != '')
      {
        var dateAr = id.split('/');
        var id = dateAr[2] + '-' + dateAr[0].slice(-2)+ '-' + dateAr[1] ;
      }
      if(id != '')
      condition[type] = id;
      else
      delete condition[type];
    });
    jQuery.ajax({
      type      : 'GET',
      url       : url,
      cache     : false,
      headers   : {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      dataType  : 'html',
      data      : {value:value,condition:condition},
      success:function(data){
       var section = urlObject.searchParams.get("section");
        if(section){
          if(section == "current_manage_document"){
            jQuery('#dynamicContent').html(data);
          }else if(section == "archive_manage_document"){
            jQuery('#dynamicContentFavorite').html(data);
          } else{
            jQuery('#dynamicContentNew').html(data);
          }
        }else{
          jQuery('#dynamicContent').html(data);
        }
        jQuery('.loader').hide();
        jQuery(this).attr(PM_URL+'inventory_item');
      }
    });
    return false;
  });
  // reset model
  jQuery('.modal').on('hidden.bs.modal', function (e) {
    jQuery('.form-control').removeClass('has-error');
    jQuery('.form-group.has-error').removeClass('has-error');
    jQuery('label.has-error').remove();
    jQuery('.form-control').removeClass('error');
    jQuery('.form-group.error').removeClass('error');
    jQuery('label.error').remove();
    jQuery('.alert').css('display','none');

    var  id  = jQuery(this).attr('id');
    jQuery('#'+id).find('input[type="text"]').val('');
    jQuery('#'+id).find('input[type="file"]').val('');
    jQuery('#'+id).find('input[type="password"]').val('');
    jQuery('#'+id).find('input[type="hidden"]').val('');
    jQuery('#'+id).find('input[type="button"]').removeAttr('disabled');
    jQuery('#'+id).find('input[type="submit"]').removeAttr('disabled');
    jQuery('#'+id).find('button[type="button"]').removeAttr('disabled');
    jQuery('#'+id).find('button[type="submit"]').removeAttr('disabled');
    jQuery('#'+id).find('textarea').val('');
    jQuery('#'+id).find('select').val('');
    jQuery('.gallery').html('');
  });
  // success
  jQuery('body').on('keyup blur change', '.error,.success', function () {
    var text = jQuery(this).val();
    text = text.trim();
    var textPl = jQuery(this).attr('placeholder');
    if (text != '') {
     // jQuery(this).css('border', '1.8px solid #9d92b4');
    } else {
      jQuery(this).val('');
      //jQuery(this).css('border', '1.8px solid #F16969');
      jQuery(this).attr('placeholder', 'This field is required');
    }
  });

  /* change category show parameter functionality */
  jQuery('body').on('change', '.change_category', function () {
    var category_id = jQuery(this).val();
    var last_generate_id = jQuery('.last_generate_id').val();
    var category_text = $(".change_category :selected").text();
    if(category_text != 'Select Category')
    {
      category_text = 'TLGT-'+category_text.replace(/\s+/g, '-').toUpperCase()+'-'+last_generate_id;
      jQuery('.generate_id').val(category_text);
    }else {
      jQuery('.generate_id').val('');
    }
    var item_id = jQuery(this).attr('ref');
    var cat_id = jQuery('.cat_id').val();
    var formData = new FormData();
    formData.append('category_id', category_id);
    if(item_id)
    {
      formData.append('item_id', item_id);
      formData.append('selected_cat_id', cat_id);
    }
      var loc = window.location.href;
      if(loc.search('/admin/') != -1){
          ajaxHit('get_parameters','POST',ADMIN_URL+'/get_parameters',formData);
      }
      if(loc.search('/pm/') != -1){
          ajaxHit('get_parameters','POST',PM_URL+'/get_parameters',formData);
      }
  });

  jQuery('body').on('click', '.is_admin_cls', function () {
    var val = jQuery(this).val();
    if(val == 1)
    {
      jQuery('.permission_div').hide();
      $('input[name="permission[]"]').prop("checked", false);
    }
    else
    jQuery('.permission_div').show();

  });
});

function ajaxHit(requestType,type,action,data=null)
{
  //console.log(requestType);
  if(requestType == 'inventory_item_filter' || requestType == 'get_item_details' || requestType == 'get_parameters')
  {
    var rType = 'html';
  }else {
    var rType = 'json';
  }
  jQuery.ajax({
    url         : action,
    type        : type,
    data        : data,
    processData: false,
    contentType: false,
    cache: false,
    headers     : {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    dataType    : rType,
    beforeSend  : function () {
      jQuery(".loader").css('display','block');
    },
    complete: function () {
      jQuery(".loader").css('display','none');
    },
    success: function (response) {
      if(requestType == 'inventory_item_filter')
      {
        if(response)
        {
          jQuery('#dynamicContent').html(response);
          jQuery('#dynamicContent').css('display','block');
        }
      }

      if(requestType == 'get_item_details')
      {
        if(response)
        {
          jQuery('#myModal').html(response);
          jQuery('#myModal').modal('show');
          jQuery.getScript(BASE_URL+'/js/formValidate.js');
        }
      }

      if(requestType == 'inventory_dashboard_filter')
      {
        if (response && response.category_id) {
          var currentOrigin = window.location.origin;
          var assignedUrl = "/admin/inventory_item?" + "status=1&category_id=" + response.category_id;
          var spareUrl = "/admin/inventory_item?" + "status=0&category_id=" + response.category_id;
          var damageUrl = "/admin/inventory_item?" + "status=2&category_id=" + response.category_id;
          var scrapUrl = "/admin/inventory_item?" + "status=3&category_id=" + response.category_id;
        
          jQuery('#categoryId1').attr('href', currentOrigin + assignedUrl);
          jQuery('#categoryId2').attr('href', currentOrigin + spareUrl);
          jQuery('#categoryId3').attr('href', currentOrigin + damageUrl);
          jQuery('#categoryId4').attr('href', currentOrigin + scrapUrl);
        
          jQuery('.assigned_items').html(response.assigned_items);
          jQuery('.spare_items').html(response.spare_items);
          jQuery('.damage_items').html(response.damage_items);
          jQuery('.scrap_items').html(response.scrap_items);
        }
      }

      if(requestType == 'get_parameters')
      {
        if(response)
        {
          jQuery('.parameter_tr').remove();
          jQuery('.category_tr').after(response);
        }
        else
        jQuery('.parameter_tr').remove();
      }
    },
    error:function(response){
      var data =[];
      console.log(response);
      jQuery.each(response.responseJSON.errors,function(k,message){
        data['error'] = true;
        data['error_message'] = message;
        alert_message(data);
      });
    }
  });
}


$( document ).ready(function() {
    var time_in = $('#today_att_exists').val();
    var complete_attendance = $('#complete_attendance').val();
    if(time_in != ''){
        // console.log("time in value from php "+time_in);
        var time = ''/*new Date(time_in).toString()*/;
    }
    if(complete_attendance !=''){
        $("#time_in_button").css("display", "none");
        $("#time_out_button").css("display", "none");
        $('#timer_id').text("Today's Total Working Hours: "+complete_attendance);
    } else {
        if(time_in !=''){
            $("#time_in_button").css("display", "none");
            $("#time_out_button").css("display", "block");
            function myTimer()
            {

                var startTime = moment(time_in);
                var endTime = moment();
                var ms = endTime.diff(startTime);
                //var d = moment.duration(ms).format("HH:mm:ss");
                var s = moment.utc(moment.duration(ms,"s").asSeconds()).format("HH:mm:ss");
                $('#timer_id').text('Timing: '+s);

                //console.log(moment(time).fromNow());
                /*console.log("time in timer function "+new Date());
                var milli = (new Date() - new Date(time));
                var seconds = Math.floor((milli / 1000) % 60);
                var minutes = Math.floor((milli / (60 * 1000)) % 60);
                var hours = Math.floor((milli / (60*60 * 1000)) % 60);
                $('#timer_id').text('Timing: '+hours.padLeft()+ ':'+minutes.padLeft()+':'+seconds.padLeft());*/
            }
            setInterval(myTimer, 1000);
        }else{
            $("#time_out_button").css("display", "none");
        }
    }
  
});

Number.prototype.padLeft = function(base,chr){
  var  len = (String(base || 10).length - String(this).length)+1;
  return len > 0? new Array(len).join(chr || '0')+this : this;
}

/* Leave Start date and end date validation*/
$(document).ready(function(){
    var start = new Date();
    // set end date to max one year period:
    var end = new Date(new Date().setYear(start.getFullYear()+1));
      $("#fromDate").datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true,  
          startDate : start,
          endDate   : end
      }).on('changeDate', function (selected) {
          var minDate = new Date(selected.date.valueOf());
          $('#toDate').datepicker('setStartDate', minDate);
      });

      $("#toDate").datepicker({
          format: 'yyyy-mm-dd',
          autoclose: true,
          startDate : start,
          endDate   : end
      }).on('changeDate', function (selected) {
              var minDate = new Date(selected.date.valueOf());
              $('#fromDate').datepicker('setEndDate', minDate);
      });

      //Auto caret down on page load.(For active sub-menu)
      $(".submenu.active i.fa-caret-right").removeClass("fa-caret-right").addClass('fa-caret-down');
      //navbar dropdown js
      $('.submenu-toggle').on('click' , function(){
          if($(this).parent().hasClass('active')){
            $(this).parent().removeClass('active')
            $(this).find('i.ml-auto').removeClass("fa-caret-down").addClass('fa-caret-right');
          } else {
            $(this).parent().addClass('active')
            $(this).find('i.ml-auto').removeClass("fa-caret-right").addClass('fa-caret-down');
          }

      });

      $('#reporting_manager_one').on("change", function(){
        console.log("selected");
          var selected_manager = $(this).val();
          var selects = $('select[name*="reportingManager2"]');
          selects.find(":disabled").prop("disabled", false);
          selects.val('');
          selects.find("[value='" + selected_manager + "']").prop("disabled", true)
      });

      /*$('#reporting_manager_two').on("change", function(){
        console.log("selected");
        var selected_manager = $(this).val();
        var selects = $('select[name*="reportingManager"]');
        selects.find(":disabled").prop("disabled", false);
        selects.find("[value='" + selected_manager + "']").prop("disabled", true);
      });*/

      $('#check_same_address').on("change", function(){
        if(this.checked) {
            $('#permanent_address').val($('#address').val());
        }
      });



  });