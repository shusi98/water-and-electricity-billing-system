<?php include('db_connect.php');?>

<div class="container-fluid">
	
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
						<b>Block List</b>
						<span class="float:right"><button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_block">
					<i class="fa fa-plus"></i> Add Block
				</button></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Block</th>
									<th class="">Floor</th>
									<th class="">Rate</th>
									<th class="">Status</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$block = $conn->query("SELECT * FROM block_locations order by id asc");
								while($row=$block->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									
									<td class="">
										 <p> <b><?php echo $row['block'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['floor'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo number_format($row['rate'],2) ?></b></p>
									</td>
									<td class="">
										<?php if($row['status'] == 1): ?>
										 <span class="badge badge-success">Available</span>
										<?php else: ?>
										 <span class="badge badge-secondary">Unavailable</span>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_block" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_block" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	$('#new_block').click(function(){
		uni_modal("New Block","manage_block.php")
	})
	
	$('.edit_block').click(function(){
		uni_modal("Edit Block","manage_block.php?id="+$(this).attr('data-id'))
		
	})
	$('.delete_block').click(function(){
		_conf("Are you sure to delete this block?","delete_block",[$(this).attr('data-id')])
	})
	
	function delete_block($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_block',
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