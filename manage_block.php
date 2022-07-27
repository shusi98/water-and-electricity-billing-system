<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM block_locations where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-block">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
		<div class="form-group">
			<label for="" class="control-label">Block</label>
			<input type="text" class="form-control" name="block"  value="<?php echo isset($block) ? $block :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Floor</label>
			<input type="text" class="form-control" name="floor"  value="<?php echo isset($floor) ? $floor :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Monthly Rate</label>
			<input type="number" step="any" class="form-control text-right" name="rate"  value="<?php echo isset($rate) ? $rate :'' ?>" required>
		</div>
	</form>
</div>
<script>
	$('#manage-block').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_block',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				
			}
		})
	})
</script>