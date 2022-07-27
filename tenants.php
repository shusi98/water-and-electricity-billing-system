<?php include('db_connect.php');?>

<div class="container-fluid">
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}
</style>
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>List of Tenants</b>
						<span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_tenant">
					<i class="fa fa-plus"></i> Add Tenant</button>
					<button class="btn btn-success btn-block btn-sm col-sm-2 float-right mr-2 mt-0" type="button" id="print">
					<i class="fa fa-print"></i> Print</button>
				</span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Business Name</th>
									<th class="">Owner</th>
									<th class="">Contact #</th>
									<th class="">Block/s</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$blocks = $conn->query("SELECT * FROM block_locations");
								while($row= $blocks->fetch_assoc()){
									$blk[$row['id']] = "Blk. ".$row['block']." ".$row['floor']." Floor";
								}
								$tenants = $conn->query("SELECT * FROM tenants order by name asc");
								while($row=$tenants->fetch_assoc()):
								?>
								<tr>
									<!-- <th class="text-center">
										<div class="form-check">
										 	<input class="form-check-input position-static input-lg" type="checkbox" name="checked[]" value="<?php echo $row['id'] ?>">
									 	</div>
									</th> -->
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['owner']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['contact'] ?></b></p>
									</td>

									<td class="">
										 <p> 
										 	<b>
										 	<?php 
										 	$b = '';
										 		foreach(explode(",", $row['block_ids']) as $k => $v){
										 			if(!empty($b)){
										 				$b .= ", ".$blk[$v];
										 			}else{
										 				$b .= $blk[$v];
										 			}
										 		}
										 		echo $b;
										 	 ?>
										 	</b>
										 </p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_tenant" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_tenant" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_tenant').click(function(){
		uni_modal("New tenant","manage_tenant.php","mid-large")
	})
	
	$('.edit_tenant').click(function(){
		uni_modal("Edit tenant","manage_tenant.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_tenant').click(function(){
		_conf("Are you sure to delete this tenant?","delete_tenant",[$(this).attr('data-id')])
	})
	$('#check_all').click(function(){
		if($(this).prop('checked') == true)
			$('[name="checked[]"]').prop('checked',true)
		else
			$('[name="checked[]"]').prop('checked',false)
	})
	$('[name="checked[]"]').click(function(){
		var count = $('[name="checked[]"]').length
		var checked = $('[name="checked[]"]:checked').length
		if(count == checked)
			$('#check_all').prop('checked',true)
		else
			$('#check_all').prop('checked',false)
	})
	$('#print').click(function(){
		start_load()
		$.ajax({
			url:"print_tenants.php",
			success:function(resp){
				if(resp){
					var nw = window.open("","_blank","height=600,width=900")
					nw.document.write(resp)
					nw.document.close()
					nw.print()
					setTimeout(function(){
						nw.close()
						end_load()
					},700)
				}
			}
		})
	})

	function delete_tenant($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_tenant',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>