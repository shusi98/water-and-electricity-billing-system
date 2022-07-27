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
<div class="containe-fluid">
    <form action="" id="maage-payment">
    <input type="hidden" name="id" value='<?php echo $id ?>'>
        <div class="form-group">
            <label for="">Amount Due</label>
            <input type="number" id="amount_due" class="form-control text-right" step="any" disabled value="<?php echo $total_amount ?>">
        </div>
        <div class="form-group">
            <label for="">Amount Tendered</label>
            <input type="number" id="amount_tendered" class="form-control text-right" step="any" value="0">
        </div>
    </form>
</div>