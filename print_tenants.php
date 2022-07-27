<?php include('db_connect.php');?>
<style type="text/css">
	table{
		width:100%;
		border-collapse:collapse;
	}
	tr,td,th{
		border:1px solid black;
	}
	.text-center{
		text-align: center
	}
	.text-right{
		text-align: right
	}
</style>
<?php
session_start();
?>
<h3 class="text-center"><b>List of Tenants</b></p>
<hr>
<table>
	<thead>
		<tr>
			<th class="text-center">#</th>
			<th class="">Business Name</th>
			<th class="">Owner Name</th>
			<th class="">Contact</th>
			<th class="">Rented Block/s</th>
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
		</tr>
		<?php endwhile; ?>
	</tbody>
</table>
