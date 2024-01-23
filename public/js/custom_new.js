$(document).ajaxStart(function () {
   if(!$(".remove-inventory-rows").val()){
   $('#loader-body').fadeIn(); 
   }

});
$(document).ajaxStop(function () {
    $('#loader-body').fadeOut();
      $(window).scrollTop(0);
});

 
/*additional validation for form*/
jQuery.validator.addMethod("alphaNumericRegex", function(value, element) {
    return this.optional(element) || /^[a-zA-Z0-9\ \s]+$/i.test(value);
}, "This field must contain only letters ,numbers & space.");

jQuery.validator.addMethod("alphaRegex", function(value, element) {
    return this.optional(element) || /^[a-z\ \s]+$/i.test(value);
}, "This field must contain only letters & space.");

jQuery.validator.addMethod("numericRegex", function(value, element) {
    return this.optional(element) || /^[0-9\ \s]+$/i.test(value);
}, "This field must contain only numbers.");

$.validator.addMethod("validEmailCheck", function (value, element) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    if (value !== '') {
        return pattern.test(value);
    }
    return true;
}, 'Please enter a valid email address.');

$.validator.addMethod("strongPassword", function (value, element) {
    var pattern = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/;
    if (value !== '') {
        return pattern.test(value);
    }
    return true;
}, 'Password must be at least 8 characters and contains at least 1 lowercase letter, 1 uppercase letter, 1 digit and 1 special character.');

jQuery.validator.addMethod("lettersonly", function(value, element) {
return this.optional(element) || /^[a-z]+$/i.test(value);
}, "This field must contain only letters. White space and digits are not allowed."); 

var btsearch = {
    init: function(search_field, searchable_elements, searchable_text_class) {
       $(search_field).keyup(function(e){
          e.preventDefault();
          var query = $(this).val().toLowerCase();   
          if(query){
             // loop through all elements to find match
            $.each($(searchable_elements), function(){
                var title = $(this).find(searchable_text_class).text().toLowerCase();
                if(title.indexOf(query) == -1){
                   $(this).hide();
                } else {
                   $(this).show();
                }
             });
          } else {
             // empty query so show everything
             $(searchable_elements).show();
          }
       });
    }
   }
   // INIT
    $(function(){
   // USAGE: btsearch.init(('search field element', 'searchable children elements', 'searchable text class');
    btsearch.init('#ccuserserch', 'tr td', 'li');
    });

    $('#change_status_it_ticket').change(function(){
        if($(this).val()){
            var ticket_id = $('#ticket_id').val();
            var status = $(this).val();
            var url = '/it-tickets/change-status?ticket_id=' + ticket_id + '&status=' + status;
            window.location.href = url;
        }else{
            $('.change_status_it_ticket').hide();
        }
    })
    $('#change_status_harmony_ticket').change(function(){
        if($(this).val()){
            var ticket_id = $('#ticket_id').val();
            var status = $(this).val();
            var url = '/tickets/change-status?ticket_id=' + ticket_id + '&status=' + status;
            window.location.href = url;
        }else{
            $('.change_status_harmony_ticket').hide();
        }
    })

    function replyDeleteAttachment(id,reply_id){
        // Show confirmation dialog box
        if (confirm('Are you sure you want to delete this attachment?')) {
            $("#loader-body").fadeIn();
            var url = '/ticket/replies/delete-attachment/'+ id;
            $.ajax({
                type: 'get',
                dataType: 'html',
                url: url ,
                success: function (response) {
                    localStorage.setItem('reply_attachment_deleted', reply_id);
                    location.reload();
                }
            });
        }
    }

