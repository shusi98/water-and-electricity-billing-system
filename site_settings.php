<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * from system_configuration limit 1");
if($qry->num_rows > 0){
	foreach($qry->fetch_array() as $k => $val){
		$meta[$k] = $val;
	}
}
 ?>
<div class="container-fluid">
	
	<div class="card col-lg-12">
		<div class="card-body">
			<form action="" id="manage-settings">
				<div class="form-group">
					<label for="name" class="control-label">System Name</label>
					<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="contact" class="control-label">Contact</label>
					<input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? $meta['contact'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="address" class="control-label">Address</label>
					<textarea name="address" class="form-control"><?php echo isset($meta['address']) ? $meta['address'] : '' ?></textarea>
				</div>
				<div class="form-group">
					<label for="electricity_rate" class="control-label">Electricity Rate</label>
					<input type="text" class="form-control" id="electricity_rate" name="electricity_rate" value="<?php echo isset($meta['electricity_rate']) ? $meta['electricity_rate'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="water_rate" class="control-label">Water Rate</label>
					<input type="text" class="form-control" id="water_rate" name="water_rate" value="<?php echo isset($meta['water_rate']) ? $meta['water_rate'] : '' ?>" required>
				</div>
				<center>
					<button class="btn btn-info btn-primary btn-block col-md-2">Save</button>
				</center>
			</form>
		</div>
	</div>
	<style>
	img#cimg{
		max-height: 10vh;
		max-width: 6vw;
	}
</style>

<script>
	function displayImg(input,_this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        	$('#cimg').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
	$('.text-jqte').jqte();

	$('#manage-settings').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_settings',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			error:err=>{
				console.log(err)
			},
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.','success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})

	})
</script>
<style>
	
</style>
</div>