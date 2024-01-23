
<span class="w-75">
	<input placeholder="Search by description and project name" type="text" id="searchDsr" name="search_dsr" class="form-control" />
</span>
  <?php 
  	$current_uri = Route::getFacadeRoot()->current()->uri();
  	$sentCase = in_array($current_uri, ['sent_dsr']) ? 1 : 0; 
  ?>

<script>
	var sentCase = {{$sentCase}}
	var url = "<?php echo url( ((Auth::user()->role_id == 1) ? 'admin' : ( (Auth::user()->role_id == 2) ? 'admin' :  ( (Auth::user()->role_id == 3 || Auth::user()->role_id == 4 || Auth::user()->role_id == 5 ? '' : '' ) ) ) ) ).'/dsr_s/search_dsrs?id='.$enId.( in_array($current_uri, ['sent_dsr', 'dsrdetail']) ? '&view='.$current_uri : '') ; ?>"
</script>
