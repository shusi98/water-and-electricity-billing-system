<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM tenants where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-tenant">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
			<div class="form-group">
				<label for="" class="control-label">Business Name</label>
				<input type="text" class="form-control" name="name"  value="<?php echo isset($name) ? $name :'' ?>" required>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Owner Name</label>
				<input type="text" class="form-control" name="owner"  value="<?php echo isset($owner) ? $owner :'' ?>" required>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Contact #</label>
				<input type="text" class="form-control" name="contact"  value="<?php echo isset($contact) ? $contact :'' ?>" required>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Block/s</label>
				<select name="block_ids[]" id="" class="custom-select select2" required multiple="">
					<option value=""></option>
					<?php 
						$establishment = $conn->query("SELECT * FROM block_locations where status = 1 ".(isset($block_ids) ? " or id in ($block_ids) " : '')." order by block asc");
						while($row= $establishment->fetch_assoc()):
					?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($block_ids) && in_array($row['id'],explode(",",$block_ids)) ? 'selected' : '' ?>><?php echo "Blk. ".$row['block'].", ".$row['floor']." Floor" ?></option>
					<?php endwhile; ?>
				</select>
			</div>
	</form>
</div>
<script>
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})
	$('#manage-tenant').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_tenant',
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
				else if(resp==2){
					$('#msg').html("<div class='alert alert-danger'>Name already exist.</div>")
					end_load()

				}
			}
		})
	})
</script>