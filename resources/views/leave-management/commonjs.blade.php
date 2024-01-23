<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script>
   function reject(rejectid,status){
   
    if(status =='not_approved'){
      $('#viewModal').modal('hide');
      $(window).scrollTop(0);
          $('.alert-danger').show();        
          $('.alert-danger').html("You are Already Rejected!"); 
           setTimeout(function(){
         $(".ajax-danger-alert").fadeOut();
   
         }, 2000);      
          return false;
         
         }
      document.getElementById("leaveid").value = rejectid;    
      $('#myModal').modal('show'); 
      $('#viewModal').modal('hide');
    
       }
      
       function statusupdate(id,status,hidden_id){    
          if(status =='approved'){
          $('#viewModal').modal('hide');
          $(window).scrollTop(0);
          $('.alert-danger').show();
          $('.alert-danger').html("You are Already Approved!");
          setTimeout(function(){
        $(".ajax-danger-alert").fadeOut();
   
      }, 2000);
          return false;        
         }
        var rejectid = document.getElementById("leaveid").value ;
        var description = document.getElementById("description").value ;      
        $.ajax({
             headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },              
                     url: "{{ url('/leave/statusUpdate/') }}",
                     method: 'post',
                     data: {
                        acceptid: id,
                        rejectid:rejectid,
                        description:description,
                     
                     },
                     success: function(result){
                       $(window).scrollTop(0);                     
                      $(".response_"+result.id).html(result.response); 
                       $(".realtimeststus_"+result.id).html(result.status);                     
                        $('.ajax-success-alert').show();
                      $('.ajax-success-alert').html(result.flash_message);
                      $('#myModal').modal('hide'); 
                      $('#viewModal').modal('hide');                     
                           setTimeout(function(){
                       $(".ajax-success-alert").fadeOut();
   
                        }, 2000);
                       
                     }});
                  }
   function daterangeSearch(today){
     if(today){
     var from = document.getElementById("from").value = '' ;
     var to = document.getElementById("to").value  = ''; 
     }
     var from = document.getElementById("from").value ;
     var to = document.getElementById("to").value ;
    var status = document.getElementById("leave_status").value ;
    
            $.ajax({
                 headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },              
                         url: "{{url()->current()}}",
                         method: 'post',
                         data: {
                           from: from,
                            to:to, 
                            today: today, 
                            status: status,                        
                         
                         },
                         success: function(result){
                           $('#dynamicContent').html(result);
                         }});
                      }
   
                      function viewdetails (id){
                        $.ajax({
                 headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },              
                         url: "{{ url('/leave/show/') }}",
                         method: 'get',
                         data: {
                           id: id,                                      
                         
                         },
                         success: function(result){
                          
                           $('#details').html(result.html);
                           $('#details').html(result.html);
                           $('#viewModal').modal('show'); 
                         }});
                      }
   
     function myleavedaterange(){
   
     var from = document.getElementById("from").value ;
     var to = document.getElementById("to").value ;
    
            $.ajax({
                 headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 },              
                         url: "{{ url('/my/leave') }}",
                         method: 'post',
                         data: {
                           from: from,
                            to:to,                         
                         
                         },
                         success: function(result){
                           $('#dynamicContent').html(result);
                         }});
                      }
   
                     
                                         
   
   
    $(document).ready(function(){
       $("#from").datepicker({format: 'yyyy-mm-d',autoclose: true}).on('changeDate', (selected) => {
        var minDate = new Date(selected.date.valueOf());
        $('#to').val('');
        $('#to').datepicker({format: 'yyyy-mm-d',autoclose: true}).datepicker('setStartDate', minDate);
      
    });
       
      $('.searchButton').click(function(){
         $('#loader-body').fadeIn(); 
        var text = $('#employeSearch').val();
          var token = $('#token').val();
   $.ajax({
     headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },   
       method:"post",
       url: "{{url()->current()}}",
       data: {text: text,token:token},
       success: function(result) {     
      
        $('#dynamicContent').html(result);
        }
   });
      });      
      $( "#employeSearch" ).autocomplete({
        source: function( request, response ) {
          
          $.ajax({
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },   
           method:"get",
           url:"{{url('autocomplete')}}",
           data: {search: request.term},
           success: function( data ) {                   
             response(data);   
            }
     
   
       });
        },
    select: function (event, ui) {          
           $('#employeSearch').val(ui.item.value); 
           return false;
        }
      });    
  
   $('#from').change(function () {
   $('#to').attr('min', $(this).val());
   });
     
   
   });       
     
   
         
   
</script>