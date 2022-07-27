<?php include 'db_connect.php' ?>
<?php
session_start();
if(isset($_GET['id'])){
$qry = $conn->query("SELECT b.*,t.name,t.owner from billing b inner join tenants t on t.id = b.tenant_id  where b.id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-billing">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
		<div class="row form-group">
			<div class="col-md-6">
					<label for="" class="control-label">Tenant</label>
					<select name="tenant_id" id="" class="custom-select select2">
						<option value=""></option>
				<?php 
					$tenant = $conn->query("SELECT * FROM tenants order by name asc");
					while($row= $tenant->fetch_assoc()):
				?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($tenant_id) && $row['id'] == $tenant_id ? 'selected' : '' ?> <?php echo isset($id) && $tenant_id != $row['id'] ? "disabled" :'' ?>><?php echo ucwords($row['name']." | ".$row['owner']) ?></option>
				<?php endwhile; ?>
					</select>
			</div>
			<div class="col-md-6">
					<label for="" class="control-label">Billing Date</label>
					<input type="month" value="<?php echo isset($billing_date) ? date('Y-m',strtotime($billing_date)) : date("Y-m") ?>" class="form-control" name="billing_date" <?php echo isset($id) ? "readonly" :'' ?> >
			</div>
		</div>
		<hr>
		<div class="row" id="prev_details">
			
		</div>
		
	</form>
</div>
<script>
	$(".select2").select2({
		placeholder:"Please select here",
		width:'100%'
	})
	$("[name='tenant_id'],[name='billing_date']").change(function(){
		if($("[name='tenant_id']").val() == '' || $("[name='billing_date']").val() == '')
			return false;
		get_det()
	})
	function get_det(){
		start_load()
			$.ajax({
			url:'get_prev_details.php',
			method:"POST",
			data:{id:$("[name='tenant_id']").val(),date:$("[name='billing_date']").val(),bid:'<?php echo isset($id) ? $id : '' ?>'},
			success:function(resp){
				if(resp){
					$('#prev_details').html(resp)
					end_load()
				}
			}
		})
	}
	$('#manage-billing').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_billing',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				resp=JSON.parse(resp)
				if(resp.status==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				
			}
		})
	})
	$(document).ready(function(){
		if('<?php echo isset($id) ?>' == 1)
			get_det()
	})
</script>