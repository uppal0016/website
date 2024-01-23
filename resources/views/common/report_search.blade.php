<span class="pull-right">
	<input placeholder="Search by Report Description and Project Name" type="text" id="searchDsr" name="search_dsr" class="form-control" />
</span>
  <?php 
  	$current_uri = Route::getFacadeRoot()->current()->uri();
  	$sentCase = in_array($current_uri, ['sent_dsr']) ? 1 : 0; 
  ?>
<script>
	var sentCase = {{$sentCase}}
	var url = "<?php echo url( '/report_s/search_reports?id='.$enId.( in_array($current_uri, ['sent_report', 'reportdetail']) ? '&view='.$current_uri : '') ) ;  ?>"

</script>

